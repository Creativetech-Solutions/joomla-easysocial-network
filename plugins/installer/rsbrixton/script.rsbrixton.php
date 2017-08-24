<?php
/**
* @package RSBrixton!
* @copyright (C) 2015 www.rsjoomla.com
* @license GPL, http://www.gnu.org/licenses/gpl-2.0.html
*/

// No direct access.
defined('_JEXEC') or die('Restricted access');

class plginstallerrsbrixtonInstallerScript
{
	public function postflight($type, $parent)
	{
		if ($type == 'install')
		{
			$db 	= JFactory::getDBO();
			$query 	= $db->getQuery(true);
			$query	->update( $db->qn('#__extensions') )
					->set( $db->qn('enabled') .' = '.$db->q(1) )
					->where( $db->qn('element').' = '.$db->q('rsbrixton') )
					->where( $db->qn('type') . '=' . $db->q('plugin') )
					->where( $db->qn('folder') . '=' . $db->q('installer') );
			$db->setQuery($query);
			$db->execute();
		}
	}
}