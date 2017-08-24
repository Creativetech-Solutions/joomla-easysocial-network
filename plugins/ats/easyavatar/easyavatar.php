<?php
/**
 * @package ats
 * @copyright Copyright (c)2011-2016 Nicholas K. Dionysopoulos / AkeebaBackup.com
 * @license GNU GPL v3 or later
 */

use FOF30\Container\Container;

defined('_JEXEC') or die();

if (!defined('FOF30_INCLUDED'))
{
    include_once(JPATH_LIBRARIES . '/fof30/include.php');
}

/**
 * Easy Social avatar integration for Akeeba Ticket System
 */
class plgAtsEasyavatar extends JPlugin
{
	public function onATSAvatar($user, $size = 64)
	{
		if (!file_exists(JPATH_ADMINISTRATOR . '/components/com_easysocial/includes/easysocial.php'))
		{
			return '';
		}

		if (!$user->id)
		{
			return '';
		}

		require_once(JPATH_ADMINISTRATOR . '/components/com_easysocial/includes/easysocial.php');

		$easyUser = ES::user($user->id);
		$avatar = $easyUser->getAvatar();

		return $avatar;
	}
}
