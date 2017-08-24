<?php
// no direct access
defined('_JEXEC') or die;

//import the joomla plugin class so we can extend it
jimport('joomla.plugin.plugin');

/**
 * plgSystemMVCOverride class.
 *
 * @extends JPlugin
 */
class plgSystemMVCOverride extends JPlugin
{
    //use the onAfterInitialise trigger because it's the first Joomla trigger and we need to load our override as soon as possible before the one we are trying to override is loaded
    public function onAfterInitialise()
    {
        $app = JFactory::getApplication();
        $component = $app->input->get('option');
        $clayout = $app->input->get('clayout');

        // Files inside files folder that are being called from inside the original file:
        // dowalo.com\media\com_easysocial\apps\user\audios\themes\default\default.player.mini.php
        // dowalo.com\media\com_easysocial\apps\user\audios\themes\default\canvas\items.php
        // dowalo.com\media\com_easysocial\apps\user\audios\themes\default\canvas\list.php

        if(($app->isSite()) && ($component == 'com_easysocial' || $clayout)) {
            JLoader::register('AudiosControllerAudios',JPATH_THEMES.DIRECTORY_SEPARATOR.JFactory::getApplication()->getTemplate().DIRECTORY_SEPARATOR.'html'.DIRECTORY_SEPARATOR.'plg_mvcoverride'.DIRECTORY_SEPARATOR.'com_easysocial'.DIRECTORY_SEPARATOR.'audios.php');
            // Add if(!class_exists('AudiosViewCanvas')){} inside of the original class
            JLoader::register('AudiosViewCanvas',JPATH_THEMES.DIRECTORY_SEPARATOR.JFactory::getApplication()->getTemplate().DIRECTORY_SEPARATOR.'html'.DIRECTORY_SEPARATOR.'plg_mvcoverride'.DIRECTORY_SEPARATOR.'com_easysocial'.DIRECTORY_SEPARATOR.'view.html.php');
            JLoader::register('EasySocialModelLikes',JPATH_THEMES.DIRECTORY_SEPARATOR.JFactory::getApplication()->getTemplate().DIRECTORY_SEPARATOR.'html'.DIRECTORY_SEPARATOR.'plg_mvcoverride'.DIRECTORY_SEPARATOR.'com_easysocial'.DIRECTORY_SEPARATOR.'likes.php');
            // Add if(!class_exists('SocialLikes')){} inside of the original class
            JLoader::register('SocialLikes',JPATH_THEMES.DIRECTORY_SEPARATOR.JFactory::getApplication()->getTemplate().DIRECTORY_SEPARATOR.'html'.DIRECTORY_SEPARATOR.'plg_mvcoverride'.DIRECTORY_SEPARATOR.'com_easysocial'.DIRECTORY_SEPARATOR.'includes'.DIRECTORY_SEPARATOR.'likes.php');
            JLoader::register('EasySocialViewLikes',JPATH_THEMES.DIRECTORY_SEPARATOR.JFactory::getApplication()->getTemplate().DIRECTORY_SEPARATOR.'html'.DIRECTORY_SEPARATOR.'plg_mvcoverride'.DIRECTORY_SEPARATOR.'com_easysocial'.DIRECTORY_SEPARATOR.'views'.DIRECTORY_SEPARATOR.'view.ajax.php');
            JLoader::register('AudiosModel',JPATH_THEMES.DIRECTORY_SEPARATOR.JFactory::getApplication()->getTemplate().DIRECTORY_SEPARATOR.'html'.DIRECTORY_SEPARATOR.'plg_mvcoverride'.DIRECTORY_SEPARATOR.'com_easysocial'.DIRECTORY_SEPARATOR.'models'.DIRECTORY_SEPARATOR.'audios.php');
        }

    }
}