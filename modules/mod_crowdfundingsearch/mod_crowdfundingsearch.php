<?php
/**
 * @package      Crowdfunding
 * @subpackage   Modules
 * @author       Todor Iliev
 * @copyright    Copyright (C) 2015 Todor Iliev <todor@itprism.com>. All rights reserved.
 * @license      GNU General Public License version 3 or later; see LICENSE.txt
 */

// no direct access
defined('_JEXEC') or die;

jimport('Prism.init');
jimport('Crowdfunding.init');

$moduleclassSfx = htmlspecialchars($params->get('moduleclass_sfx'));

// Include HTML helper
if ($params->get('enable_chosen', 0)) {
    JHtml::_('formbehavior.chosen', 'select.js-modsearch-filter');
}

$filterPhrase = $app->getUserStateFromRequest('mod_crowdfundingsearch.filter_phrase', 'filter_phrase', '');

// Get options
$displayCountries    = (int)$params->get('display_countries', 0);
$displayCategories   = (int)$params->get('display_categories', 0);
$displayProjectTypes = (int)$params->get('display_project_type', 0);

if ($displayCountries) {
    $filters   = Crowdfunding\Filters::getInstance(JFactory::getDbo());
    $countries = $filters->getCountries('code');

    $filterCountry = $app->getUserStateFromRequest('mod_crowdfundingsearch.filter_country', 'filter_country');

    $option = JHtml::_('select.option', '', JText::_('MOD_CROWDFUNDINGSEARCH_SELECT_COUNTRY'));
    $option = array($option);

    $countries = array_merge($option, $countries);
}

if ($displayCategories) {
    $filterCategory = $app->getUserStateFromRequest('mod_crowdfundingsearch.filter_category', 'filter_category');

    $config     = array(
        'filter.published' => 1
    );
    $categories = JHtml::_('category.options', 'com_crowdfunding', $config);

    $option = JHtml::_('select.option', 0, JText::_('MOD_CROWDFUNDINGSEARCH_SELECT_CATEGORY'));
    $option = array($option);

    $categories = array_merge($option, $categories);
}

if ($displayProjectTypes) {
    $filterProjectType = $app->getUserStateFromRequest('mod_crowdfundingfilters.filter_projecttype', 'filter_projecttype');

    $filters      = Crowdfunding\Filters::getInstance(JFactory::getDbo());
    $projectTypes = $filters->getProjectsTypes();

    $optionSelect = array(0 =>
        array(
          'value' => 0,
          'text'  => JText::_('MOD_CROWDFUNDINGSEARCH_SELECT_PROJECT_TYPE')
        )
    );
    $projectTypes = array_merge($optionSelect, $projectTypes);
}

require JModuleHelper::getLayoutPath('mod_crowdfundingsearch', $params->get('layout', 'default'));