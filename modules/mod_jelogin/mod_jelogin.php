<?php

/**

* @package  dowalo

* @copyright Copyright (C) 2009 - 2010 Open Source Matters. All rights reserved.

* @license   http://www.gnu.org/licenses/lgpl.html GNU/LGPL, see LICENSE.php

* Contact to : emailtohardik@gmail.com, joomextensions@gmail.com

* Visit : http://www.joomlaextensions.co.in/

**/ 
// no direct access

defined('_JEXEC') or die('Restricted access');

// Include the syndicate functions only once

require_once (dirname(__FILE__).'/'.'helper.php');

$type 	= mod_jeloginHelper::getType($params);

$return	= mod_jeloginHelper::getReturnURL($params, $type);


$document =  JFactory::getDocument();
$uri = JURI::getInstance();
$url= $uri->root();
   


require(JModuleHelper::getLayoutPath('mod_jelogin'));




?>