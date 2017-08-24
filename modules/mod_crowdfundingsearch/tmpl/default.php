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
<div class="cf-modsearch<?php echo $moduleclassSfx; ?>">
    <form action="<?php echo JRoute::_(CrowdfundingHelperRoute::getDiscoverRoute()); ?>" method="get">

        <div class="form-group">
            <label class="sr-only" for="cf-modsearch-search-field"><?php echo JText::_('MOD_CROWDFUNDINGSEARCH_SEARCH_PHRASE'); ?></label>
            <input type="text" name="filter_phrase" value="<?php echo $filterPhrase; ?>" placeholder="<?php echo JText::_('MOD_CROWDFUNDINGSEARCH_SEARCH_FOR'); ?>" class="form-control" id="cf-modsearch-search-field"/>
        </div>

        <?php if (!empty($displayCategories)) { ?>
        <div class="form-group">
            <select name="filter_category" class="js-modsearch-filter">
                <?php echo JHtml::_('select.options', $categories, 'value', 'text', $filterCategory); ?>
            </select>
        </div>
        <?php } ?>

        <?php if (!empty($displayCountries)) { ?>
        <div class="form-group">
            <select name="filter_country" class="js-modsearch-filter">
                <?php echo JHtml::_('select.options', $countries, 'value', 'text', $filterCountry); ?>
            </select>
        </div>
        <?php } ?>

        <?php if (!empty($displayProjectTypes)) { ?>
        <div class="form-group">
            <select name="filter_projecttype" class="js-modsearch-filter">
                <?php echo JHtml::_('select.options', $projectTypes, 'value', 'text', $filterProjectType); ?>
            </select>
        </div>
        <?php } ?>

        <button type="submit" class="btn btn-primary"><?php echo JText::_('MOD_CROWDFUNDINGSEARCH_SEARCH'); ?></button>
    </form>
</div>