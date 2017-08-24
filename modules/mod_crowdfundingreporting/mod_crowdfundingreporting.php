<?php
/**
 * @package      Crowdfunding
 * @subpackage   Modules
 * @author       Todor Iliev
 * @copyright    Copyright (C) 2015 Todor Iliev <todor@itprism.com>. All rights reserved.
 * @license      GNU General Public License version 3 or later; see LICENSE.txt
 */

// no direct access
defined('_JEXEC') or die;

$moduleclassSfx = htmlspecialchars($params->get('moduleclass_sfx'));

jimport('Prism.init');
jimport('Crowdfunding.init');

$option = $app->input->get('option');
$view   = $app->input->get('view');

// If option is not 'com_crowdfunding' and view is not 'details',
// do not display anything.
if ((strcmp($option, 'com_crowdfunding') !== 0) or (strcmp($view, 'details') !== 0)) {
    echo JText::_('MOD_CROWDFUNDINGPROFILE_ERROR_INVALID_VIEW');
    return;
}

$projectId = $app->input->getInt('id');
if (!$projectId) {
    echo JText::_('MOD_CROWDFUNDINGPROFILE_ERROR_INVALID_PROJECT');
    return;
}

// Get component parameters
$componentParams = JComponentHelper::getParams('com_crowdfunding');
/** @var  $componentParams Joomla\Registry\Registry */

require JModuleHelper::getLayoutPath('mod_crowdfundingreporting', $params->get('layout', 'default'));
