<?php
/**
* @copyright (C) 2015 iJoomla, Inc. - All rights reserved.
* @license GNU General Public License, version 2 (http://www.gnu.org/licenses/gpl-2.0.html)
* @author iJoomla.com <webmaster@ijoomla.com>
* @url https://www.jomsocial.com/license-agreement
* The PHP code portions are distributed under the GPL license. If not otherwise stated, all images, manuals, cascading style sheets, and included JavaScript *are NOT GPL, and are released under the IJOOMLA Proprietary Use License v1.0
* More info at https://www.jomsocial.com/license-agreement
*/
defined('_JEXEC') or die('Restricted access');

// All the module logic should be placed here
if(!class_exists('modCommunityHelloMe'))
{
    class modCommunityHelloMe
    {
        function getSomething()
        {
			// Some function here
		}

		function getuserdata($userid)
		{
			$db=  JFactory :: getDBO();

			$sql="SELECT u.*,c.country_name,s.state_name FROM `#__joomproject_users` as u 
					LEFT JOIN `#__joomproject_countries` as c ON c.country_id = u.countryId 
					LEFT JOIN `#__joomproject_state` as s ON s.state_id = u.stateId Where userId=".$userid;
			$db->setQuery($sql);
			$userdata = $db->loadObject();
			return $userdata;
		}
		function getskillname($skillid)
		{
			if(empty($skillid) || $skillid==0 || $skillid=='')
				return false;
				
			$db=  JFactory :: getDBO();
			$sql="SELECT skill_name FROM #__joomproject_skill_detail Where id=".$skillid;
			$db->setQuery($sql);
			return $db->loadResult();
			
		}
	}
}
