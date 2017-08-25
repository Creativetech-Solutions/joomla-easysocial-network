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
defined( '_JEXEC' ) or die( 'Unauthorized Access' );

/**
 * Profile view for Audios app.
 *
 * @since	1.0
 * @access	public
 */
class AudiosViewAudio extends SocialAppsView
{
	/**
	 * Displays the application output in the canvas.
	 *
	 * @since	1.0
	 * @access	public
	 * @param	int		The user id that is currently being viewed.
	 */
	public function display( $userId = null , $docType = null )
	{
		$id 	= JRequest::getInt( 'cid' );

		// Try to load the audio.
		$audio 	= $this->getTable( 'Audio' );
		$audio->load( $id );

		if (!$id || !$audio->id ){
			return;
		}

		// Perform content fixes here.
		$audio->content 	= nl2br( $audio->content );

		$this->set( 'audio' , $audio );

		echo parent::display( 'default.audio' );
	}
}
