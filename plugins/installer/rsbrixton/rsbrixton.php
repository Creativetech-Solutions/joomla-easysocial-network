<?php
/**
* @package RSBrixton!
* @copyright (C) 2015 www.rsjoomla.com
* @license GPL, http://www.gnu.org/copyleft/gpl.html
*/

defined('_JEXEC') or die;

class plgInstallerRSBrixton extends JPlugin
{
	public function onInstallerBeforePackageDownload(&$url, &$headers)
	{
		$uri 	= JUri::getInstance($url);
		$parts 	= explode('/', $uri->getPath());
		
		if ($uri->getHost() == 'www.rsjoomla.com' && in_array('tpl_rsbrixton', $parts)) {
			if (!file_exists(JPATH_SITE.'/templates/rsbrixton/classes/version.php')) {
				return;
			}
			
			require_once JPATH_SITE.'/templates/rsbrixton/classes/version.php';
			
			// Load language
			JFactory::getLanguage()->load('plg_installer_rsbrixton');
			
			// Get the version
			$version = new RSBrixtonVersion;
			
			// Get the update code
			$code = $this->params->get('code');
			
			// No code added
			if (!strlen($code)) {
				JFactory::getApplication()->enqueueMessage(JText::_('PLG_INSTALLER_RSBRIXTON_MISSING_UPDATE_CODE'), 'warning');
				return;
			}
			
			// Code length is incorrect
			if (strlen($code) != 20) {
				JFactory::getApplication()->enqueueMessage(JText::_('PLG_INSTALLER_RSBRIXTON_INCORRECT_CODE'), 'warning');
				return;
			}
			
			// Compute the update hash			
			$uri->setVar('hash', md5($code.$version->key));
			$uri->setVar('domain', JUri::getInstance()->getHost());
			$uri->setVar('code', $code);
			$url = $uri->toString();
		}
	}
}
