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
<?php if ((isset($isFeatured) && $isFeatured)) { ?>
    <div class="es-snackbar">
        <span>
            <?php echo JText::_("COM_EASYSOCIAL_FEATURED"); ?>
        </span>
        <span>
            <?php echo JText::_("COM_EASYSOCIAL_PAGE_TITLE_EVENT"); ?>
        </span>
    </div>
<?php } ?>
<div class="es-event-list clearfix<?php echo!$events ? ' is-empty' : ''; ?>">
    <?php if ($events) { ?>
        <?php
        foreach ($events as $event) {
            $guest = $event->getGuest($this->my->id);
            $owner = $event->getOwner();

            $steps = FD::model('Steps')->getSteps($event->category_id, SOCIAL_TYPE_CLUSTERS, SOCIAL_EVENT_VIEW_DISPLAY);
            $fieldsModel = FD::model('Fields');
            ?>
            <script>
                EasySocial.require().script('site/events/item').done(function ($) {
                    $('[data-event-item]').addController('EasySocial.Controller.Events.Item', {
                        id: <?php echo $event->id; ?>
                    });
                });
            </script>
            <div class="es-event-item js-eq-height-container" data-event-item
                 data-id="<?php echo $event->id; ?>"
                 >
                <div class="col-md-6">
                    <div class="es-event-thumbnail">
                        <a href="<?php echo $event->getPermalink(); ?>">
                            <div class="es-event-cover js-eq-height" style="padding:0px; background-image: url('<?php echo $event->getCover(); ?>')"></div>
                        </a>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="es-event-content js-eq-height">
                        <div class="es-profile-header-calendar">
                            <div class="es-profile-header-calendar__wrap">
                                <div class="es-profile-header-calendar__date-mth"><?php echo $event->getEventStart()->format('M', true); ?></div>
                                <div class="es-profile-header-calendar__date-day"><?php echo $event->getEventStart()->format('d', true); ?></div>
                            </div>

                        </div>
                        <div class="es-profile-header-body fd-cf">
                            <div class="es-event-title">
                                <a href="<?php echo $event->getPermalink(); ?>"><?php echo $event->getName(); ?></a>
                            </div>
                            <div class="es-event-meta mt-5">
                                <div>
                                    <?php /* <span>
                                      <?php echo $event->getCategory()->get('title'); ?>
                                      </span>
                                      <span>
                                      Event created by
                                      </span><?php */ ?>
                                    <span>
                                        created by
                                    </span>
                                    <a href="<?php echo $owner->getPermalink(); ?>" class="event-author">
                                        <?php echo $owner->getName(); ?>
                                    </a>
                                </div>
                            </div>
                        </div>

                        <div class="es-event-brief mt-10">
                            <span>
                                <i class="fa fa-calendar"></i>
                                <?php echo $event->getEventStart()->format('l M d, Y', true); ?>
                            </span>
                            <span>
                                <i class="fa fa-clock-o"></i>
                                <?php echo $event->getEventStart()->format('g:i A', true); ?>
                                <?php
                                echo ($event->hasEventEnd()) ? ' - ' . $event->getEventEnd()->format('g:i A', true) : '';
                                ?>
                            </span>
                            <?php if ($this->template->get('events_address', true) && !empty($event->address)) { ?>
                                <div>
                                    <i class="fa fa-map-marker"></i>
                                    <a href="<?php echo $event->getAddressLink(); ?>" target="_blank"><?php echo $event->address; ?></a>
                                </div>
                            <?php } ?>
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
                        </div>
                        <div class="es-profile-header-footer fd-cf">
                            <?php if (($guest->isParticipant() && !$guest->isPending()) || (!$guest->isParticipant() && !$event->isOver())) { ?>
                                <div class="fd-cf es-profile-header-footer__rsvp-wrap" data-guest-state-wrap data-id="<?php echo $event->id; ?>" data-allowmaybe="<?php echo (int) $event->getParams()->get('allowmaybe'); ?>" data-allownotgoingguest="<?php echo (int) $event->getGuest()->isOwner() || $event->getParams()->get('allownotgoingguest'); ?>" data-hidetext="0">
                                    <?php echo $this->loadTemplate('site/events/guestState.content', array('event' => $event, 'guest' => $guest)); ?>
                                </div>
                            <?php } ?>
                        </div>
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
            </div>
        <?php } ?>
    <?php } else { ?>
        <div class="empty empty-hero">
            <i class="fa fa-film"></i>
            <div><?php echo JText::_('COM_EASYSOCIAL_DASHBOARD_SIDEBAR_NO_EVENTS_YET'); ?></div>
        </div>
    <?php } ?>
</div>
