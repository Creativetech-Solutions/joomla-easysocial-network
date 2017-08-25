<?php
/**
 * @package      EasySocial
 * @copyright    Copyright (C) 2010 - 2015 Stack Ideas Sdn Bhd. All rights reserved.
 * @license      GNU/GPL, see LICENSE.php
 * EasySocial is free software. This version may have been modified pursuant
 * to the GNU General Public License, and as distributed it includes or
 * is derivative of works licensed under the GNU General Public License or
 * other free or open source software licenses.
 * See COPYRIGHT.php for copyright notices and details.
 */
defined('_JEXEC') or die('Unauthorized Access');
$script = '
jQuery(document).ready(function($) {
	var vheight = $(".es-profile-header-heading").height();
	$("body").prepend("<div class=\"photo-bg\" style=\"background: #000;position: absolute;width: 100%;height:"+vheight+"px;z-index: -1;\"></div>");
	
	var com_form = $(".event_update .es-comments-form");
	var loadmore = $(".event_update .es-comments-control");
	
	$(".event_update .es-comments-form").remove();
	$(".event_update .es-comments-control").remove();
	
	$("ul.fd-reset-list.es-comments").parent().prepend(com_form);
	$("ul.fd-reset-list.es-comments").parent().append(loadmore);
});
';
JFactory::getDocument()->addScriptDeclaration($script);
$owner = $event->getOwner();

$steps = FD::model('Steps')->getSteps($event->category_id, SOCIAL_TYPE_CLUSTERS, SOCIAL_EVENT_VIEW_DISPLAY);
$fieldsModel = FD::model('Fields');
?>
<div class="es-profile es-events-item page-item" data-event-item data-id="<?php echo $event->id; ?>">

    <?php if (!empty($group)) { ?>
        <!--        <div class="mb-10">
        <?php echo $this->loadTemplate('site/groups/mini.header', array('group' => $group)); ?>
                </div>-->
    <?php } ?>

    <?php echo $this->loadTemplate('site/events/item.header', array('event' => $event, 'guest' => $guest)); ?>

    <div class="es-container">
        <a href="javascript:void(0);" class="btn btn-block btn-es-inverse btn-sidebar-toggle" data-sidebar-toggle>
            <i class="fa fa-grid-view  mr-5"></i> <?php echo JText::_('COM_EASYSOCIAL_SIDEBAR_TOGGLE'); ?>
        </a>

        <div class="es-content">
            <i class="loading-indicator fd-small"></i>
            <?php echo $this->render('module', 'es-events-before-contents'); ?>

            <div class="es-content-wrap" data-content>
                <?php
                if (JRequest::getVar('appId') != '') {
                    if (!empty($contents)) {
                        echo $contents;
                    }
                } else {
                    ?>
                    <div class="event_overview">
                        <div class="es-snackbar">
                            <span>
                                <?php echo JText::_("COM_EASYSOCIAL_PAGE_TITLE_EVENT"); ?>
                            </span>
                            <span>
                                <?php echo JText::_("COM_EASYSOCIAL_OVERVIEW"); ?>
                            </span>
                        </div>
                        <h2 class="es-profile-header-title">
                            <?php echo $this->html('html.event', $event); ?>

                            <?php if ($event->isGroupEvent()) { ?>
                                <span class="fd-small">
                                    <?php echo JText::sprintf('COM_EASYSOCIAL_EVENTS_EVENT_OF_GROUP_TITLE', '<i class="fa fa-users"></i> ' . $this->html('html.group', $event->getGroup())); ?>
                                </span>
                            <?php } ?>
                        </h2>
                        <div class="event-meta">
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
                        <div class="es-event-brief mt-20">
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
                                <span>
                                    <i class="fa fa-map-marker"></i>
                                    <a href="<?php echo $event->getAddressLink(); ?>" target="_blank"><?php echo $event->address; ?></a>
                                </span>
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
                    </div>

                    <div class="event_description mt-20">
                        <div class="es-snackbar">
                            <span>
                                <?php echo JText::_("COM_EASYSOCIAL_PAGE_TITLE_EVENT"); ?>
                            </span>
                            <span>
                                <?php echo JText::_("COM_EASYSOCIAL_EVENTS_SIDEBAR_INFO"); ?>
                            </span>
                        </div>
                        <p><?php echo $event->getDescription(); ?></p>

                    </div>

                    <div class="event_location mt-20">
                        <div class="es-snackbar">
                            <span>
                                <?php echo JText::_("JTHE"); ?>
                            </span>
                            <span>
                                <?php echo JText::_("COM_EASYSOCIAL_LOCATION"); ?>
                            </span>
                        </div>
                        <p><?php echo $event->address; ?></p>
                        <i class="fa fa-map-marker"></i>
                        <a href="<?php echo $event->getAddressLink(); ?>" target="_blank"><?php echo JText::_("COM_EASYSOCIAL_MAP_LOCATION"); ?></a>
                    </div>

                    <div class="event_guests mt-20">
                        <div class="es-snackbar">
                            <span>
                                <?php echo JText::_("COM_EASYSOCIAL_PAGE_TITLE_EVENT"); ?>
                            </span>
                            <span>
                                <?php echo JText::_("COM_EASYSOCIAL_GUESTS"); ?>
                            </span>
                        </div>
                        <?php echo $this->render('widgets', SOCIAL_TYPE_EVENT, 'events', 'sidebarBottom', array('uid' => $event->id, 'event' => $event)); ?>
                    </div>

                    <div class="event_update mt-20">
                        <div class="es-snackbar">
                            <span>
                                <?php echo JText::_("Event"); ?>
                            </span>
                            <span>
                                <?php echo JText::_("Updates"); ?>
                            </span>
                        </div>
                        <?php
                        $comments = FD::comments($event->id, 'events', 'create', $event->creator_type, array('url' => $event->getPermalink()));
                        if($event->isAdmin($this->my->id)){
                            echo $comments->getHTML();
                        }else{
                            echo $comments->getHTML(array('hideForm' => true));
                        }

                        ?>
                    </div>

                <?php } ?>

                <?php /* ?>
                  <?php if (!empty($contents)) { ?>
                  <?php echo $contents; ?>
                  <?php } else { ?>
                  <?php if (!empty($hashtag)) { ?>
                  <div class="es-streams">
                  <div class="row">
                  <div class="col-md-12">
                  <a href="javascript:void(0);"
                  class="fd-small mt-10 pull-right"
                  data-hashtag-filter-save
                  data-tag="<?php echo $hashtag; ?>"
                  ><i class="icon-es-create"></i> <?php echo JText::_('COM_EASYSOCIAL_STREAM_SAVE_FILTER');?></a>

                  <h3 class="pull-left">
                  <a href="<?php echo FRoute::events(array('layout' => 'item' , 'id' => $event->getAlias(), 'tag' => $hashtagAlias));?>">#<?php echo $hashtag; ?></a>
                  </h3>
                  </div>
                  </div>
                  <p class="fd-small">
                  <?php echo JText::sprintf('COM_EASYSOCIAL_STREAM_HASHTAG_CURRENTLY_FILTERING' , '<a href="' . FRoute::events(array('layout' => 'item' , 'id' => $event->getAlias(), 'tag' => $hashtagAlias)) . '">#' . $hashtag . '</a>'); ?>
                  </p>
                  </div>
                  <hr />
                  <?php } ?>

                  <?php echo $this->includeTemplate('site/events/item.feeds'); ?>

                  <?php if ($this->my->guest) { ?>
                  <?php echo $this->includeTemplate('site/dashboard/default.stream.login'); ?>
                  <?php } ?>
                  <?php } ?><?php */ ?>
            </div>

            <?php echo $this->render('module', 'es-events-after-contents'); ?>
        </div>
    </div>
</div>
