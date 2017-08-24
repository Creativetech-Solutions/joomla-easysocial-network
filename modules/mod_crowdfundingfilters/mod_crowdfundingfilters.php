<?php
/**
 * @package      Crowdfunding
 * @subpackage   Modules
 * @author       Todor Iliev
 * @copyright    Copyright (C) 2015 Todor Iliev <todor@itprism.com>. All rights reserved.
 * @license      GNU General Public License version 3 or later; see LICENSE.txt
 */

// no direct access
defined("_JEXEC") or die;

jimport("Prism.init");
jimport("Crowdfunding.init");

JLoader::register("CrowdfundingFiltersModuleHelper", JPATH_ROOT . "/modules/mod_crowdfundingfilters/helper.php");

$moduleclassSfx = htmlspecialchars($params->get('moduleclass_sfx'));

if ($params->get("enable_chosen", 0)) {
    JHtml::_('formbehavior.chosen', 'select.js-modfilters-filter');
}

$view = $app->input->getCmd("view");
if (strcmp("category", $view) == 0) {
    $categoryId = $app->input->getInt("id");
    $url = JRoute::_(CrowdfundingHelperRoute::getCategoryRoute($categoryId));
} else {
    $url = JRoute::_(CrowdfundingHelperRoute::getDiscoverRoute());
}

$filterPhrase = $app->getUserStateFromRequest("mod_crowdfundingfilters.filter_phrase", "filter_phrase", "");

// START DropDown filters

$elements = array();

if ($params->get("display_countries", 0)) {
    $filterCountry = $app->getUserStateFromRequest("mod_crowdfundingfilters.filter_country", "filter_country");

    $filters   = Crowdfunding\Filters::getInstance(JFactory::getDbo());
    $countries = $filters->getCountries("code");

    $option = JHtml::_("select.option", "", JText::_("MOD_CROWDFUNDINGFILTERS_SELECT_COUNTRY"), "value", "text");
    $option = array($option);

    $countries = array_merge($option, $countries);
    $elements[]  = 1;
}

if ($params->get("display_categories", 0)) {

    if (!empty($categoryId)) {
        $app->input->set("filter_category", (int)$categoryId);
    }

    $filterCategory = $app->getUserStateFromRequest("mod_crowdfundingfilters.filter_category", "filter_category");

    $config     = array(
        "filter.published" => 1
    );
    $categories = JHtml::_("category.options", "com_crowdfunding", $config);

    $option = JHtml::_("select.option", 0, JText::_("MOD_CROWDFUNDINGFILTERS_SELECT_CATEGORY"));
    $option = array($option);

    $categories = array_merge($option, $categories);
    $elements[]  = 1;
}

if ($params->get("display_funding_type", 0)) {
    $filterFundingType = $app->getUserStateFromRequest("mod_crowdfundingfilters.filter_fundingtype", "filter_fundingtype");

    $fundingTypes = array(
        JHtml::_("select.option", "", JText::_("MOD_CROWDFUNDINGFILTERS_SELECT_FUNDING_TYPE")),
        JHtml::_("select.option", "fixed", JText::_("MOD_CROWDFUNDINGFILTERS_FIXED")),
        JHtml::_("select.option", "flexible", JText::_("MOD_CROWDFUNDINGFILTERS_FLEXIBLE"))
    );
    $elements[]  = 1;
}

if ($params->get("display_project_type", 0)) {
    $filterProjectType = $app->getUserStateFromRequest("mod_crowdfundingfilters.filter_projecttype", "filter_projecttype");

    $filters      = Crowdfunding\Filters::getInstance(JFactory::getDbo());
    $projectTypes = $filters->getProjectsTypes();

    $optionSelect = array(
        0 => array(
            "value" => 0,
            "text"  => JText::_("MOD_CROWDFUNDINGFILTERS_SELECT_PROJECT_TYPE")
        )
    );
    $projectTypes = array_merge($optionSelect, $projectTypes);
    $elements[]  = 1;
}

// Prepare the span size of the drop down elements.
$elements = (count($elements)) ?: 1;
$spanDropDowns = 12 / $elements;

// END DropDown filters

// START Predefined filters

$elements = array();

// Create a statistics object.
$statistics = new Crowdfunding\Statistics\Basic(JFactory::getDbo());

if ($params->get("display_started_soon", 0)) {
    $options = array(
        "state"    => Prism\Constants::PUBLISHED,
        "approved" => Prism\Constants::APPROVED,
        "interval" => 7
    );

    $startedSoon = $statistics->getStartedSoonProjects($options);
    $elements[]  = 1;
}

// Ending soon projects.
if ($params->get("display_ending_soon", 0)) {
    $options = array(
        "state"    => Prism\Constants::PUBLISHED,
        "approved" => Prism\Constants::APPROVED,
        "interval" => 7
    );

    $endingSoon = $statistics->getEndingSoonProjects($options);
    $elements[]  = 1;
}

// Featured projects.
if ($params->get("display_featured", 0)) {
    $options = array(
        "state"    => Prism\Constants::PUBLISHED,
        "approved" => Prism\Constants::APPROVED
    );

    $featured = $statistics->getFeaturedProjects($options);
    $elements[]  = 1;
}

// Successfully completed projects.
if ($params->get("display_successfully_completed", 0)) {
    $options = array(
        "state"    => Prism\Constants::PUBLISHED,
        "approved" => Prism\Constants::APPROVED,
    );

    $successfullyCompleted = $statistics->getSuccessfullyCompletedProjects($options);
    $elements[]  = 1;
}

// Prepare the span size.
$elements = (count($elements)) ?: 1;
$span = 12 / $elements;

// END Predefined filters


// START Sorting filters

$allowedDirections = array("ASC", "DESC");
$orderDir = $app->input->get("filter_order_Dir", "DESC");
if (!in_array($orderDir, $allowedDirections)) {
    $orderDir = "DESC";
}

// Get current ordering.
$orderBy = $app->input->get("filter_order");

$orderDirOptions = array(
    "order_by"    => $orderBy,
    "order_dir"    => $orderDir,
    "opposite_dir" => (strcmp("ASC", $orderDir) == 0) ? "DESC" : "ASC"
);

// END Sorting filters

require JModuleHelper::getLayoutPath('mod_crowdfundingfilters', $params->get('layout', 'default'));