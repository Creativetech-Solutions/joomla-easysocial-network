<?php
/**
* @package   JE communitysearch
* @copyright Copyright (C) 2009-2010 Joomlaextensions.co.in All rights reserved.
* @license   http://www.gnu.org/licenses/lgpl.html GNU/LGPL, see LICENSE.php
* Contact to : emailtohardik@gmail.com, joomextensions@gmail.com
**/

defined('_JEXEC') or die('Restricted access');
 
class mod_communitysearchHelper
{
	
	public static function getcommunitylink() {
		$user=JFactory::getuser();
		$db = JFactory::getDbo();
		$sql = 'SELECT faceboocUrl, twitterUrl FROM jmx_joomproject_users where userId='.$user->id ; 
		$db->setQuery($sql);
		return $db->LoadObject();
	}
	
	public static function getCountries()
	{
		$lists = array();
		$db = JFactory::getDbo();
		$query = 'SELECT country_id as value,country_name as text  FROM #__joomproject_countries where published=1' ;
		$db->setQuery($query);
		$countries = $db->loadObjectList();
		
		$countryarray = array();
		$countryarray[] = JHtml::_('select.option', '0', JText::sprintf('SELECT_COUNTRY'));
		$country_list=@array_merge($countryarray,$countries);	
		return $lists['country_id'] 	= JHTML::_('select.genericlist',$country_list,  'country_id', 'class="inputtext" onchange="getcountrycode(this.value)"', 'value', 'text',JRequest::getVar('country_id'));
		
	}
	
	public static function getCategory()
	{
		$lists = array();
		$db = JFactory::getDbo();
		$query = 'SELECT id as value,name as text  FROM #__community_events_category where parent=0' ;
		$db->setQuery($query);
		$categories = $db->loadObjectList();
		 
		$categoryarray = array();
		$categoryarray[] = JHtml::_('select.option', '0', JText::sprintf('SELECT_CATEGORY'));
		$category_list=@array_merge($categoryarray,$categories);	
		return $lists['category'] 	= JHTML::_('select.genericlist',$category_list,  'category', 'class="inputtext" ', 'value', 'text',JRequest::getVar('category'));
		
	}
}