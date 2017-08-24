<?php
/**
 * @package ats
 * @copyright Copyright (c)2011-2016 Nicholas K. Dionysopoulos / AkeebaBackup.com
 * @license GNU GPL v3 or later
 */

defined('_JEXEC') or die();

class PlgAtsAlphauserpointsInstallerScript
{
	/**
	 * Joomla! pre-flight event
	 *
	 * @param string $type Installation type (install, update, discover_install)
	 * @param JInstaller $parent Parent object
	 */
	public function preflight($type, $parent)
	{
		return true;

		// Check if AUP exists
		JLoader::import('joomla.filesystem.folder');
		if (!JFolder::exists(JPATH_ADMINISTRATOR . '/components/com_alphauserpoints'))
		{
			throw new Exception('The Akeeba Ticket System - Alpha User Points integration plugin was not installed because the Alpha User Points component is not installed. Please note that you DO NOT NEED the third party Alpha User Points component to run Akeeba Ticket System.');
			return false;
		}

		return true;
	}

	/**
	 * Runs after install, update or discover_update
	 * @param string $type install, update or discover_update
	 * @param JInstaller $parent
	 */
	function postflight( $type, $parent )
	{
		$db = JFactory::getDbo();

		JLoader::import('joomla.filesystem.folder');
		if (!JFolder::exists(JPATH_ADMINISTRATOR . '/components/com_alphauserpoints'))
		{
			return true;
		}

		// Do we have a record?
		$query = $db->getQuery(true)
			->select('COUNT(*)')
			->from($db->qn('#__alpha_userpoints_rules'))
			->where($db->qn('rule_plugin') . ' = ' . $db->q('com_ats'));
		$db->setQuery($query);
		$count = $db->loadResult();

		if ($count)
		{
			return true;
		}

		// Install the AUP record
		$o = (object)array(
			'rule_name'				=> 'Akeeba Ticket System integration',
			'rule_description'		=> 'Converts AUP points into ATS credits',
			'rule_plugin'			=> 'com_ats',
			'plugin_function'		=> 'plgaup_ats_convert_points',
			'access'				=> 1,
			'component'				=> '',
			'calltask'				=> '',
			'taskid'				=> '',
			'points'				=> 0.00,
			'points2'				=> 0.00,
			'percentage'			=> 0,
			'rule_expire'			=> '0000-00-00 00:00:00',
			'sections'				=> '',
			'categories'			=> '',
			'content_items'			=> '',
			'exclude_items'			=> '',
			'published'				=> 1,
			'system'				=> 0,
			'duplicate'				=> 0,
			'blockcopy'				=> 0,
			'autoapproved'			=> 1,
			'fixedpoints'			=> 0,
			'category'				=> 'ot',
			'displaymsg'			=> 1,
			'msg'					=> 'ATS conversion',
			'method'				=> 4,
			'notification'			=> 0,
			'emailsubject'			=> '',
			'emailbody'				=> '',
			'emailformat'			=> 0,
			'bcc2admin'				=> 0,
			'type_expire_date'		=> 0
		);
		$db->insertObject('#__alpha_userpoints_rules', $o);
	}
}