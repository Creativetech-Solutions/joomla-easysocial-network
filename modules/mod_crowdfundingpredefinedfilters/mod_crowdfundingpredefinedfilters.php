<?php
/**
 * @package      Crowdfunding
 * @subpackage   Modules
 * @author       Todor Iliev
 * @copyright    Copyright (C) 2013 Todor Iliev <todor@itprism.com>. All rights reserved.
 * @license      http://www.gnu.org/copyleft/gpl.html GNU/GPL
 */

// no direct access
defined( "_JEXEC" ) or die;

jimport("Prism.init");
jimport("Crowdfunding.init");

$moduleclassSfx = htmlspecialchars($params->get('moduleclass_sfx'));
$elements = array();

// Started recently projects.
$statistics = new Crowdfunding\Statistics\Basic(JFactory::getDbo());
if ($params->get("display_started_soon", 1)) {
    $options = array(
        "state"    => Prism\Constants::PUBLISHED,
        "approved" => Prism\Constants::APPROVED,
        "interval" => 7
    );

    $startedSoon = $statistics->getStartedSoonProjects($options);
    $elements[]  = 1;
}

// Ending soon projects.
if ($params->get("display_ending_soon", 1)) {
    $options = array(
        "state"    => Prism\Constants::PUBLISHED,
        "approved" => Prism\Constants::APPROVED,
        "interval" => 7
    );

    $endingSoon = $statistics->getEndingSoonProjects($options);
    $elements[]  = 1;
}

// Successfully completed projects.
if ($params->get("display_successfully_completed", 1)) {
    $options = array(
        "state"    => Prism\Constants::PUBLISHED,
        "approved" => Prism\Constants::APPROVED,
    );

    $successfullyCompleted = $statistics->getSuccessfullyCompletedProjects($options);
    $elements[]  = 1;
}

// All projects.
if ($params->get("display_all", 1)) {
    $options = array(
        "state"    => Prism\Constants::PUBLISHED,
        "approved" => Prism\Constants::APPROVED,
    );

    $allProjects = $statistics->getTotalProjects($options);
    $elements[]  = 1;
}

// Prepare the span size.
$elements = (count($elements)) ?: 1;
$span = 12 / $elements;

require JModuleHelper::getLayoutPath('mod_crowdfundingpredefinedfilters', $params->get('layout', 'default'));