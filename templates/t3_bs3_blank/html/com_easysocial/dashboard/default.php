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
$input = JFactory::getApplication()->input;
$appid = $input->get('appId', false);
$layout = $input->get('layout', '');
$view = $input->get('view', false);

//temporary - will fix it later
//for ajax content like audio page
if ($appid == "169-audios" && $content) {
    ?>
    <div class="es-dashboard" data-dashboard>
        <div class="es-container">
            <a href="javascript:void(0);" class="btn btn-block btn-es-inverse btn-sidebar-toggle" data-sidebar-toggle>
                <i class="fa fa-grid-view  mr-5"></i> <?php echo JText::_('COM_EASYSOCIAL_SIDEBAR_TOGGLE'); ?>
            </a>
            <div class="es-content" data-dashboard-content>
                <i class="loading-indicator fd-small"></i>

                <?php echo $this->render('module', 'es-dashboard-before-contents'); ?>

                <div data-dashboard-real-content>

                    <?php if ($contents) { ?>
                        <?php echo $contents; ?>
                    <?php } else { ?>
                        <?php echo $this->includeTemplate('site/dashboard/feeds'); ?>
                    <?php } ?>
                </div>

                <?php echo $this->render('module', 'es-dashboard-after-contents'); ?>
            </div>
        </div>
    </div>  
    <?php
}
//end ajax content
else if (($view == "profile" or $view == "dashboard") && empty($layout)) {
    echo $this->output('site/dashboard/dashboard');
} else if ($layout == "timeline") {
    JHtml::_('jquery.framework');
    JFactory::getDocument()->addScript(JURI::base() . 'templates/t3_bs3_blank/js/timeline.js');
    JFactory::getDocument()->addScript(JURI::base() . 'templates/t3_bs3_blank/js/responsive-toolkit.js');
    JFactory::getDocument()->addScript(JURI::base() . 'templates/t3_bs3_blank/js/sidebar.js');
    //contents loads the feed content
    ?>

    <div class="es-dashboard" data-dashboard>
        <div class="es-container">
            <a href="javascript:void(0);" class="btn btn-block btn-es-inverse btn-sidebar-toggle" data-sidebar-toggle>
                <i class="fa fa-grid-view  mr-5"></i> <?php echo JText::_('COM_EASYSOCIAL_SIDEBAR_TOGGLE'); ?>
            </a>
            <div class="es-sidebar <?php echo ($layout == 'timeline') ? "collapse in" : ""; ?>" id="es-sidebar" data-sidebar>
                <?php
                //get the online connections
                $friendsmodel = FD::model('Friends');
                $options = array('limit' => 10);
                // Get list of friends by the current user.
                $friends = $friendsmodel->getFriends($user->id, $options);
                $friendstotal = $friendsmodel->getTotalFriends($user->id);
                ?>


                <ul class="newsfeed-sidebar">
                    <li class="newsfeed active">
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
                    <li class="favorite">
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
    <?php if ($friendstotal > 0): ?>
                    <hr/>
                    <div class="connection_sidebar">
                        <div id="header"><?= JText::_("COM_EASYSOCIAL_CONNECTIONS"); ?></div>
                        <?php
                        echo $this->includeTemplate('site/dashboard/sidebar.module.onlinefriends', array('displayOn' => 'friends', 'friendsData' => $friends, 'friendsTotal' => $friendstotal));
                        ?>
                    </div>
    <?php endif; ?>
            </div>
            <div class="es-content" data-dashboard-content>


                <i class="loading-indicator fd-small"></i>
                <?php
                if ($contents) {
                    echo $contents;
                } else {
                    
                }
                ?>

            </div>
        </div>
    </div>
    <?php
}
