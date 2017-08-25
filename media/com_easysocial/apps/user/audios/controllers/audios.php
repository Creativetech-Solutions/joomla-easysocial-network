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

//$ajax	= FD::ajax();
//		$html 	= '';
//
//		// Get user list
//		$theme 		= FD::get( 'Themes' );
//		$theme->set( 'users', $users );
//		$html 		= $theme->output( 'site/users/simplelist' );
//
//		return $ajax->resolve( $html );
if (!class_exists('AudiosControllerAudios')) {
    class AudiosControllerAudios extends SocialAppsController {

        /**
         * Renders the audios form
         *
         * @since	1.0
         * @access	public
         * @param	string
         * @return
         */
        public function form() {
            // Check for request forgeriess
            FD::checkToken();

            // Ensure that the user is logged in.
            FD::requireLogin();

            // Load up ajax lib
            $ajax = FD::ajax();

            $id = JRequest::getVar('id');

            $audio = $this->getTable('Audio');
            $state = $audio->load($id);

            $my = FD::user();

            // Check if the user is allowed to edit this audio
            if ($id && $audio->user_id != $my->id) {
                return $ajax->reject();
            }

            // Set the params
            $params = $this->getParams();

            // Load the contents
            $theme = FD::themes();
            $theme->set('audio', $audio);
            $theme->set('params', $params);

            $contents = $theme->output('apps/user/audios/dialog.form');

            return $ajax->resolve($contents);
        }

        //through ajax
        public function getAudios() {
            // Check for request forgeriess
            FD::checkToken();

            // Ensure that the user is logged in.
            FD::requireLogin();

            // Load up ajax lib
            $ajax = FD::ajax();

            $id = JRequest::getVar('id');
            if (!$id) {
                return $ajax->resolve('No user Id provided');
            }

            $options = array();
            $options['uid'] = $id;
            $options['userid'] = $id;
            $options['type'] = 'user';
            $options['privacy'] = false;
            $options['filter'] = SOCIAL_TYPE_USER;
            $options['limit'] = 5;
            $audios = $this->getModel('Audios')->getAudios($options);

            if (!count($audios)) {
                return $ajax->resolve('');
            }


            // Set the params
            $params = $this->getParams();

            // Load the contents
            $theme = FD::themes();
            $theme->set('audios', $audios);
            $theme->set('params', $params);

            $contents = $theme->output('apps/user/audios/dashboard/latest');

            return $ajax->resolve($contents);
        }

        /**
         * Displays a delete confirmation dialog
         *
         * @since	1.0
         * @access	public
         * @param	string
         * @return
         */
        public function confirmDelete() {
            // Check for request forgeries
            FD::checkToken();

            // User needs to be logged in
            FD::requireLogin();

            // Load up ajax library
            $ajax = FD::ajax();

            // Get the delete confirmation dialog
            $theme = FD::themes();

            $contents = $theme->output('apps/user/audios/dialog.delete');

            return $ajax->resolve($contents);
        }

        public function confirmFeature() {
            // Check for request forgeries
            FD::checkToken();

            // User needs to be logged in
            FD::requireLogin();

            // Load up ajax library
            $ajax = FD::ajax();
            $id = JRequest::getInt('id');


            $audio = $this->getTable('audio');
            $audio->load($id);

            // Throw error when the id not valid
            $featured = $audio->isFeatured();
            // Get the delete confirmation dialog
            $theme = FD::themes();

            $contents = $theme->output('apps/user/audios/dialog.feature', array('featured' => $featured));

            return $ajax->resolve($contents);
        }

        public function feature() {
            // Check for request forgeries
            FD::checkToken();

            // User needs to be logged in
            FD::requireLogin();

            $id = JRequest::getInt('id');

            // Load up ajax library
            $ajax = FD::ajax();

            $audio = $this->getTable('audio');
            $audio->load($id);

            // Throw error when the id not valid
            if (!$id || !$audio->id) {
                return $ajax->reject();
            }

            // Get the current logged in user as we only want the current logged
            $my = FD::user();

            if ($audio->user_id != $my->id) {
                return $ajax->reject();
            }

            $state = $audio->feature();

            if (!$state) {
                return $ajax->reject(JText::_($audio->getError()));
            }

            return $ajax->resolve();
        }

        /**
         * Deletes a audio from the server
         *
         * @since	1.0
         * @access	public
         * @param	string
         * @return
         */
        public function delete() {
            // Check for request forgeries
            FD::checkToken();

            // User needs to be logged in
            FD::requireLogin();

            $id = JRequest::getInt('id');

            // Load up ajax library
            $ajax = FD::ajax();

            $audio = $this->getTable('audio');
            $audio->load($id);

            // Throw error when the id not valid
            if (!$id || !$audio->id) {
                return $ajax->reject();
            }

            // Get the current logged in user as we only want the current logged
            $my = FD::user();

            if ($audio->user_id != $my->id) {
                return $ajax->reject();
            }

            $state = $audio->delete();

            if (!$state) {
                return $ajax->reject(JText::_($audio->getError()));
            }

            return $ajax->resolve();
        }

        /**
         * Creates a new audio.
         *
         * @since	1.0
         * @access	public
         */
        public function store() {
            // Check for request forgeriess
            FD::checkToken();

            // Ensure that the user is logged in.
            FD::requireLogin();

            // Get ajax lib
            $ajax = FD::ajax();

            // Get the current user.
            $my = FD::user();

            // Check if this is an edited entry
            $id = JRequest::getInt('aid');
            $thumbUpload = JRequest::getVar('thumbUpload', false);

//        print_r($_REQUEST);
//        print_r($_FILES);exit;
            //SOCIAL_TMP
            //if upload thumb comes as a file (during ajax), save it
            $tmpThumb = $_FILES['thumbnail'];
            if ($thumbUpload) {
                if (is_array($tmpThumb)) {
                    //saveThumb
                    $uploadedFileNameParts = explode('.', $tmpThumb['name']);
                    $uploadedFileExtension = array_pop($uploadedFileNameParts);
                    $thumbFileName = 'TMP_THUMB.' . $uploadedFileExtension;

                    $fileSuccess = $this->filecheck('thumbnail', $thumbFileName, 'jpg,jpeg,png');
                    if ($fileSuccess !== true) {
                        $response = array("success" => false, "error" => $fileSuccess);
                        echo json_encode($response);
                        jexit();
                    } else {
                        $fileUrl = ES_SOCIAL_MEDIA_AUDIOS_STORAGE_URI . '/' . $my->id . '/' . $thumbFileName;
                        $response = array("success" => true, "return" => $fileUrl);
                        echo json_encode($response);
                        jexit();
                    }
                } else {
                    $response = array("success" => false, "error" => 'No file found');
                    echo json_encode($response);
                    jexit();
                }
            }


            $thumbnail = JRequest::getVar('thumbnailImage', false);

            // Create the audio
            $audio = $this->getTable('Audio');
            $state = $audio->load($id);
            if ($id && $state) {
                if ($audio->user_id != $my->id) {
                    $this->app->enqueueMessage(JText::_('Not allowed'), 'error');
                    $this->app->redirect(FRoute::_('index.php?option=com_easysocial&view=apps&layout=canvas&clayout=form&id=' . $audio->getAppAlias() . '&aid=' . $audio->id));
                }
            }
            $audioFileName = '';
            $thumbnail_name = '';
            $edit = $id ? true : false;
            if (!$id) {
                $audioFileName = preg_replace("/[^A-Za-z0-9][.]/i", "-", $_FILES['audio_file']['name']);
                $fileSuccess = $this->filecheck('audio_file', $audioFileName, 'mp3');
                if ($fileSuccess !== true) {
                    $response = array("success" => false, "error" => $fileSuccess);
                    echo json_encode($response);
                    jexit();
                }
            }
            if (!empty($thumbnail)) {
                $img = str_replace('data:image/png;base64,', '', $thumbnail);
                $img = str_replace(' ', '+', $img);
                $imgData = base64_decode($img);
                $thumbnail_name = time() . '.png';
                file_put_contents(ES_SOCIAL_MEDIA_AUDIOS_STORAGE . '/' . $my->id . '/' . $thumbnail_name, $imgData);
                //resize the damn thing
                $this->createThumbnail($thumbnail_name, 320, 320, ES_SOCIAL_MEDIA_AUDIOS_STORAGE . '/' . $my->id, ES_SOCIAL_MEDIA_AUDIOS_STORAGE . '/' . $my->id);
                //remove temp thumbnails
                foreach (glob(ES_SOCIAL_MEDIA_AUDIOS_STORAGE . '/' . $my->id . '/' . 'TMP_THUMB*') as $filename) {
                    unlink($filename);
                }
            }
            //if editing, delete the old thumbnail
            if ($edit) {
                $audio->deleteThumbnail();
            }
            // Get the title from request
            $title = JRequest::getVar('title');
            $description = JRequest::getVar('description');
            $category = JRequest::getVar('category_id');
            $tags = JRequest::getVar('tags');

            if (!$id) {
                $audio->file_title = $audioFileName;
                $audio->user_id = $my->id;
                $audio->uid = $my->id;
                $audio->state = SOCIAL_STATE_PUBLISHED;
                $audio->created = FD::date()->toMySQL();
                $audio->type = 'user';
            }
            $audio->title = $title;

            $audio->description = $description;

            $audio->category_id = $category;

            if (!empty($thumbnail_name)) {
                $audio->thumbnail = $thumbnail_name;
            }
//        print_r($audio);
//        exit;
            $state = $audio->store();


            if (!$state) {
                $response = array("success" => false, "error" => $audio->getError());
                echo json_encode($response);
                jexit();
            }

            //app alias
            $audio->link = FRoute::_('index.php?option=com_easysocial&view=apps&layout=canvas&clayout=form&id=' . $audio->getAppAlias() . '&aid=' . $audio->id);

            // Format the audio comments
            // Get the comments count
//        $comments = FD::comments($audio->id, 'audios', 'create', SOCIAL_APPS_GROUP_USER, array('url' => FRoute::apps(array('layout' => 'canvas', 'userid' => $my->getAlias(), 'cid' => $audio->id))));
//        $audio->comments = $comments->getCount();
//
//        // Get the likes count
//        $likes = FD::likes($audio->id, 'audios', 'create', SOCIAL_APPS_GROUP_USER);
//
//        $audio->likes = $likes->getCount();
            // Create a stream record

            $verb = $id ? 'update' : 'create';
            $audio->createStream($verb);


            $msg = $edit ? "COM_COMMUNITY_AUDIO_UPDATED_SUCCESS" : "COM_COMMUNITY_AUDIO_UPLOADED_SUCCESS";
            $this->app->enqueueMessage(JText::_($msg), 'success');
            $this->app->redirect($audio->link);
//
//        $response = array("success" => true, "return" => $audio->link);
//        echo json_encode($response);
//        jexit();
//        // Format the audio for display.
//        $comments = FD::comments($audio->id, 'audios', 'create', SOCIAL_APPS_GROUP_USER, array('url' => FRoute::apps(array('layout' => 'canvas', 'userid' => $my->getAlias(), 'cid' => $audio->id))));
//        $likes = FD::likes($audio->id, 'audios', 'create', SOCIAL_APPS_GROUP_USER);
//        $stream = FD::stream();
//        $options = array('comments' => $comments, 'likes' => $likes);
//
//        $audio->actions = $stream->getActions($options);
//
//        $app = $this->getApp();
//        $theme = FD::themes();
//        $theme->set('app', $app);
//        $theme->set('user', $my);
//        $theme->set('appId', $appId);
//        $theme->set('audio', $audio);
//
//        $content = $theme->output('apps/user/audios/dashboard/item');
//
//        return $ajax->resolve($content);
        }

        function createThumbnail($image_name, $new_width, $new_height, $uploadDir, $moveToDir) {
            $path = $uploadDir . '/' . $image_name;

            $mime = getimagesize($path);

            if ($mime['mime'] == 'image/png') {
                $src_img = imagecreatefrompng($path);
            }
            if ($mime['mime'] == 'image/jpg' || $mime['mime'] == 'image/jpeg' || $mime['mime'] == 'image/pjpeg') {
                $src_img = imagecreatefromjpeg($path);
            }

            $old_x = imageSX($src_img);
            $old_y = imageSY($src_img);

            if ($old_x > $old_y) {
                $thumb_w = $new_width;
                $thumb_h = $old_y * ($new_height / $old_x);
            }

            if ($old_x < $old_y) {
                $thumb_w = $old_x * ($new_width / $old_y);
                $thumb_h = $new_height;
            }

            if ($old_x == $old_y) {
                $thumb_w = $new_width;
                $thumb_h = $new_height;
            }

            $dst_img = ImageCreateTrueColor($thumb_w, $thumb_h);

            imagecopyresampled($dst_img, $src_img, 0, 0, 0, 0, $thumb_w, $thumb_h, $old_x, $old_y);


            // New save location
            $new_thumb_loc = $moveToDir . '/' . $image_name;

            if ($mime['mime'] == 'image/png') {
                $result = imagepng($dst_img, $new_thumb_loc, 8);
            }
            if ($mime['mime'] == 'image/jpg' || $mime['mime'] == 'image/jpeg' || $mime['mime'] == 'image/pjpeg') {
                $result = imagejpeg($dst_img, $new_thumb_loc, 80);
            }

            imagedestroy($dst_img);
            imagedestroy($src_img);

            return $result;
        }

        function filecheck($fieldName, $newName, $types) {

            jimport('joomla.filesystem.file');
            jimport('joomla.filesystem.folder');

            $filesizeMax = 8000000; //8MB
            //any errors the server registered on uploading
            $fileError = $_FILES[$fieldName]['error'];
            if ($fileError > 0) {
                switch ($fileError) {
                    case 1:
                        return JText::_('FILE TO LARGE THAN PHP INI ALLOWS');

                    case 2:
                        return JText::_('FILE TO LARGE THAN HTML FORM ALLOWS');

                    case 3:
                        return JText::_('ERROR PARTIAL UPLOAD');

                    case 4:
                        return JText::_('ERROR NO FILE');
                }
            }

            //check for filesize
            $fileSize = $_FILES[$fieldName]['size'];
            if ($fileSize > $filesizeMax) {
                return JText::_('FILE BIGGER THAN 2MB');
            }

            //check the file extension is ok
            $fileTemp = $_FILES[$fieldName]['name'];
            $uploadedFileNameParts = explode('.', $fileTemp);
            $uploadedFileExtension = array_pop($uploadedFileNameParts);

            $validFileExts = explode(',', $types);

            //assume the extension is false until we know its ok
            $extOk = false;

            //go through every ok extension, if the ok extension matches the file extension (case insensitive)
            //then the file extension is ok
            foreach ($validFileExts as $key => $value) {
                if (preg_match("/$value/i", $uploadedFileExtension)) {
                    $extOk = true;
                }
            }

            if ($extOk == false) {
                return JText::_('INVALID EXTENSION');
            }

            //the name of the file in PHP's temp directory that we are going to move to our folder
//
//        //we are going to define what file extensions/MIMEs are ok, and only let these ones in (whitelisting), rather than try to scan for bad
//        //types, where we might miss one (whitelisting is always better than blacklisting)
//        $okMIMETypes = 'audio/mpeg';
//        $validFileTypes = explode(",", $okMIMETypes);
            //lose any special characters in the filename
            $fileName = $newName;

            //always use constants when making file paths, to avoid the possibilty of remote file inclusion
            $my = FD::user();
            if (!file_exists(ES_SOCIAL_MEDIA_AUDIOS_STORAGE . '/' . $my->id)) {
                if (!mkdir(ES_SOCIAL_MEDIA_AUDIOS_STORAGE . '/' . $my->id, 0755, true)) {
                    return 'Directory creation error';
                }
            }

            $uploadPath = ES_SOCIAL_MEDIA_AUDIOS_STORAGE . '/' . $my->id . '/' . $fileName;

            if (!JFile::upload($_FILES[$fieldName]['tmp_name'], $uploadPath)) {
                return JText::_('ERROR MOVING FILE');
            } else {
                return true;
            }
        }

    }
}

