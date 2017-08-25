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

class ProjectsControllerVideos extends SocialAppsController {
    public function confirmFeature() {
        // Check for request forgeries
        FD::checkToken();

        // User needs to be logged in
        FD::requireLogin();

        // Load up ajax library
        $ajax = FD::ajax();
        $id = JRequest::getInt('id');

        $videoTable = ES::table('Video');
        $videoTable->load($id);

        // Throw error when the id not valid
        $featured = $videoTable->featured;

        // Get the delete confirmation dialog
        $theme = FD::themes();
        $theme->set('id', $id);
        $theme->set('featured', $featured);

        $contents = $theme->output('apps/user/projects/dialog.video.feature');

        return $ajax->resolve($contents);
    }

    public function feature() {
        // Check for request forgeries
        FD::checkToken();

        // User needs to be logged in
        FD::requireLogin();

        $id = JRequest::getInt('id');
        $feature = $this->input->get('feature');


        // Load up ajax library
        $ajax = FD::ajax();

        $videoTable = ES::table('Video');
        $videoTable->load($id);

        // Throw error when the id not valid
        if (!$id || !$videoTable->id) {
            return $ajax->reject('No id given.');
        }

        // Get the current logged in user as we only want the current logged
        $my = FD::user();

        if ($videoTable->user_id != $my->id) {
            return $ajax->reject('You are not an owner or admin of this video.');
        }


        $db = ES::db();

        //unfeature all other events
        $query = "UPDATE #__social_videos SET `featured` = 0 WHERE `user_id` = ".$my->id;
        $db->setQuery($query);
        $unfeature = $db->execute();
        if(!$unfeature){
            return $ajax->reject(JText::_('An error occured while reseting all featured events.'));
        }

        if ($feature == 'true') {
            $query = "UPDATE #__social_videos SET `featured` = 1 WHERE `id` = ".$id." AND `user_id` =".$my->id;
        } elseif ($feature == 'false') {
            $query = "UPDATE #__social_videos SET `featured` = 0 WHERE `id` = ".$id." AND `user_id` =".$my->id;
        }

        $db->setQuery($query);
        $result = $db->execute();

        if (!$result) {
            return $ajax->reject(JText::_($result->getError()));
        }

        return $ajax->resolve();
    }
}