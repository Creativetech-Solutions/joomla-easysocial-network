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

$moduleclassSfx = htmlspecialchars($params->get('moduleclass_sfx'));

jimport("Prism.init");
jimport("Crowdfunding.init");
JLoader::register("CrowdfundingLatestModuleHelper", JPATH_ROOT . "/modules/mod_crowdfundinglatest/helper.php");

$limitResults = $params->get("results_limit", 5);
if ($limitResults <= 0) {
    $limitResults = 5;
}

$projects = CrowdfundingLatestModuleHelper::getProjects($limitResults);

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
    $socialProfiles = null;

    // Get users IDs
    $usersIds = array();
    foreach ($projects as $project) {
        $usersIds[] = $project->user_id;
    }

    $usersIds = array_filter($usersIds);

    // Get a social platform for integration
    $socialPlatform = $componentParams->get("integration_social_platform");

    // Load the class
    if (!empty($socialPlatform)) {
        $config = array(
            "social_platform" => $socialPlatform,
            "users_ids" => $usersIds
        );
        $socialProfilesBuilder = new Prism\Integration\Profiles\Builder($config);
        $socialProfilesBuilder->build();

        $socialProfiles = $socialProfilesBuilder->getProfiles();
    }
}

if (!empty($projects)) {
    require JModuleHelper::getLayoutPath('mod_crowdfundinglatest', $params->get('layout', 'default'));
}
