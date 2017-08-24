<?php
/**
* @package   JE communitysociallinks
* @copyright Copyright (C) 2009-2010 Joomlaextensions.co.in All rights reserved.
* @license   http://www.gnu.org/licenses/lgpl.html GNU/LGPL, see LICENSE.php
* Contact to : emailtohardik@gmail.com, joomextensions@gmail.com
**/

defined('_JEXEC') or die('Restricted access');
 
class mod_communitysociallinksHelper
{
	public function getReturnURL()
	{
		$uri = JFactory::getURI();
		$url = $uri->toString(array('path', 'query', 'fragment'));
		return base64_encode($url);
	}
	
	public function getcommunitylink() {
		$user=JFactory::getuser();
		$db = JFactory::getDbo();
		$sql = 'SELECT faceboocUrl, twitterUrl FROM jmx_joomproject_users where userId='.$user->id ; 
		$db->setQuery($sql);
		return $db->LoadObject();
	}
}