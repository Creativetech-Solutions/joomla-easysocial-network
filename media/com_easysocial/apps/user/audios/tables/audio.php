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

class AudiosTableAudio extends SocialTable {

    public $id = null;
    public $title = null;
    public $description = null;
    public $user_id = null;
    public $uid = null;
    public $type = null;
    public $created = null;
    public $file_title = null;
    public $state = null;
    public $featured = null;
    public $category_id = null;
    public $hits = null;
    public $duration = null;
    public $params = null;
    public $storage = 'joomla';
    public $source = 'folder';
    public $thumbnail = null;

    public function __construct($db) {
        parent::__construct('#__social_audios', 'id', $db);
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
        $stream->delete($this->id, ES_SOCIAL_TYPE_AUDIOS);

        return $state;
    }

    public function feature($pk = null) {


        $db = JFactory::getDbo();

        $query = $db->getQuery(true);

        if(!$pk){
            $pk = $this->id;
        }
        $featured = $this->isFeatured()? 0 : 1;
// Fields to update.
        $fields = array(
            $db->quoteName('featured') . ' = ' . $featured,
        );

// Conditions for which records should be updated.
        $conditions = array(
            $db->quoteName('id') . ' = ' . $pk,
        );

        $query->update($db->quoteName('#__social_audios'))->set($fields)->where($conditions);

        $db->setQuery($query);

        $result = $db->execute();


        return $result;
    }

    /* public function getAudio( $vidio_id = 0)
      {

      $query  = ' SELECT * '
      . ' FROM ' . $this->_db->quoteName('#__social_audios')
      . ' WHERE ' . $this->_db->quoteName('video_id') . ' = ' . $this->_db->quote($vidio_id);
      $this->_db->setQuery($query);
      $data = $this->_db->loadObject();
      return $data;
      } */

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

        if (!empty($this->thumbnail) && file_exists(ES_SOCIAL_MEDIA_AUDIOS_STORAGE . '/' . $this->user_id . '/' . $this->thumbnail)) {
            return ES_SOCIAL_MEDIA_AUDIOS_STORAGE_URI . '/' . $this->user_id . '/' . $this->thumbnail;
        } else if ($nothumb) {
            return ES_SOCIAL_MEDIA_AUDIOS_APP_URI . '/assets/icons/no-audio.png';
        }

        return false;
    }

    public function getFileUrl() {

        if (!empty($this->file_title) && file_exists(ES_SOCIAL_MEDIA_AUDIOS_STORAGE . '/' . $this->user_id . '/' . $this->file_title)) {
            return ES_SOCIAL_MEDIA_AUDIOS_STORAGE_URI . '/' . $this->user_id . '/' . $this->file_title;
        }

        return false;
    }

    public function getThumbnailURL() {

        if (!empty($this->thumbnail) && file_exists(ES_SOCIAL_MEDIA_AUDIOS_STORAGE . '/' . $this->user_id . '/' . $this->thumbnail)) {
            return ES_SOCIAL_MEDIA_AUDIOS_STORAGE_URI . '/' . $this->user_id . '/' . $this->thumbnail;
        }
        return false;
    }

    public function deleteThumbnail() {

        if (!empty($this->thumbnail)) {
            @unlink(ES_SOCIAL_MEDIA_AUDIOS_STORAGE . '/' . $this->user_id . '/' . $this->thumbnail);
        }
    }

    public function getComments($verb = '', $streamId = '') {

        if (!$verb) {
            $verb = 'create';
        }

        // Generate comments for the video
        $comments = FD::comments($this->id, ES_SOCIAL_TYPE_AUDIOS, $verb, $this->type, array(), $streamId);
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

    public function getLikes($verb = '', $streamId = '') {
        if (!$verb) {
            $verb = 'create';
        }

        $likes = FD::likes();
        $likes->get($this->id, ES_SOCIAL_TYPE_AUDIOS, $verb, $this->type, $streamId);
        //FD::likes( $note->id , 'notes', 'create', SOCIAL_APPS_GROUP_USER );
        return $likes;
    }

    public function getPermalink($xhtml = true) {
        $options = array('cid' => $this->id, 'clayout' => 'form');

//        if ($this->uid && $this->type && $this->type != SOCIAL_TYPE_USER) {
//            $cluster = ES::cluster($this->type, $this->uid);
//
//            $options['uid'] = $cluster->getAlias();
//            $options['type'] = $this->type;
//        }

        $url = FRoute::apps(array('layout' => 'canvas', 'clayout' => 'form', 'aid' => $this->id, 'id' => $this->getAppAlias()));

        return $url;
    }

    public function getEditLink() {
        return $this->getPermalink();
    }

    public function getStreamId($verb, $audioId = false) {
        if (!$audioId) {
            $audioId = $this->id;
        }
        $db = ES::db();
        $sql = $db->sql();
        $sql->select('#__social_stream_item', 'a');
        $sql->column('a.uid');
        $sql->where('a.context_type', ES_SOCIAL_TYPE_AUDIOS);
        $sql->where('a.context_id', $audioId);
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
        $streamTemplate->setContext($this->id, ES_SOCIAL_TYPE_AUDIOS);

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
            $app->load(array('type' => SOCIAL_TYPE_APPS, 'group' => SOCIAL_APPS_GROUP_USER, 'element' => ES_SOCIAL_TYPE_AUDIOS));
        }

        return $app;
    }

}
