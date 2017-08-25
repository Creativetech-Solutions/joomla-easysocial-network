<?php
/**
 * @package		EasySocial
 * @copyright	Copyright (C) 2010 - 2015 Stack Ideas Sdn Bhd. All rights reserved.
 * @license		GNU/GPL, see LICENSE.php
 * EasySocial is free software. This version may have been modified pursuant
 * to the GNU General Public License, and as distributed it includes or
 * is derivative of works licensed under the GNU General Public License or
 * other free or open source software licenses.
 * See COPYRIGHT.php for copyright notices and details.
 */
defined('_JEXEC') or die('Unauthorized Access');

?>
<?php echo $this->output('site/dashboard/profile-header'); ?>
<div class="es-dashboard" data-dashboard>
    <div class="es-container">
        <a href="javascript:void(0);" class="btn btn-block btn-es-inverse btn-sidebar-toggle" data-sidebar-toggle>
            <i class="fa fa-grid-view  mr-5"></i> <?php echo JText::_('COM_EASYSOCIAL_SIDEBAR_TOGGLE'); ?>
        </a>
        <div class="es-sidebar" data-sidebar data-dashboard-sidebar>
            <ul class="newsfeed-sidebar">
                <li class="newsfeed">
                    <a href="<?php echo FRoute::profile(array('id' => $user->getAlias(), 'layout' => 'timeline')); ?>">
                        <i class="fa fa-rss"></i>
                        <?php echo JText::_('COM_EASYSOCIAL_USER_NEWSFEED'); ?>
                    </a>
                </li>
                <li class="dashboard">
                    <a href="<?php echo FRoute::dashboard(); ?>">
                        <i class="fa fa-dashboard"></i>
                        <?php echo JText::_('COM_EASYSOCIAL_USER_DASHBOARD'); ?>
                    </a>
                </li>
                <li class="favorite active">
                    <a href="<?php echo FRoute::dashboard(array('type' => 'bookmarks')); ?>">
                        <i class="fa fa-heart"></i>
                        <?php echo JText::_('COM_EASYSOCIAL_USER_SAVED'); ?>
                    </a>
                </li>
                <hr />

                <li class="feed_messages">
                    <a href="<?php echo FRoute::conversations(array(), false); ?>">
                        <i class="fa fa-envelope-o"></i>
                        <?php echo JText::_('COM_EASYSOCIAL_USER_MESSAGES'); ?>
                    </a>
                </li>
                <li class="feed_notifications">
                    <a href="<?php echo FRoute::notifications(); ?>">
                        <i class="fa fa-bell"></i>
                        <?php echo JText::_('COM_EASYSOCIAL_TOOLBAR_NOTIFICATIONS'); ?>
                    </a>
                </li>
                <li class="feed_settings">
                    <a href="<?php echo FRoute::dashboard(); ?>">
                        <i class="fa fa-cog"></i>
                        <?php echo JText::_('COM_EASYSOCIAL_USER_SETTINGS'); ?>
                    </a>
                </li>
            </ul>        
        </div>

        <div class="es-content" data-dashboard-content>

            <i class="loading-indicator fd-small"></i>

            <?php echo $this->render('module', 'es-dashboard-before-contents'); ?>
            <?php echo $this->includeTemplate('site/dashboard/feeds'); ?>
            <?php echo $this->render('module', 'es-dashboard-after-contents'); ?>
        </div>
    </div>
</div>
