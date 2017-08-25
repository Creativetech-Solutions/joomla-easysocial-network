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

class ProjectsViewCanvas extends SocialAppsView {

    /**
     * Displays the single project item
     *
     * @since	1.0
     * @access	public
     * @param	string
     * @return
     */
    public function display($userId = null, $docType = null) {
        // Require user to be logged in
        $uid = $this->input->get('uid', 0, 'int');
        $type = $this->input->get('type', 'user', 'word');
        $sort = $this->input->get('sort', '', 'word');

        $Projectapp = FD::table('app');
        $Projectapp->loadByElement('projects', SOCIAL_APPS_GROUP_USER, 'apps');

        if ($this->my->id == 0 || $uid == 0) {
            ES::requireLogin();
        }
        if ($uid == 0 && $this->my->id != 0) {
            $uid = $this->my->id;
        }
        $mid = ES::user($uid)->id;
        $user = ES::user($mid);
        $this->set('user', $user);
        $projectURL = FRoute::apps(array('layout' => 'canvas', 'id' => $Projectapp->getAlias(), 'uid' => $user->getAlias(), 'type' => SOCIAL_TYPE_USER));
        $this->set('projectURL', $projectURL);

        $clayout = $this->input->get('clayout', '', 'word');
        $model = $this->getModel('Projects');
        $table = $this->getTable('Project');
        $options = array();
        
        $options['privacy'] = false;
        
        if (empty($clayout)) {

            $clayout = 'default';

            // Get the projects
            //$options['featured'] = false;
            $options['uid'] = $uid;
            $options['layout'] = 'default';
            $options['userid'] = $this->my->id;
            $options['type'] = $type;
            //$options['filter'] = SOCIAL_TYPE_USER;
            $options['limit'] = 999;
            $projects = $model->getProjects($options);
            // Set the page title
            FD::page()->title($user->name . ' Projects');
            $featuredProjects = array();
            $options['featured'] = true;
            $options['layout'] = '';
            $options['limit'] = 1;
            $featuredProjects = $model->getProjects($options);
            $this->set('projects', $projects);
            $this->set('featuredProjects', $featuredProjects);
        }

        $prid = $this->input->get('prid', 0, 'int');
        if ($clayout == "form") {
            $projects = $model->getTable('Project'); //default empty
            // Default page title
            $title = JText::_('COM_EASYSOCIAL_ADD_PROJECT');
            
            $catid = '';
            if ($prid) {
                //load project data
                $options['prid'] = $prid;
                $projects = $model->getProjects($options);
                //print_r($projects[0]);
                $this->set('project', $projects[0]);
                $catid = $projects[0]->category_id;
                $address_value = array('address' => $projects[0]->location);
                $address = $table->getAddressValue($address_value);
                $address->geocode();
                // Set the address value.
                $this->set('address', $address);

                //get project team
                $invites = $this->getModel('Invitations');
                $members = $invites->getProjectTeamMembers($prid);

                $this->set('members', $members);

                $this->set('jobs', $model->getProjectJobs($prid));
                $title = JText::_('COM_EASYSOCIAL_EDIT_PROJECT').' - '.$projects[0]->getTitle();
            }
            $this->set('categories', $model->getCategoryListHtml($catid));
            FD::page()->title($title);
        }
        // Get application params
        if ($clayout == "all" or $clayout == "all2") {
            if ($sort) {
                $options['sort'] = $sort;
            }
            $options['uid'] = $uid;
            $options['layout'] = 'default';
            $options['userid'] = $this->my->id;
            $options['type'] = $type;
            //$options['filter'] = SOCIAL_TYPE_USER;
            $projects = $model->getProjects($options);
            $options['layout'] = '';
            $options['featured'] = 1;
            $featuredProjects = $model->getProjects($options);
            $this->set('projects', $projects);
            $this->set('featuredProjects', $featuredProjects);
            $title = JText::sprintf('COM_EASYSOCIAL_ALL_PROJECTS', $user->getName());
            FD::page()->title($title);
        }
        if ($clayout == "detail") {
            $options['prid'] = $prid;
            $projects = $model->getProjects($options);
            //print_r($projects);exit;
            $this->set('project', $projects[0]);
            $title = $projects[0]->getTitle();
            FD::page()->title($title);
        }
        if ($clayout == "acceptInvite") {
            if ($prid) {
                //load project data
                $opts = array();
                $opts['privacy'] = false;
                $opts['prid'] = $prid;
                $projects = $model->getProjects($opts);
                $this->set('project', $projects[0]);

                $invites = $this->getModel('Invitations');
                $invite_options = array();
                $invite_options['user_id'] = $uid;
                $invite = $invites->getProjectInvitations($prid, $invite_options);

                if (!$invite) {
                    $this->set('msg', 'Sorry! No invitation request found.');
                } elseif ($invite->status == 1) {
                    $this->set('msg', 'You have already accepted this invitation.');
                } else {
                    $accept_options = array();
                    $accept_options['user_id'] = $uid;
                    $accept_options['status'] = 1;
                    $state = $invites->acceptInvitation($prid, $accept_options);
                    if ($state) {
                        $this->set('msg', 'Thank you for accepting the invitation.');
                    } else {
                        $this->set('msg', 'An error occured while accepting the invitation.');
                    }
                }
            }
        }

        $params = $this->app->getParams();

        $this->set('params', $params);

        echo parent::display('canvas/' . $clayout);
    }

}
