<?php
/**
 * @package   AkeebaTicketSystem
 * @copyright Copyright (c)2011-2016 Nicholas K. Dionysopoulos
 * @license   GNU General Public License version 3, or later
 */

namespace Akeeba\TicketSystem\Admin\Model;

use Akeeba\TicketSystem\Admin\Helper\ComponentParams;
use Akeeba\TicketSystem\Admin\Helper\Credits;
use Akeeba\TicketSystem\Admin\Helper\Email;
use Akeeba\TicketSystem\Admin\Helper\Filter;
use Akeeba\TicketSystem\Admin\Helper\Mail;
use Akeeba\TicketSystem\Admin\Helper\Permissions;
use Exception;
use FOF30\Model\Model;
use JFile;

defined('_JEXEC') or die;

class EmailCheck extends Model
{
    protected $mailbox;

	/**
	 * Get the timestamp of the last email check
	 *
	 * @return int
	 */
	public function getLastTimestamp()
	{
		return ComponentParams::getParam('email_lastcheck', 0);
	}

	/**
	 * Set the timestamp of the last email check to now
	 */
	public function setLastTimestamp()
	{
		$timestamp = time();

		ComponentParams::setParam('email_lastcheck', $timestamp);
	}

	/**
	 * Should we check for new email?
	 *
	 * @return bool
	 */
	public function shouldCheckForEmail()
	{
		$period = ComponentParams::getParam('email_checkevery', 5) * 60;

		if ($period < 0)
		{
			$period = 300;
		}

		$now = time();
		$last = $this->getLastTimestamp();

		return ($now - $last) >= $period;
	}

    public function getMailbox()
    {
        if(!$this->mailbox)
        {
            $mboxparams = array(
                'mailbox_type'         => ComponentParams::getParam('mailboxtype', 'imap'),
                'server'               => ComponentParams::getParam('email_server', 'imap.gmail.com'),
                'port'                 => ComponentParams::getParam('email_port', ''),
                'ssl'                  => ComponentParams::getParam('email_ssl', '0'),
                'tls'                  => ComponentParams::getParam('email_tls', '1'),
                'validate_certificate' => ComponentParams::getParam('email_validate_certificate', '1'),
                'gmail'                => ComponentParams::getParam('gmail', '0'),
                'username'             => ComponentParams::getParam('email_username', ''),
                'password'             => ComponentParams::getParam('email_password', ''),
                'folder'               => ComponentParams::getParam('email_folder', 'INBOX')
            );

            $this->mailbox = new Mail($mboxparams);
        }

        return $this->mailbox;
    }

	public function checkEmail($cliFeedback = false)
	{
		// Instanciate the mail server connector object
		$mailbox = $this->getMailbox();

		// Get parameters
		$replyByEmail     = ComponentParams::getParam('replybyemail', '0');
		$newByEmail       = ComponentParams::getParam('newbyemail', '0');
		$delete_after     = ComponentParams::getParam('email_delete', '1');

		// Bail out if no email feature is set at all
		if (!$replyByEmail && !$newByEmail)
		{
			return;
		}

		// Open the mailbox connection
		$mailbox->open();

		// Get the IMAP date since when to look for email
		$lastcheck = $this->getLastTimestamp();

		if (empty($lastcheck))
		{
			$lastcheck = time() - 86400;
		}

		$since = date("d-M-Y", $lastcheck - 86400);

		// Set the last check date to prevent this loop running in parallel several times
		$this->setLastTimestamp();

		// Fetch new emails
		try
		{
			$emails = $mailbox->getMessagesSince($since);
		}
		catch (Exception $e)
		{
			$emails = array();
		}

		// Bail out if we have no new email to check
		if (!count($emails))
		{
			return;
		}

		// Loop through all messages
		foreach ($emails as $email)
		{
			if ($cliFeedback)
			{
				echo $email['mid'] . ' -- ' . $email['fromaddress'] . ' ' . $email['subject'] . "\n";
			}

			$this->processEmailNew($email);

			if ($delete_after)
			{
				if ($cliFeedback)
				{
					echo "   deleting original message from server\n";
				}

				$mailbox->delete($email['mid']);
			}
		}

		// Close and expunge mailbox
		$mailbox->expunge();
	}

    private function processEmailNew($email)
    {
        // Make sure we are not processing the same email twice (only if requested to)
        if(ComponentParams::getParam('track_email_id', 0))
        {
            $db    = $this->container->db;
            $query = $db->getQuery(true)
                        ->select('COUNT(*)')
                        ->from($db->qn('#__ats_posts'))
                        ->where($db->qn('email_uid') . ' = ' . $db->q($email['uid']));
            $uid_count = $db->setQuery($query)->loadResult();

            if ($uid_count)
            {
                return;
            }
        }

        $replyByEmail       = ComponentParams::getParam('replybyemail', '0');
        $newByEmail         = ComponentParams::getParam('newbyemail', '0');
        $default_category   = ComponentParams::getParam('email_default_category', '0');
        $nonewtickets       = ComponentParams::getParam('nonewtickets', 0);
        $noreplies          = ComponentParams::getParam('noreplies', 0);
        $showcredits        = ComponentParams::getParam('showcredits', 0);

        // Get the ticket ID and validate it
        $ticketID  = $this->getTicketID($email);
        /** @var Tickets $ticket */
        $ticket    = $this->container->factory->model('Tickets')->tmpInstance();

        if ($ticketID)
        {
            $ticket->load($ticketID);
        }

        // Get the category ID
        if (!$ticket->getId())
        {
            $categoryID = $this->getCategoryIDFromEmail($email['toemail']);

            // If no category was found, use the default category
            if (empty($categoryID))
            {
                $categoryID = $default_category;
            }
        }
        else
        {
            $categoryID = $ticket->catid;
        }

        // Perform some generic checks. More specific ones are performed later
        if(!$this->genericChecks($email, $categoryID, $ticket))
        {
            return;
        }

        // Get the user
        $from  = $email['fromemail'];
        $user  = $this->getUserFromEmail($from);
        $perms = Permissions::getAclPrivileges($categoryID, $user->id);
        $isManager = $perms['core.admin'] || $perms['core.manage'];

        $canPost     = false;
        $post        = array();
        $newTicket   = false;

        if (!$ticket->getId() && $newByEmail)
        {
            // If we are not allowed to create new tickets globally, cancel this op
            if ($nonewtickets && !$isManager)
            {
                // Send an email to the user telling him that the No New Tickets global switch is enabled
                $this->sendErrorEmail('mailgateway-nonewtickets', $from, array(
                    'name'     => $user->name,
                    'username' => $user->username
                ));

                return;
            }

            // Do we have enough credits? Perform the check only if the credits feature is turned on
            if($showcredits)
            {
                $priority    = $perms['ats.private'] ? 1 : 5;
                $haveCredits = Credits::haveEnoughCredits($user->id, $categoryID, true, !$perms['ats.private'], $priority);

                if (!$haveCredits)
                {
                    // Send an email to the user telling him that he doesn't have enough credits
                    $this->sendErrorEmail('mailgateway-nocredits-ticket', $from, array(
                        'name'             => $user->name,
                        'username'         => $user->username,
                        'credits_required' => Credits::creditsRequired($categoryID, true, !$perms['ats.private'], $priority),
                        'credits_left'     => Credits::creditsLeft($user->id, true),
                    ));

                    return;
                }
            }

            // Allow posting to the (new) ticket
            $canPost   = true;
            $newTicket = true;

            // We have to decode the email subject, otherwise we end up with a wrong title when non UTF-8 chars are used
            // (for example German umlauts)
            $old_enc = mb_internal_encoding();
            mb_internal_encoding("UTF-8");
            $title = mb_decode_mimeheader($email['subject']);
            mb_internal_encoding($old_enc);

            $data = array(
                'ats_ticket_id' => null,
                'id'            => 0,
                'catid'         => $categoryID,
                'status'        => 'O',
                'title'         => $title,
                'public'        => !$perms['ats.private'],
                'origin'        => 'email',
                'enabled'       => 1,
                'created_by'    => $user->id,
            );

            try
            {
                $ticket::$overrideuser = 1;
                $ticket->save($data);
                $ticket::$overrideuser = 0;
            }
            catch(Exception $e)
            {
                // Send an email informing the user that ticket creation failed
                $this->sendErrorEmail('mailgateway-newticketfailed', $from, array(
                    'name'     => $user->name,
                    'username' => $user->username,
                ));

                return;
            }
        }
        elseif ($ticket->getId())
        {
            // Existing ticket. Can we post the reply?
            if(!$replyByEmail)
            {
                // New ticket creation not allowed in config
                $this->sendErrorEmail('mailgateway-newreplydisabled', $from, array(
                    'name'     => $user->name,
                    'username' => $user->username,
                ));

                return;
            }

            // Replying to al old ticket. Do I have the special ticket line?
            if (!$this->hasReplyAboveLine($email['body']))
            {
                $this->sendErrorEmail('mailgateway-noreplyline', $from, array(
                    'name'     => $user->name,
                    'username' => $user->username,
                ));

                return;
            }
        }

        if ($canPost && !$newTicket)
        {
            // Make sure the user has enough credits - Perform the check only if the credits feature is turned on
            if($showcredits)
            {
                $haveCredits = Credits::haveEnoughCredits($user->id, $categoryID, false, !$perms['ats.private'], $ticket->priority);

                if (!$haveCredits)
                {
                    // Send an email to the user telling him that he doesn't have enough credits
                    $this->sendErrorEmail('mailgateway-nocredits-post', $from, array(
                        'name'             => $user->name,
                        'username'         => $user->username,
                        'credits_required' => Credits::creditsRequired($categoryID, false, !$perms['ats.private'], $ticket->priority),
                        'credits_left'     => Credits::creditsLeft($user->id, true),
                    ));

                    return;
                }
            }

            // Make sure the user is replying to his own ticket or he's an admin
            if (!Permissions::isManager($categoryID))
            {
                if ($user->id != $ticket->created_by)
                {
                    // Illegal post by a non-admin, non-owner user
                    // Send an email telling the user he has no access
                    $this->sendErrorEmail('mailgateway-noaccess', $from, array(
                        'name'     => $user->name,
                        'username' => $user->username
                    ));

                    return;
                }
                else
                {
                    // User posted: set ticket status to 'O'
                    $update = array(
                        'ats_ticket_id' => $post['ats_ticket_id'],
                        'status'        => 'O',
                        'modified_by'   => $user->id,
                        'modified_on'   => gmdate($this->container->db->getDateFormat()),
                    );

                    $ticket::$noPermissionsCheck = 1;
                    $ticket->save($update);
                    $ticket::$noPermissionsCheck = 0;
                }
            }
            else
            {
                // Admin posted: set ticket status to 'P'
                $update = array(
                    'ats_ticket_id' => $post['ats_ticket_id'],
                    'status'        => 'P',
                    'modified_by'   => $user->id,
                    'modified_on'   => gmdate($this->container->db->getDateFormat()),
                );

                $ticket::$noPermissionsCheck = 1;
                $ticket->save($update);
                $ticket::$noPermissionsCheck = 0;
            }
        }
        elseif (!$replyByEmail && !$newTicket)
        {
            // New ticket creation not allowed in config
            $this->sendErrorEmail('mailgateway-newreplydisabled', $from, array(
                'name'     => $user->name,
                'username' => $user->username,
            ));

            return;
        }

        // If we are not allowed to create new replies globally, cancel this op
        if ($noreplies && !$isManager)
        {
            // Send an email to the user telling him that the No New Replies global switch is enabled
            $this->sendErrorEmail('mailgateway-nonewreplies', array(
                'name'     => $user->name,
                'username' => $user->username
            ));

            return;
        }

        // If the user is a manager and there isn't an assigned user, let's assign him to the ticket
        if (!$ticket->assigned_to && Permissions::isManager($ticket->catid, $user->id))
        {
            $ticket::$noPermissionsCheck = 1;
            $ticket->assigned_to = $user->id;
            $ticket->save();
            $ticket::$noPermissionsCheck = 0;
        }

        $emailmessage = $this->extractMessage($email);
        $emailmessage = Filter::filterText($emailmessage, $user);

        // Add a post
        $post['content_html']  = $emailmessage;
        $post['content']       = '';
        $post['enabled']       = 1;
        $post['origin']        = 'email';
        $post['created_by']    = $user->id;
        $post['email_uid']     = $email['uid'];
        $post['ats_ticket_id'] = $ticket->getId();
        // Primer the attachment field with an array with a 0, so I will have a consistent value in the database even
        // if there is no attachment to the email
        $post['ats_attachment_id'] = array(0);

        $ats_attachment_id = false;

        if($perms['ats.attachment'])
        {
            $ats_attachment_id = $this->extractAttachment($email['parts'], $user->id);
            $post['ats_attachment_id'] = array($ats_attachment_id);
        }

        /** @var Posts $postModel */
        $postModel = $this->container->factory->model('Posts');
        // I have to do this trick instead of using the tmpInstance otherwise I lost the link with the parent ticket model
        $postModel->reset(true, false);
        /** @var Attachments $attachmentModel */
        $attachmentModel = $this->container->factory->model('Attachments')->tmpInstance();

        try
        {
            $postModel->save($post);
        }
        catch(Exception $e)
        {
            // If an error occurred after saving the post, I have to delete it as well
            if($postModel->getId())
            {
                $postModel->delete();
            }

            if ($newTicket)
            {
                $ticket->delete();
            }

            if ($ats_attachment_id)
            {
                $attachmentModel->delete($ats_attachment_id);
            }

            // Send an email informing the user that ticket creation failed
            $this->sendErrorEmail('mailgateway-newpostfailed', $from, array(
                'name'     => $user->name,
                'username' => $user->username,
            ));

            return;
        }

        // Update post reference inside attachment record
        if ($ats_attachment_id)
        {
            $attachmentModel->find($ats_attachment_id);
            $attachmentModel->ats_post_id = $postModel->ats_post_id;
            $attachmentModel->store();
        }
    }

	/**
	 * Returns the ticket ID found inside an email
	 *
	 * @param array $email
	 *
	 * @return boolean|int False if no ID is found, else the ticket ID
	 */
    private function getTicketID(&$email)
	{
		$ticketId = $email['ticketid'];

		$body = $email['body'];
		$hasTicketId = preg_match('/\{ticketid\:(\d+)\}/ism', $body, $matches);

		if ($hasTicketId)
		{
			$ticketId_alt = $matches[1];
		}
		else
		{
			$ticketId_alt = 0;
		}

		$ticketId = max($ticketId, $ticketId_alt);

		if (empty($ticketId))
		{
			return false;
		}
		else
		{
			return $ticketId;
		}
	}

	/**
	 * Extracts the part of the message which is above the reply line
	 *
	 * @param array $email
	 *
	 * @return string
	 */
    private function extractMessage($email)
	{
		$body = $email['body'];
		$parts = explode('!-!-', $body);

		return $parts[0];
	}

    private function extractAttachment($parts, $userid)
    {
        $ats_attachment_id = 0;

        if(!count($parts))
        {
            return 0;
        }

        // Do we have attachments, at all?
        $tempdir = \JFactory::getConfig()->get('tmp_path');

        // Find the largest attached file
        $largestSize = 0;
        $partid      = false;

        foreach ($parts as $id => $part)
        {
            $size = strlen($part->data);

            if ($size > $largestSize)
            {
                $tmpName = tempnam($tempdir, 'atsup');
                $res = @file_put_contents($tmpName, $part->data);

                if ($res === false)
                {
                    continue;
                }

                $fakeFileStructure = array(
                    'name'     => $part->filename,
                    'size'     => $size,
                    'tmp_name' => $tmpName,
                );

                $allowedFile = $this->canUpload($fakeFileStructure);

                @unlink($tmpName);

                if ($allowedFile)
                {
                    $largestSize = $size;
                    $partid      = $id;
                }
            }
        }

        if($partid === false)
        {
            return 0;
        }

        $part = $parts[$partid];
        $size = strlen($part->data);

        $tmpName = tempnam($tempdir, 'atsup');
        $res     = @file_put_contents($tmpName, $part->data);

        if ($res !== false)
        {
            $fakeFileStructure = array(
                'name'     => $part->filename,
                'size'     => $size,
                'tmp_name' => $tmpName,
            );

            /** @var Attachments $attachmentModel */
            $attachmentModel = $this->container->factory->model('Attachments')->tmpInstance();
            $filedef = $attachmentModel->uploadFile($fakeFileStructure, false);

            if ($filedef !== false)
            {
                $jdate = $this->container->platform->getDate();

                $filedef['created_by'] = $userid;
                $filedef['created_on'] = $jdate->toSql();
                $filedef['enabled'] = 1;

                $attachmentModel->reset();
                $attachmentModel->save($filedef);

                $ats_attachment_id = $attachmentModel->ats_attachment_id;
            }

            @unlink($tmpName);
        }

        return $ats_attachment_id;
    }

	/**
	 * Does the message have a "reply above this line" line?
	 *
	 * @param array $email
	 *
	 * @return bool
	 */
    private function hasReplyAboveLine($email)
	{
		$regex = '#!-!-(.*)-!-!#s';

		if (is_array($email))
		{
			$body = $email['body'];
		}
		elseif (is_string($email))
		{
			$body = $email;
		}

		$result = preg_match_all($regex, $body, $matches);

		return ($result !== false) && ($result > 0);
	}

	/**
	 * Gets a user object from an email address
	 *
	 * @param string $from Email address
	 *
	 * @return \JUser a user object
	 */
    private function getUserFromEmail($from)
	{
		$db = $this->container->db;
		$query = $db->getQuery(true)
                    ->select($db->qn('id'))
                    ->from($db->qn('#__users'))
                    ->where($db->qn('email') . ' = ' . $db->q($from));

		// Enforce int type, otherwise we could have weird behaviors with nulls
		$uid = (int)$db->setQuery($query, 0, 1)->loadResult();

        if(!$uid)
        {
            return false;
        }

		$user = $this->container->platform->getUser($uid);

		return $user;
	}

	/**
	 * Gets the category ID based on the email address of the user
	 *
	 * @staticvar null|array $catmap Holds a map of emails to categories
	 *
	 * @param string $email Email address to check
	 *
	 * @return int The category ID, or 0 if none is found
	 */
    private function getCategoryIDFromEmail($email)
	{
		static $catmap = null;

		if (is_null($catmap))
		{
            /** @var Categories $model */
			$model = $this->container->factory->model('Categories')->tmpInstance();
			$items = $model->ignoreUser(1)->get(true);

			$catmap = array();

            foreach ($items as $item)
            {
                $params = new \JRegistry($item->params);
                $catemail = $params->get('category_email', '');

                if (empty($catemail))
                {
                    continue;
                }

                if (array_key_exists($catemail, $catmap))
                {
                    continue;
                }

                $catmap[$catemail] = $item->id;
            }
		}

		if (array_key_exists($email, $catmap))
		{
			return $catmap[$email];
		}

		return 0;
	}

	private function sendErrorEmail($key, $toEmail, Array $extraVars = array())
	{
		list($subject, $templateText, $loadLanguage) = Email::loadEmailTemplateFromDB($key);

		if (empty($templateText) || empty($subject))
		{
			return false;
		}

		try
		{
			$mailer = Email::getMailer();

			Email::parseTemplate($templateText, $subject, $extraVars, $mailer);

			$mailer->addRecipient($toEmail);
			$mailer->Send();
			unset($mailer);
		}
		catch (\Exception $e)
		{
			return false;
		}

        return true;
	}

	/**
	 * Checks if the file can be uploaded
	 *
	 * @param   array  $file      File information
	 * @param   string $component The option name for the component storing the parameters
	 *
	 * @return  boolean
	 *
	 * @since   3.2
	 */
	private function canUpload($file, $component = 'com_media')
	{
        // TODO Is this a copy of the code contained inside the core media helper?
		$params = \JComponentHelper::getParams($component);

		if (empty($file['name']))
		{
			return false;
		}

        \JLoader::import('joomla.filesystem.file');

		if (str_replace(' ', '', $file['name']) != $file['name'] || $file['name'] !== JFile::makeSafe($file['name']))
		{
			return false;
		}

		$format = strtolower(JFile::getExt($file['name']));

		// Media file names should never have executable extensions buried in them.
		$executable = array(
			'php', 'js', 'exe', 'phtml', 'java', 'perl', 'py', 'asp', 'dll', 'go', 'ade', 'adp', 'bat', 'chm', 'cmd', 'com', 'cpl', 'hta', 'ins', 'isp',
			'jse', 'lib', 'mde', 'msc', 'msp', 'mst', 'pif', 'scr', 'sct', 'shb', 'sys', 'vb', 'vbe', 'vbs', 'vxd', 'wsc', 'wsf', 'wsh'
		);

		$explodedFileName = explode('.', $file['name']);

		if (count($explodedFileName) > 2)
		{
			foreach ($executable as $extensionName)
			{
				if (in_array($extensionName, $explodedFileName))
				{
					return false;
				}
			}
		}

		$allowable = explode(',', $params->get('upload_extensions'));
		$ignored   = explode(',', $params->get('ignore_extensions'));

		if ($format == '' || $format == false || (!in_array($format, $allowable) && !in_array($format, $ignored)))
		{
			return false;
		}

		$maxSize = (int)($params->get('upload_maxsize', 0) * 1024 * 1024);

		if ($maxSize > 0 && (int)$file['size'] > $maxSize)
		{
			return false;
		}

		if ($params->get('restrict_uploads', 1))
		{
			$images = explode(',', $params->get('image_extensions'));

			if (in_array($format, $images))
			{
				// If it is an image run it through getimagesize
				// If tmp_name is empty, then the file was bigger than the PHP limit
				if (!empty($file['tmp_name']))
				{
					if (($imginfo = getimagesize($file['tmp_name'])) === false)
					{
						return false;
					}
				}
				else
				{
					return false;
				}
			}
			elseif (!in_array($format, $ignored))
			{
				// If it's not an image, and we're not ignoring it
				$allowed_mime = explode(',', $params->get('upload_mime'));
				$illegal_mime = explode(',', $params->get('upload_mime_illegal'));

				if (function_exists('finfo_open') && $params->get('check_mime', 1))
				{
					// We have fileinfo
					$finfo = finfo_open(FILEINFO_MIME);
					$type  = finfo_file($finfo, $file['tmp_name']);

					if (strlen($type) && !in_array($type, $allowed_mime) && in_array($type, $illegal_mime))
					{
						return false;
					}

					finfo_close($finfo);
				}
				elseif (function_exists('mime_content_type') && $params->get('check_mime', 1))
				{
					// We have mime magic.
					$type = mime_content_type($file['tmp_name']);

					if (strlen($type) && !in_array($type, $allowed_mime) && in_array($type, $illegal_mime))
					{
						return false;
					}
				}
				else
				{
					return false;
				}
			}
		}

		$xss_check = file_get_contents($file['tmp_name'], false, null, -1, 256);

		$html_tags = array(
			'abbr', 'acronym', 'address', 'applet', 'area', 'audioscope', 'base', 'basefont', 'bdo', 'bgsound', 'big', 'blackface', 'blink',
			'blockquote', 'body', 'bq', 'br', 'button', 'caption', 'center', 'cite', 'code', 'col', 'colgroup', 'comment', 'custom', 'dd', 'del',
			'dfn', 'dir', 'div', 'dl', 'dt', 'em', 'embed', 'fieldset', 'fn', 'font', 'form', 'frame', 'frameset', 'h1', 'h2', 'h3', 'h4', 'h5', 'h6',
			'head', 'hr', 'html', 'iframe', 'ilayer', 'img', 'input', 'ins', 'isindex', 'keygen', 'kbd', 'label', 'layer', 'legend', 'li', 'limittext',
			'link', 'listing', 'map', 'marquee', 'menu', 'meta', 'multicol', 'nobr', 'noembed', 'noframes', 'noscript', 'nosmartquotes', 'object',
			'ol', 'optgroup', 'option', 'param', 'plaintext', 'pre', 'rt', 'ruby', 's', 'samp', 'script', 'select', 'server', 'shadow', 'sidebar',
			'small', 'spacer', 'span', 'strike', 'strong', 'style', 'sub', 'sup', 'table', 'tbody', 'td', 'textarea', 'tfoot', 'th', 'thead', 'title',
			'tr', 'tt', 'ul', 'var', 'wbr', 'xml', 'xmp', '!DOCTYPE', '!--'
		);

		foreach ($html_tags as $tag)
		{
			// A tag is '<tagname ', so we need to add < and a space or '<tagname>'
			if (stristr($xss_check, '<' . $tag . ' ') || stristr($xss_check, '<' . $tag . '>'))
			{
				return false;
			}
		}

		return true;
	}

    /**
     * @param   array   $email
     * @param   int     $categoryID
     * @param   Tickets $ticket
     *
     * @return  bool
     */
    private function genericChecks($email, $categoryID, $ticket)
    {
        $from = $email['fromemail'];

        // We need a valid user to go on
        if(!$user = $this->getUserFromEmail($from))
        {
            // Maybe send an email telling the user he screwed up if there's a valid ticket ID?
            $this->sendErrorEmail('mailgateway-invaliduser', $from);

            return false;
        }

        $newByEmail     = ComponentParams::getParam('newbyemail', 0);
        $emailadminonly = ComponentParams::getParam('emailadminonly', 0);

        // Do I have a valid category id? Let's protect us from no categories set and missing default category
        if(!$categoryID)
        {
            // Send an email telling the user he has no access
            $this->sendErrorEmail('mailgateway-noaccess', $from, array(
                'name'     => $user->name,
                'username' => $user->username
            ));

            return false;
        }

        // Does the user has access to the category?
        /** @var Categories $catModel */
        $catModel = $this->container->factory->model('Categories')->tmpInstance();
        $categories = $catModel
                        ->category($categoryID)
                        ->userid($user->id)
                        ->get(true);
        /** @var Categories $category */
        $category = $categories->first();

        $canCreate = false;

        // Get ACL permissions and make sure we can post here
        if($category)
        {
            $perms     = Permissions::getAclPrivileges($category->id, $user->id);
            $canCreate = $perms['core.create'];
        }

        // No category found (wrong or no access to it) or the user can't create a ticket
        if (is_null($category) || !$canCreate)
        {
            // Send an email telling the user that replies by email are not allowed
            $this->sendErrorEmail('mailgateway-newreplydisabled', $from, array(
                'name'     => $user->name,
                'username' => $user->username
            ));

            return false;
        }

        $isManager = Permissions::isManager($category->id, $user->id);

        // Replies only allowed by a manager and this is not a manager
        if (!$isManager && $emailadminonly)
        {
            // Send an email telling the user he has no access
            $this->sendErrorEmail('mailgateway-noaccess', $from, array(
                'name'     => $user->name,
                'username' => $user->username
            ));

            return false;
        }

        if (!$ticket->getId() && !$newByEmail)
        {
            // New ticket creation not allowed in config
            $this->sendErrorEmail('mailgateway-newticketdisabled', $from, array(
                'name'     => $user->name,
                'username' => $user->username,
            ));

            return false;
        }

        return true;
    }
}