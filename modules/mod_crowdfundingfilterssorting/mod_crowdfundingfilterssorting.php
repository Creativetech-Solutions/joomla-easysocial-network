<?php
/**
 * @package      Crowdfunding
 * @subpackage   Modules
 * @author       Todor Iliev
 * @copyright    Copyright (C) 2013 Todor Iliev <todor@itprism.com>. All rights reserved.
 * @license      GNU General Public License version 3 or later; see LICENSE.txt
 */

// no direct access
defined( "_JEXEC" ) or die;

jimport("Prism.init");
jimport("Crowdfunding.init");

JLoader::register("CrowdfundingFiltersSortingModuleHelper", JPATH_ROOT . "/modules/mod_crowdfundingfilterssorting/helper.php");

$moduleclassSfx = htmlspecialchars($params->get('moduleclass_sfx'));

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

require JModuleHelper::getLayoutPath('mod_crowdfundingfilterssorting', $params->get('layout', 'default'));