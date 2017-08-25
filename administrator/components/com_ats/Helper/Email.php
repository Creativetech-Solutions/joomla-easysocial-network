<?php
/**
 * @package   AkeebaTicketSystem
 * @copyright Copyright (c)2011-2016 Nicholas K. Dionysopoulos
 * @license   GNU General Public License version 3, or later
 */

namespace Akeeba\TicketSystem\Admin\Helper;

use FOF30\Container\Container;
use JFactory;
use JFile;
use JMail;
use JUri;
use phpmailerException;

defined('_JEXEC') or die();

class Email
{
	/**
	 * Load an email template from the database
	 *
	 * @param   string  $key  The email template key
	 *
	 * @return  array  A plain array: [$subject, $body, $loadedLanguage]
	 */
	public static function loadEmailTemplateFromDB($key)
	{
		// Initialise
		$templateText = '';
		$subject      = '';

		// Look for desired languages
		$jLang     = JFactory::getLanguage();
		$userLang  = JFactory::getUser()->getParam('language', '');
		$languages = array(
			$userLang,
			$jLang->getTag(),
			$jLang->getDefault(),
			'en-GB',
			'*'
		);

		// Look for an override in the database
		$db    = JFactory::getDbo();
		$query = $db->getQuery(true)
		            ->select('*')
		            ->from($db->qn('#__ats_emailtemplates'))
		            ->where($db->qn('key') . '=' . $db->q($key))
		            ->where($db->qn('enabled') . '=' . $db->q(1));
		$db->setQuery($query);
		$allTemplates = $db->loadObjectList('language');

		// Try finding the most relevant language override and load it
		$loadLanguage = null;

		foreach ($languages as $lang)
		{
			if (!array_key_exists($lang, $allTemplates))
			{
				continue;
			}

			if ($lang != '*')
			{
				$loadLanguage = $lang;
			}

			$subject      = $allTemplates[ $lang ]->subject;
			$templateText = $allTemplates[ $lang ]->template;

			// If I found a match for this $languages key, it has higher
			// affinity than the next entries on the list, therefore I needn't
			// look further.
			break;
		}

		// Because SpamAssassin blacklists our domain when it falsely thinks an email is spam.
		$replaceThat = array(
			'<p style=\"text-align: right; font-size: 7pt; color: #ccc;\">Powered by <a href=\"https://www.akeebabackup.com/products/ats.html\" style=\"color: #ccf; text-decoration: none;\">Akeeba Ticket System</a></p>',
			'<p style="text-align: right; font-size: 7pt; color: #ccc;">Powered by <a href="https://www.akeebabackup.com/products/ats.html" style="color: #ccf; text-decoration: none;">Akeeba Ticket System</a></p>',
			'https://www.akeebabackup.com',
			'http://www.akeebabackup.com',
			'http://akeebabackup.com',
			'https://akeebabackup.com',
			'www.akeebabackup.com',
			'akeebabackup.com',
		);

		foreach ($replaceThat as $find)
		{
			$subject = str_ireplace($find, '', $subject);
			$templateText = str_ireplace($find, '', $templateText);
		}

		// Because SpamAssassin demands there is a body and surrounding html tag even though it's not necessary.
		if (strpos($templateText, '<body') == false)
		{
			$templateText = '<body>' . $templateText . '</body>';
		}

		if (strpos($templateText, '<html') == false)
		{
			$templateText = <<< HTML
<html>
<head>
<title>{$subject}</title>
</head>
$templateText
</html>
HTML;

		}

		return array($subject, $templateText, $loadLanguage);
	}

	/**
	 * Returns a new JMail object, set up to send UTF-8 encoded, HTML emails.
	 *
	 * @return  JMail  The mailer object
	 *
	 * @throws  phpmailerException
	 */
	public static function &getMailer()
	{
		$mailer = JFactory::getMailer();

		// We always send HTML emails
		$mailer->isHtml(true);

		// Required to not get broken characters in emails
		$mailer->CharSet = 'UTF-8';

		return $mailer;
	}

	/**
	 * Parses template text and subject with email variables
	 *
	 * @param   string  $templateText  Body text
	 * @param   string  $subject       Subject text
	 * @param   array   $mailInfo      Associative array of variables to replace in the boby and subject text
	 * @param   JMail   $mailer        If set to null, this function will return values, instead of setting them to the mailer
	 *
	 * @return  bool|array  If a mailer is passed, it returns true, otherwise it will return parsed subject and body
	 */
	public static function parseTemplate($templateText, $subject, $mailInfo, &$mailer = null)
	{
		// Email variables
		$return    = array();
		$container = Container::getInstance('com_ats');
		$isCli     = $container->platform->isCli();

		if ($isCli)
		{
			\JLoader::import('joomla.application.component.helper');

			$baseURL    = \JComponentHelper::getParams('com_ats')->get('siteurl', 'http://www.example.com');
			$temp       = str_replace('http://', '', $baseURL);
			$temp       = str_replace('https://', '', $temp);
			$parts      = explode($temp, '/', 2);
			$subpathURL = count($parts) > 1 ? $parts[1] : '';
		}
		else
		{
			$baseURL    = JURI::base();
			$subpathURL = JURI::base(true);
		}

		$baseURL    = str_replace('/administrator', '', $baseURL);
		$subpathURL = str_replace('/administrator', '', $subpathURL);
		$sitename   = JFactory::getConfig()->get('sitename');

		$emailVars = array(
			'sitename' => $sitename,
			'siteurl'  => $baseURL,
		);

		if (is_array($mailInfo) && !empty($mailInfo))
		{
			$emailVars = array_merge($emailVars, $mailInfo);
		}

		// Perform substitutions
		foreach ($emailVars as $key => $value)
		{
			$tag          = '[' . strtoupper($key) . ']';
			$templateText = str_replace($tag, $value, $templateText);
			$subject      = str_replace($tag, $value, $subject);
		}

		if ($mailer)
		{
			$mailer->setSubject($subject);
		}
		else
		{
			$return['subject'] = $subject;
		}


		// Include inline images
		$pattern           = '/(src)=\"([^"]*)\"/i';
		$number_of_matches = preg_match_all($pattern, $templateText, $matches, PREG_OFFSET_CAPTURE);

		if ($number_of_matches > 0)
		{
			$substitutions = $matches[2];
			$last_position = 0;
			$temp          = '';

			// Loop all URLs
			$imgidx    = 0;
			$imageSubs = array();
			foreach ($substitutions as &$entry)
			{
				// Copy unchanged part, if it exists
				if ($entry[1] > 0)
				{
					$temp .= substr($templateText, $last_position, $entry[1] - $last_position);
				}
				// Examine the current URL
				$url = $entry[0];
				if ((substr($url, 0, 7) == 'http://') || (substr($url, 0, 8) == 'https://'))
				{
					// External link, skip
					$temp .= $url;
				}
				else
				{
					$ext = strtolower(JFile::getExt($url));
					if (!JFile::exists($url))
					{
						// Relative path, make absolute
						$url = trim($baseURL, '/') . '/' . ltrim($url, '/');
					}

					if (!JFile::exists($url) || !in_array($ext, array('jpg', 'png', 'gif')))
					{
						// Not an image or inexistent file
						$temp .= $url;
					}
					else
					{
						// Image found, substitute
						if (!array_key_exists($url, $imageSubs))
						{
							// First time I see this image, add as embedded image and push to
							// $imageSubs array.
							$imgidx ++;
							$mailer->addEmbeddedImage($url, 'img' . $imgidx, basename($url));
							$imageSubs[ $url ] = $imgidx;
						}
						// Do the substitution of the image
						$temp .= 'cid:img' . $imageSubs[ $url ];
					}
				}

				// Calculate next starting offset
				$last_position = $entry[1] + strlen($entry[0]);
			}
			// Do we have any remaining part of the string we have to copy?
			if ($last_position < strlen($templateText))
			{
				$temp .= substr($templateText, $last_position);
			}
			// Replace content with the processed one
			$templateText = $temp;
		}

		if ($mailer)
		{
			$mailer->setBody($templateText);
		}
		else
		{
			$return['body'] = $templateText;
		}

		return $return;
	}
}