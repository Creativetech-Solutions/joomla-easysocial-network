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
?>
<div class="notifications-result" data-notificationsystem>
    <div class="popbox-header">
        <div class="es-title">
            <?php echo JText::_('COM_EASYSOCIAL_TOOLBAR_NOTIFICATIONS'); ?>
        </div>
        <div class="es-action">
            <a href="javascript:void(0);" data-notificationsystem-readall><?php echo JText::_('COM_EASYSOCIAL_MARK_ALL_READ'); ?></a>
            |
            <a href="<?php echo FRoute::notifications(); ?>"><?php echo JText::_('COM_EASYSOCIAL_VIEW_ALL'); ?></a>
        </div>
    </div>
    <div class="popbox-body">
        <ul class="fd-reset-list<?php echo!$notifications ? ' is-empty' : ''; ?>" data-notificationsystem-items>
            <?php if ($notifications) { ?>
                <?php foreach ($notifications as $notification) { ?>
                    <li class="type-<?php echo $notification->type; ?> is-unread">

                        <div class="media notice-message">
                            <?php
                            if ($notification->type == 'projects' && $notification->cmd == 'projects.team.invited') {
                                $Projectapp = FD::table('app');
                                $Projectapp->loadByElement('projects', SOCIAL_APPS_GROUP_USER, 'apps');
                                $params 	= FD::makeObject( $notification->params );
                                ?>
                                <div data-popbox-project-item>
                                    <div class="media-object pull-left">
                                        <div class="es-avatar">
                                            <img src="<?php echo $notification->user->getAvatar(); ?>" title="<?php echo $this->html('string.escape', $notification->user->getName()); ?>" />
                                        </div>
                                    </div>
                                    <div class="media-body">

                                        <?php if ($notification->image) { ?>
                                            <span class="pull-right object-image">
                                                <span style="background-image: url('<?php echo $notification->image; ?>');"></span>
                                            </span>
                                        <?php } ?>

                                        <div class="object-info">
                                            <div class="object-title">
                                                <div class="fd-small">
                                                    <a href="<?php echo $notification->user->getPermalink(); ?>"><?php echo $notification->user->getName(); ?></a>
                                                    <span data-project-item-text> invited you to join the team for:</span>
                                                </div>
                                                <div class="toolbar-project-meta" data-project-item-title>
                                                    <?php
                                                    $projectURL = FRoute::apps(array('layout' => 'canvas', 'clayout' => 'detail', 'id' => $Projectapp->getAlias(), 'prid' => $notification->uid,));
                                                    echo '<a href="' . $projectURL . '" target="_blank">' . str_replace('Invitation to Join team for ', '', $notification->title) . '</a>';
                                                    ?>
                                                </div>
                                            </div>
                                            <?php if ($notification->content) { ?>
                                                <div class="object-content">
                                                    "<b><?php echo $this->html('string.escape', $notification->content); ?></b>"
                                                </div>
                                            <?php } ?>

                                            <div class="object-timestamp mt-5" data-project-datetime>
                                                <?php if ($notification->icon) { ?>
                                                    <i class="icon-es-games icon-tb-notice pull-left"></i>
                                                <?php } else if ($notification->type == 'broadcast') { ?>
                                                    <i class="fa fa-bullhorn "></i>
                                                <?php } else { ?>
                                                    <i class="fa fa-globe "></i>
                                                <?php } ?>
                                                <small><?php echo $notification->since; ?></small>
                                            </div>
                                        </div>

                                    </div>
                                    <div class="es-action-wrap">
                                            <div class="es-action" data-project-item-actions>
                                                    <i class="loading-indicator fd-small"></i>
                                                    <a class="btn btn-es btn-small reject-project" href="javascript:void(0);" data-project-item-reject data-project-item-action data-id="<?php echo $params->projectId;?>" data-ntid="<?php echo $notification->id;?>">
                                                            <?php echo JText::_( 'COM_EASYSOCIAL_REJECT_BUTTON' );?>
                                                    </a>
                                                    <a class="btn btn-es-primary btn-small accept-project" href="javascript:void(0);" data-project-item-accept data-project-item-action data-id="<?php echo $params->projectId;?>" data-ntid="<?php echo $notification->id;?>">
                                                            <?php echo JText::_( 'COM_EASYSOCIAL_ACCEPT_BUTTON' );?>
                                                    </a>
                                            </div>
                                    </div>
                                </div>
        <?php } else { ?>
                                <a href="<?php echo FRoute::notifications(array('id' => $notification->id, 'layout' => 'route')); ?>">
                                    <div class="media-object pull-left">
                                        <div class="es-avatar">
                                            <img src="<?php echo $notification->user->getAvatar(); ?>" title="<?php echo $this->html('string.escape', $notification->user->getName()); ?>" />
                                        </div>
                                    </div>
                                    <div class="media-body">

            <?php if ($notification->image) { ?>
                                            <span class="pull-right object-image">
                                                <span style="background-image: url('<?php echo $notification->image; ?>');"></span>
                                            </span>
            <?php } ?>

                                        <div class="object-info">
                                            <div class="object-title">
                                            <?php echo $notification->title; ?>
                                            </div>

                                            <?php if ($notification->content) { ?>
                                                <div class="object-content">
                                                    "<b><?php echo $this->html('string.escape', $notification->content); ?></b>"
                                                </div>
                                                <?php } ?>

                                            <div class="object-timestamp mt-5">
                                                <?php if ($notification->icon) { ?>
                                                    <i class="icon-es-games icon-tb-notice pull-left"></i>
                                                <?php } else if ($notification->type == 'broadcast') { ?>
                                                    <i class="fa fa-bullhorn "></i>
                                                <?php } else { ?>
                                                    <i class="fa fa-globe "></i>
            <?php } ?>
                                                <small><?php echo $notification->since; ?></small>
                                            </div>
                                        </div>

                                    </div>
                                </a>
                    <?php } ?>
                        </div>
                    </li>
    <?php } ?>
<?php } else { ?>
                <li class="requestItem empty center">
                    <div class="mt-20 pl-10 pr-10 fd-small">
                        <i class="fa fa-info  mr-5"></i> <?php echo JText::_('COM_EASYSOCIAL_NOTIFICATIONS_NO_UNREAD'); ?>
                    </div>
                </li>
<?php } ?>
        </ul>
    </div>

</div>
