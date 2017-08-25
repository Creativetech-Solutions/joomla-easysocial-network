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
$sort = 'start';
if (JRequest::getVar('ordering')) {
    $sort = JRequest::getVar('ordering');
}
//echo '<pre>';
//print_r($events);
//echo '</pre>';
?>

<div class="dashboard_header">
    <h1> <span><?php echo $user->getName() . '\'s </span> <span>Events</span>'; ?> </h1>
    <div class="nav-back">
        <a href="<?php echo base64_decode($returnUrl) ?>">Back</a> to <?php echo '<span>' . $user->getName() . '\'s</span> Profile'; ?>
    </div>
</div>


<div class="es-event-list all-items-layout clearfix<?php echo!$events ? ' is-empty' : ''; ?>">
    <div class="es-event-list-container"> 

        <!-- Nav tabs -->
        <ul class="nav nav-tabs" role="tablist">
            <li role="presentation" class="active"> <a href="#events" role="tab" data-toggle="tab"> <span> <?php echo count($events) + count($featuredEvents); ?> </span> <?php echo JText::_("COM_EASYSOCIAL_EVENTS_UPCOMING"); ?> </a> </li>
            <?php /*<li role="presentation"> <a href="#featured" aria-controls="profile" role="tab" data-toggle="tab"> <span> <?php echo count($featuredEvents); ?> </span> Featured </a> </li>
            
            <li role="presentation"> <a href="#past" aria-controls="profile" role="tab" data-toggle="tab"> <span> <?php echo count($pastEvents); ?> </span> Past </a> </li><?php */ ?>
            
            <li role="presentation"> <a href="#attending" aria-controls="profile" role="tab" data-toggle="tab"> <span> <?php echo count($allattending_events); ?> </span> Attending </a> </li>
        </ul>

        <!--add video if logged in user viewing his own-->
        <?php if ($allowCreation) { ?>
            <div class="es-widget-create mr-10 add-event-container">
                <a class="btn-add-event pull-right" href="<?php echo $createLink; ?>">
                    <?php echo JText::_('COM_EASYSOCIAL_EVENTS_CREATE_EVENT'); ?>
                </a>
            </div>
        <?php } ?>
        <!--sorting container-->
        <div class="events_sort">
            <ul class="">
                <li>
                    <span>Sort: </span>
                </li>
                <li class="<?php echo ($sort == 'start') ? 'active' : ''; ?>"> 
                    <a href="<?php echo FRoute::events( array('filter' => 'all','ordering' => 'start', 'subview' => 'all'), false); ?>">Date
                    </a>
                </li>
                <li class="<?php echo ($sort == 'distance') ? 'active' : ''; ?>">
                    <a href="<?php echo FRoute::events( array('filter' => 'nearby','ordering' => 'distance', 'subview' => 'all'), false);?>">Nearby
                    </a>
                </li>
            </ul>
        </div>
        <!-- Tab panes -->
        <div class="tab-content">
            <div role="tabpanel" class="tab-pane active" id="events">
                <?php
                if ($events || $featuredEvents) {
                    if ($featuredEvents) {
                        $events = array_merge($events, $featuredEvents);
                    }
                    ?>
                    <?php
                    foreach ($events as $event) {

                        $guest = $event->getGuest($user->id);
                        $owner = $event->getOwner();
                        //var_dump($owner);
                        ?>
                        <div class="es-event-item" data-event-item
                             data-id="<?php echo $event->id; ?>"
                             >
                            <div class="col-md-3">
                                <div class="es-event-thumbnail">
                                    <a href="<?php echo $event->getPermalink(); ?>">
                                        <div class="es-event-cover" style="background-image: url('<?php echo $event->getCover(); ?>')"></div>
                                    </a>
                                </div>
                            </div>
                            <div class="col-md-9">
                                <div class="es-event-content">
                                    <div class="es-profile-header-body fd-cf">
                                        <span>
                                            <?php echo $event->getEventStart()->format('D, M d g:i A', true); ?>
                                        </span>
                                        <div class="es-event-title">
                                            <a href="<?php echo $event->getPermalink(); ?>"><?php echo $event->getName(); ?></a>
                                        </div>
                                        <div class="es-event-meta mt-5">
                                            <div>
                                                <span>
                                                    <?php echo $event->getCategory()->get('title'); ?>
                                                </span>
                                                <span>
                                                    Event created by 
                                                </span>
                                                <a href="<?php echo $owner->getPermalink(); ?>" class="event-author">
                                                    <?php echo $owner->getName(); ?>
                                                </a>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="es-event-location mt-10">
                                        <?php if ($this->template->get('events_address', true) && !empty($event->address)) { ?>
                                            <div>
                                                <a href="<?php echo $event->getAddressLink(); ?>" target="_blank"><?php echo $event->address; ?></a>
                                            </div>
                                        <?php } ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php } ?>
                <?php } else { ?>
                    <div class="empty empty-hero"> <i class="fa fa-film"></i>
                        <div><?php echo JText::_('COM_EASYSOCIAL_DASHBOARD_SIDEBAR_NO_EVENTS_YET'); ?></div>
                    </div>
                <?php } ?>
            </div>
            <div role="tabpanel" class="tab-pane" id="featured">
                <?php if ($featuredEvents) { ?>
                    <?php
                    foreach ($featuredEvents as $event) {
                        $guest = $event->getGuest($user->id);
                        $owner = $event->getOwner();
                        ?>
                        <div class="es-event-item" data-event-item
                             data-id="<?php echo $event->id; ?>"
                             >
                            <div class="col-md-3">
                                <div class="es-event-thumbnail">
                                    <a href="<?php echo $event->getPermalink(); ?>">
                                        <div class="es-event-cover" style="background-image: url('<?php echo $event->getCover(); ?>')"></div>
                                    </a>
                                </div>
                            </div>
                            <div class="col-md-9">
                                <div class="es-event-content">
                                    <div class="es-profile-header-body fd-cf">
                                        <span>
                                            <?php echo $event->getEventStart()->format('D, M d g:i A', true); ?>
                                        </span>
                                        <div class="es-event-title">
                                            <a href="<?php echo $event->getPermalink(); ?>"><?php echo $event->getName(); ?></a>
                                        </div>
                                        <div class="es-event-meta mt-5">
                                            <div>
                                                <span>
                                                    <?php echo $event->getCategory()->get('title'); ?>
                                                </span>
                                                <span>
                                                    Event created by 
                                                </span>
                                                <a href="<?php echo $owner->getPermalink(); ?>" class="event-author">
                                                    <?php echo $owner->getName(); ?>
                                                </a>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="es-event-location mt-10">
                                        <?php if ($this->template->get('events_address', true) && !empty($event->address)) { ?>
                                            <div>
                                                <a href="<?php echo $event->getAddressLink(); ?>" target="_blank"><?php echo $event->address; ?></a>
                                            </div>
                                        <?php } ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php } ?>
                <?php } else { ?>
                    <div class="empty empty-hero"> <i class="fa fa-film"></i>
                        <div><?php echo JText::_('COM_EASYSOCIAL_DASHBOARD_SIDEBAR_NO_EVENTS_YET'); ?></div>
                    </div>
                <?php } ?>
            </div>
            <div role="tabpanel" class="tab-pane" id="past">
                <?php if ($pastEvents) { ?>
                    <?php
                    foreach ($pastEvents as $event) {
                        $guest = $event->getGuest($user->id);
                        $owner = $event->getOwner();
                        ?>
                        <div class="es-event-item" data-event-item
                             data-id="<?php echo $event->id; ?>"
                             >
                            <div class="col-md-3">
                                <div class="es-event-thumbnail">
                                    <a href="<?php echo $event->getPermalink(); ?>">
                                        <div class="es-event-cover" style="background-image: url('<?php echo $event->getCover(); ?>')"></div>
                                    </a>
                                </div>
                            </div>
                            <div class="col-md-9">
                                <div class="es-event-content">
                                    <div class="es-profile-header-body fd-cf">
                                        <span>
                                            <?php echo $event->getEventStart()->format('D, M d g:i A', true); ?>
                                        </span>
                                        <div class="es-event-title">
                                            <a href="<?php echo $event->getPermalink(); ?>"><?php echo $event->getName(); ?></a>
                                        </div>
                                        <div class="es-event-meta mt-5">
                                            <div>
                                                <span>
                                                    <?php echo $event->getCategory()->get('title'); ?>
                                                </span>
                                                <span>
                                                    Event created by 
                                                </span>
                                                <a href="<?php echo $owner->getPermalink(); ?>" class="event-author">
                                                    <?php echo $owner->getName(); ?>
                                                </a>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="es-event-location mt-10">
                                        <?php if ($this->template->get('events_address', true) && !empty($event->address)) { ?>
                                            <div>
                                                <a href="<?php echo $event->getAddressLink(); ?>" target="_blank"><?php echo $event->address; ?></a>
                                            </div>
                                        <?php } ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php } ?>
                <?php } else { ?>
                    <div class="empty empty-hero"> <i class="fa fa-film"></i>
                        <div><?php echo JText::_('COM_EASYSOCIAL_DASHBOARD_SIDEBAR_NO_EVENTS_YET'); ?></div>
                    </div>
                <?php } ?>                
            </div>
            
            <div role="tabpanel" class="tab-pane" id="attending">
                <?php if ($allattending_events) { ?>
                    <?php
                    foreach ($allattending_events as $event) {
                        $guest = $event->getGuest($user->id);
                        $owner = $event->getOwner();
                        ?>
                        <div class="es-event-item" data-event-item
                             data-id="<?php echo $event->id; ?>"
                             >
                            <div class="col-md-3">
                                <div class="es-event-thumbnail">
                                    <a href="<?php echo $event->getPermalink(); ?>">
                                        <div class="es-event-cover" style="background-image: url('<?php echo $event->getCover(); ?>')"></div>
                                    </a>
                                </div>
                            </div>
                            <div class="col-md-9">
                                <div class="es-event-content">
                                    <div class="es-profile-header-body fd-cf">
                                        <span>
                                            <?php echo $event->getEventStart()->format('D, M d g:i A', true); ?>
                                        </span>
                                        <div class="es-event-title">
                                            <a href="<?php echo $event->getPermalink(); ?>"><?php echo $event->getName(); ?></a>
                                        </div>
                                        <div class="es-event-meta mt-5">
                                            <div>
                                                <span>
                                                    <?php echo $event->getCategory()->get('title'); ?>
                                                </span>
                                                <span>
                                                    Event created by 
                                                </span>
                                                <a href="<?php echo $owner->getPermalink(); ?>" class="event-author">
                                                    <?php echo $owner->getName(); ?>
                                                </a>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="es-event-location mt-10">
                                        <?php if ($this->template->get('events_address', true) && !empty($event->address)) { ?>
                                            <div>
                                                <a href="<?php echo $event->getAddressLink(); ?>" target="_blank"><?php echo $event->address; ?></a>
                                            </div>
                                        <?php } ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php } ?>
                <?php } else { ?>
                    <div class="empty empty-hero"> <i class="fa fa-film"></i>
                        <div><?php echo JText::_('COM_EASYSOCIAL_DASHBOARD_SIDEBAR_NO_EVENTS_YET'); ?></div>
                    </div>
                <?php } ?>                
            </div>
            
        </div>
    </div>
</div>
