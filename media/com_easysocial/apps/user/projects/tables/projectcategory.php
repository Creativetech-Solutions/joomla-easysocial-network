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

class ProjectsTableProjectCategory extends SocialTable {

    public $id = null;
    public $title = null;
    public $alias = null;
    public $description = null;
    public $parent_id = null;
    public $state = null;
    public $default = null;
    public $user_id = null;
    public $created = null;
    public $ordering = null;

    public function __construct(& $db) {
        parent::__construct('#__social_projects_categories', 'id', $db);
    }

    public function getTitle() {
        return JText::_($this->title);
    }

    public function store($updateNulls = false) {
        // @TODO: Automatically set the alias
        if (!$this->alias) {
            
        }

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

    /**
     * Overrides parent's delete behavior
     *
     * @since	1.0
     * @access	public
     * @param	string
     * @return
     */
    public function delete($pk = null) {
        $state = parent::delete($pk);

        // Delete streams that are related to this project.
        $stream = FD::stream();
        $stream->delete($this->id, 'projects');

        return $state;
    }

    /**
     * Shorthand to get the permalink of this project.
     *
     * @author Jason Rey <jasonrey@stackideas.com>
     * @since  1.2
     * @access public
     * @param  boolean   $external True of the link should be external ready.
     * @return string              The permalink of the project.
     */
    public function getPermalink($external = false, $xhtml = true, $sef = true) {
        return $this->getApp()->getCanvasUrl(array('cid' => $this->id, 'userid' => FD::user($this->user_id)->getAlias(), 'external' => $external, 'sef' => $sef), $xhtml);
    }

}
