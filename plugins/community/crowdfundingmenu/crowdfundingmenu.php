<?php
/**
 * @package         ITPrism
 * @subpackage      Plugins
 * @copyright       Copyright (C) 2015 Todor Iliev <todor@itprism.com>. All rights reserved.
 * @license         http://www.gnu.org/licenses/gpl-3.0.en.html GNU/GPLv3
 */

// no direct access
defined('_JEXEC') or die;

jimport('Prism.init');
jimport('Crowdfunding.init');

if (!class_exists('CFactory')) {
    $path = JPath::clean(JPATH_ROOT . '/components/com_community/libraries/core.php');
    if (!is_file($path)) {
        return;
    }

    require_once $path;
}

/**
 * Community - Crowdfunding Menu Plugin
 *
 * @package        ITPrism
 * @subpackage     Plugins
 */
class plgCommunityCrowdfundingMenu extends CApplications
{
    /**
     * Affects constructor behavior. If true, language files will be loaded automatically.
     *
     * @var    boolean
     * @since  3.1
     */
    protected $autoloadLanguage = true;

    public $name  = 'Crowdfunding Menu';
    public $_name = 'community_crowdfundingmenu';

    public function onSystemStart()
    {
        if (!JComponentHelper::isInstalled('com_crowdfunding')) {
            return;
        }

        //initialize the toolbar object
        $toolbar = CFactory::getToolbar();

        $toolbar->addGroup('CROWDFUNDINGMENU', '<img src="/plugins/community/crowdfundingmenu/crowdfundingmenu/favicon.png" width="16" height="16" />', JRoute::_(CrowdfundingHelperRoute::getDiscoverRoute()));

        if ($this->params->get('display_user_projects', 0)) {
            $toolbar->addItem('CROWDFUNDINGMENU', 'CROWDFUNDINGMENU_USER_PROJECTS', 'PLG_COMMUNITY_CROWDFUNDINGMENU_MY_PROJECTS', JRoute::_(CrowdfundingHelperRoute::getProjectsRoute()));
        }

        if ($this->params->get('display_user_transactions', 0)) {
            $toolbar->addItem('CROWDFUNDINGMENU', 'CROWDFUNDINGMENU_USER_TRANSACTIONS', 'PLG_COMMUNITY_CROWDFUNDINGMENU_MY_TRANSACTIONS', JRoute::_(CrowdfundingHelperRoute::getTransactionsRoute()));
        }

        if ($this->params->get('display_user_proof', 0) and JComponentHelper::isInstalled('com_identityproof')) {
            jimport("IdentityProof.init");
            $toolbar->addItem('CROWDFUNDINGMENU', 'CROWDFUNDINGMENU_USER_PROOF', 'PLG_COMMUNITY_CROWDFUNDINGMENU_PROOF_OF_IDENTITY', JRoute::_(IdentityProofHelperRoute::getProofRoute()));
        }

    }
}
