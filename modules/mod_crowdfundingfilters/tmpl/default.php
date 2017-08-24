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
    <form action="<?php echo $url; ?>" method="get" class="cf-modfilters-main">
        <?php if ($params->get('display_categories', 0)) { ?>
        <div class="form-group">
            <select name="filter_category" class="js-modfilters-filter">
                <?php echo JHtml::_('select.options', $categories, 'value', 'text', $filterCategory); ?>
            </select>
        </div>
        <?php } ?>

        <?php if ($params->get('display_countries', 0)) { ?>
        <div class="form-group">
            <select name="filter_country" class="js-modfilters-filter">
                <?php echo JHtml::_('select.options', $countries, 'value', 'text', $filterCountry); ?>
            </select>
        </div>
        <?php } ?>

        <?php if ($params->get('display_funding_type', 0)) { ?>
        <div class="form-group">
            <select name="filter_fundingtype" class="js-modfilters-filter">
                <?php echo JHtml::_('select.options', $fundingTypes, 'value', 'text', $filterFundingType); ?>
            </select>
        </div>
        <?php } ?>

        <?php if ($params->get('display_project_type', 0)) { ?>
        <div class="form-group">
            <select name="filter_projecttype" class="js-modfilters-filter">
                <?php echo JHtml::_('select.options', $projectTypes, 'value', 'text', $filterProjectType); ?>
            </select>
        </div>
        <?php } ?>

        <button type="submit" class="btn btn-primary"><?php echo JText::_('MOD_CROWDFUNDINGFILTERS_SUBMIT'); ?></button>
    </form>

    <br/>
    <?php if ($params->get('enable_predefined', 0)) { ?>
        <div class="cf-modfilters-predefined">
            <?php if ($params->get('display_started_soon', 0)) { ?>
            <div class="cf-started-soon">
                <a href="<?php echo JRoute::_(CrowdfundingHelperRoute::getDiscoverRoute(array('filter_date' => Crowdfunding\Constants::FILTER_STARTED_SOON))); ?>"><?php echo JText::_('MOD_CROWDFUNDINGFILTERS_NEW_PROJECTS'); ?></a>
                <span class="badge"><?php echo $startedSoon; ?></span>
            </div>
            <?php } ?>

            <?php if ($params->get('display_ending_soon', 0)) { ?>
            <div class="cf-final-phase">
                <a href="<?php echo JRoute::_(CrowdfundingHelperRoute::getDiscoverRoute(array('filter_date' => Crowdfunding\Constants::FILTER_ENDING_SOON))); ?>"><?php echo JText::_('MOD_CROWDFUNDINGFILTERS_ENDING_SOON'); ?></a>
                <span class="badge"><?php echo $endingSoon; ?></span>
            </div>
            <?php } ?>

            <?php if ($params->get('display_featured', 0)) { ?>
            <div class="cf-final-phase">
                <a href="<?php echo JRoute::_(CrowdfundingHelperRoute::getDiscoverRoute(array('filter_featured' => Prism\Constants::FEATURED))); ?>"><?php echo JText::_('MOD_CROWDFUNDINGFILTERS_RECOMMENDED'); ?></a>
                <span class="badge"><?php echo $featured; ?></span>
            </div>
            <?php } ?>

            <?php
            if ($params->get('display_successfully_completed', 0)) { ?>
            <div class="cf-successfully-completed">
                <a href="<?php echo JRoute::_(CrowdfundingHelperRoute::getDiscoverRoute(array('filter_funding_state' => Crowdfunding\Constants::FILTER_SUCCESSFULLY_COMPLETED))); ?>"><?php echo JText::_('MOD_CROWDFUNDINGFILTERS_SUCCESS_STORIES'); ?></a>
                <span class="badge"><?php echo $successfullyCompleted; ?></span>
            </div>
            <?php
            } ?>
        </div>
    <?php } ?>

    <?php if ($params->get('enable_sorting', 0)) { ?>
        <div class="cf-modfilters-sorting">
            <h5><?php echo JText::_('MOD_CROWDFUNDINGFILTERS_SORTING'); ?></h5>
            <?php if ($params->get('display_alphabet', 1)) {
                echo CrowdfundingFiltersModuleHelper::orderByLink(JText::_('MOD_CROWDFUNDINGFILTERS_ALPHABET'), Crowdfunding\Constants::ORDER_BY_NAME, $orderDirOptions, 'cf-order-alphabet');
            } ?>
            <?php if ($params->get('display_date', 1)) {
                echo CrowdfundingFiltersModuleHelper::orderByLink(JText::_('MOD_CROWDFUNDINGFILTERS_END_DATE'), Crowdfunding\Constants::ORDER_BY_END_DATE, $orderDirOptions, 'cf-order-end-date');
            } ?>
            <?php if ($params->get('display_popularity', 1)) {
                echo CrowdfundingFiltersModuleHelper::orderByLink(JText::_('MOD_CROWDFUNDINGFILTERS_POPULARITY'), Crowdfunding\Constants::ORDER_BY_POPULARITY, $orderDirOptions, 'cf-order-popularity');
            } ?>
            <?php if ($params->get('display_popularity', 1)) {
                echo CrowdfundingFiltersModuleHelper::orderByLink(JText::_('MOD_CROWDFUNDINGFILTERS_FUNDING'), Crowdfunding\Constants::ORDER_BY_FUNDING, $orderDirOptions, 'cf-order-funding');
            } ?>
            <?php if ($params->get('display_popularity', 1)) {
                echo CrowdfundingFiltersModuleHelper::orderByLink(JText::_('MOD_CROWDFUNDINGFILTERS_FANS'), Crowdfunding\Constants::ORDER_BY_FANS, $orderDirOptions, 'cf-order-fans');
            } ?>
        </div>
    <?php } ?>
</div>