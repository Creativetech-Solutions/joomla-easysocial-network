<?php
/**
 * @package ats
 * @copyright Copyright (c)2011-2016 Nicholas K. Dionysopoulos / AkeebaBackup.com
 * @license GNU GPL v3 or later
 */

defined('_JEXEC') or die();

JLoader::import('joomla.plugin.plugin');

class plgAtsSociallike extends JPlugin
{
	/**
	 * Public constructor
	 *
	 * @param object $subject The onject to observe
	 * @param array $config Configuration parameters to the plugin
	 */
	public function __construct(&$subject, $config = array())
    {
		parent::__construct($subject, $config);

		if(!defined('F0F_INCLUDED')) {
			require_once JPATH_LIBRARIES.'/f0f/include.php';
		}
	}

    public function onBeforeTicketConversationDisplay($ticket)
    {
        // Share only public tickets
        if(!$ticket->public)
        {
            return '';
        }

        // Are we on localhost? Output a warning, since things won't work
        $url   = JRoute::_(JURI::root().'index.php?option=com_ats&view=ticket&id='.$ticket->ats_ticket_id);
        //$url   = 'https://www.akeebabackup.com';
        $title = $ticket->title;

        if(strpos($url, 'localhost') !== false || strpos($url, '127.0.0.1') !== false)
        {
            return '<div style="font-weight:bold; padding: 5px">Please enable this plugin on a live site only, otherwise social site won\'t be able to contact
    your site, throwing javascript errors; leading to a broken page display.</div>';
        }

        $this->addJavascript();

        $html  = '<div class="addthis_toolbox addthis_default_style">';

        if($this->params->get('button_facebook', 1))
        {
            $html .= '<a class="addthis_button_facebook_like" addthis:url="'.$url.'" fb:like:layout="button_count"></a>';
        }

        if($this->params->get('button_twitter', 1))
        {
            $html .= '<a class="addthis_button_tweet" addthis:url="'.$url.'" tw:text="'.$title.'"></a>';
        }

        if($this->params->get('button_gplus', 1))
        {
            $html .= '<a class="addthis_button_google_plusone" addthis:url="'.$url.'" g:plusone:annotation="bubble" g:plusone:size="medium"></a>';
        }

        if($this->params->get('button_linkedin', 1))
        {
            $html .= '<a class="addthis_button_linkedin_counter"></a>';
        }

        if($this->params->get('button_stumbleupon', 1))
        {
            $html .= '<a class="addthis_button_stumbleupon_badge"></a>';
        }

        if($this->params->get('button_pinterest', 1))
        {
            $html .= '<a class="addthis_button_pinterest_pinit" pi:pinit:url="'.$url.'" pi:pinit:media="'.F0FTemplateUtils::parsePath('media://com_ats/images/ats-256.png').'" pi:pinit:layout="horizontal"></a>';
        }

        if($this->params->get('button_addthis', 1))
        {
            $html .= '<a class="addthis_counter addthis_pill_style" addthis:url="'.$url.'"></a>';
        }

        $html .= '</div>';

        return $html;
    }

    public function onAfterTicketConversationDisplay($ticket)
    {
        return $this->onBeforeTicketConversationDisplay($ticket);
    }

    private function addJavascript()
    {
        static $included = false;

        if($included)
        {
            return;
        }

        // Get the correct protocol (http or https)
        $uri      = JUri::getInstance();
        $protocol = $uri->toString(array('scheme'));

        JFactory::getDocument()->addScript($protocol.'s7.addthis.com/js/300/addthis_widget.js');

        // Avoid duplicated content
        $exclude = array();

        if($this->params->get('button_facebook', 1))
        {
            $exclude[] = 'facebook';
        }

        if($this->params->get('button_twitter', 1))
        {
            $exclude[] = 'twitter';
        }

        if($this->params->get('button_gplus', 1))
        {
            $exclude[] = 'google_plusone';
        }

        if($this->params->get('button_linkedin', 1))
        {
            $exclude[] = 'linkedin';
        }

        if($this->params->get('button_stumbleupon', 1))
        {
            $exclude[] = 'stumbleupon';
        }

        if($this->params->get('button_pinterest', 1))
        {
            $exclude[] = 'pinterest';
        }

        if($exclude)
        {
            $js = 'var addthis_config = {services_exclude : "'.implode(',', $exclude).'"}';
        }

        // I got some js errors with this. Let's keep it for the future...
        //JFactory::getDocument()->addScriptDeclaration($js);

        $included = true;
    }
}