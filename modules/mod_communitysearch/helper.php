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

	function getData()
	{
		$post = JRequest::get('post');
		$db = JFactory::getDbo();
		//echo '<pre>';print_r($post);exit;
		$post['filteroption'] = JRequest::getVar('filteroption','0');
		$searchkeyword = $post['searchkeyword'];
		$userProfile = array();
		$eventProfile = array(); 
		$userQuery = "";
		if(isset($post['filteroption']) && ($post['filteroption']==0 || $post['filteroption']==1)){
		
		if($searchkeyword)
			$userQuery .= ' AND ( ju.firstName like "%'.$searchkeyword.'%" OR ju.lastName like "%'.$searchkeyword.'%" )';
		
		if(isset($post['country_id']) && $post['country_id']){
			$userQuery .= ' AND ( ju.countryId='.$post['country_id'].')';
			
			if(isset($post['city']) && $post['city'])
				$userQuery .= ' AND ( ju.city='.$post['city'].')';
			
		}
		
		if(isset($post['skillId']) && $post['skillId'])
			$userQuery .= ' AND ( ju.skillFirst='.$post['skillId'].' OR ju.skillSecond='.$post['skillId'].' OR ju.skillThird='.$post['skillId'].' )';
		
			$query = 'SELECT u.avatar,u.thumb,u.follow,ju.firstName,ju.lastName,ju.skillFirst,ju.skillSecond,ju.skillThird,ju.aboutme,ju.city, mu.registerDate,c.country_2_code as countryCode, 1 as resultType FROM #__community_users AS u LEFT JOIN #__joomproject_users AS ju ON  u.userid = ju.userId LEFT JOIN  #__users AS mu ON  mu.id = u.userid LEFT JOIN `#__joomproject_countries` as c ON c.country_id = ju.countryId WHERE 1=1 '.$userQuery;
			$db->setQuery($query);
			$userProfile = $db->loadObjectList();
		 
		if(count($userProfile)){
			
			for($i=0;$i<count($userProfile);$i++){
				$skillname = "";
				if($userProfile[$i]->skillFirst){
					$queryskill = 'SELECT skill_name FROM #__joomproject_skill_detail WHERE id='.$userProfile[$i]->skillFirst;
					$db->setQuery($queryskill);
					$skillnameresult = $db->loadResult();
					if($skillnameresult)
						$skillname = $skillnameresult.', ';
				}
				if($userProfile[$i]->skillSecond){
					$queryskill = 'SELECT skill_name FROM #__joomproject_skill_detail WHERE id='.$userProfile[$i]->skillSecond;
					$db->setQuery($queryskill);
					$skillnameresult = $db->loadResult();
					if($skillnameresult)
						$skillname = $skillnameresult.', ';
				}
				if($userProfile[$i]->skillThird){
					$queryskill = 'SELECT skill_name FROM #__joomproject_skill_detail WHERE id='.$userProfile[$i]->skillThird;
					$db->setQuery($queryskill);
					$skillnameresult = $db->loadResult();
					if($skillnameresult)
						$skillname = $skillnameresult.', ';
				}
				
				$userProfile[$i]->skills = rtrim($skillname, ', ');
				//echo "start".$userProfile[$i]->follow."end";
				if($userProfile[$i]->follow){
					$userProfilefollow = explode(',',$userProfile[$i]->follow);
					$userProfile[$i]->followers = count($userProfilefollow);
				}else{
					$userProfile[$i]->followers = 0;
				}
			}
		
		}
		
		
		}
		if(isset($post['filteroption']) && ($post['filteroption']==0 || $post['filteroption']==5)){
			$eventQuery = "";
			
			if($searchkeyword)
				$eventQuery .= ' AND ( e.title like "%'.$searchkeyword.'%" OR e.description like "%'.$searchkeyword.'%" )';
			
			if(isset($post['category']) && $post['category']){
				$eventQuery .= ' AND ( e.catid='.$post['category'].')';
			
			}
			
			if(isset($post['countrytext']) && $post['countrytext'])
				$eventQuery .= ' AND ( e.location like "%'.$post['countrytext'].'%" OR e.location like "%'.$post['countrytext'].'%" )';
			if(isset($post['city']) && $post['city'])
				$eventQuery .= ' AND ( e.location like "%'.$post['city'].'%" OR e.location like "%'.$post['city'].'%" )';
			
				$query = 'SELECT ju.firstName, ju.lastName, c.name as categoryName, e.title, e.description, e.startdate, e.location, e.avatar, e.thumb, 5 as resultType FROM #__community_events AS e LEFT JOIN #__joomproject_users AS ju ON  e.creator = ju.userId LEFT JOIN `#__community_events_category` as c ON c.id = e.catid WHERE 1=1 '.$eventQuery;
				$db->setQuery($query);
				$eventProfile = $db->loadObjectList();
		
		}
		
	 
		
		
		$userProfile = array_merge($userProfile,$eventProfile);
		//$this->_data = $userProfile;
		
		return $userProfile;
		
	}
}