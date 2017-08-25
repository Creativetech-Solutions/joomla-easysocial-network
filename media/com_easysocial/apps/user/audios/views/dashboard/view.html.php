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
 * Dashboard view for Audios app.
 *
 * @since	1.0
 * @access	public
 */
class AudiosViewDashboard extends SocialAppsView
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
		$model 	= $this->getModel( 'Audios' );
		$audios 	= $model->getItems( $userId );
		$user 	= FD::user( $userId );

		// We need to get the comment and likes count.
		$this->format( $audios );

		$params	= $this->getUserParams( $userId );

		$this->set( 'app'		, $this->app );
		$this->set( 'params'	, $params );
		$this->set( 'user'		, $user );
		$this->set( 'audios' 	, $audios );

		echo parent::display( 'dashboard/default' );
	}

	public function format( &$audios )
	{
		if( !$audios )
		{
			return;
		}

		// Since this is the dashboard view, we may freely use the current user.
		$my 	= FD::user();
		$stream	= FD::stream();

		foreach( $audios as &$audio )
		{
			$comments			= FD::comments( $audio->id , 'audios' , 'create', SOCIAL_APPS_GROUP_USER , array( 'url' => FRoute::apps( array( 'layout' => 'canvas', 'userid' => $my->getAlias() , 'cid' => $audio->id ) ) ) );
			$likes 				= FD::likes( $audio->id , 'audios', 'create', SOCIAL_APPS_GROUP_USER );

			$options 		= array( 'comments' => $comments , 'likes' => $likes );

			$audio->actions 	= $stream->getActions( $options );
		}

	}
}
