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

if(!class_exists('AudiosViewCanvas')){
    class AudiosViewCanvas extends SocialAppsView {

        /**
         * Displays the single audio item
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

            if ($this->my->id == 0 || $uid == 0) {
                ES::requireLogin();
            }
            if ($uid == 0 && $this->my->id != 0) {
                $uid = $this->my->id;
            }
            $mid = ES::user($uid)->id;
            $user = ES::user($mid);
            $this->set('user', $user);

            $clayout = $this->input->get('clayout', '', 'word');
            $model = $this->getModel('Audios');
            $options = array();
            $options['uid'] = $uid;
            $options['userid'] = $this->my->id;
            $options['type'] = $type;
            $options['privacy'] = false;
            $options['filter'] = SOCIAL_TYPE_USER;
            if (empty($clayout)) {

                $clayout = 'default';

                // Get the audios
                //$result = $model->getItems($user->id);

                $options['featured'] = false;

                $audios = $model->getAudios($options);
                //$user->isViewer()
                // Set the page title
                FD::page()->title($user->name.' Audios');
                // Get featured videos
                // Get featured videos
                $featuredAudios = array();
                $options['featured'] = true;
                $options['limit'] = 1;
                $featuredAudios = $model->getAudios($options);

//            $stream	= FD::stream();
//
//		foreach( $audios as &$audio )
//		{
//			$comments			= FD::comments( $audio->id , 'audios' , 'create', SOCIAL_APPS_GROUP_USER , array( 'url' => FRoute::apps( array( 'layout' => 'canvas', 'userid' => $my->getAlias() , 'cid' => $audio->id ) ) ) );
//			$likes 				= FD::likes( $audio->id , 'audios', 'create', SOCIAL_APPS_GROUP_USER );
//
//			$options 		= array( 'comments' => $comments , 'likes' => $likes );
//
//			$audio->actions 	= $stream->getActions( $options );
//		}


//        $returnUrl = base64_encode($returnUrl);
//
//        $this->set('returnUrl', $returnUrl);
// 		$this->set('uid', $uid);
// 		$this->set('type', $type);
// 		$this->set('adapter', $adapter);
                $this->set('audios', $audios);
                $this->set('featuredAudios', $featuredAudios);
            }

            $aid = $this->input->get('aid', 0, 'int');
            if ($clayout == "form") {
                $audios = $model->getTable('Audio'); //default empty
                $catid = '';
                if ($aid) {
                    //load audio data
                    $options['aid'] = $aid;
                    $audios = $model->getAudios($options);
                    $this->set('audio', $audios[0]);
                    $catid = $audios[0]->category_id;
                }
                $this->set('categories', $model->getCategoryListHtml($catid));
            }
            // Get application params
            if ($clayout == "all" or $clayout=="all2"){
                $audios = $model->getAudios($options);
                $this->set('audios', $audios);
            }

            $params = $this->app->getParams();

            $this->set('params', $params);

            echo parent::display('canvas/' . $clayout);
        }

    }
}