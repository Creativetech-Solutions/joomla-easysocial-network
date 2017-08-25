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
FD::import('admin:/includes/model');

class InvitationsModel extends EasySocialModel {

    //override coz the damn thing wasn't working from within the model
    public function getTable($type = 'ProjectTeamInvitation') {
        $project = new SocialAppsController('user', ES_SOCIAL_TYPE_PROJECTS);
        $table = $project->getTable($type);
        return $table;
    }

    public function getProjectInvitations($projectId, $options = array()) {
        $db = FD::db();
        $sql = $db->sql();
        $query = array();

        $userId = isset($options['user_id']) ? $options['user_id'] : '';
        $status = isset($options['status']) ? $options['status'] : 0;

        $query[] = "SELECT * FROM #__social_projects_team_invitation";

        $query[] = 'WHERE 1 ';

        if ($projectId) {
            $query[] = 'AND ';
            $query[] = $db->qn('pid') . ' = ' . $projectId;
        }
        if ($userId) {
            $query[] = 'AND ';
            $query[] = $db->qn('user_id') . ' = ' . $userId;
        }
        if ($status) {
            $query[] = 'AND ';
            $query[] = $db->qn('status') . ' = ' . $status;
        }

        $query = implode(' ', $query);
        $sql->raw($query);
        $db->setQuery($sql);
        $result = $db->loadObject();
        return $result;
    }

    public function acceptInvitation($projectId, $options = array()) {
        $db = FD::db();
        if (!$projectId) {
            return false;
        }
        $sql = $db->sql();
        $userId = isset($options['user_id']) ? $options['user_id'] : '';
        $status = isset($options['status']) ? $options['status'] : 0;
        if ($projectId && $userId) {
            $query = "UPDATE #__social_projects_team_invitation SET `status` = 1 WHERE `user_id` = " . $userId . " AND `pid` = " . $projectId;
            $db->setQuery($query);
            $state = $db->execute();
            if ($state) {
                $query = "INSERT INTO #__social_projects_team (`user_id`, `pid`) VALUES (" . $userId . "," . $projectId . ")";
                $db->setQuery($query);
                $state = $db->execute();
            }

            return $state;
        }
    }
    
    public function rejectInvitation($projectId, $options = array()) {
        $db = FD::db();
        if (!$projectId) {
            return false;
        }
        $sql = $db->sql();
        $userId = isset($options['user_id']) ? $options['user_id'] : '';
        $status = isset($options['status']) ? $options['status'] : -1;
        if ($projectId && $userId) {
            $query = "UPDATE #__social_projects_team_invitation SET `status` = -1 WHERE `user_id` = " . $userId . " AND `pid` = " . $projectId;
            $db->setQuery($query);
            $state = $db->execute();
            return $state;
        }
    }

    public function addMemberRole($projectId, $options = array() ) {
        $db = FD::db();
        if (!$projectId) {
            return false;
        }
        $user_id = isset($options['user_id']) ? $options['user_id'] : '';
        $role = isset($options['role']) ? $options['role'] : '';
        $is_admin = isset($options['is_admin']) ? $options['is_admin'] : '';
        
        $query = "UPDATE #__social_projects_team SET `role` = ".$db->quote($role).", `is_admin` = ".$db->quote($is_admin)." WHERE `user_id` = " . $user_id . " AND `pid` = " . $projectId;
        $db->setQuery($query);
        $state = $db->execute();
    }

    public function createInvitation($projectId, $user_id) {
        $db = FD::db();
        if (!$projectId) {
            return false;
        }
        if ($projectId && $user_id) {
            $query = "INSERT INTO #__social_projects_team_invitation (`status`, `user_id`, `pid`) VALUES (0," . $user_id . "," . $projectId . ")";

            $db->setQuery($query);
            $state = $db->execute();

            return $state;
        }
    }
    
    public function deleteInvitation($projectId, $user_id) {
        $db = FD::db();
        if (!$projectId || !$user_id) {
            return false;
        }
        $sql = $db->sql();
        if ($projectId && $user_id) {
            $query = "DELETE FROM #__social_projects_team_invitation WHERE `user_id` = " . $user_id . " AND `pid` = " . $projectId;
            $db->setQuery($query);
            $state = $db->execute();
            return $state;
        }
    }

    public function getProjectTeamMembers($projectId = '') {
        $db = FD::db();
        if (!$projectId) {
            return false;
        }
        $query = "SELECT * FROM #__social_projects_team WHERE `pid` = " . $projectId;
        $db->setQuery($query);
        $result = $db->loadObjectList();
        return $result;
    }

}
