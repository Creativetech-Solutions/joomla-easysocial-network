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



class mod_jeloginHelper
{

	public static function getReturnURL($params, $type)
	{

		if($itemid =  $params->get($type))
		{
			$menu = JSite::getMenu();
			$item = $menu->getItem($itemid);
			$url = JRoute::_($item->link.'&Itemid='.$itemid, false);
		}

		else
		{
			$uri = JFactory::getURI();
			$url = $uri->toString(array('path', 'query', 'fragment'));
		} 

		return base64_encode($url);

	}



	public static function getType($params)
	{
 		$user =  JFactory::getUser();
		return (!$user->get('guest')) ? 'logout' : 'login';
	}
	


 
	 
}

