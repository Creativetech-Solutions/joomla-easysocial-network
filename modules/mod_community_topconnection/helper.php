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
if(!class_exists('modCommunityTopConnection'))
{
    class modCommunityTopConnection
	{
        /**
         * @param int $days
         * @param $type
         */
         function gettopmember(){
            $db = JFactory::getDbo();
           $user = CFactory::getUser();
 
     

            $query = "SELECT cu.userId,cu.thumb,cu.avatar,u.name,u.id,(select if(ju.gender='','Male',ju.gender) from #__joomproject_users ju where ju.userId=u.id ) as gender FROM #__community_users cu inner join #__users u on u.id=cu.userId WHERE cu.userId in(SELECT distinct(connect_to) FROM #__community_connection 
            		 WHERE connect_from=".$user->id." AND status='1' ORDER BY created DESC) LIMIT 0,6";

            $db->setQuery($query);

            return $db->loadObjectList();
            
          
        }

	 function gettotalmember(){
            $db = JFactory::getDbo();
           $user = CFactory::getUser();
 
     
 			$query = "SELECT count(userId) FROM #__community_users WHERE userId in(SELECT distinct(connect_to) FROM #__community_connection 
            		 WHERE connect_from=".$user->id." AND status='1')";

            $db->setQuery($query);

            return $db->loadResult();

        }
		 
	}
}
