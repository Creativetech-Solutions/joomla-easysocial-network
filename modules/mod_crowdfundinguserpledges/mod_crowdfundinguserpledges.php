<?php
/**
 * @package      Crowdfunding
 * @subpackage   Modules
 * @author       Todor Iliev
 * @copyright    Copyright (C) 2015 Todor Iliev <todor@itprism.com>. All rights reserved.
 * @license      http://www.gnu.org/copyleft/gpl.html GNU/GPL
 */

// no direct access
defined("_JEXEC") or die;

jimport("Prism.init");
jimport("Crowdfunding.init");

JLoader::register("CrowdfundingUserPledgesModuleHelper", JPATH_ROOT . "/modules/mod_crowdfundinguserpledges/helper.php");

$moduleclassSfx = htmlspecialchars($params->get('moduleclass_sfx'));

// Get user ID.
$userId   = Prism\Integration\Helper::getUserId();
if (!$userId) {
    return;
}

// Prepare results limit.
$limitResults = $params->get("results_limit", 5);
if ($limitResults <= 0) {
    $limitResults = 5;
}

// Get projects.
$projects = CrowdfundingUserPledgesModuleHelper::getProjects($limitResults, $userId);
if (!$projects) {
    return;
}

// Get options
$displayInfo        = $params->get("display_info", 1);
$displayDescription = $params->get("display_description", 0);
$titleLength        = $params->get("title_length", 32);
$descriptionLength  = $params->get("description_length", 63);
$displayCreator     = $params->get("display_creator", true);

// Get component parameters
/** @var  $componentParams Joomla\Registry\Registry */
$componentParams = JComponentHelper::getParams("com_crowdfunding");
$imagesDirectory = $componentParams->get("images_directory", "images/crowdfunding");

if ($displayInfo) {
    $currency   = Crowdfunding\Currency::getInstance(JFactory::getDbo(), $componentParams->get("project_currency"));

    $amount = new Crowdfunding\Amount($componentParams);
    $amount->setCurrency($currency);
}

// Display user social profile ( integrate ).
if (!empty($displayCreator)) {
    $config = array(
        "social_platform" => $componentParams->get("integration_social_platform"),
        "user_id" => $userId
    );

    $socialProfileBuilder  = new Prism\Integration\Profile\Builder($config);
    $socialProfileBuilder->build();

    $socialProfile = $socialProfileBuilder->getProfile();
}

if (!empty($projects)) {
    require JModuleHelper::getLayoutPath('mod_crowdfundinguserpledges', $params->get('layout', 'default'));
}
