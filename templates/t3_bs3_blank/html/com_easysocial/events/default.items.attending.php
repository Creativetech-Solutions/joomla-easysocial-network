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

<div class="es-snackbar">
    <span>
        <?php echo JText::_("COM_EASYSOCIAL_EVENTS_GUEST_GOING"); ?>
    </span>
    <span>
        <?php echo JText::_("COM_EASYSOCIAL_PAGE_TITLE_EVENTS"); ?>
    </span>
    <a style="float: right;" href="<?php echo FRoute::events(array('uid' => $user->getAlias(true, true), 'type' => SOCIAL_TYPE_USER, 'subview' => 'all')); ?>">See All</a>
</div>


<div class="es-event-list clearfix<?php echo!$events ? ' is-empty' : ''; ?>">
    <?php if ($events) { ?>
        <?php
        foreach ($events as $event) {
            $guest = $event->getGuest($this->my->id);
            
            $steps = FD::model('Steps')->getSteps($event->category_id, SOCIAL_TYPE_CLUSTERS, SOCIAL_EVENT_VIEW_DISPLAY);
            $fieldsModel = FD::model('Fields');
            ?>
            <div class="es-event-item col-md-4 col-sm-6" data-event-item
                 data-id="<?php echo $event->id; ?>"
                 >

                <?php if ($event->isFeatured()) { ?>
                    <!--                    <div class="es-event-featured-label">
                                            <span><?php echo JText::_('COM_EASYSOCIAL_FEATURED'); ?></span>
                                        </div>-->
                <?php } ?>

                <div class="es-event-thumbnail">
                    <a href="<?php echo $event->getPermalink(); ?>">
                        <div class="es-event-time">
                            <?php echo $event->getEventStart()->format('M d', true); ?>
                            <br>
                            <?php echo $event->getEventStart()->format('Y', true); ?>
                        </div>
                        <div class="es-event-cover" style="background-image: url('<?php echo $event->getCover(); ?>')"></div>
                    </a>
                </div>
                <div class="es-event-content">
                    <div class="es-event-title">
                        <a href="<?php echo $event->getPermalink(); ?>"><?php echo $event->getName(); ?></a>
                    </div>
                    <?php /*<span>
                        <i class="fa fa-tag" aria-hidden="true"></i>
                        <?php echo '&nbsp;&nbsp;' . $event->getCategory()->get('title'); ?>
                    </span><?php */ ?>
                    <?php
                    foreach ($steps as $step) {
                        $step->fields = $fieldsModel->getCustomFields(array('step_id' => $step->id, 'data' => true, 'dataId' => $event->id, 'dataType' => SOCIAL_TYPE_EVENT, 'visible' => SOCIAL_EVENT_VIEW_DISPLAY, 'key' => 'event-category'));
                        //print_r($step->fields);
                        foreach ($step->fields as $field) {
                            //print_r($field);
                            //echo $field->data;
                            if($field->data){
                            ?>
                            <span>
                                <i class="fa fa-tag"></i>
                                <?php echo $field->data; ?>
                            </span>
                            <?php
                            }
                        }
                    }
                    ?>
            <?php if($event->isAdmin($this->my->id) || $event->isOwner()){ ?>
                    <div class="es-stream-control pull-right btn-group">
                        <a class="control-button" href="javascript:void(0);" data-bs-toggle="dropdown">
                            <i class="fa fa-ellipsis-h" aria-hidden="true"></i>
                        </a>
                        <ul class="dropdown-menu fd-reset-list" data-event-extras data-id="<?php echo $event->id; ?>">

                            <?php if (($event->isAdmin($this->my->id) || $event->isOwner()) && $event->featured == false) { ?>
                                <li>
                                    <a href="javascript:void(0);" data-event-feature><?php echo JText::_('APP_AUDIOS_FEATURE_BUTTON');?></a>
                                </li>
                            <?php } ?>

                            <?php if (($event->isAdmin($this->my->id) || $event->isOwner()) && $event->featured == true) { ?>
                                <li>
                                    <a href="javascript:void(0);" data-event-unfeature><?php echo JText::_('APP_AUDIOS_UNFEATURE_BUTTON');?></a>
                                </li>
                            <?php } ?>

                        </ul>
                    </div>
            <?php } ?>
                </div>
            </div>
        <?php } ?>

    <?php } else { ?>
        <div class="empty empty-hero">
            <i class="fa fa-film"></i>
            <div><?php echo JText::_('COM_EASYSOCIAL_DASHBOARD_SIDEBAR_NO_EVENTS_YET'); ?></div>
        </div>
    <?php } ?>
</div>