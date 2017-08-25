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
require_once(JPATH_SITE . '/components/com_easysocial/helpers/dashboard.php');
//JLoader::register('EasySocialDashboardHelper', 'components/com_easysocials/helpers/dashboard.php');

$currentProfile = $user->getProfile()->get('title');

//echo 'layout: '.JRequest::getVar('layout');
$ltype = JRequest::getVar('type');
$layout = JRequest::getVar('layout');
//print_r($_REQUEST);
//echo 'layout: '. JFactory::getApplication()->input->get('layout', '', 'word');
$cover = $user->getCoverData();
// Retrieve user's photos
$photosModel = FD::model('Photos');
$photos = $photosModel->getPhotos(array('uid' => $user->id, 'limit' => 5, 'ordering' => 'created'));

// Retrieve user's photos
$videosModel = FD::model('Videos');
$videos = $videosModel->getVideos(array('uid' => $user->id, 'limit' => 5, 'type' => SOCIAL_TYPE_USER));

// Retrieve user's stream
$ustream = FD::model('Stream');
$ustream = $ustream->getStreamData(array('profileId' => $user->getProfile()->get('id'), 'actorid' => $user->id, 'limit' => 4, 'type' => SOCIAL_TYPE_USER, 'context' => 'all'));
$ustream_data = FD::stream()->format($ustream);

// Retrieve user's step
$usersModel = ES::model('Users');
// Get the active step
$activeStep = 0;

// Get the list of available steps on the user's profile
$steps = $usersModel->getAbout($user, $activeStep);

//user's audio
$audios = ES::model('Audio');
$audios = $audios->getAudios(array('uid' => $user->id, 'limit' => 5, 'type' => SOCIAL_TYPE_USER, 'privacy' => false));
//profile fields
/* $stepsModel = ES::model('Steps');
  $pfields = $stepsModel->getSteps($user->getProfile()->get('id')); */
/* echo '<pre>';
  var_dump($ustream_data);
  echo '</pre>'; */

/* echo '<pre>';
  print_r($pfields);
  echo '</pre>'; */

$collapseScript = '
jQuery(document).ready(function($) {
    $(\'#collapseSklls\').on(\'shown.bs.collapse\', function () {
	   $(".cskills").removeClass("fa-plus").addClass("fa-minus");
	});
	
	$(\'#collapseSklls\').on(\'hidden.bs.collapse\', function () {
	   $(".cskills").removeClass("fa-minus").addClass("fa-plus");
	});
	
	$(\'#collapseHire\').on(\'shown.bs.collapse\', function () {
	   $(".chire").removeClass("fa-plus").addClass("fa-minus");
	});
	
	$(\'#collapseHire\').on(\'hidden.bs.collapse\', function () {
	   $(".chire").removeClass("fa-minus").addClass("fa-plus");
	});
	
	$(\'#collapseTeach\').on(\'shown.bs.collapse\', function () {
	   $(".cteach").removeClass("fa-plus").addClass("fa-minus");
	});
	
	$(\'#collapseTeach\').on(\'hidden.bs.collapse\', function () {
	   $(".cteach").removeClass("fa-minus").addClass("fa-plus");
	});
	
});
';

if ($layout != 'timeline') {
    JFactory::getDocument()->addScriptDeclaration($collapseScript);
}

if ($layout == 'timeline') {
    JHtml::_('jquery.framework');
    //JFactory::getDocument()->addScript(JURI::base() . 'templates/t3_bs3_blank/js/masonry.js');
    JFactory::getDocument()->addScript(JURI::base() . 'templates/t3_bs3_blank/js/timeline.js');
    JFactory::getDocument()->addScript(JURI::base() . 'templates/t3_bs3_blank/js/responsive-toolkit.js');
    JFactory::getDocument()->addScript(JURI::base() . 'templates/t3_bs3_blank/js/sidebar.js');
}
?>
<div class="es-profile userProfile" data-id="<?php echo $user->id; ?>" data-profile>

    <a href="javascript:void(0);" class="btn btn-block btn-es-inverse btn-sidebar-toggle" data-sidebar-toggle>
        <i class="fa fa-grid-view  mr-5"></i> <?php echo JText::_('COM_EASYSOCIAL_SIDEBAR_TOGGLE'); ?>
    </a>

    <?php if ($layout != 'timeline') { ?>

        <?php echo $this->render('widgets', 'user', 'profile', 'aboveHeader', array($user)); ?>

        <?php echo $this->render('module', 'es-profile-before-header'); ?>

        <!-- Include cover section -->
        <?php echo $this->includeTemplate('site/profile/default.header', array('currentProfile' => $currentProfile, 'steps' => $steps)); ?>

        <?php echo $this->render('module', 'es-profile-after-header'); ?>

    <?php } ?>
    
    <div class="es-container">


        <?php //class= col-sm-12 col-md-4 col-lg-3 ?>
        <div class="es-sidebar <?php echo ($layout == 'timeline') ? "collapse in" : ""; ?>" id="es-sidebar" data-sidebar>


            <?php
            if ($layout == 'timeline') {

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
            <?php } ?>
            <?php
            if (($layout != 'about' && $layout != '') || $ltype != '' || JRequest::getVar('appId') != '') {
                ?>
                <?php /* ?><?php if ($appFilters && $this->template->get('profile_feeds_apps')) { ?>
                  <div class="es-widget">
                  <div class="es-widget-head">
                  <div class="widget-title pull-left"><?php echo JText::_('COM_EASYSOCIAL_PROFILE_SIDEBAR_NEWSFEEDS_APPS'); ?></div>
                  </div>
                  <div class="es-widget-body">
                  <ul class="fd-nav fd-nav-stacked feed-items" data-profile-feeds>
                  <?php $i = 1; ?>
                  <?php foreach ($appFilters as $appFilter) { ?>
                  <?php echo $this->includeTemplate('site/profile/default.sidebar.filter', array('filter' => $appFilter, 'hide' => $i > 3)); ?>
                  <?php $i++; ?>
                  <?php } ?>

                  <?php if (count($appFilters) > 3) { ?>
                  <li>
                  <a href="javascript:void(0);" class="filter-more" data-app-filters-showall><?php echo JText::_('COM_EASYSOCIAL_PROFILE_SIDEBAR_SHOW_MORE_FILTERS'); ?></a>
                  </li>
                  <?php } ?>
                  </ul>
                  </div>
                  </div>
                  <?php } ?>

                  <?php echo $this->render('module', 'es-profile-sidebar-top' , 'site/dashboard/sidebar.module.wrapper'); ?>

                  <?php echo $this->render('widgets', 'user', 'profile', 'sidebarTop', array($user)); ?>

                  <div class="es-widget">
                  <div class="es-widget-head">
                  <div class="pull-left widget-title">
                  <?php echo JText::_('COM_EASYSOCIAL_PROFILE_APPS_HEADING');?>
                  </div>

                  <?php if ($user->isViewer() && $this->config->get('apps.browser') && $this->template->get('profile_apps_browse')) { ?>
                  <a class="pull-right fd-small" href="<?php echo FRoute::apps();?>">
                  <i class="icon-es-add"></i> <?php echo JText::_('COM_EASYSOCIAL_BROWSE'); ?>
                  </a>
                  <?php } ?>
                  </div>
                  <div class="es-widget-body">
                  <ul class="widget-list fd-nav fd-nav-stacked" data-profile-apps>
                  <li
                  data-profile-apps-item
                  data-layout="custom"
                  >
                  <a href="<?php echo FRoute::profile(array('id' => $user->getAlias(), 'layout' => 'about')); ?>"
                  data-info <?php if (!empty($infoSteps)) { ?>data-loaded="1"<?php } ?>
                  title="<?php echo JText::_('COM_EASYSOCIAL_PROFILE_ABOUT'); ?>"
                  >
                  <i class="icon-es-aircon-user mr-5"></i> <?php echo JText::_('COM_EASYSOCIAL_PROFILE_ABOUT'); ?>
                  </a>
                  </li>

                  <?php if (!empty($infoSteps)) { ?>
                  <?php foreach ($infoSteps as $step) { ?>
                  <?php if (!$step->hide) { ?>
                  <li
                  class="<?php if ($step->active) { ?>active<?php } ?>"
                  data-profile-apps-item
                  data-layout="custom"
                  >
                  <a class="ml-20" href="<?php echo $step->url; ?>" title="<?php echo $step->title; ?>" data-info-item data-info-index="<?php echo $step->index; ?>">
                  <i class="fa fa-info  mr-5"></i> <?php echo $step->title; ?>
                  </a>
                  </li>
                  <?php } ?>
                  <?php } ?>
                  <?php } ?>

                  <li class="<?php echo !empty($timeline) ? 'active' : '';?>"
                  data-layout="embed"
                  data-id="<?php echo $user->id;?>"
                  data-namespace="site/controllers/profile/getStream"
                  data-embed-url="<?php echo FRoute::profile(array('id' => $user->getAlias(), 'layout' => 'timeline'));?>"
                  data-profile-apps-item
                  data-title="<?php echo JText::_( 'COM_EASYSOCIAL_PROFILE_TIMELINE' );?>"
                  >
                  <a href="javascript:void(0);" title="<?php echo JText::_( 'COM_EASYSOCIAL_PROFILE_TIMELINE' );?>">
                  <i class="icon-es-genius mr-5"></i> <?php echo JText::_( 'COM_EASYSOCIAL_PROFILE_TIMELINE' );?>
                  </a>
                  </li>
                  <?php if ($apps) { ?>
                  <?php foreach ($apps as $app) { ?>
                  <?php $app->loadCss(); ?>
                  <li class="app-item<?php echo $activeApp == $app->id ? ' active' : '';?>"
                  data-app-id="<?php echo $app->id;?>"
                  data-id="<?php echo $user->id;?>"
                  data-layout="<?php echo $app->getViews('profile')->type; ?>"
                  data-namespace="site/controllers/profile/getAppContents"
                  data-canvas-url="<?php echo FRoute::apps(array('id' => $app->getAlias(), 'layout' => 'canvas', 'uid' => $user->getAlias(), 'type' => SOCIAL_TYPE_USER));?>"
                  data-embed-url="<?php echo FRoute::profile(array('id' => $user->getAlias(), 'appId' => $app->getAlias()));?>"
                  data-title="<?php echo $app->getPageTitle(); ?>"
                  data-profile-apps-item
                  >
                  <a href="javascript:void(0);">

                  <img src="<?php echo $app->getIcon();?>" class="app-icon-small mr-5" /> <?php echo $app->getAppTitle(); ?>
                  </a>
                  </li>
                  <?php } ?>
                  <?php } ?>
                  </ul>
                  </div>
                  </div>

                  <?php echo $this->render('module', 'es-profile-sidebar-after-apps'); ?>

                  <?php echo $this->render('widgets', 'user', 'profile', 'sidebarBottom', array($user)); ?>

                  <?php echo $this->render('module', 'es-profile-sidebar-bottom' , 'site/dashboard/sidebar.module.wrapper');<?php */ ?> 
                <?php
            } elseif (($layout == 'about' || $layout == '') && $ltype == '' && JRequest::getVar('appId') == '') {
                echo $this->includeTemplate('site/profile/default.sidebar.dashboard', array('steps' => $steps, 'hide' => $i > 3));
            }
            ?>
        </div>

        <div class="es-content <?php echo $layout; ?>" data-profile-contents>
            <i class="loading-indicator fd-small"></i>

            <?php echo $this->render('widgets', 'user', 'profile', 'aboveStream', array($user)); ?>

            <?php echo $this->render('module', 'es-profile-before-contents'); ?>
            <div class="es-profile-details" data-profile-real-content>
                <?php
                if (($layout != 'about' && $layout != '') || $ltype != '' || JRequest::getVar('appId') != '') {
                    echo $contents;
                } elseif (($layout == 'about' || $layout == '') && $ltype == '' && JRequest::getVar('appId') == '') {
                    ?>
                    <div data-dashboard-real-content class="dashboard-boxes">

                        <div class="col-xs-12 col-sm-12 col-md-12 box-container">
                            <div class="dashboard_header">
                                <div class="dashboard-box-title storyabout">
                                    <span>Story / </span>
                                    <span>About</span>
                                </div>
                            </div>
                            <div class="user_story_details">
                                <?php foreach ($steps as $step) {
                                    ?>
                                    <?php if (!$step->hide) { ?>
                                        <?php
                                        if ($step->fields) {
                                            $empty = true;
                                            ?>
                                            <?php
                                            foreach ($step->fields as $field) {
                                                if ($field->unique_key == 'story_about') {
                                                    echo $field->data;
                                                }
                                            }
                                            ?>
                                            <?php
                                        }
                                        ?>
                                    <?php } ?>
                                <?php } ?>
                            </div>

                        </div>

                        <!--Projects & Audio-->
                        <div class="col-xs-12 col-sm-12 col-md-12 box-container">
                            <div class="col-xs-12 col-sm-6 col-md-6">
                                <div class="dashboard_header">
                                    <div class="dashboard-box-title cprojects">
                                        <span>Current</span>
                                        <span>Projects</span>
                                    </div>
                                </div>
                            </div>

                            <div class="col-xs-12 col-sm-6 col-md-6">
                                <div class="dashboard_header">
                                    <div class="dashboard-box-title laudio">
                                        <span>Latest</span>
                                        <span>Audio</span>
                                    </div>
                                </div>
                                <div class="es-audios audio-dashboard audios-<?php echo count($audios); ?> es-stream-audios">
                                    <?php if ($audios) { ?>
                                        <?php foreach ($audios as $audio) { ?>
                                            <div class="es-audio-item" data-video-item data-id="<?php echo $audio->id; ?>">
                                                <div class="es-audio-content">
                                                    <?php echo $this->output('site/dashboard/player', array('audio' => $audio)); ?>
                                                    <div class="es-audio-title">
                                                       <!--<a href="<?php echo $audio->getPermalink(); ?>">-->
                                                        <?php echo $audio->getTitle(); ?>
                                                        <!--</a>-->
                                                    </div>
                                                </div>
                                            </div>
                                        <?php } ?>
                                        <div class="box-bottom">
                                            <div class="col-xs-12 col-sm-10 col-md-9">
                                                <span></span>
                                                <span></span>
                                            </div>
                                            <div class="col-xs-12 col-sm-2 col-md-3">
                                                <a class="ds-all-audios" href="<?php echo FRoute::audio(); ?>"><?php echo JText::_('JALL'); ?>
                                                </a>
                                            </div>
                                        </div>
                                    <?php } else { ?>
                                        <div class="empty empty-hero">
                                            <i class="fa fa-film"></i>
                                            <div><?php echo JText::_('COM_EASYSOCIAL_NO_AUDIOS_AVAILABLE_CURRENTLY'); ?></div>
                                        </div>
                                    <?php } ?>
                                </div>

                            </div>


                        </div>

                        <!--Photos & Events-->
                        <div class="col-xs-12 col-sm-12 col-md-12 box-container">
                            <div class="col-xs-12 col-sm-6 col-md-6">
                                <!--Photos-->
                                <?php
                                $config = FD::config();

                                // Normalize options
                                $defaultOptions = array(
                                    'size' => $config->get('photos.layout.size'),
                                    'mode' => $config->get('photos.layout.pattern') == 'flow' ? 'contain' : $config->get('photos.layout.mode'),
                                    'pattern' => $config->get('photos.layout.pattern'),
                                    'ratio' => $config->get('photos.layout.ratio'),
                                    'threshold' => $config->get('photos.layout.threshold')
                                );

                                if (isset($options)) {
                                    $options = array_merge_recursive($options, $defaultOptions);
                                } else {
                                    $options = $defaultOptions;
                                }
                                ?>

                                <div class="dashboard_header">
                                    <div class="dashboard-box-title lphotos">
                                        <span>Latest</span>
                                        <span>Photos</span>
                                    </div>
                                </div>

                                <div class="es-photos photos-<?php echo count($photos); ?> es-stream-photos pattern-<?php echo $options['pattern']; ?>"
                                     data-es-photo-group="<?php echo 'album:' . $photos[0]->album_id; ?>">

                                    <?php
                                    if ($photos) {
                                        foreach ($photos as $photo) {
                                            ?>
                                            <div class="es-photo es-stream-photo ar-<?php echo $options['ratio']; ?>">
                                                <a href="<?php echo $photo->getPermalink(); ?>"
                                                   data-es-photo="<?php echo $photo->id; ?>"
                                                   title="<?php echo $photo->title . (($photo->caption !== '') ? ' - ' . $photo->caption : ''); ?>">
                                                    <u><b data-mode="<?php echo $options['mode']; ?>"
                                                          data-threshold="<?php echo $options['threshold']; ?>">
                                                            <img src="<?php echo $photo->getSource($options['size']); ?>"
                                                                 alt="<?php echo $photo->title . (($photo->caption !== '') ? ' - ' . $photo->caption : ''); ?>"
                                                                 data-width="<?php echo $photo->getWidth(); ?>"
                                                                 data-height="<?php echo $photo->getHeight(); ?>"
                                                                 onload="window.ESImage ? ESImage(this) : (window.ESImageList || (window.ESImageList = [])).push(this);" />
                                                        </b></u>
                                                </a>
                                            </div>
                                        <?php }
                                        ?>
                                        <div class="box-bottom">
                                            <div class="col-xs-12 col-sm-10 col-md-9">
                                                <span></span>
                                                <span></span>
                                            </div>
                                            <div class="col-xs-12 col-sm-2 col-md-3">
                                                <a class="ds-all-photos" href="<?php echo FRoute::albums(array('uid' => $user->getAlias(), 'type' => SOCIAL_TYPE_USER)); ?>"><?php echo JText::_('JALL'); ?>
                                                </a>
                                            </div>
                                        </div>
                                        <?php
                                    } else {
                                        echo '<p>No photos available at the moment.</p>';
                                    }
                                    ?>
                                </div>
                            </div>

                            <div class="col-xs-12 col-sm-6 col-md-6">
                                <!--Upcoming Events-->
                                <div class="dashboard_header">
                                    <div class="dashboard-box-title uevents">
                                        <span>Upcoming</span>
                                        <span>Events</span>
                                    </div>
                                </div>
                                <div class="es-widget-body pl-0 pl-5 pr-5">
                                    <div id="fd" class="es mod-es-events">
                                        <?php if ($events) { ?>
                                            <ul class="es-events-list fd-reset-list">
                                                <?php
                                                /* echo '<pre>';
                                                  print_r($events);
                                                  echo '</pre>'; */
                                                foreach ($events as $event) {
                                                    $startString = $event->getEventStart();
                                                    ?>
                                                    <li>
                                                        <?php /* ?><div class="es-event-avatar es-avatar es-avatar-sm es-avatar-border-sm">
                                                          <img src="<?php echo $event->getAvatar(); ?>">
                                                          </div><?php */ ?>

                                                        <div class="es-event-meta pull-left">
                                                           <!--<span>-->
                                                            <?php
                                                            //$startString = $event->getEventStart()->toSql(true);
                                                            //$evdate = $startString->format("M j Y", true);
                                                            $evnmonth = $startString->format("M", true);
                                                            $evndate = $startString->format("j", true);
                                                            $evnyear = $startString->format("Y", true);
                                                            //echo $evdate;
                                                            echo '<span class="emonth">' . $evnmonth . '</span>';
                                                            echo '<span class="edate">' . $evndate . '</span>';
                                                            echo '<span class="eyear">' . $evnyear . '</span>';
                                                            ?>
                                                            <!--</span> -->
                                                        </div>
                                                        <div class="es-event-object">
                                                            <a class="event-title" href="<?php echo $event->getPermalink(); ?>"><?php echo $event->getName(); ?></a>
                                                            <span class="fd-small es-muted event-info-box">
                                                                <?php
                                                                //echo $event->getStartEndDisplay(array('end' => false));
                                                                $evtime = $startString->format("H A", true);
                                                                echo $evtime;
                                                                echo ' . ' . $event->address;
                                                                ?>
                                                            </span>
                                                        </div>

                                                        <?php /* ?><div class="mb-10">
                                                          <?php echo $event->showRsvpButton(true); ?>
                                                          </div><?php */ ?>
                                                    </li>
                                                <?php } ?>
                                            </ul>
                                            <div class="box-bottom">
                                                <div class="col-xs-12 col-sm-10 col-md-9">
                                                    <span></span>
                                                    <span></span>
                                                </div>
                                                <div class="col-xs-12 col-sm-2 col-md-3">
                                                    <a class="ds-all-events" href="<?php echo FRoute::events(array('uid' => $user->getAlias(), 'type' => SOCIAL_TYPE_USER)); ?>"><?php echo JText::_('JALL'); ?>
                                                    </a>
                                                </div>
                                            </div>
                                        <?php } else { ?>
                                            <div class="fd-small"><?php echo JText::_('APP_USER_EVENTS_WIDGET_NO_EVENTS'); ?></div>
                                        <?php } ?>    
                                    </div>

                                </div>
                            </div>
                        </div>

                        <!--Videos & Posts-->
                        <div class="col-xs-12 col-sm-12 col-md-12 box-container">
                            <div class="col-xs-12 col-sm-6 col-md-6">
                                <!--Videos-->
                                <div class="dashboard_header">
                                    <div class="dashboard-box-title rvideos">
                                        <span>Recent</span>
                                        <span>Videos</span>
                                    </div>
                                </div>
                                <div class="es-video-list clearfix<?php echo!$videos ? ' is-empty' : ''; ?>">
                                    <?php if ($videos) { ?>
                                        <?php foreach ($videos as $video) { ?>
                                            <div class="es-video-item" data-video-item
                                                 data-id="<?php echo $video->id; ?>"
                                                 >                    
                                                     <?php if ($video->table->isFeatured()) { ?>
                                                         <?php /* ?><div class="es-video-featured-label">
                                                           <span><?php echo JText::_('COM_EASYSOCIAL_FEATURED');?></span>
                                                           </div><?php */ ?>
                                                     <?php } ?>

                                                <div class="es-video-thumbnail">
                                                    <a href="<?php echo $video->getPermalink(); ?>">
                                                        <div class="es-video-cover" style="background-image: url('<?php echo $video->getThumbnail(); ?>')"></div>
                                                        <div class="es-video-time"><?php echo $video->getDuration(); ?></div>
                                                    </a>
                                                </div>
                                                <?php /* ?><div class="es-video-content">
                                                  <div class="es-video-title">
                                                  <a href="<?php echo $video->getPermalink();?>"><?php echo $video->getTitle();?></a>
                                                  </div>

                                                  <div class="es-video-meta mt-5">
                                                  <div>
                                                  <a href="<?php echo $video->getAuthor()->getPermalink();?>">
                                                  <i class="fa fa-user mr-5"></i> <?php echo $video->getAuthor()->getName();?>
                                                  </a>
                                                  </div>

                                                  <div>
                                                  <a href="<?php echo $video->getCategory()->getPermalink();?>">
                                                  <i class="fa fa-folder mr-5"></i> <?php echo JText::_($video->getCategory()->title);?>
                                                  </a>
                                                  </div>
                                                  </div>
                                                  </div><?php */ ?>
                                            </div>
                                        <?php } ?>
                                        <div class="box-bottom">
                                            <div class="col-xs-12 col-sm-10 col-md-9">
                                                <span></span>
                                                <span></span>
                                            </div>
                                            <div class="col-xs-12 col-sm-2 col-md-3">
                                                <a class="ds-all-videos" href="<?php echo FRoute::videos(array('uid' => $user->getAlias(), 'type' => SOCIAL_TYPE_USER)); ?>"><?php echo JText::_('JALL'); ?>
                                                </a>
                                            </div>
                                        </div>

                                    <?php } else { ?>
                                        <div class="empty empty-hero">
                                            <i class="fa fa-film"></i>
                                            <div><?php echo JText::_('COM_EASYSOCIAL_VIDEOS_NO_VIDEOS_AVAILABLE_CURRENTLY'); ?></div>
                                        </div>
                                    <?php } ?>
                                </div>
                            </div>

                            <div class="col-xs-12 col-sm-6 col-md-6">
                                <!--Posts-->
                                <div class="dashboard_header">
                                    <div class="dashboard-box-title lphotos">
                                        <span>Recent</span>
                                        <span>Posts</span>
                                    </div>
                                </div>
                                <div class="recent-posts-dashboard">
                                    <?php if ($ustream_data) { ?>
                                        <ul data-stream-list class="es-stream-list fd-reset-list">
                                            <?php
                                            foreach ($ustream_data as $streamdata) {
                                                //echo '<pre>';var_dump($streamdata);echo '</pre>';
                                                ?>
                                                <li class="type-<?php echo $streamdata->favicon; ?> streamItem<?php echo $streamdata->display == SOCIAL_STREAM_DISPLAY_FULL ? ' es-stream-full' : ' es-stream-mini'; ?> stream-context-<?php echo $streamdata->context; ?>" data-id="<?php echo $stream->uid; ?>" data-ishidden="0"  data-streamItem data-context="<?php echo $streamdata->context; ?>">
                                                    <div class="es-stream" data-stream-item >                
                                                        <?php if ($streamdata->display == SOCIAL_STREAM_DISPLAY_FULL) { ?>
                                                            <div class="es-stream-meta">
                                                                <div class="media-body">
                                                                    <div class="es-stream-title">
                                                                        <?php echo $streamdata->title; ?>
                                                                    </div>
                                                                    <div class="es-stream-meta-footer">
                                                                        <?php /* ?><span class="text-muted">
                                                                          <?php echo $streamdata->label;?>
                                                                          <b>&middot;</b>
                                                                          </span><?php */ ?>

                                                                        <?php if ($this->config->get('stream.timestamp.enabled')) { ?>
                                                                            <time>
                                                                                <!--<a href="<?php echo FRoute::stream(array('id' => $streamdata->uid, 'layout' => 'item')); ?>">-->
                                                                                <?php echo $streamdata->friendlyDate; ?>
                                                                                <!--</a>-->
                                                                            </time>
                                                                        <?php } ?>
                                                                    </div>
                                                                </div>
                                                                <div class="media">
                                                                    <div class="media-object pull-left">
                                                                        <div class="es-avatar es-avatar-sm es-stream-avatar" data-comments-item-avatar="">
                                                                            <?php if ($streamdata->actor->id) { ?>
                                                                                <a href="<?php echo $streamdata->actor->getPermalink(); ?>"><img src="<?php echo $streamdata->actor->getAvatar(); ?>" alt="<?php echo $streamdata->actor->getName(); ?>" /></a>
                                                                            <?php } else { ?>
                                                                                <img src="<?php echo $streamdata->actor->getAvatar(); ?>" alt="<?php echo $stream->actor->getName(); ?>" />
                                                                            <?php } ?>
                                                                        </div>
                                                                    </div>

                                                                </div>
                                                            </div>                
                                                        <?php } else { ?>
                                                            <div class="es-stream-meta">
                                                                <div class="media-body">
                                                                    <div class="es-stream-title">
                                                                        <?php echo $streamdata->title; ?>
                                                                    </div>
                                                                    <div class="es-stream-meta-footer">
                                                                        <span class="text-muted">
                                                                            <?php echo $streamdata->label; ?>
                                                                            <b>&middot;</b>
                                                                        </span>

                                                                        <?php if ($this->config->get('stream.timestamp.enabled')) { ?>
                                                                            <time>
                                                                                <a href="<?php echo FRoute::stream(array('id' => $streamdata->uid, 'layout' => 'item')); ?>"><?php echo $streamdata->friendlyDate; ?></a>
                                                                            </time>
                                                                        <?php } ?>
                                                                    </div>
                                                                </div>
                                                                <div class="media">
                                                                    <div class="media-object pull-left">
                                                                        <div class="es-avatar es-avatar-sm es-stream-avatar" data-comments-item-avatar="">
                                                                            <?php if ($streamdata->actor->id) { ?>
                                                                                <a href="<?php echo $streamdata->actor->getPermalink(); ?>"><img src="<?php echo $streamdata->actor->getAvatar(); ?>" alt="<?php echo $streamdata->actor->getName(); ?>" /></a>
                                                                            <?php } else { ?>
                                                                                <img src="<?php echo $streamdata->actor->getAvatar(); ?>" alt="<?php echo $stream->actor->getName(); ?>" />
                                                                            <?php } ?>
                                                                        </div>
                                                                    </div>

                                                                </div>
                                                            </div>
                                                        <?php } ?>               
                                                    </div>
                                                </li>
                                            <?php } ?> 
                                        </ul>
                                        <div class="box-bottom">
                                            <div class="col-xs-12 col-sm-10 col-md-9">
                                                <span></span>
                                                <span></span>
                                            </div>
                                            <div class="col-xs-12 col-sm-2 col-md-3">
                                                <a class="ds-all-posts" href="<?php echo FRoute::profile(array('id' => $user->getAlias(), 'layout' => 'timeline')); ?>"><?php echo JText::_('JALL'); ?>
                                                </a>
                                            </div>
                                        </div>
                                    <?php } else { ?>
                                        <div class="fd-small"><?php echo JText::_('No recent posts.'); ?></div>
                                    <?php } ?>                    
                                </div>
                            </div>

                        </div>
                    </div>
                    <?php
                }
                ?>
            </div>
            <?php echo $this->render('module', 'es-profile-after-contents'); ?>
        </div>

    </div>

</div>
