<?php
/**
 * @package ats
 * @copyright Copyright (c)2011-2016 Nicholas K. Dionysopoulos / AkeebaBackup.com
 * @license GNU GPL v3 or later
 */

use Akeeba\TicketSystem\Admin\Helper\Credits;

defined('_JEXEC') or die();

JLoader::import('joomla.plugin.plugin');

// PHP version check
if(defined('PHP_VERSION'))
{
    $version = PHP_VERSION;
}
elseif(function_exists('phpversion'))
{
    $version = phpversion();
}
else
{
    // No version info. I'll lie and hope for the best.
    $version = '5.0.0';
}
// Old PHP version detected. EJECT! EJECT! EJECT!
if(!version_compare($version, '5.4.0', '>='))
{
    return;
}

// Make sure F0F is loaded, otherwise do not run
if (!defined('FOF30_INCLUDED') && !@include_once(JPATH_LIBRARIES . '/fof30/include.php'))
{
    return;
}

class plgContentAtscredits extends JPlugin
{
    public function __construct(& $subject, $config)
    {
        parent::__construct($subject, $config);

        $this->loadLanguage();
    }

    public function onContentPrepare($context, &$article, &$params, $limitstart = 0)
    {
        // Check whether the plugin should process or not
        if (JString::strpos($article->text, 'atscredits') === false)
        {
            return true;
        }

        // Search for this tag in the content
        $regex = "#{atscredits(.*?)}#s";

        $article->text = preg_replace_callback($regex, array('self', 'process'), $article->text);
    }

    private function process($match)
    {
        if(JFactory::getUser()->guest)
        {
            $ret = '';
        }
        else
        {
            $credits = Credits::creditsLeft(JFactory::getUser()->id, true);
            $ret     = JText::sprintf('PLG_ATSCREDITS_CREDITS', $credits);
        }

        return $ret;
    }
}

