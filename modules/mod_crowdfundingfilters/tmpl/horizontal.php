<?php
/**
 * @package      Crowdfunding
 * @subpackage   Modules
 * @author       Todor Iliev
 * @copyright    Copyright (C) 2015 Todor Iliev <todor@itprism.com>. All rights reserved.
 * @license      GNU General Public License version 3 or later; see LICENSE.txt
 */
 
// no direct access
defined('_JEXEC') or die; ?>
<div class="cf-modfilters<?php echo $moduleclassSfx; ?>">
    <form action="<?php echo $url;?>" method="get">
        <div class="row">
            <?php if($params->get("display_categories", 0)) {?>
            <div class="col-md-<?php echo $spanDropDowns;?>">
                <select name="filter_category" class="js-modfilters-filter">
                    <?php echo JHtml::_("select.options", $categories, "value", "text", $filterCategory);?>
                </select>
            </div>
            <?php }?>
        
            <?php if($params->get("display_countries", 0)) {?>
            <div class="col-md-<?php echo $spanDropDowns;?>">
                <select name="filter_country" class="js-modfilters-filter">
                    <?php echo JHtml::_("select.options", $countries, "value", "text", $filterCountry);?>
                </select>
            </div>
            <?php }?>
        
            <?php if($params->get("display_funding_type", 0)) {?>
            <div class="col-md-<?php echo $spanDropDowns;?>">
                <select name="filter_fundingtype" class="js-modfilters-filter">
                    <?php echo JHtml::_("select.options", $fundingTypes, "value", "text", $filterFundingType);?>
                </select>
            </div>
            <?php }?>
            
            <?php if($params->get("display_project_type", 0)) {?>
            <div class="col-md-<?php echo $spanDropDowns;?>">
                <select name="filter_projecttype" class="js-modfilters-filter">
                    <?php echo JHtml::_("select.options", $projectTypes, "value", "text", $filterProjectType);?>
                </select>
            </div>
            <?php }?>
        </div>
        <br />
        <button type="submit" class="btn btn-primary"><?php echo JText::_("MOD_CROWDFUNDINGFILTERS_SUBMIT");?></button>
    </form>

    <div class="cf-predefined-filters row">
        <?php if($params->get("display_started_soon", 0)) {?>
            <div class="cf-started-soon col-md-<?php echo $span; ?>">
                <a href="<?php echo JRoute::_(CrowdfundingHelperRoute::getDiscoverRoute(array("filter_date" => Crowdfunding\Constants::FILTER_STARTED_SOON))); ?>"><?php echo JText::_("MOD_CROWDFUNDINGFILTERS_NEW_PROJECTS"); ?></a>
                <span class="badge"><?php echo $startedSoon; ?></span>
            </div>
        <?php } ?>

        <?php if($params->get("display_ending_soon", 0)) { ?>
            <div class="cf-final-phase col-md-<?php echo $span; ?>">
                <a href="<?php echo JRoute::_(CrowdfundingHelperRoute::getDiscoverRoute(array("filter_date" => Crowdfunding\Constants::FILTER_ENDING_SOON))); ?>"><?php echo JText::_("MOD_CROWDFUNDINGFILTERS_ENDING_SOON"); ?></a>
                <span class="badge"><?php echo $endingSoon; ?></span>
            </div>
        <?php } ?>

        <?php if($params->get("display_featured", 0)) { ?>
            <div class="cf-final-phase col-md-<?php echo $span; ?>">
                <a href="<?php echo JRoute::_(CrowdfundingHelperRoute::getDiscoverRoute(array("filter_featured" => Prism\Constants::FEATURED))); ?>"><?php echo JText::_("MOD_CROWDFUNDINGFILTERS_RECOMMENDED"); ?></a>
                <span class="badge"><?php echo $featured; ?></span>
            </div>
        <?php } ?>

        <?php if($params->get("display_successfully_completed", 0)) { ?>
            <div class="cf-successfully-completed col-md-<?php echo $span; ?>">
                <a href="<?php echo JRoute::_(CrowdfundingHelperRoute::getDiscoverRoute(array("filter_funding_state" => Crowdfunding\Constants::FILTER_SUCCESSFULLY_COMPLETED))); ?>"><?php echo JText::_("MOD_CROWDFUNDINGFILTERS_SUCCESS_STORIES"); ?></a>
                <span class="badge"><?php echo $successfullyCompleted; ?></span>
            </div>
        <?php } ?>
    </div>

</div>