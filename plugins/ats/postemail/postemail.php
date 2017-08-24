<?php
/**
 * @package   ats
 * @copyright Copyright (c)2011-2016 Nicholas K. Dionysopoulos / AkeebaBackup.com
 * @license   GNU GPL v3 or later
 */

use Akeeba\TicketSystem\Admin\Helper\ComponentParams;
use Akeeba\TicketSystem\Admin\Helper\Email;
use Akeeba\TicketSystem\Admin\Helper\Html;
use Akeeba\TicketSystem\Admin\Helper\Permissions;
use Akeeba\TicketSystem\Admin\Helper\Subscriptions;

defined('_JEXEC') or die();

JLoader::import('joomla.plugin.plugin');

class plgAtsPostemail extends JPlugin
{
	/** @var \FOF30\Container\Container FOF Container */
	private $container;

	/**
	 * Public constructor
	 *
	 * @param   object  $subject  The object to observe
	 * @param   array   $config   Configuration parameters to the plugin
	 */
	public function __construct(&$subject, $config = array())
	{
		parent::__construct($subject, $config);

		if (!defined('FOF30_INCLUDED') && !@include_once(JPATH_LIBRARIES . '/fof30/include.php'))
		{
			throw new RuntimeException('FOF 3.0 is not installed', 500);
		}

		JLoader::import('joomla.filesystem.file');

		$version_php = JPATH_ADMINISTRATOR . '/components/com_ats/version.php';

		if (!defined('ATS_VERSION') && JFile::exists($version_php))
		{
			require_once $version_php;
		}

		$this->loadLanguage();
		$this->container = FOF30\Container\Container::getInstance('com_ats');
	}

	/**
	 * This handles the onATSPost event which is triggered every time a post is
	 * created or edited.
	 *
	 * @param   array  $info  An indexed array with post info. The post key contains an AtsTablePost object.
	 *
	 * @return  void
	 */
	public function onATSPost($info)
	{
		// Only send notifications for NEW posts, or posts edited by a non-administrator
		$sendEmail = false;

		if ($info['new'])
		{
			// New post; send email
			$sendEmail = true;
		}
		else
		{
			// Modified post. Let's think...
			$modified_by = $info['post']->modified_by;

			if ($modified_by <= 0)
			{
				// Not modified / modified by invalid user - no email
				$sendEmail = false;
			}
			else
			{
				$isAdmin = Permissions::isManager($info['ticket']->catid, $modified_by);

				if (!$isAdmin)
				{
					$sendEmail = true;
				}
			}
		}

		if (!$sendEmail)
		{
			return;
		}

		// Extract the post and ticket objects
		/** @var \Akeeba\TicketSystem\Site\Model\Posts $post */
		$post = $info['post'];

		/** @var \Akeeba\TicketSystem\Site\Model\Tickets $ticket */
		$ticket = $info['ticket'];

		$isNewTicket = false;

		// Is this a new ticket?
		if (abs(strtotime($ticket->created_on) - strtotime($post->created_on)) < 3)
		{
			$isNewTicket = true;
		}

		// Let's get the user ID's of all users who are managers of ATS or the specific category
		$managers        = $this->_getManagersForATSCategory($ticket->catid);
		$skipNotAssigned = ComponentParams::getParam('assigned_noemail', 0);

		if (!empty($managers))
		{
			foreach ($managers as $manager)
			{
				// Make sure we are not sending an email to the user who posted
				if ($manager == $post->created_by)
				{
					continue;
				}

				// Should I notify only the assigned manager?
				if ($skipNotAssigned)
				{
					// Ticket assigned, let's skip everyone else!
					if ($info['ticket']->assigned_to && $info['ticket']->assigned_to != $manager)
					{
						continue;
					}
				}

				// Send email to the manager
				$this->_sendEmailToUser($manager, $post, $ticket, 'manager');
			}
		}

		// Send email to the ticket owner, unless it's a reply sent by himself.
		// New ticket notifications will always be sent.
		if (($ticket->created_by != $post->created_by) || $isNewTicket)
		{
			$this->_sendEmailToUser($ticket->created_by, $post, $ticket, 'owner');
		}
	}

	/**
	 * Event fired when a ticket is assigned to someone. ATS will notify assigned user (unless they assigned
	 * a ticket to themselves
	 *
	 * @param   \Akeeba\TicketSystem\Admin\Model\Tickets  $ticket  Assigned ticket
	 *
	 * @return  bool  Is everything alright?
	 */
	public function onATSassign($ticket)
	{
		// Current user is the one assigned to, it's useless to notify myself
		if ($ticket->assigned_to == JFactory::getUser()->id)
		{
			return true;
		}

		try
		{
			$mailer = Email::getMailer();
		}
		catch (Exception $e)
		{
			// JMail died on us
			return true;
		}

		list($subject, $body,) = Email::loadEmailTemplateFromDB('manager-assignedticket');

		$sitename = JFactory::getConfig()->get('sitename');
		$user     = JUser::getInstance($ticket->assigned_to);

		try
		{
			$mailer->addRecipient($user->email, $user->name);
		}
		catch (Exception $e)
		{
			// JMail died on us
			return true;
		}

		$category   = $ticket->joomla_category;
		$baseURL    = str_replace('/administrator', '', JUri::base());
		$subpathURL = str_replace('/administrator', '', JUri::base(true));
		$url        = JRoute::_('index.php?option=com_ats&view=ticket&id=' . $ticket->ats_ticket_id, false);

		if (substr($url, 0, 14) == '/administrator')
		{
			$url = substr($url, 14);
		}

		$url        = ltrim($url, '/');
		$subpathURL = ltrim($subpathURL, '/');

		// Ehm... why?
		if (substr($url, 0, strlen($subpathURL) + 1) == "$subpathURL/")
		{
			$url = substr($url, strlen($subpathURL) + 1);
		}

		$ticketURL = rtrim($baseURL, '/') . '/' . ltrim($url, '/');

		//Grab first post, so we can use its text inside the email
		/** @var \Akeeba\TicketSystem\Admin\Model\Posts $post */
		$post = $ticket->posts->first();

		$attachmentURL = '';

		if ($post->ats_attachment_id)
		{
			/** @var \Akeeba\TicketSystem\Site\Model\Attachments $attachment */
			$attachment = $this->container->factory->model('Attachments')->tmpInstance();
			$attachment->find($post->ats_attachment_id[0]);

			$attFilename = JPATH_ROOT . '/media/com_ats/attachments/' . $attachment->mangled_filename;
			$realName    = $attachment->original_filename;

			if (@file_exists($attFilename))
			{
				$attSize = @filesize($attFilename);

				if ($attSize < 2097152)
				{
					// Attach the file
					try
					{
						$mailer->addAttachment($attFilename, $realName, 'base64', $attachment->mime_type);
					}
					catch (Exception $e)
					{
						// JMail died on us
					}
				}
				else
				{
					// Create a link to the attachment, for attachments over 2Mb
					$baseURL    = JUri::base();
					$baseURL    = str_replace('/administrator', '', $baseURL);
					$subpathURL = JUri::base(true);
					$subpathURL = str_replace('/administrator', '', $subpathURL);

					$url = JRoute::_('index.php?option=com_ats&view=attachment&task=read&format=raw&id=' . $attachment->ats_attachment_id, true);

					if (substr($url, 0, 14) == '/administrator')
					{
						$url = substr($url, 14);
					}

					$url        = ltrim($url, '/');
					$subpathURL = ltrim($subpathURL, '/');

					if (substr($url, 0, strlen($subpathURL) + 1) == "$subpathURL/")
					{
						$url = substr($url, strlen($subpathURL) + 1);
					}

					$attachmentURL = rtrim($baseURL, '/') . '/' . ltrim($url, '/');
				}
			}
		}

		$mailInfo = array(
			'id'         => $ticket->ats_ticket_id,
			'title'      => $ticket->title,
			'attachment' => empty($attachmentURL) ? '' : JText::sprintf('PLG_ATS_POSTEMAIL_ATTACHMENT_LINK', $attachmentURL, $realName),
			'url'        => $ticketURL,
			'catname'    => $category->title,
			'text'       => $post->content_html,
			'sitename'   => $sitename,
			'user_name'  => $user->name,
		);

		try
		{
			$this->_parseTemplate($body, $subject, $mailInfo, $mailer);

			// Send the email
			$mailer->Send();
			unset($mailer);
		}
		catch (Exception $e)
		{
			// JMail died on us
			return true;
		}

		return true;
	}

	/**
	 * Gets all the user IDs registered as managers for a specific ATS category
	 *
	 * @param    int $catid
	 *
	 * @return   array
	 */
	private function _getManagersForATSCategory($catid)
	{
		$managers = Permissions::getManagers($catid);

		/** @var \Akeeba\TicketSystem\Site\Model\Categories $category */
		$category = $this->container->factory->model('Categories')->tmpInstance();
		$category->load($catid);

		$params = new JRegistry($category->params);

		if (!is_object($params))
		{
			$notify  = array('all');
			$exclude = array();
		}
		else
		{
			$notify  = $params->get('notify_managers', array('all'));
			$exclude = $params->get('exclude_managers', array());
		}

		$whitelist = array();
		$blacklist = array();

		// Only notify the managers selected by the site administrators if they are, indeed, managers of this category.
		foreach ($managers as $manager)
		{
			if (in_array('all', $notify) || in_array($manager->id, $notify))
			{
				$whitelist[] = $manager->id;
			}
		}

		// Remove managers from the whitelist array
		foreach ($managers as $manager)
		{
			if (in_array('all', $exclude) || in_array($manager->id, $exclude))
			{
				$blacklist[] = $manager->id;
			}
		}

		$ret = array_diff($whitelist, $blacklist);

		return $ret;
	}

	/**
	 * Sends a new post email notification
	 *
	 * @param  int                                      $user_id Recipient's user ID
	 * @param  \Akeeba\TicketSystem\Admin\Model\Posts   $post
	 * @param  \Akeeba\TicketSystem\Admin\Model\Tickets $ticket
	 * @param  string                                   $towho   One of 'manager', 'owner' or 'subscriber'. Determines
	 *                                                           the subject and post body template.
	 *
	 * @return bool  Is the email correctly sent?
	 */
	private function _sendEmailToUser($user_id, $post, $ticket, $towho = 'manager')
	{
		$isCLI = $this->container->platform->isCli();

		if ($isCLI)
		{
			$siteURL = ComponentParams::getParam('siteurl', 'http://www.example.com');
		}

		// Extract the useful information
		$userReceipient = JUser::getInstance($user_id);

		if ($post->created_by == - 1)
		{
			$userPoster           = clone JFactory::getUser();
			$userPoster->username = 'system';
			$userPoster->name     = JText::_('COM_ATS_CLI_SYSTEMUSERLABEL');
		}
		else
		{
			$userPoster = JUser::getInstance($post->created_by);
		}

		try
		{
			$mailer = Email::getMailer();
		}
		catch (Exception $e)
		{
			// JMail died on us
			return true;
		}

		// If the category specifies a category_email parameter,
		// set the Reply-To address to it.
		/** @var \Akeeba\TicketSystem\Site\Model\Categories $category */
		$category = $this->container->factory->model('Categories')->tmpInstance();
		$category->find($ticket->catid);

		$params = new JRegistry();
		$params->loadString($category->params, 'JSON');

		$category_email = $params->get('category_email', '');
		$category_email = trim($category_email);

		if (!empty($category_email))
		{
			$mailfrom = $category_email;
			$conf     = JFactory::getConfig();
			$fromname = $conf->get('fromname');

			$didWeAddAReplyToAddress = false;

			try
			{
				$mailer->addReplyTo(array(JMailHelper::cleanLine($mailfrom), JMailHelper::cleanLine($fromname)));
				$didWeAddAReplyToAddress = true;
			}
			catch (Exception $e)
			{
				// It is very possible that we have Joomla! 3.5 which doesn't let us pass an array, unlike Joomla! 3.4
				// or earlier. However, we do not know if this behaviour will continue on Joomla! 3.6 or if it will be
				// reverted, therefore we cannot use a JVERSION if-block. Instead, we have to assume that a failure here
				// means we should retry without using an array. #jfail #inconsistent #idiots
			}

			if (!$didWeAddAReplyToAddress)
			{
				try
				{
					$mailer->addReplyTo(JMailHelper::cleanLine($mailfrom), JMailHelper::cleanLine($fromname));
				}
				catch (Exception $e)
				{
					// JMail died on us
					return true;
				}
			}
		}

		try
		{
			$mailer->addRecipient($userReceipient->email, $userReceipient->name);
		}
		catch (Exception $e)
		{
			// JMail died on us
			return true;
		}

		$isNewTicket = false;

		// Is this a new ticket? I have to rely on modified/creation time since the user could be different (ticket created on user behalf)
		if (abs(strtotime($ticket->modified_on) - strtotime($ticket->created_on)) < 3)
		{
			$isNewTicket = true;
		}

		$newold = $isNewTicket ? 'NEW' : 'OLD';
		$status = $ticket->public ? 'PUBLIC' : 'PRIVATE';

		// Get default body and subject
		$subject = JText::sprintf("PLG_ATS_POSTEMAIL_{$newold}TICKET_{$status}_TO{$towho}_SUBJECT", $ticket->ats_ticket_id, $ticket->title, $category->title);
		list($template, $subject) = $this->_getEmailTemplate($towho, $isNewTicket, $ticket->public);

		// I have no body and subject, this means that the webmaster doesn't want to send any emails,
		// so let's stop here
		if (!$template && !$subject)
		{
			return true;
		}

		// Set mail priority using phpmailer Priority variable and setting custom headers.
		// X-Priority header is already set by phpmailer
		// http://www.php.net/manual/en/function.mail.php#91058
		try
		{
			if ($ticket->priority == 1)
			{
				$mailer->Priority = 2;
				$head_priority    = 'High';
			}
			elseif ($ticket->priority == 5)
			{
				$mailer->Priority = 3;
				$head_priority    = 'Normal';
			}
			else
			{
				$mailer->Priority = 5;
				$head_priority    = 'Low';
			}
		}
		catch (Exception $e)
		{
			// JMail died on us
			return true;
		}

		try
		{
			$mailer->addCustomHeader('X-MSMail-Priority: ' . $head_priority);
			$mailer->addCustomHeader('Importance: ' . $head_priority);
		}
		catch (Exception $e)
		{
			// JMail died on us
			return true;
		}

		// Attach files if less than 2Mb (with addAttachment($attachFile)), or set up a link to them
		$attachmentURLs = array();

		if ($post->ats_attachment_id)
		{
			/** @var \Akeeba\TicketSystem\Site\Model\Attachments $attachment */
			$attachment = $this->container->factory->model('Attachments')->tmpInstance();

			foreach ($post->ats_attachment_id as $id_attachment)
			{
				// Post with no attachment (id_attachment == 0)
				if (!$id_attachment)
				{
					continue;
				}

				$attachment->find($id_attachment);

				$attFilename = JPATH_ROOT . '/media/com_ats/attachments/' . $attachment->mangled_filename;
				$realName    = $attachment->original_filename;

				if (!@file_exists($attFilename))
				{
					continue;
				}

				$attSize = @filesize($attFilename);

				if ($attSize < 2097152 && count($post->ats_attachment_id) == 1)
				{
					// Attach the file
					try
					{
						$mailer->addAttachment($attFilename, $realName, 'base64', $attachment->mime_type);
					}
					catch (Exception $e)
					{
						// JMail died on us
					}
				}
				else
				{
					// Create a link to the attachment, for attachments over 2Mb or for multiple attachments
					$baseURL    = JUri::base();
					$baseURL    = str_replace('/administrator', '', $baseURL);
					$subpathURL = JUri::base(true);
					$subpathURL = str_replace('/administrator', '', $subpathURL);

					$url = JRoute::_('index.php?option=com_ats&view=attachment&task=read&format=raw&id=' . $attachment->ats_attachment_id, false);

					if ($isCLI)
					{
						$url = rtrim($siteURL, '/') . '/index.php?option=com_ats&view=attachment&task=read&format=raw&id=' . $attachment->ats_attachment_id;
					}

					if (substr($url, 0, 14) == '/administrator')
					{
						$url = substr($url, 14);
					}

					$url        = ltrim($url, '/');
					$subpathURL = ltrim($subpathURL, '/');

					if (substr($url, 0, strlen($subpathURL) + 1) == "$subpathURL/")
					{
						$url = substr($url, strlen($subpathURL) + 1);
					}

					$attachmentURLs[ $realName ] = rtrim($baseURL, '/') . '/' . ltrim($url, '/');
				}
			}
		}

		// Get a link to the new post
		$baseURL    = JUri::base();
		$baseURL    = str_replace('/administrator', '', $baseURL);
		$subpathURL = JUri::base(true);
		$subpathURL = str_replace('/administrator', '', $subpathURL);

		if ($isCLI)
		{
			$url = rtrim($siteURL, '/') . '/index.php?option=com_ats&view=ticket&id=' . $ticket->ats_ticket_id;
		}
		elseif (class_exists('JApplication'))
		{
			$url = JRoute::_('index.php?option=com_ats&view=ticket&id=' . $ticket->ats_ticket_id, false);
		}
		else
		{
			$url = 'index.php?option=com_ats&view=ticket&id=' . $ticket->ats_ticket_id;
		}

		$url .= '#p' . $post->ats_post_id;

		if (substr($url, 0, 14) == '/administrator')
		{
			$url = substr($url, 14);
		}

		$url        = ltrim($url, '/');
		$subpathURL = ltrim($subpathURL, '/');

		if (substr($url, 0, strlen($subpathURL) + 1) == "$subpathURL/")
		{
			$url = substr($url, strlen($subpathURL) + 1);
		}

		if ($isCLI)
		{
			$postURL = rtrim($siteURL, '/') . '/' . ltrim($url, '/');
		}
		else
		{
			$postURL = rtrim($baseURL, '/') . '/' . ltrim($url, '/');
		}

		// Get the body of the message, based on overridable template files
		$sitename = JFactory::getConfig()->get('sitename');

		$attachmentToken = '';

		if ($attachmentURLs)
		{
			foreach ($attachmentURLs as $realName => $attachmentURL)
			{
				$attachmentToken[] = JText::sprintf('PLG_ATS_POSTEMAIL_ATTACHMENT_LINK', $attachmentURL, $realName);
			}

			$attachmentToken = implode('<br/>', $attachmentToken);
		}

		$mailInfo = array(
			'id'              => $ticket->ats_ticket_id,
			'title'           => $ticket->title,
			'url'             => $postURL,
			'attachment'      => $attachmentToken,
			'poster_username' => $userPoster->username,
			'poster_name'     => $userPoster->name,
			'user_username'   => $userReceipient->username,
			'user_name'       => $userReceipient->name,
			'text'            => $post->content_html,
			'catname'         => $category->title,
			'sitename'        => $sitename,
			'origin'          => $post->origin,
			'avatar'          => Html::getAvatarURL($userPoster, 64),
			'assigned_to'     => $ticket->assigned_to ? JUser::getInstance($ticket->assigned_to)->name : JText::_('COM_ATS_TICKETS_UNASSIGNED'),
		);

		$mailInfo['subscriptions'] = '';

		if (!$isCLI)
		{
			if (Subscriptions::hasSubscriptionsComponent())
			{
				$subs                      = Subscriptions::getSubscriptionsList($userPoster);
				$mailInfo['subscriptions'] = implode(', ', $subs->active);
			}
		}

		// Prefix the text with the ticket reply information if this is a
		// manager or user email and the reply by email is enabled.
		$emailadminonly = ComponentParams::getParam('emailadminonly', 0);

		if (ComponentParams::getParam('replybyemail', 0))
		{
			if ((($towho == 'manager') && !$emailadminonly) || ($towho != 'manager'))
			{
				// Add the reply above line
				$ticketInfo = '{ticketid:' . $mailInfo['id'] . '}';
				$body       = '!-!- ' . JText::_('PLG_ATS_POSTEMAIL_REPLYABOVE') . ' ' . $ticketInfo . ' -!-!';
				$body       = '<p>' . $body . '</p>';
				$template   = $body . $template;
				// Add a custom header, too
				try
				{
					$mailer->addCustomHeader('X-ATS-ticketid:' . $mailInfo['id']);
				}
				catch (Exception $e)
				{
					// JMail died on us
					return true;
				}
			}
		}

		try
		{
			$this->_parseTemplate($template, $subject, $mailInfo, $mailer);

			// Send the email
			$ret = $mailer->Send();
			unset($mailer);

			return $ret;
		}
		catch (Exception $e)
		{
			// JMail died on us
			return true;
		}
	}

	/**
	 * Gets an email template from the database (preferred), from the overrides
	 * directory (deprecated) or from the translation keys (not encouraged).
	 *
	 * @param string $tpl         The template type to fetch, e.g manager or owner
	 * @param bool   $isNewTicket Is this a new ticket (true) or a reply (false)?
	 * @param bool   $isPublic    Is this a public (true) or a private (false) ticket?
	 *
	 * @return array An array containing the subject and template text
	 */
	private function _getEmailTemplate($tpl = 'manager', $isNewTicket, $isPublic)
	{
		// Get status variables
		$tmplFile     = '';
		$templateText = '';
		$subject      = '';
		$loadLanguage = null;

		// Get status variables
		$newold = $isNewTicket ? 'NEW' : 'OLD';
		$status = $isPublic ? 'PUBLIC' : 'PRIVATE';
		$key    = strtolower("$tpl-$status-$newold");

		// If I'm under ATS PRO, I will only load the template from the DB
		if (ATS_PRO)
		{
			list($templateText, $subject, $loadLanguage) = Email::loadEmailTemplateFromDB($key);
		}
		// Otherwise I'll load it from the filesystem
		else
		{
			// Get default body
			$loadLanguage = null;
			$basePath     = dirname(__FILE__) . '/templates/';
			$jLang        = JFactory::getLanguage();
			$userLang     = JFactory::getUser()->getParam('language', '');
			$languages    = array(
				$userLang,
				$jLang->getTag(),
				$jLang->getDefault(),
				'en-GB',
				'*'
			);

			foreach ($languages as $lang)
			{
				if (empty($lang))
				{
					continue;
				}

				if (!empty($loadLanguage))
				{
					continue;
				}

				$filename = "$lang/$tpl.tpl";

				if (file_exists($basePath . 'overrides/' . $filename))
				{
					$loadLanguage = $lang;
					$tmplFile     = $basePath . 'overrides/' . $filename;
					break;
				}
				elseif (file_exists($basePath . 'default/' . $filename))
				{
					$loadLanguage = $lang;
					$tmplFile     = $basePath . 'default/' . $filename;
					break;
				}
			}

			JLoader::import("joomla.filesystem.file");

			// Load the template text
			if ($tmplFile && JFile::exists($tmplFile))
			{
				$templateText = @file_get_contents($tmplFile);

				if (empty($templateText))
				{
					$templateText = JFile::read($tmplFile);
				}
			}
		}

		return array($subject, $templateText);
	}

	/**
	 * Parses the template's variables
	 *
	 * @param        $templateText
	 * @param string $subject  The subject of the email
	 * @param array  $mailInfo Extra parameters
	 * @param JMail  $mailer   The PHPMailer object
	 */
	private function _parseTemplate($templateText, $subject, $mailInfo, &$mailer)
	{
		Email::parseTemplate($templateText, $subject, $mailInfo, $mailer);
	}
}