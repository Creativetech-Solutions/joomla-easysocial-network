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
class ProjectsControllerProjects extends SocialAppsController {

    static $sizes = array(
        'square' => array(
            'width' => SOCIAL_PHOTOS_SQUARE_WIDTH,
            'height' => SOCIAL_PHOTOS_SQUARE_HEIGHT,
            'mode' => 'fill'
        ),
        'thumbnail' => array(
            'width' => SOCIAL_PHOTOS_THUMB_WIDTH,
            'height' => SOCIAL_PHOTOS_THUMB_HEIGHT,
            'mode' => 'outerFit'
        ),
        'featured' => array(
            'width' => SOCIAL_PHOTOS_FEATURED_WIDTH,
            'height' => SOCIAL_PHOTOS_FEATURED_HEIGHT,
            'mode' => 'outerFit'
        ),
        'large' => array(
            'width' => SOCIAL_PHOTOS_LARGE_WIDTH,
            'height' => SOCIAL_PHOTOS_LARGE_HEIGHT,
            'mode' => 'fit'
        )
    );

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

        $project = $this->getTable('Project');
        $state = $project->load($id);

        $my = FD::user();

        // Check if the user is allowed to edit this audio
        if ($id && !$project->canEdit($my->id)) {
            return $ajax->reject('You are not the owner or admin of this project.');
        }

        // Set the params
        $params = $this->getParams();

        // Load the contents
        $theme = FD::themes();
        $theme->set('project', $project);
        $theme->set('params', $params);

        $contents = $theme->output('apps/user/projects/dialog.form');

        return $ajax->resolve($contents);
    }

    //through ajax
    public function getProjects() {
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
        $projects = $this->getModel('Projects')->getProjects($options);

        if (!count($projects)) {
            return $ajax->resolve('');
        }


        // Set the params
        $params = $this->getParams();

        // Load the contents
        $theme = FD::themes();
        $theme->set('projects', $projects);
        $theme->set('params', $params);

        $contents = $theme->output('apps/user/projects/dashboard/latest');

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
        $theme->set('title', JText::_( 'APP_PROJECTS_DIALOG_DELETE_CONFIRMATION_TITLE' ));
        $theme->set('desc', JText::_( 'APP_PROJECTS_DIALOG_PROJECT_DELETE_CONFIRMATION_DESC' ));

        $contents = $theme->output('apps/user/projects/dialog.delete');

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



        $project = $this->getTable('project');
        $project->load($id);

        // Throw error when the id not valid
        $featured = $project->isFeatured();

        // Get the delete confirmation dialog
        $theme = FD::themes();
        $theme->set('id', $id);
        $theme->set('featured', $featured);

        $contents = $theme->output('apps/user/projects/dialog.feature');

        return $ajax->resolve($contents);
    }

    /**
     * Displays confirmation to unfeature videos
     *
     * @since	1.4
     * @access	public
     * @param	string
     * @return
     */
    public function confirmUnfeature() {
        // Get the video id
        $id = $this->input->get('id', 0, 'int');

        // Determines if the user wants to specify a custom callback url
        $callback = $this->input->get('callbackUrl', '', 'default');

        // Ensure that the user is really allowed to delete this video
        $videoTable = ES::table('Video');
        $videoTable->load($id);

        $video = ES::video($videoTable->uid, $videoTable->type, $videoTable);

        if (!$video->canUnfeature()) {
            return JError::raiseError(500, JText::_('COM_EASYSOCIAL_VIDEOS_NOT_ALLOWED_TO_UNFEATURE'));
        }

        $theme = ES::themes();
        $theme->set('id', $id);
        $theme->set('callback', $callback);

        $output = $theme->output('site/videos/dialog.unfeature');

        return $this->ajax->resolve($output);
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

        $project = $this->getTable('project');
        $project->load($id);

        // Throw error when the id not valid
        if (!$id || !$project->id) {
            return $ajax->reject();
        }

        // Get the current logged in user as we only want the current logged
        $my = FD::user();

        if (!$project->canEdit($my->id)) {
            return $ajax->reject('You are not an owner or admin of this project.');
        }

        if ($feature == true) {
            if (!$this->unFeatureAll($id)) {
                return $ajax->reject(JText::_('An error occured while reseting all featured projects.'));
            }
        }


        if ($feature == 'true') {
            $project->featured = 1;
        } elseif ($feature == 'false') {
            $project->featured = 0;
        }

        $state = $project->store();
        //$state = $project->feature();

        if (!$state) {
            return $ajax->reject(JText::_($project->getError()));
        }

        return $ajax->resolve();
    }

    public function unFeatureAll($id = '') {
        if (!$id) {
            return false;
        }

        $db = ES::db();

        $query = "UPDATE #__social_projects SET `featured` = 0 WHERE `id` != " . $id;
        $db->setQuery($query);
        $result = $db->execute();

        return $result;
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

        $project = $this->getTable('project');
        $project->load($id);

        // Throw error when the id not valid
        if (!$id || !$project->id) {
            return $ajax->reject();
        }

        // Get the current logged in user as we only want the current logged
        $my = FD::user();

        if (!$project->canEdit($my->id)) {
            return $ajax->reject('You are not an owner or admin of this project.');
        }

        $state = $project->delete();

        if (!$state) {
            return $ajax->reject(JText::_($project->getError()));
        }

        return $ajax->resolve();
    }

    public function generateFilename($size, $fileName = '', $image) {
        if (empty($fileName)) {
            $fileName = $image->getName(false);
        }

        // Remove any previously _stock from the image name
        $fileName = str_ireplace('_stock', '', $fileName);

        $extension = $image->getExtension();

        $fileName = str_ireplace($extension, '', $fileName);

        // Ensure that the file name is lowercased
        $fileName = strtolower($fileName);

        // Ensure that the file name is valid
        $fileName = JFilterOutput::stringURLSafe($fileName);

        // Append the size and extension back to the file name.
        $fileName = $fileName . '_' . $size . $extension;

        return $fileName;
    }

    //create project image and thumbnails
    public function createProjectImage($file, $inputName, $project) {
        jimport('joomla.filesystem.folder');
        jimport('joomla.filesystem.file');
        // Load our own image library
        $image = FD::image();

        // Get the current user.
        $my = FD::user();

        // Generates a unique name for this image.
        $name = $file['name'];

        // Load up the file.
        $image->load($file['tmp_name'], $name);

        // Ensure that the image is valid.
        if (!$image->isValid()) {
            // @TODO: Add some logging here.
            echo JText::_('PLG_FIELDS_AVATAR_VALIDATION_INVALID_IMAGE');
            exit;
        }
        // Get the storage path
        $storage = ES_SOCIAL_MEDIA_PROJECTS_STORAGE . '/' . $my->id . '/' . $project->id;

        // Create folder if necessary.
        if (!JFolder::exists($storage)) {
            FD::makeFolder($storage);
        }

        // Re-generate the storage path since we do not want to store the JPATH_ROOT
        $path = str_replace(JPATH_ROOT, '', $storage);

        // Create avatars
        $sizes = $this->create($path, array(), '', $image);
        // We want to format the output to get the full absolute url.
        $result = array();
        foreach ($sizes as $size => $value) {
            $row = new stdClass();

            $row->title = $file['name'];
            $row->file = $value;
            $row->path = $path;
            $row->uri = rtrim(JURI::root(), '/') . $path . '/' . $value;

            $result[$size] = $row;
        }

        return $result;
    }

    public function create($path, $exclusion = array(), $overrideFileName = '', $image) {
        // Files array store a list of files
        // created for this photo.
        $files = array();

        // Create stock image
        $filename = $this->generateFilename('stock', $overrideFileName, $image);

        $file = $path . '/' . $filename;
        $files['stock'] = $filename;

        $image->copy(JPATH_ROOT . $path . '/' . $filename);

        // Create original image
        $filename = $this->generateFilename('original', $overrideFileName, $image);
        $file = JPATH_ROOT . $path . '/' . $filename;
        $files['original'] = $filename;
        $image->rotate(0); // Fake an operation queue
        $image->save($file);

        // Use original image as source image
        // for all other image sizes.
        $sourceImage = FD::image()->load($file);

        // Create the rest of the image sizes
        foreach (self::$sizes as $name => $size) {
            if (in_array($name, $exclusion))
                continue;
            // Clone an instance of the source image.
            // Otherwise subsequent resizing operations
            // in this loop would end up using the image
            // instance that was resized by the previous loop.
            $imageclone = $sourceImage->cloneImage();

            $filename = $this->generateFilename($name, $overrideFileName, $image);
            $file = JPATH_ROOT . $path . '/' . $filename;
            $files[$name] = $filename;

            // Resize image
            $method = $size['mode'];
            $imageclone->$method($size['width'], $size['height']);

            // Save image
            $imageclone->save($file);

            // Free up memory
            unset($imageclone);
        }

        return $files;
    }

    /**
     * Creates a new project.
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

        $data = $this->input->getVar('post');
        parse_str($data, $post);

        // Check if this is an edited entry
        $id = $post['prid'];
        // $post['website']= implode(',',$post['website']);
        // Create the project
        $project = $this->getTable('Project');
        $state = $project->load($id);
        if ($id && $state) {
            if (!$project->canEdit($my->id)) {
                return $ajax->reject(JText::_('You are not an owner or admin of this project.'));
            }
        }

        $registry = ES::registry();
        // Get disallowed keys so we wont get wrong values.
        $disallowed = array(ES::token(), 'option', 'task', 'controller', 'appId', 'appTask', 'appController', 'thumbUpload', 'current_step', 'all_steps');
        // Process $_POST vars
        foreach ($post as $key => $value) {
            if (!in_array($key, $disallowed)) {
                if (is_array($value)) {
                    $value = json_encode($value);
                }
                $registry->set($key, $value);
            }
        }

        // Convert the values into an array.
        $data = $registry->toArray();
        $project->bind($data);
        $state = $project->store();
        if (!$state) {
            return $ajax->reject(JText::_('Data not saved in table'));
        }

        $thumbUpload = $post['thumbUpload'];

        $tmpThumb = $_FILES['project_image'];

        $file = array();

        //if upload thumb comes as a file (during ajax), save it
        //$tmpThumb = $_FILES['project_image'];
        if ($thumbUpload && $tmpThumb['tmp_name'] != '') {
            foreach ($tmpThumb as $k => $v) {
                $file[$k] = $v['file'];
            }

            if (!isset($file['tmp_name']) || empty($file['tmp_name'])) {
                return $ajax->reject(JText::_('PLG_FIELDS_COVER_VALIDATION_INVALID_IMAGE'));
            }
            // We need to perform sanity checking here
            $options = array('name' => 'project_image', 'maxsize' => '4M', 'multiple' => false);
            $uploader = ES::uploader($options);
            $file = $uploader->getFile(null);
            // If there was an error getting uploaded file, stop.
            if ($file instanceof SocialException) {
                return $ajax->reject($file->message);
            }
            $result = $this->createProjectImage($file, 'project_image', $project);

            $project->file_title = $result['original']->title;
            $project->path = $result['original']->path;
            $project->thumbnail = $result['thumbnail']->file;
            $project->original = $result['original']->file;
        }
        if (!empty($post['project_image_position'])) {
            $position = FD::makeObject($post['project_image_position']);

            if (isset($position->x)) {
                $project->x = $position->x;
            }

            if (isset($position->y)) {
                $project->y = $position->y;
            }
        }
        $edit = $id ? true : false;

        $project->user_id = $my->id;
        $project->uid = $my->id;
        $project->state = SOCIAL_STATE_PUBLISHED;
        $project->created = FD::date()->toMySQL();
        $project->type = 'user';

        $state = $project->store();
        if (!$state) {
            return $ajax->reject(JText::_('Data not saved in table'));
        }
        // Create a stream record

        $verb = $id ? 'update' : 'create';
        $project->createStream($verb);

        $link = FRoute::_('index.php?option=com_easysocial&view=apps&layout=canvas&clayout=form&id=' . $project->getAppAlias() . '&prid=' . $project->id);

        return $ajax->resolve(json_encode(array('project_id' => $project->id, 'project_url' => $link)));
    }

    public function upload() {
        // Check for request forgeriess
        FD::checkToken();

        // Ensure that the user is logged in.
        FD::requireLogin();

        // Get ajax lib
        $ajax = FD::ajax();

        // Get the current user.
        $my = FD::user();

        $data = $this->input->getVar('post');
        parse_str($data, $post);

        // Check if this is an edited entry
        $id = $post['prid'];
        // $post['website']= implode(',',$post['website']);
        // Create the project
        $project = $this->getTable('Project');
        $state = $project->load($id);
        if ($id && $state) {
            if (!$project->canEdit($my->id)) {
                return $ajax->reject(JText::_('You are not an owner or admin of this project.'));
            }
        }

        $registry = ES::registry();
        // Get disallowed keys so we wont get wrong values.
        $disallowed = array(ES::token(), 'option', 'task', 'controller', 'appId', 'appTask', 'appController', 'thumbUpload', 'current_step', 'all_steps');
        // Process $_POST vars
        foreach ($post as $key => $value) {
            if (!in_array($key, $disallowed)) {
                if (is_array($value)) {
                    $value = json_encode($value);
                }
                $registry->set($key, $value);
            }
        }

        // Convert the values into an array.
        $data = $registry->toArray();
        $project->bind($data);
        $state = $project->store();
        if (!$state) {
            return $ajax->reject(JText::_('Data not saved in table'));
        }

        //$thumbUpload = $post['thumbUpload'];

        $tmpThumb = $_FILES['project_image'];

        $file = array();

        //if upload thumb comes as a file (during ajax), save it
        //$tmpThumb = $_FILES['project_image'];
        if ($tmpThumb['tmp_name'] != '') {
            foreach ($tmpThumb as $k => $v) {
                $file[$k] = $v['file'];
            }

            if (!isset($file['tmp_name']) || empty($file['tmp_name'])) {
                return $ajax->reject(JText::_('PLG_FIELDS_COVER_VALIDATION_INVALID_IMAGE'));
            }
            // We need to perform sanity checking here
            $options = array('name' => 'project_image', 'maxsize' => '4M', 'multiple' => false);
            $uploader = ES::uploader($options);
            $file = $uploader->getFile(null);
            // If there was an error getting uploaded file, stop.
            if ($file instanceof SocialException) {
                return $ajax->reject($file->message);
            }
            $result = $this->createProjectImage($file, 'project_image', $project);

            $project->file_title = $result['original']->title;
            $project->path = $result['original']->path;
            $project->thumbnail = $result['thumbnail']->file;
            $project->original = $result['original']->file;
        }
        $edit = $id ? true : false;

        $project->user_id = $my->id;
        $project->uid = $my->id;
        $project->state = SOCIAL_STATE_PUBLISHED;
        $project->created = FD::date()->toMySQL();
        $project->type = 'user';

        $state = $project->store();
        if (!$state) {
            return $ajax->reject(JText::_('Data not saved in table'));
        }

        return $ajax->resolve(json_encode(array('project_id' => $project->id, 'result' => $result)));
    }

    public function save() {
        return $this->saveClose();
    }

    public function saveClose() {
        // Check for request forgeriess
        FD::checkToken();

        // Ensure that the user is logged in.
        FD::requireLogin();

        // Get the current user.
        $my = FD::user();

        $data = $_POST;
        $post = $data;

        // Check if this is an edited entry
        $id = $post['prid'];

        // Create the project
        $project = $this->getTable('Project');
        $state = $project->load($id);
        if ($id && $state) {
            if (!$project->canEdit($my->id)) {
                $this->app->enqueueMessage(JText::_('You are not an owner or admin of this project.'), 'error');
                $this->app->redirect(FRoute::_('index.php?option=com_easysocial&view=apps&layout=canvas&clayout=form&id=' . $project->getAppAlias() . '&prid=' . $project->id));
            }
        }

        $registry = ES::registry();
        // Get disallowed keys so we wont get wrong values.
        $disallowed = array(ES::token(), 'option', 'task', 'controller', 'appId', 'appTask', 'appController', 'thumbUpload', 'current_step', 'all_steps');
        // Process $_POST vars
        foreach ($post as $key => $value) {
            if (!in_array($key, $disallowed)) {
                if (is_array($value)) {
                    $value = json_encode($value);
                }
                $registry->set($key, $value);
            }
        }

        // Convert the values into an array.
        $data = $registry->toArray();
        $project->bind($data);

        $project->user_id = $my->id;
        $project->uid = $my->id;
        $project->state = SOCIAL_STATE_PUBLISHED;
        $project->created = FD::date()->toMySQL();
        $project->type = 'user';
        $state = $project->store();

        if (!$state) {
            $this->app->enqueueMessage(JText::_('There was an error while saving the data. Please try again.'), 'error');
            $this->app->redirect(FRoute::_('index.php?option=com_easysocial&view=apps&layout=canvas&clayout=form&id=' . $project->getAppAlias() . '&prid=' . $project->id));
        }

        $thumbUpload = $post['thumbUpload'];
        $tmpThumb = $_FILES['project_image'];

        $file = array();

        //if upload thumb comes as a file (during ajax), save it
        //$tmpThumb = $_FILES['project_image'];
        if ($thumbUpload === true) {
            foreach ($tmpThumb as $k => $v) {
                $file[$k] = $v['file'];
            }

            if (!isset($file['tmp_name']) || empty($file['tmp_name'])) {
                $this->app->enqueueMessage(JText::_('PLG_FIELDS_COVER_VALIDATION_INVALID_IMAGE'), 'error');
                $this->app->redirect(FRoute::_('index.php?option=com_easysocial&view=apps&layout=canvas&clayout=form&id=' . $project->getAppAlias() . '&prid=' . $project->id));
            }

            // We need to perform sanity checking here           
            $options = array('name' => 'project_image', 'maxsize' => '4M', 'multiple' => false);
            $uploader = ES::uploader($options);
            $file = $uploader->getFile(null);
            // If there was an error getting uploaded file, stop.
            if ($file instanceof SocialException) {
                $this->app->enqueueMessage($file->message, 'error');
                $this->app->redirect(FRoute::_('index.php?option=com_easysocial&view=apps&layout=canvas&clayout=form&id=' . $project->getAppAlias() . '&prid=' . $project->id));
            }
            $result = $this->createProjectImage($file, 'project_image', $project);

            $project->file_title = $result['original']->title;
            $project->path = $result['original']->path;
            $project->thumbnail = $result['thumbnail']->file;
            $project->original = $result['original']->file;
        }
        
        if (!empty($post['project_image_position'])) {
            $position = FD::makeObject($post['project_image_position']);

            if (isset($position->x)) {
                $project->x = $position->x;
            }

            if (isset($position->y)) {
                $project->y = $position->y;
            }
        }
        
        $edit = $id ? true : false;

        $state = $project->store();
        if (!$state) {
            $this->app->enqueueMessage(JText::_('Data not saved in table'), 'error');
            $this->app->redirect(FRoute::_('index.php?option=com_easysocial&view=apps&layout=canvas&clayout=form&id=' . $project->getAppAlias() . '&prid=' . $project->id));
        }

        //app alias
        $project->link = FRoute::_('index.php?option=com_easysocial&view=apps&layout=canvas&clayout=form&id=' . $project->getAppAlias() . '&prid=' . $project->id);

        // Create a stream record

        $verb = $id ? 'update' : 'create';
        $project->createStream($verb);

        //update team info if any
        if ($post['team_role']) {
            $invites = $this->getModel('Invitations');
            foreach ($post['team_role'] as $key => $value) {
                //echo 'key: ' . $key . '<br>' . $value . '<br>';
                $is_admin = (in_array($key,$post['admin_ids'])) ? 1 : 0;
                $invites->addMemberRole($project->id, array('user_id' => $key, 'role' => $value,'is_admin'=>$is_admin));
            }
        }
        //exit;

        $msg = $edit ? "COM_COMMUNITY_PROJECT_UPDATED_SUCCESS" : "COM_COMMUNITY_PROJECT_UPLOADED_SUCCESS";
        $this->app->enqueueMessage(JText::_($msg), 'success');
        $this->app->redirect($project->link);
    }

    public function showFormError() {
        // Only registered users can see this
        FD::requireLogin();

        $ajax = FD::ajax();

        $theme = FD::themes();

        $contents = $theme->output('apps/user/projects/dialog.project.error');

        return $ajax->resolve($contents);
    }
    
    public function removeMemberDialog() {
        FD::requireLogin();

        $pid = $this->input->get('pid', '0', 'int');
        $member_id = $this->input->get('member_id', '0', 'int');

        $theme = FD::themes();
        $theme->set('pid', $pid);
        $theme->set('member_id', $member_id);
        
        $project = $this->getTable('Project');
        $state = $project->load($pid);

        if (empty($project) || empty($project->id)) {
            return $this->ajax->reject(JText::_('COM_EASYSOCIAL_PROJECT_INVALID_PROJECT_ID'), SOCIAL_MSG_ERROR);
        }

        if (!$project->isPublished()) {
            return $this->ajax->reject(JText::_('COM_EASYSOCIAL_PROJECT_UNAVAILABLE'), SOCIAL_MSG_ERROR);
        }
        
        $contents = $theme->output('apps/user/projects/dialog.removeMember');

        return $this->ajax->resolve($contents);
    }
    
    public function removeMember() {

        $member_id = $this->input->get('member_id', '0', 'int');
        $pid = $this->input->get('pid', '0', 'int');
        $id = $this->input->get('id', '0', 'int');

        $team = $this->getTable('ProjectTeam');
        $state = $team->load($id);
        if (!$state && $team->user_id != $member_id && $team->pid != $pid) {
            return $this->ajax->reject(JText::_('Error removing the team member. Either member id provided does not exists or the project does not exists.'));
        }
        
        if($team->delete()){
            $invites = $this->getModel('Invitations');
            if(!$invites->deleteInvitation($pid,$member_id)){
                return $this->ajax->reject(JText::_('Error removing invitation.'));
            }
        }
        return $this->ajax->resolve(array('msg' => JText::_('COM_EASYSOCIAL_PROJECT_MEMBER_SUCCESSFULLY_REMOVED')));
    }

    public function inviteFriends() {
        FD::checkToken();

        FD::requireLogin();

        $projectId = $this->input->get('id', 0, 'int');

        $project = $this->getTable('Project');
        $state = $project->load($projectId);

        if (empty($project) || empty($project->id)) {
            return $this->ajax->reject(JText::_('COM_EASYSOCIAL_PROJECT_INVALID_PROJECT_ID'), SOCIAL_MSG_ERROR);
        }

        if (!$project->isPublished()) {
            return $this->ajax->reject(JText::_('COM_EASYSOCIAL_PROJECT_UNAVAILABLE'), SOCIAL_MSG_ERROR);
        }

        /* $guest = $event->getGuest($this->my->id);

          if (!$guest->isGuest()) {
          $this->view->setMessage(JText::_('COM_EASYSOCIAL_EVENTS_NO_ACCESS_TO_EVENT'), SOCIAL_MSG_ERROR);

          return $this->view->call(__FUNCTION__);
          } */

        $ids = $this->input->get('uid', array(), 'var');

        if (empty($ids)) {
            return $this->ajax->reject(JText::_('COM_EASYSOCIAL_PROJECT_INVALID_USER_ID'), SOCIAL_MSG_ERROR);
        }
        $invites = $this->getModel('Invitations');

        foreach ($ids as $id) {
            $invite_options = array();
            $invite_options['user_id'] = $id;
            $invite = $invites->getProjectInvitations($projectId, $invite_options);
            if (!$invite) {
                $project->invite($id, $this->my->id);
            }
        }
        if ($project->getError()) {
            return $this->ajax->reject($project->getError());
        }
        $invite_errors = 0;
        foreach ($ids as $id) {
            $createInvite = $invites->createInvitation($projectId, $id);
            if (!$createInvite) {
                $invite_errors++;
            }
        }
        if ($invite_errors > 0) {
            return $this->ajax->reject(JText::_('COM_EASYSOCIAL_PROJECT_ERROR_SAVING_INVITE'), SOCIAL_MSG_ERROR);
        }
        return $this->ajax->resolve(JText::_('COM_EASYSOCIAL_EVENTS_SUCCESSFULLY_INVITED_FRIENDS'));
    }

    public function inviteFriendsDialog() {
        FD::requireLogin();

        $id = $this->input->get('id', '0', 'int');

        $project = $this->getTable('Project');
        $state = $project->load($id);

        if (empty($project) || empty($project->id)) {
            return $this->ajax->reject(JText::_('COM_EASYSOCIAL_PROJECT_INVALID_PROJECT_ID'));
        }

        if (!$project->isPublished()) {
            return $this->ajax->reject(JText::_('COM_EASYSOCIAL_PROJECT_UNAVAILABLE'));
        }

        $model = $this->getModel('Projects');
        $friends = $model->getFriendsInProject($project->id, array('userId' => $this->my->id));

        $exclusion = array();

        foreach ($friends as $friend) {
            $exclusion[] = $friend->id;
        }

        $theme = FD::themes();
        $theme->set('exclusion', $exclusion);
        $theme->set('project', $project);
        $contents = $theme->output('apps/user/projects/dialog.inviteFriends');

        return $this->ajax->resolve($contents);
    }

    public function inviteEmailDialog() {
        FD::requireLogin();

        $id = $this->input->get('id', '0', 'int');

        $project = $this->getTable('Project');
        $state = $project->load($id);

        if (empty($project) || empty($project->id)) {
            return $this->ajax->reject(JText::_('COM_EASYSOCIAL_PROJECT_INVALID_PROJECT_ID'));
        }

        if (!$project->isPublished()) {
            return $this->ajax->reject(JText::_('COM_EASYSOCIAL_PROJECT_UNAVAILABLE'));
        }


        $theme = FD::themes();
        $theme->set('project', $project);
        $contents = $theme->output('apps/user/projects/dialog.inviteEmail');

        return $this->ajax->resolve($contents);
    }

    public function inviteEmail() {
        FD::checkToken();

        FD::requireLogin();

        $my = FD::user();

        $projectId = $this->input->get('id', 0, 'int');

        $project = $this->getTable('Project');
        $state = $project->load($projectId);

        if (empty($project) || empty($project->id)) {
            return $this->ajax->reject(JText::_('COM_EASYSOCIAL_PROJECT_INVALID_PROJECT_ID'), SOCIAL_MSG_ERROR);
        }

        if (!$project->isPublished()) {
            return $this->ajax->reject(JText::_('COM_EASYSOCIAL_PROJECT_UNAVAILABLE'), SOCIAL_MSG_ERROR);
        }

        /* $guest = $event->getGuest($this->my->id);

          if (!$guest->isGuest()) {
          $this->view->setMessage(JText::_('COM_EASYSOCIAL_EVENTS_NO_ACCESS_TO_EVENT'), SOCIAL_MSG_ERROR);

          return $this->view->call(__FUNCTION__);
          } */

        // Get the list of emails
        $emails = $this->input->get('emails', '', 'html');

        if (!$emails) {
            return $this->ajax->reject(JText::_('COM_EASYSOCIAL_FRIENDS_INVITE_PLEASE_ENTER_EMAILS'), SOCIAL_MSG_ERROR);
        }

        $emails = explode(",", $emails);

        // Get the message
        $message = $this->input->get('message', '', 'default');

        /* foreach ($ids as $id) {
          $project->invite($id, $this->my->id);
          } */

        $jconfig = FD::jconfig();
        $mailer = FD::mailer();
        $template = $mailer->getTemplate();

        $sender = FD::user($my->id);

        $params = new stdClass;
        $params->senderName = $sender->getName();
        $params->message = $this->message;
        $params->siteName = $jconfig->getValue('sitename');
        $params->manageAlerts = false;
        $params->link = FRoute::registration(array('invite' => $projectId, 'external' => true));

        // it seems like some mail server disallow to change the sender name and reply to. we will commment out this for now.
        // $template->setSender($sender->getName(), $sender->email);
        // $template->setReplyTo($sender->email);
        foreach ($emails as $email) {
            $template->setRecipient('', $email);
            $template->priority = 3;
            $template->setTitle(JText::sprintf('COM_EASYSOCIAL_FRIENDS_INVITE_MAIL_SUBJECT', $jconfig->getValue('sitename')));
            $template->setTemplate('apps/user/projects/invite', $params);

            $mailer->create($template);
        }


        /* if ($mailer->getError()) {
          return $this->ajax->reject($mailer->getError());
          } */
        return $this->ajax->resolve(JText::_('COM_EASYSOCIAL_EVENTS_SUCCESSFULLY_INVITED_FRIENDS'));
    }

    public function acceptInvite() {
        FD::requireLogin();

        $my = FD::user();
        $id = $this->input->get('id', '0', 'int');
        $ntid = $this->input->get('ntid', '0', 'int');
        
        //mark notification as read
        $notification = ES::table('Notification');
        $notification->load($ntid);
        
        $project = $this->getTable('Project');
        $state = $project->load($id);

        if (empty($project) || empty($project->id)) {
            return $this->ajax->reject(JText::_('COM_EASYSOCIAL_PROJECT_INVALID_PROJECT_ID'));
        }

        if (!$project->isPublished()) {
            return $this->ajax->reject(JText::_('COM_EASYSOCIAL_PROJECT_UNAVAILABLE'));
        }

        $invites = $this->getModel('Invitations');
        $invite_options = array();
        $invite_options['user_id'] = $my->id;
        $invite = $invites->getProjectInvitations($id, $invite_options);

        if (!$invite) {
            return $this->ajax->reject('Sorry! No invitation request found.');
        } elseif ($invite->status == 1) {
            $notification->markAsRead();
            return $this->ajax->reject('You have already accepted this invitation.');
        } elseif ($invite->status == -1) {
            $notification->markAsRead();
            return $this->ajax->reject('You have already rejected this invitation.');
        } else {
            $accept_options = array();
            $accept_options['user_id'] = $my->id;
            $accept_options['status'] = 1;
            $state = $invites->acceptInvitation($id, $accept_options);
            
            $theme = FD::themes();
            $theme->set('project', $project);
            if ($state) {
                $contents = $theme->output('apps/user/projects/invite.accepted');
                $notification->markAsRead();
                return $this->ajax->resolve($contents);
            } else {
                return $this->ajax->reject('An error occured while accepting the invitation.');
            }
        }
    }
    
    public function rejectInvite() {
        FD::requireLogin();

        $my = FD::user();
        $id = $this->input->get('id', '0', 'int');
        $ntid = $this->input->get('ntid', '0', 'int');
        
        //mark notification as read
        $notification = ES::table('Notification');
        $notification->load($ntid);
        
        $project = $this->getTable('Project');
        $state = $project->load($id);

        if (empty($project) || empty($project->id)) {
            return $this->ajax->reject(JText::_('COM_EASYSOCIAL_PROJECT_INVALID_PROJECT_ID'));
        }

        if (!$project->isPublished()) {
            return $this->ajax->reject(JText::_('COM_EASYSOCIAL_PROJECT_UNAVAILABLE'));
        }

        $invites = $this->getModel('Invitations');
        $invite_options = array();
        $invite_options['user_id'] = $my->id;
        $invite = $invites->getProjectInvitations($id, $invite_options);

        if (!$invite) {
            return $this->ajax->reject('Sorry! No invitation request found.');
        } elseif ($invite->status == 1) {
            return $this->ajax->reject('You have already accepted this invitation.');
        }elseif ($invite->status == -1) {
            return $this->ajax->reject('You have already rejected this invitation.');
        } else {
            
            $accept_options = array();
            $accept_options['user_id'] = $my->id;
            $accept_options['status'] = -1;
            $state = $invites->rejectInvitation($id, $accept_options);
            
            $theme = FD::themes();

            if ($state) {
                $notification->markAsRead();
                return $this->ajax->resolve();
            } else {
                return $this->ajax->reject('An error occured while rejecting the invitation.');
            }
        }
    }

    public function addJobDialog() {
        FD::requireLogin();

        $id = $this->input->get('id', '0', 'int');

        $project = $this->getTable('Project');
        $state = $project->load($id);

        if (empty($project) || empty($project->id)) {
            return $this->ajax->reject(JText::_('COM_EASYSOCIAL_PROJECT_INVALID_PROJECT_ID'));
        }

        if (!$project->isPublished()) {
            return $this->ajax->reject(JText::_('COM_EASYSOCIAL_PROJECT_UNAVAILABLE'));
        }

        $catid = '';

        $model = $this->getModel('Projects');
        //$friends = $model->getFriendsInProject($project->id, array('userId' => $this->my->id));

        /* $exclusion = array();

          foreach ($friends as $friend) {
          $exclusion[] = $friend->id;
          } */

        $theme = FD::themes();
        //$theme->set('exclusion', $exclusion);
        $theme->set('project', $project);
        $theme->set('categories', $model->getCategoryListHtml($catid));
        $contents = $theme->output('apps/user/projects/dialog.addJob');

        return $this->ajax->resolve($contents);
    }

    public function addJob() {
        FD::checkToken();

        FD::requireLogin();

        $my = FD::user();

        $pid = $this->input->get('pid', 0, 'int');

        $project = $this->getTable('Project');
        $model = $this->getModel('Projects');
        $state = $project->load($pid);

        if (empty($project) || empty($project->id)) {
            return $this->ajax->reject(JText::_('COM_EASYSOCIAL_PROJECT_INVALID_PROJECT_ID'), SOCIAL_MSG_ERROR);
        }

        if (!$project->isPublished()) {
            return $this->ajax->reject(JText::_('COM_EASYSOCIAL_PROJECT_UNAVAILABLE'), SOCIAL_MSG_ERROR);
        }

        if ($pid && $state) {
            //$isProjectAdmin = $project->isProjectAdmin($my->id);
           //echo $isProjectAdmin;exit;
            if (!$project->canEdit($my->id)) {
                return $this->ajax->reject(JText::_('You are not the owner or admin of this project.'));
            }
        }

        // Get the list of emails
        $title = $this->input->get('title', '', 'html');
        $job_description = $this->input->get('job_description', '', 'html');
        $location = $this->input->get('location', '', 'html');
        $category_id = $this->input->get('category_id', 0, 'int');
        $position = $this->input->get('position', 0, 'int');
        $posted_by = $my->id;

        if (!$title) {
            return $this->ajax->reject(JText::_('COM_EASYSOCIAL_PROJECT_JOB_PLEASE_ENTER_TITLE'), SOCIAL_MSG_ERROR);
        }

        $jobs = $this->getTable('ProjectJobs');

        $jobs->title = $title;
        $jobs->pid = $pid;
        $jobs->posted_by = $posted_by;
        $jobs->job_description = $job_description;
        $jobs->location = $location;
        $jobs->category_id = $category_id;
        $jobs->position = $position;
        $jobs->status = 1;

        $state = $jobs->store();
        if (!$state) {
            return $this->ajax->reject(JText::_('Data not saved in table'));
        }
        //$job = $jobs->load($jobs->id);
        //echo 'id: '.$jobs->id;exit;
        //print_r($job);exit;
        return $this->ajax->resolve(array('msg' => JText::_('COM_EASYSOCIAL_PROJECT_JOB_SUCCESSFULLY_SAVED')));
    }

    public function editJobDialog() {
        FD::requireLogin();
        $table = $this->getTable('Project');

        $id = $this->input->get('jobid', '0', 'int');

        $job = $this->getTable('ProjectJobs');
        $state = $job->load($id);

        if (empty($job) || empty($job->id)) {
            return $this->ajax->reject(JText::_('COM_EASYSOCIAL_PROJECT_INVALID_JOB_ID'));
        }

        if (!$job->isPublished()) {
            return $this->ajax->reject(JText::_('COM_EASYSOCIAL_PROJECT_JOB_UNAVAILABLE'));
        }

        $catid = '';

        $model = $this->getModel('Projects');
        //$friends = $model->getFriendsInProject($project->id, array('userId' => $this->my->id));

        /* $exclusion = array();

          foreach ($friends as $friend) {
          $exclusion[] = $friend->id;
          } */

        $address_value = array('address' => $job->location);
        $address = $table->getAddressValue($address_value);
        $address->geocode();

        $theme = FD::themes();
        //$theme->set('exclusion', $exclusion);
        $theme->set('job', $job);
        $theme->set('address', $address);
        $theme->set('categories', $model->getCategoryListHtml($job->category_id));
        $contents = $theme->output('apps/user/projects/dialog.editjob');

        return $this->ajax->resolve($contents);
    }

    public function editJob() {
        FD::checkToken();

        FD::requireLogin();

        $my = FD::user();
        $jobid = $this->input->get('jobid', 0, 'int');

        $job = $this->getTable('ProjectJobs');
        $state = $job->load($jobid);

        if (empty($job) || empty($job->id)) {
            return $this->ajax->reject(JText::_('COM_EASYSOCIAL_PROJECT_INVALID_JOB_ID'), SOCIAL_MSG_ERROR);
        }

        if (!$job->isPublished()) {
            return $this->ajax->reject(JText::_('COM_EASYSOCIAL_PROJECT_JOB_UNAVAILABLE'), SOCIAL_MSG_ERROR);
        }

        // Get the list of emails
        $title = $this->input->get('title', '', 'html');
        $job_description = $this->input->get('job_description', '', 'html');
        $location = $this->input->get('location', '', 'html');
        $category_id = $this->input->get('category_id', 0, 'int');
        $position = $this->input->get('position', 0, 'int');
        $posted_by = $my->id;

        if (!$title) {
            return $this->ajax->reject(JText::_('COM_EASYSOCIAL_PROJECT_JOB_PLEASE_ENTER_TITLE'), SOCIAL_MSG_ERROR);
        }

        //$jobs = $this->getTable('ProjectJobs');

        $job->title = $title;
        $job->job_description = $job_description;
        $job->location = $location;
        $job->category_id = $category_id;
        $job->position = $position;

        $state = $job->store();
        if (!$state) {
            return $this->ajax->reject(JText::_('Data not saved in table'));
        }
        return $this->ajax->resolve(array('msg' => JText::_('COM_EASYSOCIAL_PROJECT_JOB_SUCCESSFULLY_SAVED')));
    }

    public function deleteJobDialog() {
        FD::requireLogin();

        $id = $this->input->get('jobid', '0', 'int');

        $job = $this->getTable('ProjectJobs');
        $state = $job->load($id);

        if (empty($job) || empty($job->id)) {
            return $this->ajax->reject(JText::_('COM_EASYSOCIAL_PROJECT_INVALID_JOB_ID'));
        }

        if (!$job->isPublished()) {
            return $this->ajax->reject(JText::_('COM_EASYSOCIAL_PROJECT_JOB_UNAVAILABLE'));
        }

        $theme = FD::themes();
        //$theme->set('exclusion', $exclusion);
        $theme->set('job', $job);
        $theme->set('title', JText::_( 'APP_PROJECTS_DIALOG_DELETE_CONFIRMATION_TITLE' ));
        $theme->set('desc', JText::_( 'APP_PROJECTS_DIALOG_JOB_DELETE_CONFIRMATION_DESC' ));
        $contents = $theme->output('apps/user/projects/dialog.delete');

        return $this->ajax->resolve($contents);
    }

    public function deleteJob() {

        $id = $this->input->get('jobid', '0', 'int');

        $job = $this->getTable('ProjectJobs');
        $state = $job->load($id);
        if (!$state) {
            return $this->ajax->reject(JText::_('Error deleting the job.'));
        }
        $job->delete();

        return $this->ajax->resolve(array('msg' => JText::_('COM_EASYSOCIAL_PROJECT_JOB_SUCCESSFULLY_SAVED')));
    }

    public function applyJobDialog() {
        // Check for request forgeries
        FD::checkToken();

        // User needs to be logged in
        FD::requireLogin();

        // Load up ajax library
        $ajax = FD::ajax();

        // Get the delete confirmation dialog
        $theme = FD::themes();

        $jobid = $this->input->get('jobid', 0, 'int');
        $job = $this->getTable('ProjectJobs');
        $state = $job->load($jobid);
        $theme->set('jobid', $jobid);
        $theme->set('job', $job);
        $contents = $theme->output('apps/user/projects/dialog.applyjob');

        return $ajax->resolve($contents);
    }

    public function applyJob() {
        FD::checkToken();

        FD::requireLogin();

        $my = FD::user();

        $projectId = $this->input->get('pid', 0, 'int');

        $project = $this->getTable('Project');
        $state = $project->load($projectId);

        if (empty($project) || empty($project->id)) {
            return $this->ajax->reject(JText::_('COM_EASYSOCIAL_PROJECT_INVALID_PROJECT_ID'), SOCIAL_MSG_ERROR);
        }

        if (!$project->isPublished()) {
            return $this->ajax->reject(JText::_('COM_EASYSOCIAL_PROJECT_UNAVAILABLE'), SOCIAL_MSG_ERROR);
        }

        $job_id = $this->input->get('job_id', 0, 'int');
        $full_name = $this->input->get('full_name', '', 'default');
        $phone_num = $this->input->get('phone_num', '', 'default');
        $email = $this->input->get('email', '', 'html');
        $website = $this->input->get('website', '', 'html');

        $job = $this->getTable('ProjectJobs');
        $jobstate = $job->load($job_id);

        if (!$email) {
            return $this->ajax->reject(JText::_('COM_EASYSOCIAL_FRIENDS_INVITE_PLEASE_ENTER_EMAILS'), SOCIAL_MSG_ERROR);
        }

        // Get the message
        $message = $this->input->get('message', '', 'default');

        /* foreach ($ids as $id) {
          $project->invite($id, $this->my->id);
          } */
        $sender = FD::user($my->id);

        $senderEmail = $email;
        $senderName = $full_name;
        $senderData = array($senderEmail, $senderName);

        $jconfig = FD::jconfig();
        $config = FD::config();

        $defaultRecipient = $config->get('email.replyto', $jconfig->getValue('mailfrom'));

        // Get the mailer
        $mailer = JFactory::getMailer();

        // Set the sender's info.
        $mailer->setSender($senderData);

        // Set the reply to info.
        $replyToEmail = $senderEmail;
        $mailer->addReplyTo($replyToEmail);

        // Set the recipient properties.
        $mailer->addRecipient($defaultRecipient);

        // Set mail's subject.
        $title = 'Job application for ' . $job->getTitle();
        $mailer->setSubject($title);

        $mailer->isHtml(true);

        $output = 'Following are the details of the job applicant. <br /><br />';
        $output .= '<b>Full Name:</b> ' . $full_name . '<br />';
        $output .= '<b>Phone Number:</b> ' . $phone_num . '<br />';
        $output .= '<b>Website:</b> ' . $website . '<br />';
        $output .= '<b>Message:</b> ' . $message . '<br />';

        $mailer->setBody($output);

        $stateMail = $mailer->send();


        if (!$stateMail) {
            return $this->ajax->reject($mailer->getError());
        }
        return $this->ajax->resolve(JText::_('COM_EASYSOCIAL_PROJECT_JOB_SUCCESSFULLY_APPLIED'));
    }

}
