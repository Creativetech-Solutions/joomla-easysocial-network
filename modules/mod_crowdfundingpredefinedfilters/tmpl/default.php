<?php
/**
 * @package      Crowdfunding
 * @subpackage   Modules
 * @author       Todor Iliev
 * @copyright    Copyright (C) 2013 Todor Iliev <todor@itprism.com>. All rights reserved.
 * @license      http://www.gnu.org/copyleft/gpl.html GNU/GPL
 */
 
// no direct access
defined('_JEXEC') or die;?>
<div class="cf-modpredefinedfilters<?php echo $moduleclassSfx; ?>">
    <?php if($params->get("display_started_soon", 1)) { ?>
    <div class="cf-started-soon">
        <a href="<?php echo JRoute::_(CrowdfundingHelperRoute::getDiscoverRoute(array("filter_date" => Crowdfunding\Constants::FILTER_STARTED_SOON))); ?>"><?php echo JText::_("MOD_CROWDFUNDINGPREDEFINEDFILTERS_RECENTLY_STARTED"); ?></a>
        <span class="badge"><?php echo $startedSoon; ?></span>
    </div>
    <?php } ?>
    <?php if($params->get("display_ending_soon", 1)) { ?>
        <div class="cf-final-phase">
            <a href="<?php echo JRoute::_(CrowdfundingHelperRoute::getDiscoverRoute(array("filter_date" => Crowdfunding\Constants::FILTER_STARTED_SOON))); ?>"><?php echo JText::_("MOD_CROWDFUNDINGPREDEFINEDFILTERS_ENDING_SOON"); ?></a>
            <span class="badge"><?php echo $endingSoon; ?></span>
        </div>
    <?php } ?>
    <?php if($params->get("display_successfully_completed", 1)) { ?>
        <div class="cf-successfully-completed">
            <a href="<?php echo JRoute::_(CrowdfundingHelperRoute::getDiscoverRoute(array("filter_funding_state" => Crowdfunding\Constants::FILTER_SUCCESSFULLY_COMPLETED))); ?>"><?php echo JText::_("MOD_CROWDFUNDINGPREDEFINEDFILTERS_SUCCESSFULLY_COMPLETED"); ?></a>
            <span class="badge"><?php echo $successfullyCompleted; ?></span>
        </div>
    <?php } ?>
    <?php if($params->get("display_all", 1)) { ?>
        <div class="cf-all">
            <a href="<?php echo JRoute::_(CrowdfundingHelperRoute::getDiscoverRoute()); ?>"><?php echo JText::_("MOD_CROWDFUNDINGPREDEFINEDFILTERS_ALL"); ?></a>
            <span class="badge"><?php echo $allProjects; ?></span>
        </div>
    <?php } ?>
</div>
