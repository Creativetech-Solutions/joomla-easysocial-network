<?php
/**
 * @package   AkeebaTicketSystem
 * @copyright Copyright (c)2010-2016 Nicholas K. Dionysopoulos
 * @license   GNU General Public License version 3, or later
 */

namespace Akeeba\TicketSystem\Admin\Model;

defined('_JEXEC') or die;

use Akeeba\TicketSystem\Admin\Helper\Email;
use FOF30\Container\Container;
use JFactory;
use JLoader;
use JText;

class EmailTemplates extends DefaultDataModel
{
    public function __construct(Container $container, array $config = array())
    {
        parent::__construct($container, $config);

        $this->addBehaviour('Filters');
    }

    public function check()
    {
        $this->assertNotEmpty($this->key, 'COM_ATS_EMAILTEMPLATES_ERR_KEY');

        parent::check();
    }

    /**
     * Gets an email template from the database (pro version), from the overrides
     * directory or from the translation keys (core version).
     *
     * @param string  $key   Template unique key
     *
     * @return array An array containing the subject and template text
     */
    public function getEmailTemplate($key)
    {
        // Initialise
        $templateText   = '';
        $subject        = '';
        $loadLanguage   = null;
        $key            = strtolower($key);
        $parts          = explode('-', $key);
        $tpl            = $parts[0];

        list($templateText, $subject, $loadLanguage) = $this->loadTemplate($key);

        if(empty($templateText))
        {
            // Get default body
            $loadLanguage   = null;
            $basePath       = dirname(__FILE__).'/templates/';
            $jLang          = JFactory::getLanguage();
            $userLang       = JFactory::getUser()->getParam('language','');
            $languages      = array($userLang,
                $jLang->getTag(),
                $jLang->getDefault(),
                'en-GB',
                '*');

            foreach($languages as $lang)
            {
                if(empty($lang))            continue;
                if(!empty($loadLanguage))   continue;

                $filename = "$lang/$tpl.tpl";

                if(file_exists($basePath.'overrides/'.$filename))
                {
                    $loadLanguage = $lang;
                    $tmplFile     = $basePath.'overrides/'.$filename;
                    break;
                }
                elseif(file_exists($basePath.'default/'.$filename))
                {
                    $loadLanguage = $lang;
                    $tmplFile     = $basePath.'default/'.$filename;
                    break;
                }
            }

            JLoader::import("joomla.filesystem.file");

            // Load the template text
            $templateText = @file_get_contents($tmplFile);

            if(empty($templateText))
            {
                $templateText = \JFile::read($tmplFile);
            }
        }

        // Now, (re)load the plugin's language files
        // -- English (default fallback)
        $jLang = JFactory::getLanguage();
        $jLang->load('plg_ats_postemail', JPATH_ADMINISTRATOR, 'en-GB', true);
        $jLang->load('plg_ats_postemail.override', JPATH_ADMINISTRATOR, 'en-GB', true);
        // -- The preferred language
        $jLang->load('plg_ats_postemail', JPATH_ADMINISTRATOR, $loadLanguage, true);
        $jLang->load('plg_ats_postemail.override', JPATH_ADMINISTRATOR, $loadLanguage, true);

        // Get default subject if necessary
        if(empty($subject))
        {
            $subject = JText::sprintf("PLG_ATS_POSTEMAIL_{$newold}TICKET_{$status}_TO{$towho}_SUBJECT", $ticket->ats_ticket_id, $ticket->title, $category->title);
        }

        return array($subject, $templateText);
    }

    /**
     * Get template key from an id
     *
     * @param   int    $id   Template ID
     *
     * @return  string Template key
     */
    public function getKey($id)
    {
        $db = JFactory::getDbo();

        $query = $db->getQuery(true)
                    ->select($db->qn('key'))
                    ->from('#__ats_emailtemplates')
                    ->where('ats_emailtemplate_id = '.$id);

        return $db->setQuery($query)->loadResult();
    }

    private static function loadTemplate($key)
    {
        // Initialise
        $templateText = '';
        $subject      = '';

        // Look for desired languages
        $jLang    = JFactory::getLanguage();
        $userLang = JFactory::getUser()->getParam('language','');
        $languages = array(
            $userLang, $jLang->getTag(), $jLang->getDefault(), 'en-GB', '*'
        );

        // Look for an override in the database
        $db    = JFactory::getDbo();
        $query = $db->getQuery(true)
                    ->select('*')
                    ->from($db->qn('#__ats_emailtemplates'))
                    ->where($db->qn('key').'='.$db->q($key))
                    ->where($db->qn('enabled').'='.$db->q(1));

        $allTemplates = $db->setQuery($query)->loadObjectList('language');

        // Try finding the most relevant language override and load it
        $loadLanguage = null;
        foreach($languages as $lang)
        {
            if(!array_key_exists($lang, $allTemplates)) continue;

            if($lang != '*') $loadLanguage = $lang;

            $subject      = $allTemplates[$lang]->subject;
            $templateText = $allTemplates[$lang]->template;

            // If I found a match for this $languages key, it has higher
            // affinity than the next entries on the list, therefore I needn't
            // look further.
            break;
        }

        return array($subject, $templateText, $loadLanguage);
    }

    /**
     * Parses the template's variables
     *
     * @param string  $templateText   Text of the email
     * @param string  $subject        The subject of the email
     * @param array   $mailInfo       Values for template substitution
     *
     * @return array  Indexed array with parsed subject and body
     */
    public function parseTemplate($templateText, $subject, $mailInfo)
    {
        return Email::parseTemplate($templateText, $subject, $mailInfo);
    }
}