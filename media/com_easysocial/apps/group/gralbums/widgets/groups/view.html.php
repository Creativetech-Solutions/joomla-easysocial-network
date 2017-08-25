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

class GralbumsWidgetsGroups extends SocialAppsWidgets {

    /**
     * Display user photos on the side bar
     *
     * @since	1.0
     * @access	public
     * @param	string
     * @return
     */
    public function groupAdminStart($group) {
        $category = $group->getCategory();
        $config = FD::config();

        if (!$config->get('photos.enabled', true) || !$category->getAcl()->get('photos.enabled', true) || !$group->getParams()->get('photo.albums', true)) {
            return;
        }

        $theme = FD::themes();

        $theme->set('group', $group);
        $theme->set('app', $this->app);

        echo $theme->output('themes:/apps/group/gralbums/widgets/widget.menu');
    }

    public function groupHeaderFooter($group) {
        $config = FD::config();
        $category = $group->getCategory();
        if (!$config->get('photos.enabled', true) || !$category->getAcl()->get('photos.enabled', true) || !$group->getParams()->get('photo.albums', true)) {
            return;
        }

        $theme = FD::themes();

        $link = FRoute::groups(array('id' => $group->getAlias(), 'appId' => $this->app->getAlias(), 'layout' => 'item'));

        $theme->set('link', $link);
        $theme->set('group', $group);

        echo $theme->output('themes:/apps/group/gralbums/widgets/widget.header.link');
    }

    /**
     * Renders the sidebar widget for group members
     *
     * @since	1.3
     * @access	public
     * @param	string
     * @return
     */
    public function sidebarBottom($groupId) {
        // Load up the group
        $group = FD::group($groupId);

        if (!$this->app->getParams()->get('show_gralbums', true) || !$group->getCategory()->getAcl()->get('photos.enabled', true) || !$group->getParams()->get('photo.albums', true)) {
            return;
        }

        $theme = FD::themes();

        $params = $this->app->getParams();

        $limit = (int) $params->get('limit', 10);

        $options = array(
            'order' => 'assigned_date',
            'direction' => 'desc',
            'limit' => $limit
        );

        // Get the list of albums from this group
        $model = FD::model('Albums');

        $albums = $model->getAlbums($group->id, SOCIAL_TYPE_GROUP, $options);
        $options = array('uid' => $group->id, 'type' => SOCIAL_TYPE_GROUP);

        if (!$albums) {
            return;
        }

        $link = FRoute::groups(array('id' => $group->getAlias(), 'appId' => $this->app->getAlias(), 'layout' => 'item'));

        // Get the total number of albums
        $total = $model->getTotalAlbums($options);

        $theme->set('group', $group);
        $theme->set('total', $total);
        $theme->set('albums', $albums);
        $theme->set('link', $link);

        echo $theme->output('themes:/apps/group/gralbums/widgets/widget.gralbums');
    }

}
