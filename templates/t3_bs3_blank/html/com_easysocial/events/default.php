<?php
/**
 * @package      EasySocial
 * @copyright    Copyright (C) 2010 - 2014 Stack Ideas Sdn Bhd. All rights reserved.
 * @license      GNU/GPL, see LICENSE.php
 * EasySocial is free software. This version may have been modified pursuant
 * to the GNU General Public License, and as distributed it includes or
 * is derivative of works licensed under the GNU General Public License or
 * other free or open source software licenses.
 * See COPYRIGHT.php for copyright notices and details.
 */
defined('_JEXEC') or die('Unauthorized Access');

$uid = $this->input->get('uid', null, 'int');
$type = $this->input->get('type', SOCIAL_TYPE_USER, 'cmd');

$user = FD::user($uid);
// If this is a user type, we will want to get a list of albums the current logged in user created
if ($type == SOCIAL_TYPE_USER) {
    $user = FD::user($uid);
    $uid = $user->id;
}

$currentProfile = $user->getProfile()->get('title');
// Retrieve user's step
$usersModel = ES::model('Users');
// Get the active step
$activeStep = 0;
$privacy = $this->my->getPrivacy();

// Get the list of available steps on the user's profile
$steps = $usersModel->getAbout($user, $activeStep);


$createLink = FRoute::events(array('layout' => 'create'));
$allowCreation = false;
if ($this->my->isSiteAdmin() || ($this->access->allowed('events.create') && $this->my->id) && !$this->access->intervalExceeded('events.limit', $this->my->id)) {
    $allowCreation = true;
}

$returnUrl = base64_encode(FRoute::events(array('uid' => $user->getAlias(true,true), 'type' => SOCIAL_TYPE_USER)));
?>
<?php
if (JRequest::getVar('subview') != 'all') {
    ?>
    <div class="es-events userProfile" data-id="<?php echo $user->id; ?>" data-events>
        <!-- Include cover section -->
        <?php echo $this->includeTemplate('site/events/default.header', array('currentProfile' => $currentProfile, 'user' => $user, 'cover' => $cover, 'steps' => $steps, 'privacy' => $privacy)); ?>
    </div>
    <?php
}
?>
<div class="es-container" data-events data-filter="<?php echo $activeCategory ? 'category' : $filter; ?>" data-categoryid="<?php echo $activeCategory ? $activeCategory->id : 0; ?>">
    <a href="javascript:void(0);" class="btn btn-block btn-es-inverse btn-sidebar-toggle" data-sidebar-toggle>
        <i class="fa fa-grid-view mr-5"></i> <?php echo JText::_('COM_EASYSOCIAL_SIDEBAR_TOGGLE'); ?>
    </a>

    <div class="es-content">
        <?php
        //get featured Events
        $model = ES::model('Events');
        $options['featured'] = 1;
        $options['limit'] = 1;
        $options['type'] = SOCIAL_TYPE_USER;
        $options['userid'] = $user->id;
        $options['creator_uid'] = $user->id;
        $options['ongoing'] = true;
        $options['upcoming'] = true;
        $dashfeaturedEvents = array();
        $dashfeaturedEvents = $model->getEvents($options);

        /* get past events */
        $optionspast['past'] = 1;
        $optionspast['limit'] = 9999;
        $optionspast['type'] = SOCIAL_TYPE_USER;
        $optionspast['creator_uid'] = $user->id;
        $pastEvents = array();
        $pastEvents = $model->getEvents($optionspast);

        /* get going events */
        $optionsgoing['gueststate'] = SOCIAL_EVENT_GUEST_GOING;
        $optionsgoing['guestuid'] = $user->id;
        $optionsgoing['type'] = 'all';
        $optionsgoing['limit'] = 9999;
        $optionsgoing['ongoing'] = true;
        $optionsgoing['upcoming'] = true;
        $goingtoEvents = array();
        $goingtoEvents = $model->getEvents($optionsgoing);
        
        /* get maybe events */
        $optionsmaybe['gueststate'] = SOCIAL_EVENT_GUEST_MAYBE;
        $optionsmaybe['guestuid'] = $user->id;
        $optionsmaybe['type'] = 'all';
        $optionsmaybe['limit'] = 9999;
        $optionsmaybe['ongoing'] = true;
        $optionsmaybe['upcoming'] = true;
        $maybeEvents = array();
        $maybeEvents = $model->getEvents($optionsmaybe);
        
        /* merge maybe and attending events */
        $allattending_events = array_merge($goingtoEvents, $maybeEvents);

        //get all featured Events
        $model = ES::model('Events');
        $optionsallf['featured'] = 1;
        $optionsallf['limit'] = 999;
        $optionsallf['type'] = SOCIAL_TYPE_USER;
        $optionsallf['creator_uid'] = $user->id;
        $optionsallf['ongoing'] = true;
        $optionsallf['upcoming'] = true;
        $allfeaturedEvents = array();
        $allfeaturedEvents = $model->getEvents($optionsallf);

        /* get all events */
        $optionsall['limit'] = 9999;
        $optionsall['type'] = 'all';
        $optionsall['creator_uid'] = $user->id;
        $optionsall['creator_type'] = SOCIAL_TYPE_USER;

        // We do not want to include past events here

        $optionsall['ongoing'] = true;
        $optionsall['upcoming'] = true;

        $events = array();
        $events = $model->getEvents($optionsall);

        if (JRequest::getVar('subview') == 'all') {
            echo $this->output('site/events/default.all.items', array('events' => $events, 'pastEvents' => $pastEvents, 'featuredEvents' => $allfeaturedEvents,'allattending_events'=>$allattending_events, 'pagination' => $pagination, 'filter' => $filter, 'returnUrl' => $returnUrl, 'user' => $user, 'allowCreation' => $allowCreation, 'createLink' => $createLink));
        } else {
            ?>

            <div class="es-event-listing es-event-item-group" data-events-result>
                <div class="add-event-container">

                    <?php if ($this->my->isSiteAdmin() || ($this->access->allowed('events.create') && $this->my->id) && !$this->access->intervalExceeded('events.limit', $this->my->id) && $this->my->id == $user->id) { ?>
                       <?php
                       echo $this->output('site/events/events.category.bypass'); ?>


                        <div class="es-widget-create pull-right">  
                            <a class="btn-add-event" href="<?php echo FRoute::events(array('layout' => 'steps', 'step' => 1), false); ?>">
        <?php echo JText::_('COM_EASYSOCIAL_EVENTS_CREATE_EVENT'); ?>
                            </a>
                        </div>
    <?php } ?>

                </div>
                <div class="">
    <?php if ($dashfeaturedEvents) { ?>
                        <?php echo $this->output('site/events/default.featured.items', array('dashfeaturedEvents' => $dashfeaturedEvents, 'returnUrl' => $returnUrl)); ?>
                    <?php } ?>                  
                </div>
                <div class="other-events">
    <?php echo $this->output('site/events/default.items', array('events' => $events, 'pagination' => $pagination, 'filter' => $filter, 'returnUrl' => $returnUrl, 'user' => $user)); ?>
                </div>
                <div class="other-events">
                    <?php echo $this->output('site/events/default.items.attending', array('events' => $allattending_events, 'pagination' => $pagination, 'filter' => $filter, 'returnUrl' => $returnUrl, 'user' => $user)); ?>
                </div>
            </div>

            <?php
        }
        ?>

        <?php echo $this->render('module', 'es-videos-after-contents'); ?>
    </div>
</div>
