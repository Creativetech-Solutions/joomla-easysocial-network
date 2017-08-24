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

switch ($params->get("order_by", Crowdfunding\Constants::ORDER_BY_CREATED_DATE)) {
    case 1: // Title
        $orderBy = "a.title";
        break;

    case 2: // Created date
        $orderBy = "a.created_time";
        break;

    default: // Ordering
         $orderBy = "a.lft";
        break;
}

// Get parent category ID.
$parentCategoryId = $app->input->getInt("id", Prism\Constants::CATEGORY_ROOT);

// Prepare the options.
$options = array(
    "offset" => 0,
    "limit" => $params->get("limit"),
    "order_by" => $orderBy,
    "order_dir" => $params->get("order_dir", "ASC")
);

$categories = new Crowdfunding\Categories();
$categories->setDb(JFactory::getDbo());
$categories->load($parentCategoryId, $options);

$items = $categories->toArray();
$items = Joomla\Utilities\ArrayHelper::toObject($items);

if (!empty($items)) {

    // Get description length
    $descriptionLength = $params->get("description_length", 128);

    $numberInRow = $params->get("items_in_row", 3);
    $itemSpan    = (!empty($numberInRow)) ? round(12 / $numberInRow) : 3;

    $items = CrowdfundingHelper::prepareCategories($items, $numberInRow);

    // Get the number of projects in the categories.
    if ($params->get("display_projects_number", 0)) {
        $projectsNumber = $categories->getProjectsNumber();
    }

    require JModuleHelper::getLayoutPath('mod_crowdfundingcategories', $params->get('layout', 'default'));
}
