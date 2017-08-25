<?php
/**
 * @package		EasySocial
 * @copyright	Copyright (C) 2010 - 2014 Stack Ideas Sdn Bhd. All rights reserved.
 * @license		GNU/GPL, see LICENSE.php
 * EasySocial is free software. This version may have been modified pursuant
 * to the GNU General Public License, and as distributed it includes or
 * is derivative of works licensed under the GNU General Public License or
 * other free or open source software licenses.
 * See COPYRIGHT.php for copyright notices and details.
 */
defined('_JEXEC') or die('Unauthorized Access');

$allowedRss = array('me', 'everyone', 'list', 'bookmarks', 'following', 'custom');

$streamType = $this->input->get('type');

$showRSS = in_array($streamType, $allowedRss) || !$streamType;
?>
<?php echo $this->output('site/dashboard/profile-header'); ?>
<div class="es-profile userProfile" data-id="<?php echo $user->id; ?>" data-profile>
    <div class="es-container">
        <a href="javascript:void(0);" class="btn btn-block btn-es-inverse btn-sidebar-toggle" data-sidebar-toggle>
            <i class="fa fa-grid-view  mr-5"></i> <?php echo JText::_('COM_EASYSOCIAL_SIDEBAR_TOGGLE'); ?>
        </a>
        <div class="es-sidebar <?php echo ($layout == 'timeline') ? "collapse in" : ""; ?>" id="es-sidebar" data-sidebar>
            <?php //get the online connections
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

        <div class="es-content <?php echo $layout; ?>" data-profile-contents>

            <i class="loading-indicator fd-small"></i>

            <?php echo $this->render('module', 'es-dashboard-before-contents'); ?>
            
            
            <?php //stream content starts ?>
            <?php if (!empty($eventId)) { ?>
                <div class="item-heading mb-10">
                    <?php echo $this->loadTemplate('site/events/mini.header', array('event' => FD::event($eventId), 'showApps' => false)); ?>
                </div>
            <?php } ?>

            <?php if (!empty($groupId)) { ?>
                <div class="item-heading mb-10">
                    <?php echo $this->loadTemplate('site/groups/mini.header', array('group' => FD::group($groupId), 'showApps' => false)); ?>
                </div>
            <?php } ?>

            <?php if ($hashtag) { ?>
                <div class="es-streams">
                    <div class="row">
                        <div class="col-md-12">
                            <a href="javascript:void('0');"
                               class="fd-small mt-10 pull-right"
                               data-hashtag-filter-save
                               data-tag="<?php echo $hashtag; ?>"
                               ><i class="icon-es-create"></i> <?php echo JText::_('COM_EASYSOCIAL_STREAM_SAVE_FILTER'); ?></a>

                            <h3 class="pull-left">
                                <a href="<?php echo FRoute::dashboard(array('layout' => 'hashtag', 'tag' => $hashtagAlias)); ?>">#<?php echo $hashtag; ?></a>
                            </h3>
                        </div>
                    </div>
                    <p class="fd-small">
                        <?php echo JText::sprintf('COM_EASYSOCIAL_STREAM_HASHTAG_CURRENTLY_FILTERING', '<a href="' . FRoute::dashboard(array('layout' => 'hashtag', 'tag' => $hashtagAlias)) . '">#' . $hashtag . '</a>'); ?>
                    </p>
                </div>
                <hr />
            <?php } ?>

            <?php //end of stream content?>
   
            <?php echo $this->render('module', 'es-dashboard-after-contents'); ?>
        </div>
    </div>
</div>

