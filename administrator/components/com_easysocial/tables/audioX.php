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
FD::import('admin:/includes/indexer/indexer');

class SocialTableAudio extends SocialTable implements ISocialIndexerTable {

    public $id = null;
	public $title = null;
	public $description = null;
	public $user_id = null;
	public $uid = null;
	public $type = null;
	public $created = null;
	public $state = null;
	public $featured = null;
	public $gener = null;
	public $hits = null;
	public $duration = null;
	public $size = null;
	public $params = null;
	public $storage = 'joomla';
	public $path = null;
	public $original = null;
	public $file_title = null;
	public $source = 'folder';
	public $thumbnail = null;

    public function __construct($db) {
        parent::__construct('#__social_audios', 'id', $db);
    }
	
	public function syncIndex()
	{
	}

	public function deleteIndex()
	{		
	}
	
	/* public function getAudio( $vidio_id = 0)
    {
   
      $query  = ' SELECT * '
                . ' FROM ' . $this->_db->quoteName('#__social_audios')
                . ' WHERE ' . $this->_db->quoteName('video_id') . ' = ' . $this->_db->quote($vidio_id);
        $this->_db->setQuery($query);
         $data = $this->_db->loadObject();
		return $data;
    }*/
	
	public function isUpload()
	{
		return $this->source == 'upload';
	}

	public function getAlias($withId = true)
    {
        $title = $this->title;
        $title = JFilterOutput::stringURLSafe($title);
        $alias = $title;

        if ($withId) {
        	$alias = $this->id . ':' . $alias;
        }

        return $alias;
    }
	
	public function getPermalink($xhtml = true)
	{
		$options = array('id' => $this->getAlias(), 'layout' => 'item');

		if ($this->uid && $this->type && $this->type != SOCIAL_TYPE_USER) {
			$cluster = ES::cluster($this->type, $this->uid);

			$options['uid'] = $cluster->getAlias();
			$options['type'] = $this->type;
		}

		$url = FRoute::audio($options, $xhtml);

		return $url;
	}
	
	/**
	 * Determines if the video item is unfeatured
	 *
	 * @since	1.4
	 * @access	public
	 * @param	string
	 * @return
	 */
	public function isUnfeatured()
	{
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
	public function isFeatured()
	{
		return $this->featured == 1;
	}
}
