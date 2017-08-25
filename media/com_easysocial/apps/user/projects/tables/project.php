<?php

/**
 * @copyright (C) 2013 iJoomla, Inc. - All rights reserved.
 * @license GNU General Public License, version 2 (http://www.gnu.org/licenses/gpl-2.0.html)
 * @author iJoomla.com <webmaster@ijoomla.com>
 * @url https://www.jomsocial.com/license-agreement
 * The PHP code portions are distributed under the GPL license. If not otherwise stated, all images, manuals, cascading style sheets, and included JavaScript *are NOT GPL, and are released under the IJOOMLA Proprietary Use License v1.0
 * More info at https://www.jomsocial.com/license-agreement
 */
defined('_JEXEC') or die('Restricted access');
FD::import('admin:/tables/table');

//FD::import('admin:/includes/indexer/indexer');

class ProjectsTableProject extends SocialTable {

    public $id = null;
    public $title = null;
    public $summary = null;
    public $description = null;
    public $location = null;
    public $website = null;
    public $category_id = null;
    public $user_id = null;
    public $uid = null;
    public $admin_ids = null;
    public $type = null;
    public $created = null;
    public $state = null;
    public $featured = null;
    public $hits = null;
    public $params = null;
    public $storage = 'joomla';
    public $path = null;
    public $original = null;
    public $file_title = null;
    public $source = 'folder';
    public $thumbnail = null;
    public $x = null;
    public $y = null;
    public $video_url = null;
    public $video_gallery = null;

    public function __construct($db) {
        parent::__construct('#__social_projects', 'id', $db);
    }

    public function getContent() {
        // Apply e-mail replacements
        $content = FD::string()->replaceEmails($this->description);

//        // Apply hyperlinks
//        $content = FD::string()->replaceHyperlinks($content);
//
//        // Apply bbcode
//        $content = FD::string()->parseBBCode($content, array('code' => true, 'escape' => false));

        return $content;
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

        // Delete streams that are related to this note.
        $stream = FD::stream();
        $stream->delete($this->id, ES_SOCIAL_TYPE_PROJECTS);

        return $state;
    }

    public function feature($pk = null) {


        $db = JFactory::getDbo();

        $query = $db->getQuery(true);

        if (!$pk) {
            $pk = $this->id;
        }
        $featured = $this->isFeatured() ? 0 : 1;
// Fields to update.
        $fields = array(
            $db->quoteName('featured') . ' = ' . $featured,
        );

// Conditions for which records should be updated.
        $conditions = array(
            $db->quoteName('id') . ' = ' . $pk,
        );

        $query->update($db->quoteName('#__social_projects'))->set($fields)->where($conditions);

        $db->setQuery($query);

        $result = $db->execute();


        return $result;
    }

    public function isUpload() {
        return $this->source == 'upload';
    }

    public function getAlias($withId = true) {
        $title = $this->title;
        $title = JFilterOutput::stringURLSafe($title);
        $alias = $title;

        if ($withId) {
            $alias = $this->id . ':' . $alias;
        }

        return $alias;
    }

    public function getThumbnail($nothumb = false) {

        if (!empty($this->thumbnail) && file_exists(ES_SOCIAL_MEDIA_PROJECTS_STORAGE . '/' . $this->user_id . '/' . $this->id . '/' . $this->thumbnail)) {
            return ES_SOCIAL_MEDIA_PROJECTS_STORAGE_URI . '/' . $this->user_id . '/' . $this->id . '/' . $this->thumbnail;
        } else if ($nothumb) {
            return ES_SOCIAL_MEDIA_PROJECTS_APP_URI . '/assets/icons/no-project.png';
        }

        return false;
    }

    public function getOriginal($nothumb = false) {

        if (!empty($this->original) && file_exists(ES_SOCIAL_MEDIA_PROJECTS_STORAGE . '/' . $this->user_id . '/' . $this->id . '/' . $this->original)) {
            return ES_SOCIAL_MEDIA_PROJECTS_STORAGE_URI . '/' . $this->user_id . '/' . $this->id . '/' . $this->original;
        } else if ($nothumb) {
            return ES_SOCIAL_MEDIA_PROJECTS_APP_URI . '/assets/icons/no-project.png';
        }

        return false;
    }

    public function getFileUrl() {

        if (!empty($this->file_title) && file_exists(ES_SOCIAL_MEDIA_PROJECTS_STORAGE . '/' . $this->user_id . '/' . $this->id . '/' . $this->file_title)) {
            return ES_SOCIAL_MEDIA_PROJECTS_STORAGE_URI . '/' . $this->user_id . '/' . $this->id . '/' . $this->file_title;
        }

        return false;
    }

    public function getThumbnailURL() {

        if (!empty($this->thumbnail) && file_exists(ES_SOCIAL_MEDIA_PROJECTS_STORAGE . '/' . $this->user_id . '/' . $this->id . '/' . $this->thumbnail)) {
            return ES_SOCIAL_MEDIA_PROJECTS_STORAGE_URI . '/' . $this->user_id . '/' . $this->id . '/' . $this->thumbnail;
        }
        return false;
    }

    public function deleteThumbnail() {

        if (!empty($this->thumbnail)) {
            @unlink(ES_SOCIAL_MEDIA_PROJECTS_STORAGE . '/' . $this->user_id . '/' . $this->id . '/' . $this->thumbnail);
        }
    }

    public function getCoverPosition() {
        $position = ( $this->x * 100 ) . '% ' . ( $this->y * 100 ) . '%';

        return $position;
    }

    public function getComments($verb = '', $streamId = '') {

        if (!$verb) {
            $verb = 'create';
        }

        // Generate comments for the video
        $comments = FD::comments($this->id, ES_SOCIAL_TYPE_PROJECTS, $verb, $this->type, array('url' => $this->getPermalink('detail')), $streamId);
//        $my = Foundry::user();
        //$comments	= FD::comments( $note->id , 'notes' , 'create', SOCIAL_APPS_GROUP_USER , array( 'url' => FRoute::apps( array( 'layout' => 'canvas', 'userid' => $my->getAlias() , 'cid' => $note->id ) ) ) );

        return $comments;
    }

    /**
     * Retrieves the description of the video
     *
     * @since	1.4
     * @access	public
     * @param	string
     * @return
     */
    public function getDescription() {
        // Load site's language file.
        ES::language()->loadSite();

        if (!$this->description) {
            return JText::_('COM_EASYSOCIAL_VIDEOS_NO_DESCRIPTION_AVAILABLE');
        }

        return $this->description;
    }

    public function getLocation() {
        return $this->location;
    }

    public function getWebsites() {
        return $this->website;
    }

    public function getShortSummary() {
        // Load site's language file.
        ES::language()->loadSite();

        if (!$this->summary) {
            return JText::_('COM_EASYSOCIAL_VIDEOS_NO_DESCRIPTION_AVAILABLE');
        }

        return $this->summary;
    }

    /**
     * Retrieves the hits for the video
     *
     * @since	1.4
     * @access	public
     * @param	string
     * @return
     */
    public function getHits() {
        return $this->hits;
    }

    public function getCreatedDate() {
        $date = ES::date($this->created);

        return $date;
    }

    public function getTitle() {
        return JText::_($this->title);
    }

    public function getAuthor() {
        $author = ES::user($this->user_id);

        return $author;
    }

    public function canEdit($uid = '') {
        if ($uid) {
            //$isMember = $this->isTeamMember($uid);
            $isProjectAdmin = $this->isProjectAdmin($uid);
            if ($uid == $this->user_id) {
                return true;
            }elseif($isProjectAdmin){
                return true;
            }else {
                return false;
            }
        } else {
            return false;
        }
    }

    public function getLikes($verb = '', $streamId = '') {
        if (!$verb) {
            $verb = 'create';
        }

        $likes = FD::likes();
        $likes->get($this->id, ES_SOCIAL_TYPE_PROJECTS, $verb, $this->type, $streamId);
        return $likes;
    }

    public function getPermalink($clayout = '') {
        $options = array('cid' => $this->id, 'clayout' => 'form');

        if ($clayout) {
            $url = FRoute::apps(array('layout' => 'canvas', 'clayout' => $clayout, 'prid' => $this->id, 'id' => $this->getAppAlias()));
        } else {
            $url = FRoute::apps(array('layout' => 'canvas', 'prid' => $this->id, 'id' => $this->getAppAlias()));
        }

        return $url;
    }

    public function getEditLink() {
        return $this->getPermalink('form');
    }

    public function getCategory() {
        $project = new SocialAppsController('user', ES_SOCIAL_TYPE_PROJECTS);
        $category = $project->getTable('ProjectCategory');
        $category->load($this->category_id);

        return $category;
    }

    public function getJobCategory($job_catid = '') {
        if (!$job_catid) {
            return false;
        }
        $project = new SocialAppsController('user', ES_SOCIAL_TYPE_PROJECTS);
        $category = $project->getTable('ProjectCategory');
        $category->load($job_catid);

        return $category;
    }

    public function getProjectTeam() {
        $project = new SocialAppsController('user', ES_SOCIAL_TYPE_PROJECTS);
        $team = $project->getTable('ProjectTeam');
        $projectTeam = $team->getTeam($this->id);

        return $projectTeam;
    }
    
    public function isTeamMember($user_id = ''){
        if(!$user_id){
            return false;
        }
        $project = new SocialAppsController('user', ES_SOCIAL_TYPE_PROJECTS);
        $team = $project->getTable('ProjectTeam');
        $isMember = $team->isMember($user_id);
        return $isMember;
    }
    
    public function isProjectAdmin($user_id = ''){
        if(!$user_id){
            return false;
        }
        $project = new SocialAppsController('user', ES_SOCIAL_TYPE_PROJECTS);
        $team = $project->getTable('ProjectTeam');
        $isadmin = $team->isProjectAdmin($user_id,$this->id);
        return $isadmin;
    }

    public function getProjectJobs() {
        $project = new SocialAppsController('user', ES_SOCIAL_TYPE_PROJECTS);
        $job = $project->getTable('ProjectJobs');
        $projectJobs = $job->getJobs($this->id);

        return $projectJobs;
    }

    public function getJobDetails($jobid = '') {
        if (!$jobid) {
            return;
        }
        $project = new SocialAppsController('user', ES_SOCIAL_TYPE_PROJECTS);
        $job = $project->getTable('ProjectJobs');
        $job_details = $job->load($jobid);

        return $job_details;
    }

    public function getStreamId($verb, $projectId = '') {
        if (!$projectId) {
            $projectId = $this->id;
        }
        $db = ES::db();
        $sql = $db->sql();
        $sql->select('#__social_stream_item', 'a');
        $sql->column('a.uid');
        $sql->where('a.context_type', ES_SOCIAL_TYPE_PROJECTS);
        $sql->where('a.context_id', $projectId);
        $sql->where('a.verb', $verb);

        $db->setQuery($sql);

        $uid = (int) $db->loadResult();

        return $uid;
    }

    /**
     * Determines if the video item is unfeatured
     *
     * @since	1.4
     * @access	public
     * @param	string
     * @return
     */
    public function isUnfeatured() {
        return !$this->isFeatured();
    }

    /**
     * Determines if the video item is featured
     *
     * @since	1.4
     * @access	public
     * @param	string
     * @return
     */
    public function isFeatured() {
        return $this->featured == 1;
    }

    public function isPublished() {
        return $this->state == 1;
    }

    public function createStream($verb) {
        // Add activity logging when a friend connection has been made.
        // Activity logging.
        $stream = FD::stream();
        $streamTemplate = $stream->getTemplate();

        // Set the actor.
        $streamTemplate->setActor($this->user_id, SOCIAL_TYPE_USER);

        // Set the context.
        $streamTemplate->setContext($this->id, ES_SOCIAL_TYPE_PROJECTS);

        // Set the verb.
        $streamTemplate->setVerb($verb);

        $streamTemplate->setAccess('core.view');

        // Create the stream data.
        $stream->add($streamTemplate);
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
            $app->load(array('type' => SOCIAL_TYPE_APPS, 'group' => SOCIAL_APPS_GROUP_USER, 'element' => ES_SOCIAL_TYPE_PROJECTS));
        }

        return $app;
    }

    public function getAddressValue($data = '') {
        $address = new SocialProjectsAddressObject($data);

        return $address;
    }

    public function invite($target, $actor = null) {
        $actor = FD::user($actor);
        $target = FD::user($target);

        /* $guest = FD::table('EventGuest');

          $guest->cluster_id = $this->id;
          $guest->uid = $target->id;
          $guest->type = SOCIAL_TYPE_USER;
          $guest->state = SOCIAL_EVENT_GUEST_INVITED;
          $guest->invited_by = $actor->id;

          $guest->store();

          FD::points()->assign('events.guest.invite', 'com_easysocial', $actor->id); */

        $url = FRoute::apps(array('layout' => 'canvas', 'prid' => $this->id, 'id' => $this->getAppAlias()));

        $emailOptions = (object) array(
                    'title' => 'COM_EASYSOCIAL_EMAILS_PROJECT_TEAM_INVITED_SUBJECT',
                    'template' => 'apps/user/projects/team.invited',
                    'event' => $this->getTitle(),
                    'eventName' => $this->getTitle(),
                    'eventAvatar' => $this->getThumbnailURL(),
                    'eventLink' => $this->getPermalink('acceptInvite'),
                    'invitorName' => $actor->getName(),
                    'invitorLink' => $actor->getPermalink(false, true),
                    'invitorAvatar' => $actor->getAvatar()
        );

        $systemOptions = (object) array(
                    'uid' => $this->id,
                    'actor_id' => $actor->id,
                    'title' => 'Invitation to Join team for ' . $this->getTitle() . '',
                    'target_id' => $target->id,
                    'context_type' => 'projects',
                    'type' => 'projects',
                    'url' => $this->getPermalink('acceptInvite'),
                    'projectId' => $this->id
        );

        FD::notify('projects.team.invited', array($target->id), $emailOptions, $systemOptions);

        return true;
    }

}

class SocialProjectsAddressObject {

    public $address1 = '';
    public $address2 = '';
    public $city = '';
    public $state = '';
    public $zip = '';
    public $country = '';
    // Geocode
    public $latitude = '';
    public $longitude = '';
    // Full address
    public $address = '';

    public function __construct($data = null) {
        $this->load($data);
    }

    public function load($data) {
        if (empty($data)) {
            return false;
        }

        $vars = (object) get_object_vars($this);

        $data = FD::makeObject($data);

        if (!$data) {
            return false;
        }

        foreach ($data as $key => $val) {
            if (isset($vars->$key) && !empty($val)) {
                $this->$key = $val;
            }
        }

        return true;
    }

    public function toArray() {
        return (array) $this;
    }

    public function toJson() {
        return FD::json()->encode($this);
    }

    public function toString($del = ' ') {
        if (!empty($this->address)) {
            return $this->address;
        }

        $components = array('address1', 'address2', 'city', 'state', 'zip', 'country');

        $address = array();

        foreach ($components as $key) {
            if (!empty($this->$key)) {
                $address[] = $this->$key;
            }
        }

        $address = trim(implode($del, $address));

        return $address;
    }

    public function isEmpty() {
        $components = array('address1', 'address2', 'city', 'state', 'zip', 'country');

        foreach ($components as $key) {
            if (!empty($this->$key)) {
                return false;
            }
        }

        return true;
    }

    public function geocode() {
        $lib = FD::get('GeoCode');
        $address = $this->toString(',');

        $data = $lib->geocode($address);

        if (!$data) {
            return;
        }

        // We only want the geometry data here
        $geometry = $data->geometry;

        $this->latitude = $geometry->location->lat;
        $this->longitude = $geometry->location->lng;
    }

    public function __toString() {
        return $this->toString();
    }

    /**
     * Prepares this object to be save ready.
     *
     * @author Jason Rey <jasonrey@stackideas.com>
     * @since  1.3
     * @access public
     * @return SocialFieldsUserAddressObject    The address object.
     */
    public function export() {
        if (empty($this->address)) {
            $this->address = $this->toString();
        }

        return $this;
    }

}
