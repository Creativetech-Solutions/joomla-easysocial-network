<?php
/**
 * @package      Crowdfunding
 * @subpackage   Modules
 * @author       Todor Iliev
 * @copyright    Copyright (C) 2013 Todor Iliev <todor@itprism.com>. All rights reserved.
 * @license      GNU General Public License version 3 or later; see LICENSE.txt
 */
 
// no direct access
defined('_JEXEC') or die;?>
<div class="cf-modfilterssorting<?php echo $moduleclassSfx; ?>">
    <?php if($params->get("display_alphabet", 1)) {
        echo CrowdfundingFiltersSortingModuleHelper::orderByLink(JText::_("MOD_CROWDFUNDINGFILTERSSORTING_ALPHABET"), Crowdfunding\Constants::ORDER_BY_NAME, $orderDirOptions, "cf-order-alphabet");
    } ?>
    <?php if($params->get("display_date", 1)) {
        echo CrowdfundingFiltersSortingModuleHelper::orderByLink(JText::_("MOD_CROWDFUNDINGFILTERSSORTING_END_DATE"), Crowdfunding\Constants::ORDER_BY_END_DATE, $orderDirOptions, "cf-order-end-date");
    } ?>
    <?php if($params->get("display_popularity", 1)) {
        echo CrowdfundingFiltersSortingModuleHelper::orderByLink(JText::_("MOD_CROWDFUNDINGFILTERSSORTING_POPULARITY"), Crowdfunding\Constants::ORDER_BY_POPULARITY, $orderDirOptions, "cf-order-popularity");
    } ?>
    <?php if($params->get("display_popularity", 1)) {
        echo CrowdfundingFiltersSortingModuleHelper::orderByLink(JText::_("MOD_CROWDFUNDINGFILTERSSORTING_FUNDING"), Crowdfunding\Constants::ORDER_BY_FUNDING, $orderDirOptions, "cf-order-funding");
    } ?>
    <?php if($params->get("display_popularity", 1)) {
        echo CrowdfundingFiltersSortingModuleHelper::orderByLink(JText::_("MOD_CROWDFUNDINGFILTERSSORTING_FANS"), Crowdfunding\Constants::ORDER_BY_FANS, $orderDirOptions, "cf-order-fans");
    } ?>
</div>
