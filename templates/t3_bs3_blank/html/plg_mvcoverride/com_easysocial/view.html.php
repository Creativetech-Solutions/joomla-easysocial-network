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
//require_once JPATH_ROOT.'administrator/components/com_easysocial/includes/router.php';


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

            $app = JFactory::getApplication();
            $input = $app->input;
            $orderby = $input->get('orderby','date');
            $datedirection = $input->get('datedirection','desc');
            $titledirection = $input->get('titledirection','desc');
            $playsdirection = $input->get('playsdirection','asc');
            $likesdirection = $input->get('likesdirection','asc');
            $this->set('orderby', $orderby);
            $this->set('datedirection',$datedirection);
            $this->set('titledirection',$titledirection);
            $this->set('playsdirection',$playsdirection);
            $this->set('likesdirection',$likesdirection);

            if ($this->my->id == 0 || $uid == 0) {
                ES::requireLogin();
            }
            if ($uid == 0 && $this->my->id != 0) {
                $uid = $this->my->id;
            }
            $mid = ES::user($uid)->id;
            $user = ES::user($mid);
            $this->set('user', $user);

            if($user->name[strlen($user->name)-1] == 's'){
                $owner = $user->name.'\'';
            }else{
                $owner = $user->name.'\'s';
            }
            $this->set('owner', $owner);

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
                $options['featured'] = false;

                $audios = $model->getAudios($options);
                FD::page()->title($user->name.' Audios');
                $featuredAudios = array();
                $options['featured'] = true;
                $options['limit'] = 1;
                $featuredAudios = $model->getAudios($options);
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
            if ($clayout == 'list' || $clayout == "all" or $clayout=="all2"){
                $audios = $model->getAudios($options);
                $featuredAudios = array();
                foreach($audios as $audio){
                    if($audio->featured){
                        $featuredAudios[] = $audio;
                    }
                }
                $this->set('audios', $audios);
                $this->set('featuredAudios', $featuredAudios);
            }

            $params = $this->app->getParams();

            $this->set('params', $params);

            echo parent::display('canvas/' . $clayout);
        }

    }


