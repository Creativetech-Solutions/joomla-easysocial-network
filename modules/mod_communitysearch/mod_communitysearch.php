<?php 
/**
* @package   JE communitysearch
* @copyright Copyright (C) 2009-2010 Joomlaextensions.co.in All rights reserved.
* @license   http://www.gnu.org/licenses/lgpl.html GNU/LGPL, see LICENSE.php
* Contact to : emailtohardik@gmail.com, joomextensions@gmail.com
**/

// no direct access

defined('_JEXEC') or die('Restricted access');
require_once (dirname(__FILE__).'/'.'helper.php');
 
$layout = $params->get('layout','default');
//$community=mod_communitysearchHelper::getcommunitylink();
$countrylist = mod_communitysearchHelper::getCountries();
$categorylist = mod_communitysearchHelper::getCategory();
$data = mod_communitysearchHelper::getData();

$path = JModuleHelper::getLayoutPath('mod_communitysearch', $layout);
if (file_exists($path)) {
	require($path);
}