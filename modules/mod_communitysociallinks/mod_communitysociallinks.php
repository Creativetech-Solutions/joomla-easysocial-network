<?php 
/**
* @package   JE communitysociallinks
* @copyright Copyright (C) 2009-2010 Joomlaextensions.co.in All rights reserved.
* @license   http://www.gnu.org/licenses/lgpl.html GNU/LGPL, see LICENSE.php
* Contact to : emailtohardik@gmail.com, joomextensions@gmail.com
**/

// no direct access

defined('_JEXEC') or die('Restricted access');
require_once (dirname(__FILE__).'/'.'helper.php');
 
$layout = $params->get('layout','default');
$community=mod_communitysociallinksHelper::getcommunitylink();

$path = JModuleHelper::getLayoutPath('mod_communitysociallinks', $layout);
if (file_exists($path)) {
	require($path);
}