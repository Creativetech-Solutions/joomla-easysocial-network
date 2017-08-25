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
class AudiosViewProfile extends SocialAppsView
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
		// Get Audios model library
		$model 	= $this->getModel( 'Audios' );

		// Retrieve list of audios created by user
		$result 	= $model->getItems( $userId );
		$audios 		= array();

		if( $result )
		{
			foreach( $result as $row )
			{
				$audio 	= $this->getTable( 'Audio' );
				$audio->bind( $row );

				$audio->likes 	= FD::likes( $row->id , 'audios' , 'create', SOCIAL_APPS_GROUP_USER );
				$audio->comments	= FD::comments( $row->id , 'audios' , 'create', SOCIAL_APPS_GROUP_USER );

				$audios[]	= $audio;
			}
		}

		// Get the profile
		$user 	= FD::user( $userId );

		$this->set( 'user'	, $user );
		$this->set( 'audios' , $audios );

		echo parent::display( 'profile/default' );
	}
}
