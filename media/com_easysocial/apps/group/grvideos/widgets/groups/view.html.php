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

class GrvideosWidgetsGroups extends SocialAppsWidgets {

    /**
     * Display user photos on the side bar
     *
     * @since	1.0
     * @access	public
     * @param	string
     * @return
     */
    public function groupAdminStart(SocialGroup $group) {
        $theme = ES::themes();
        $theme->set('group', $group);
        $theme->set('app', $this->app);

        echo $theme->output('themes:/apps/group/grvideos/widgets/widget.menu');
    }

    public function groupHeaderFooter($group) {
        $config = FD::config();
        $category = $group->getCategory();
        $theme = FD::themes();

        $link = FRoute::groups(array('id' => $group->getAlias(), 'appId' => $this->app->getAlias(), 'layout' => 'item'));

        $theme->set('link', $link);
        $theme->set('group', $group);

        echo $theme->output('themes:/apps/group/grvideos/widgets/widget.header.link');
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

        if (!$this->app->getParams()->get('show_grvideos', true)) {
            return;
        }

        $theme = FD::themes();

        $params = $this->app->getParams();

        $limit = (int) $params->get('limit', 10);

        $options = array();
        $options['uid'] = $group->id;
        $options['type'] = SOCIAL_TYPE_GROUP;

        // Get the list of albums from this group
        $model = ES::model('Videos');

        $videos = $model->getVideos($options);

        if (!$videos) {
            return;
        }

        $link = FRoute::groups(array('id' => $group->getAlias(), 'appId' => $this->app->getAlias(), 'layout' => 'item'));

        $totalVideos = $model->getTotalVideos($options);

        $theme->set('total', $totalVideos);
        $theme->set('videos', $videos);
        $theme->set('group', $group);
        $theme->set('link', $link);

        echo $theme->output('themes:/apps/group/grvideos/widgets/widget.grvideos');
    }

}
