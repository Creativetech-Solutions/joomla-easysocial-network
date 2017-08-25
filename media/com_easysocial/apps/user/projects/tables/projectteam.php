<?php

/**
 * @package		EasySocial
 * @copyright	Copyright (C) 2010 - 2014 Stack Ideas Sdn Bhd. All rights reserved.
 * @license		GNU/GPL, see LICENSE.php
 * EasySocial is free software. This version may have been modified pursuant
 * to the GNU General Public License, and as distributed it includes or
 * is derivative of works licensed under the GNU General Public License or
 * other free or open source software licenses.
 * See COPYRIGHT.php for copyright notices and details.
 */
defined('_JEXEC') or die('Unauthorized Access');

FD::import('admin:/tables/table');

class ProjectsTableProjectTeam extends SocialTable {

    public $id = null;
    public $pid = null;
    public $user_id = null;
    public $role = null;

    public function __construct(& $db) {
        parent::__construct('#__social_projects_team', 'id', $db);
    }

    public function store($updateNulls = false) {
        $state = parent::store();
        return $state;
    }

    public function getAppId() {
        return $this->getApp()->id;
    }

    public function getAppAlias() {
        return $this->getApp()->getAlias();
    }

    public function getApp() {
        static $app;

        if (empty($app)) {
            $app = FD::table('app');
            $app->load(array('type' => SOCIAL_TYPE_APPS, 'group' => SOCIAL_APPS_GROUP_USER, 'element' => 'projects'));
        }

        return $app;
    }
    
    public function getTeam($projectId = false) {
        if (!$projectId) {
            return false;
        }
        $db = ES::db();
        
        $query = "SELECT * FROM #__social_projects_team WHERE `pid` = ".$projectId;
        $db->setQuery($query);

        return $db->loadObjectList();
    }
    
    public function isMember($user_id = '') {
        if (!$user_id) {
            return false;
        }
        $db = ES::db();
        
        $query = "SELECT * FROM #__social_projects_team WHERE `user_id` = ".$user_id;
        $db->setQuery($query);
        if($db->loadObjectList()){
            return true;
        }else{
            return false;
        }
        //return $db->loadObjectList();
    }
    
    public function isProjectAdmin($user_id = '', $project_id = ''){
        if (!$user_id) {
            return false;
        }
        $db = ES::db();
        
        $query = "SELECT * FROM #__social_projects_team WHERE `user_id` = ".$user_id." AND `is_admin` = 1 AND `pid` = ".$project_id;
        $db->setQuery($query);
        if($db->loadObjectList()){
            return true;
        }else{
            return false;
        }
    }
}