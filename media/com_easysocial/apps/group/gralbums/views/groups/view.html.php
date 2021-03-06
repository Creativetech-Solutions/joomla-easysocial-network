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

// We need the router
require_once( JPATH_ROOT . '/components/com_content/helpers/route.php' );

/**
 * Profile view for article app
 *
 * @since	1.0
 * @access	public
 */
class GralbumsViewGroups extends SocialAppsView
{
	/**
	 * Displays the application output in the canvas.
	 *
	 * @since	1.0
	 * @access	public
	 * @param	int		The user id that is currently being viewed.
	 */
	public function display($groupId = null, $docType = null)
	{
		$options = array();

		$group = ES::group($groupId);

		// Get the pagination settings
		$themes = ES::themes();
		$limit = $themes->getConfig()->get('userslimit');

		// Members to display per page.
		$options['limit'] = $limit;

		$model = FD::model('Albums');
		
		$albums = $model->getAlbums($group->id, SOCIAL_TYPE_GROUP, $options);
		//$options = array('uid' => $group->id, 'type' => SOCIAL_TYPE_GROUP);

		// Set pagination properties
		$pagination	= $model->getPagination();
		$pagination->setVar('view', 'groups');
		$pagination->setVar('layout', 'item');
		$pagination->setVar('id', $group->getAlias() );
		$pagination->setVar('appId', $this->app->getAlias());
		$pagination->setVar('Itemid', ESR::getItemId('groups', 'item', $group->id ) );
		
		$this->set('group', $group);
		$this->set('albums', $albums);
		$this->set('pagination', $pagination);

		echo parent::display('groups/default');
	}

}
