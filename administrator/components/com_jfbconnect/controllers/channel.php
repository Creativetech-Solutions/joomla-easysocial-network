<?php
/**
 * @package         JFBConnect
 * @copyright (c)   2009-2015 by SourceCoast - All Rights Reserved
 * @license         http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
 * @version         Release v7.1.1
 * @build-date      2016/11/18
 */

if (!(defined('_JEXEC') || defined('ABSPATH'))) {     die('Restricted access'); };

jimport('joomla.application.component.controllerform');
class JFBConnectControllerChannel extends JControllerForm
{
    private function checkAutotune()
    {
        // Saving an object or action
        $appConfig = JFBCFactory::config()->getSetting('autotune_app_config', array());
        $namespace = $appConfig['namespace'];
        if ($namespace == '')
            return false;
        else
            return true;
    }
}