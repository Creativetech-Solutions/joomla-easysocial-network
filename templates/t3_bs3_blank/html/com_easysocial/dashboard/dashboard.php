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
<!--<script>
    (function ($) {

        EasySocial.require()
                .done(function ($)
                {
                    EasySocial.ajax('apps/user/audios/controllers/audios/getAudios', {id: <?= $user->id ?>})
                            .done(function (items) {
                                // Append item to the list.
                                if (items !== false) {

                                    jQuery(".audio-dashboard").removeClass("empty-module").empty();
                                    
                                    $.buildHTML( items ).prependTo( ".audio-dashboard" );
                                }

                            });
                });
    })();
</script>-->
<?php
require_once(JPATH_SITE . '/components/com_easysocial/helpers/dashboard.php');
//JLoader::register('EasySocialDashboardHelper', 'components/com_easysocials/helpers/dashboard.php');
//print_r($_REQUEST);
$currentProfile = $user->getProfile()->get('title');

// Retrieve user's photos
$photosModel = FD::model('Photos');
$photos = $photosModel->getPhotos(array('uid' => $user->id, 'limit' => 5, 'ordering' => 'created'));

// Retrieve user's photos
$videosModel = FD::model('Videos');
$videos = $videosModel->getVideos(array('uid' => $user->id, 'limit' => 5, 'type' => SOCIAL_TYPE_USER));

// Retrieve user's stream
$ustream = FD::model('Stream');
$ustream = $ustream->getStreamData(array('profileId' => $user->getProfile()->get('id'), 'actorid' => $user->id, 'limit' => 4, 'type' => SOCIAL_TYPE_USER, 'context' => 'all'));
$ustream_data = array(); //FD::stream()->format($ustream);
// Retrieve user's step
$usersModel = ES::model('Users');
// Get the active step
$activeStep = 0;

// Get the list of available steps on the user's profile
$steps = $usersModel->getAbout($user, $activeStep);

//user's audio
$audios = new SocialAppsController('user', 'audios');
$aModel = $audios->getModel('Audios');
$audios = $aModel->getAudios(array('uid' => $user->id, 'limit' => 5, 'type' => SOCIAL_TYPE_USER, 'privacy' => false));
$app = FD::table('app');
$audioapp = $app->load(array('type' => SOCIAL_TYPE_APPS, 'group' => SOCIAL_APPS_GROUP_USER, 'element' => ES_SOCIAL_TYPE_AUDIOS));

//user's project
$projects = new SocialAppsController('user', 'projects');
$pjModel = $projects->getModel('Projects');
$projects = $pjModel->getProjects(array('uid' => $user->id, 'limit' => 5, 'type' => SOCIAL_TYPE_USER, 'privacy' => false, 'layout' => 'default'));
$pjapp = FD::table('app');
$projectapp = $pjapp->load(array('type' => SOCIAL_TYPE_APPS, 'group' => SOCIAL_APPS_GROUP_USER, 'element' => 'projects'));

$address = '';
foreach ($steps as $step) {
    foreach ($step->fields as $field) {
        if ($field->unique_key == 'ADDRESS') {
            if ($field->data['address']) {
                $address = $field->data['address'];
            }
        }
    }
}

// Retrieve user's events
$eventModel = FD::model('Events');
$events = $eventModel->getEvents(array('creator_uid' => $user->id, 'creator_type' => SOCIAL_TYPE_USER, 'ongoing' => true, 'upcoming' => true, 'ordering' => 'start', 'limit' => 5));

$privacy = $user->getPrivacy();

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
JFactory::getDocument()->addScriptDeclaration($collapseScript);
?>
<?php echo $this->includeTemplate('site/dashboard/profile-header', array("address" => $address)); ?>
<div class="es-dashboard" data-dashboard>
    <div class="es-container">
        <a href="javascript:void(0);" class="btn btn-block btn-es-inverse btn-sidebar-toggle" data-sidebar-toggle>
            <i class="fa fa-grid-view  mr-5"></i> <?php echo JText::_('COM_EASYSOCIAL_SIDEBAR_TOGGLE'); ?>
        </a>

        <div class="es-sidebar hidden-xs" data-sidebar data-dashboard-sidebar>

            <div class="account_overview_siderbar sort-it" data-sort="1">
                <div class="dashboard_header">
                    <div class="dashboard-box-title saccount-overview">
                        <span>Profile</span>
                        <span>Overview</span>
                    </div>
                </div>
                <div class="account_overview_details">
                    <?php
                    foreach ($steps as $step) {
                        if ($step->title == 'Skills & Specialties') {
                            //print_r($step->fields);
                            ?>
                            <?php if (!$step->hide) { ?>
                                <?php
                                if ($step->fields) {
                                    $empty = true;
                                    ?>
                                    <div class="mt-5 user-skills">
                                        <span>Skills & Specialty</span>
                                        <button data-toggle="collapse" data-target="#collapseSklls">
                                            <i class="cskills fa fa-plus"></i>
                                        </button>
                                        <div class="collapse" id="collapseSklls">
                                            <ul>
                                                <?php
                                                foreach ($step->fields as $field) {

                                                    if ($field->unique_key == 'creativefieldsone' && $field->data != '') {
                                                        echo '<li>';
                                                        //echo $field->data;
                                                        //$row[$field->data]['title']
                                                        $sktitle = EasySocialDashboardHelper::getSkillsTitle($field->id);
                                                        echo $sktitle[$field->data]['title'];
                                                        //print_r($sktitle);
                                                    }
                                                    if ($field->unique_key == 'crfone_speciality') {
                                                        if ($field->data != '') {
                                                            echo '<span> / ' . $field->data . '</span>';
                                                            echo '</li>';
                                                        } else {
                                                            echo '</li>';
                                                        }
                                                    }

                                                    if ($field->unique_key == 'creativefieldstwo' && $field->data != '') {
                                                        echo '<li>';
                                                        //echo $field->data;
                                                        $sktitle = EasySocialDashboardHelper::getSkillsTitle($field->id);
                                                        echo $sktitle[$field->data]['title'];
                                                    }
                                                    if ($field->unique_key == 'crftwo_speciality') {
                                                        if ($field->data != '') {
                                                            echo '<span> / ' . $field->data . '</span>';
                                                            echo '</li>';
                                                        } else {
                                                            echo '</li>';
                                                        }
                                                    }

                                                    if ($field->unique_key == 'creativefieldsthree' && $field->data != '') {
                                                        echo '<li>';
                                                        //echo $field->data;
                                                        $sktitle = EasySocialDashboardHelper::getSkillsTitle($field->id);
                                                        echo $sktitle[$field->data]['title'];
                                                    }
                                                    if ($field->unique_key == 'crfthree_speciality') {
                                                        if ($field->data != '') {
                                                            echo '<span> / ' . $field->data . '</span>';
                                                            echo '</li>';
                                                        } else {
                                                            echo '</li>';
                                                        }
                                                    }
                                                }
                                                ?>
                                            </ul>
                                        </div>
                                    </div>
                                    <?php
                                }
                                ?>
                            <?php } ?>
                            <?php
                        }
                        if ($step->title == 'Hobbies & Interests') {
                            //print_r($step->fields);
                            ?>
                            <?php if (!$step->hide) { ?>
                                <?php
                                if ($step->fields) {
                                    $empty = true;
                                    ?>
                                    <div class="mt-5 user-hobbies">
                                        <span>Hobbies & Interests</span>
                                        <button data-toggle="collapse" data-target="#collapseSklls">
                                            <i class="cskills fa fa-plus"></i>
                                        </button>
                                        <div class="collapse" id="collapseSklls">
                                            <ul>
                                                <?php
                                                foreach ($step->fields as $field) {

                                                    if ($field->unique_key == 'interestsfieldsone' && $field->data != '') {
                                                        echo '<li>';
                                                        //echo $field->data;
                                                        //$row[$field->data]['title']
                                                        $sktitle = EasySocialDashboardHelper::getSkillsTitle($field->id);
                                                        echo $sktitle[$field->data]['title'];
                                                        //print_r($sktitle);
                                                    }
                                                    if ($field->unique_key == 'infone_speciality') {
                                                        if ($field->data != '') {
                                                            echo '<span> / ' . $field->data . '</span>';
                                                            echo '</li>';
                                                        } else {
                                                            echo '</li>';
                                                        }
                                                    }

                                                    if ($field->unique_key == 'interestsfieldstwo' && $field->data != '') {
                                                        echo '<li>';
                                                        //echo $field->data;
                                                        $sktitle = EasySocialDashboardHelper::getSkillsTitle($field->id);
                                                        echo $sktitle[$field->data]['title'];
                                                    }
                                                    if ($field->unique_key == 'inftwo_speciality') {
                                                        if ($field->data != '') {
                                                            echo '<span> / ' . $field->data . '</span>';
                                                            echo '</li>';
                                                        } else {
                                                            echo '</li>';
                                                        }
                                                    }

                                                    if ($field->unique_key == 'interestsfieldsthree' && $field->data != '') {
                                                        echo '<li>';
                                                        //echo $field->data;
                                                        $sktitle = EasySocialDashboardHelper::getSkillsTitle($field->id);
                                                        echo $sktitle[$field->data]['title'];
                                                    }
                                                    if ($field->unique_key == 'infthree_speciality') {
                                                        if ($field->data != '') {
                                                            echo '<span> / ' . $field->data . '</span>';
                                                            echo '</li>';
                                                        } else {
                                                            echo '</li>';
                                                        }
                                                    }
                                                }
                                                ?>
                                            </ul>
                                        </div>
                                    </div>
                                    <?php
                                }
                                ?>
                            <?php } ?>
                            <?php
                        }
                    }
                    foreach ($steps as $step) {
                        ?>
                        <?php if (!$step->hide) { ?>
                            <?php
                            if ($step->fields) {
                                $empty = true;
                                ?>
                                <?php
                                foreach ($step->fields as $field) {
                                    /* if($field->unique_key == 'creativefieldsone' && $field->data != ''){
                                      echo '
                                      <div class="mt-5 user-skills">
                                      <span>Skills & Speciality</span>
                                      <button data-toggle="collapse" data-target="#collapseSklls">
                                      <i class="fa fa-plus"></i>
                                      </button>
                                      <div class="collapse" id="collapseSklls">
                                      <ul>
                                      <li>

                                      </li>
                                      </ul>
                                      </div>
                                      </div>';
                                      } */
                                    if ($field->unique_key == 'ADDRESS') {
                                        if ($field->data['address']) {
                                            echo '<div class="mt-5 user-location"><span>' . $address . ' </span></div>';
                                        }
                                    }
                                    if ($field->unique_key == 'WEBSITE' && $field->data != '') {
                                        echo '<div class="mt-5 user-web"><span>' . $field->data . '</span></div>';
                                    }
                                }
                                ?>
                                <?php
                            }
                            ?>
                        <?php } ?>
                    <?php } ?>
                    <?php
                    $usrRegdate = $user->getRegistrationDate();
                    echo '<div class="mt-5 user-registered"><span>' . $usrRegdate->toLapsed() . '</span></div>';


                    foreach ($steps as $step) {
                        if ($step->title == 'Skills & Specialties') {
                            //print_r($step->fields);
                            ?>
                            <?php if (!$step->hide) { ?>
                                <?php
                                if ($step->fields) {
                                    $empty = true;
                                    $teach_arr = array();
                                    $hire_arr = array();

                                    foreach ($step->fields as $field) {

                                        if ($field->unique_key == 'creativefieldsone' && $field->data != '') {
                                            //$cfone =  $field->data;
                                            $sktitle = EasySocialDashboardHelper::getSkillsTitle($field->id);
                                            $cfone = $sktitle[$field->data]['title'];
                                        }
                                        if ($field->unique_key == 'crfone_teach_hire' && $field->data != '') {
                                            if (strpos($field->data, 'cfone-hire-available') !== false) {
                                                //echo '<li><span>';
                                                //echo $cfone;
                                                //echo '</span></li>';
                                                $hire_arr['cfone'] = $cfone;
                                            }
                                            if (strpos($field->data, 'cfone-teach-lessons') !== false) {
                                                $teach_arr['cfone'] = $cfone;
                                            }
                                        }
                                        if ($field->unique_key == 'creativefieldstwo' && $field->data != '') {
                                            //$cftwo =  $field->data;
                                            $sktitle = EasySocialDashboardHelper::getSkillsTitle($field->id);
                                            $cftwo = $sktitle[$field->data]['title'];
                                        }
                                        if ($field->unique_key == 'crftwo_teach_hire' && $field->data != '') {
                                            if (strpos($field->data, 'cftwo-hire-available') !== false) {
                                                //echo '<li><span>';
                                                //echo $cftwo;
                                                //echo '</span></li>';
                                                $hire_arr['cftwo'] = $cftwo;
                                            }
                                            if (strpos($field->data, 'cftwo-teach-lessons') !== false) {
                                                $teach_arr['cftwo'] = $cftwo;
                                            }
                                        }
                                        if ($field->unique_key == 'creativefieldsthree' && $field->data != '') {
                                            //$cfthree =  $field->data;
                                            $sktitle = EasySocialDashboardHelper::getSkillsTitle($field->id);
                                            $cfthree = $sktitle[$field->data]['title'];
                                        }
                                        if ($field->unique_key == 'crfthree_teach_hire' && $field->data != '') {
                                            if (strpos($field->data, 'cfthree-hire-available') !== false) {
                                                //echo '<li><span>';
                                                //echo $cfthree;
                                                //echo '</span></li>';
                                                $hire_arr['cfthree'] = $cfthree;
                                            }
                                            if (strpos($field->data, 'cfthree-teach-lessons') !== false) {
                                                $teach_arr['cfthree'] = $cfthree;
                                            }
                                        }
                                    }
                                    if (count($hire_arr) > 0) {
                                    ?>
                                    <div class="mt-5 user-hire">
                                        <span>Available for Hire</span>
                                        <button data-toggle="collapse" data-target="#collapseHire">
                                            <i class="chire fa fa-plus"></i>
                                        </button>
                                        <div class="collapse" id="collapseHire">
                                            <ul>
                                                <?php
                                                foreach ($hire_arr as $hire) {
                                                    echo '<li><span>';
                                                    echo $hire;
                                                    echo '</span></li>';
                                                }
                                                ?>
                                            </ul>
                                        </div>
                                    </div>
                                    <?php
                                    }
                                    if (count($teach_arr) > 0) {
                                        ?>
                                    <div class="mt-5 user-teach">
                                        <span>I Teach Lessons</span>
                                        <button data-toggle="collapse" data-target="#collapseTeach">
                                            <i class="cteach fa fa-plus"></i>
                                        </button>
                                        <div class="collapse" id="collapseTeach">
                                            <ul>
                                                <?php
                                                    //print_r($teach_arr);
                                                    foreach ($teach_arr as $teach) {
                                                        echo '<li><span>';
                                                        echo $teach;
                                                        echo '</span></li>';
                                                    }
                                                ?>
                                            </ul>
                                        </div>
                                    </div>
                                    <?php
                                    }
                                }
                                ?>
                            <?php } ?>
                            <?php
                        }
                    }
                    ?>
                </div>
            </div>

            <div class="connections_sidebar sort-it" data-sort="11">
                <div class="dashboard_header">
                    <?php
                    // Get friends model
                    $friendsmodel = FD::model('Friends');
                    $options = array('limit' => 9);
                    // Get list of friends by the current user.
                    $friends = $friendsmodel->getFriends($user->id, $options);
                    $friendstotal = $friendsmodel->getTotalFriends($user->id);
                    ?>
                    <div class="dashboard-box-title connections">
                        <span>Latest</span>
                        <span>Connections</span>
                        <span class="count"><?php echo $friendstotal; ?></span>
                    </div>
                </div>
                <div class="connections_details">
                    <?php echo $this->includeTemplate('site/dashboard/sidebar.module.wrapper', array('displayOn' => 'friends', 'friendsData' => $friends, 'friendsTotal' => $friendstotal)); ?>
                </div>
            </div>

            <div class="social_siderbar sort-it" data-sort="10">
                <?php
                foreach ($steps as $step) {
                    if ($step->title == 'Social Links') {
                        ?>
                        <?php if (!$step->hide) { ?>
                            <div class="dashboard_header">
                                <div class="dashboard-box-title social">
                                    <span>Social</span>
                                    <span>Networks</span>
                                </div>
                            </div>
                            <?php
                            if ($step->fields) {
                                $empty = true;
                                ?>
                                <ul>
                                    <?php
                                    foreach ($step->fields as $field) {
                                        echo '<li class="social-icon-' . $field->unique_key . '">';
                                        echo '<a href="' . $field->data . '" target="_blank">';
                                        if ($field->unique_key == 'gplus') {
                                            echo '<i class="fa fa-google-plus fa-2"></i>';
                                        }
                                        if ($field->unique_key == 'facebook_url') {
                                            echo '<i class="fa fa-facebook fa-2"></i>';
                                        }
                                        if ($field->unique_key == 'twitter_url') {
                                            echo '<i class="fa fa-twitter fa-2"></i>';
                                        }
                                        echo '</a>';

                                        echo '</li>';
                                    }
                                    ?>
                                </ul>
                                <?php
                            }
                            ?>
                        <?php } ?>
                    <?php } ?>
                <?php } ?>
            </div>

            <div class="location_siderbar sort-it" data-sort="9">
                <div class="dashboard_header">
                    <div class="dashboard-box-title slocation">
                        <span>Location</span>
                        <span>Details</span>
                    </div>
                </div>
                <div class="sidebar_location_details">
                    <?php foreach ($steps as $step) {
                        ?>
                        <?php if (!$step->hide) { ?>
                            <?php
                            if ($step->fields) {
                                $empty = true;
                                ?>
                                <?php
                                $business_hours = '';
                                foreach ($step->fields as $field) {

                                    if ($field->unique_key == 'ADDRESS') {
                                        if ($currentProfile == 'Business') {

                                            if ($field->data['address']) {
                                                echo '<script>
                                                jQuery(window).load(function(){
                                                        var map;
                                                        var myLatLng = {lat: ' . $field->data['latitude'] . ', lng: ' . $field->data['longitude'] . '};
                                                        map = new google.maps.Map(document.getElementById(\'mapsidebar\'), {
                                                          center: myLatLng,
                                                          zoom: 17
                                                        });
                                                        var marker = new google.maps.Marker({
                                                          position: myLatLng,
                                                          map: map
                                                        });
                                                });
                                                </script>';
                                                //echo '<img border="0" src="//maps.googleapis.com/maps/api/staticmap?center='.$field->data['address'].'&amp;zoom=17&amp;size=220x80&amp;maptype=roadmap&amp;markers=color:red%7C'.$field->data['latitude'].','.$field->data['longitude'].'">';
                                                //echo '<div id="mapsidebar" style="width:220px;height:80px;margin-bottom: 20px;"></div>';
                                                 echo '<a href="http://maps.google.com/maps?&z=10&q=' . $field->data['latitude'] . '+' . $field->data['longitude'] . '&ll=' . $field->data['latitude'] . '+' . $field->data['longitude'] . '" target="_blank"><div id="mapsidebar" data-lat="' . $field->data['latitude'] . '" data-lng="' . $field->data['longitude'] . '" style="width:220px;height:80px;margin-bottom: 20px;"></div></a>';
                                            }
                                        }
                                        echo '<div class="mt-5 es-teaser-about"><i class="fa fa-home fa-2"></i><span>' . $address . '</span></div>';
                                    }
                                    if ($field->unique_key == 'phone_number' && $field->data != '') {
                                        echo '<div class="mt-5 es-teaser-about color-white"><i class="fa fa-phone  fa-2"></i><span>' . $field->data . '</span></div>';
                                    }
                                    if ($field->unique_key == 'business_hours' && $field->data != '') {
                                        //print_r($field);
                                        $business_hours = '<div class="mt-5 es-teaser-about color-white"><i class="fa fa-clock-o  fa-2"></i><span>' . $field->data . '</span></div>';
                                    }
                                }
                                echo $business_hours;
                                ?>
                                <?php
                            }
                            ?>
                        <?php } ?>
                    <?php } ?>
                </div>
            </div>

        </div>

        <div class="es-content hidden-xs" data-dashboard-content>

            <i class="loading-indicator fd-small"></i>

            <?php echo $this->render('module', 'es-dashboard-before-contents'); ?>
            <div data-dashboard-real-content class="dashboard-boxes">

                <div class="col-xs-12 col-sm-12 col-md-12 box-container sort-it" data-sort="2">
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

                <!--Photos & Videos-->
                <div class="col-xs-12 col-sm-12 col-md-12 box-container">
                    <div class="row js-eq-height-container">
                        <div class="col-xs-12 col-sm-12 col-md-6 sort-it" data-sort="3">
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
                                    <?php
                                    $Photolib = FD::albums($user->id, SOCIAL_TYPE_USER);
                                    if (($Photolib->canCreateAlbums() && $this->my->id == JFactory::getApplication()->input->get('id', 0, 'int')) || JRequest::getVar('view') == 'dashboard') {
                                        ?>
                                        <a href="<?php echo $Photolib->getCreateLink(); ?>" class="btn-upload-photo pull-right"><i class="fa fa-plus" aria-hidden="true"></i>  Add</a>

                                    <?php }
                                    ?>
                                </div>
                            </div>

                            <div class="js-eq-height es-photos photos-<?php echo count($photos); ?> es-stream-photos pattern-<?php echo $options['pattern']; ?> <?php if (!$photos) echo 'empty-module'; ?>"
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
                                        <div class="col-xs-7 col-sm-10 col-md-9">
                                            <span></span>
                                            <span></span>
                                        </div>
                                        <div class="col-xs-4 col-sm-2 col-md-3">
                                            <a class="ds-all-photos" href="<?php echo FRoute::albums(array('uid' => $user->getAlias(), 'type' => SOCIAL_TYPE_USER)); ?>"><?php echo JText::_('COM_EASYSOCIAL_READMORE'); ?>
                                            </a>
                                        </div>
                                    </div>
                                    <?php
                                } else {
                                    ?>
                                    <div class="message"><?php echo JText::_('APP_EMPTY_CONTENT'); ?></div>
                                    <?php
                                }
                                ?>
                            </div>
                        </div>

                        <div class="col-xs-12 col-sm-12 col-md-6 sort-it" data-sort="4">
                            <!--Videos-->
                            <div class="dashboard_header">
                                <div class="dashboard-box-title rvideos">
                                    <span>Recent</span>
                                    <span>Videos</span>
                                    <?php
                                    $Videoadapter = ES::video($user->id, SOCIAL_TYPE_USER);
                                    if (($Videoadapter->allowCreation() && $this->my->id == JFactory::getApplication()->input->get('id', 0, 'int')) || JRequest::getVar('view') == 'dashboard') {
                                        ?>
                                        <a class="btn-add-video pull-right" href="<?php echo FRoute::videos(array('layout' => 'form')); ?>"><i class="fa fa-plus" aria-hidden="true"></i>  Add</a>
                                        <?php
                                    }
                                    ?>
                                </div>
                            </div>
                            <div class="js-eq-height es-video-list clearfix<?php echo!$videos ? ' is-empty' : ''; ?> <?php if (!$videos) echo 'empty-module'; ?>" >
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
                                        <div class="col-xs-7 col-sm-10 col-md-9">
                                            <span></span>
                                            <span></span>
                                        </div>
                                        <div class="col-xs-4 col-sm-2 col-md-3">
                                            <a class="ds-all-videos" href="<?php echo FRoute::videos(array('uid' => $user->getAlias(), 'type' => SOCIAL_TYPE_USER)); ?>"><?php echo JText::_('COM_EASYSOCIAL_READMORE'); ?>
                                            </a>
                                        </div>
                                    </div>

                                <?php } else { ?>
                                    <div class="message"><?php echo JText::_('APP_EMPTY_CONTENT'); ?></div>
                                <?php } ?>
                            </div>
                        </div>
                    </div>
                </div>

                <!--Audio & Posts-->
                <div class="col-xs-12 col-sm-12 col-md-12 box-container">
                    <div class="row js-eq-height-container">
                        <div class="col-xs-12 col-sm-12 col-md-6 sort-it" data-sort="5">
                            <!--Posts-->
                            <div class="dashboard_header">
                                <div class="dashboard-box-title lphotos">
                                    <span>Recent</span>
                                    <span>Posts</span>
                                    <?php if (($this->my->id == JFactory::getApplication()->input->get('id', 0, 'int')) || JRequest::getVar('view') == 'dashboard') { ?>
                                        <a class="pull-right" href="<?php echo FRoute::profile(array('id' => $user->getAlias(), 'layout' => 'timeline')); ?>"><i class="fa fa-plus" aria-hidden="true"></i> Add</a>
                                    <?php } ?>
                                </div>
                            </div>
                            <div class="js-eq-height  recent-posts-dashboard <?php if (!$ustream_data) echo 'empty-module'; ?>">
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
                                                            </div>
                                                        </div>                
                                                    <?php } else { ?>
                                                        <div class="es-stream-meta">
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
                                                            </div>
                                                        </div>
                                                    <?php } ?>               
                                                </div>
                                            </li>
                                        <?php } ?> 
                                    </ul>
                                    <div class="box-bottom">
                                        <div class="col-xs-7 col-sm-10 col-md-9">
                                            <span></span>
                                            <span></span>
                                        </div>
                                        <div class="col-xs-4 col-sm-2 col-md-3">
                                            <a class="ds-all-posts" href="<?php echo FRoute::profile(array('id' => $user->getAlias(), 'layout' => 'timeline')); ?>"><?php echo JText::_('COM_EASYSOCIAL_READMORE'); ?>
                                            </a>
                                        </div>
                                    </div>
                                <?php } else { ?>
                                    <div class="message"><?php echo JText::_('APP_EMPTY_CONTENT'); ?></div>
                                <?php } ?>                    
                            </div>
                        </div>

                        <div class="col-xs-12 col-sm-12 col-md-6 sort-it audio-div" data-sort="6">
                            <!--Audio-->
                            <div class="dashboard_header">
                                <div class="dashboard-box-title laudio">
                                    <span>Latest</span>
                                    <span>Audio</span>
                                    <?php if (($this->my->id == JFactory::getApplication()->input->get('id', 0, 'int')) || JRequest::getVar('view') == 'dashboard') { ?>
                                        <a class="btn-add-audio pull-right" href="<?= FRoute::apps(array('layout' => 'canvas', 'clayout' => 'form', 'id' => $app->getAlias())) ?>"><i class="fa fa-plus" aria-hidden="true"></i>  Add</a>
                                    <?php } ?>
                                </div>
                            </div>
                            <div class="es-audios audio-dashboard audios-<?php echo count($audios); ?> es-stream-audios <?php
                            if (!count($audios)) {
                                echo 'empty-module';
                            };
                            ?> js-eq-height">
                                 <?php if ($audios) { ?>
                                     <?php foreach ($audios as $audio) {
                                         ?>
                                         <?php echo $this->output('apps/user/audios/default.player.mini', array("audio" => $audio)); ?>
                                     <?php } ?>
                                    <div class="box-bottom">
                                        <div class="col-xs-7 col-sm-10 col-md-9">
                                            <span></span>
                                            <span></span>
                                        </div>
                                        <?php
                                        $Audioapp = FD::table('app');
                                        $Audioapp->loadByElement('audios', SOCIAL_APPS_GROUP_USER, 'apps');
                                        ?>
                                        <div class="col-xs-4 col-sm-2 col-md-3">
                                            <a class="ds-all-audios" href="<?= FRoute::apps(array('layout' => 'canvas', 'id' => $Audioapp->getAlias(), 'uid' => $user->getAlias(), 'type' => SOCIAL_TYPE_USER)) ?>"><?php echo JText::_('COM_EASYSOCIAL_READMORE'); ?>
                                            </a>
                                        </div>
                                    </div>
                                <?php } else { ?>
                                    <div class="message"><?php echo JText::_('APP_EMPTY_CONTENT'); ?></div>
                                <?php } ?>
                            </div>

                        </div>
                    </div>
                </div>

                <!--Projects & Events-->
                <div class="col-xs-12 col-sm-12 col-md-12 box-container">
                    <div class="row js-eq-height-container">
                        <div class="col-xs-12 col-sm-12 col-md-6 sort-it box-container" data-sort="7">
                            <!--Upcoming Events-->
                            <div class="dashboard_header">
                                <div class="dashboard-box-title uevents">
                                    <span>Upcoming</span>
                                    <span>Events</span>
                                    <?php
                                    if (($user->isSiteAdmin() || ($user->getAccess()->get('events.create') && !$user->getAccess()->intervalExceeded('events.limit', $user->id)) && $this->my->id == JFactory::getApplication()->input->get('id', 0, 'int')) || JRequest::getVar('view') == 'dashboard') {
                                        ?>
                                        <a class="btn-add-event pull-right" href="<?php echo FRoute::events(array('layout' => 'steps', 'step' => 1), false); ?>"><i class="fa fa-plus" aria-hidden="true"></i>  Add</a>
                                        <?php
                                    }
                                    ?>
                                </div>
                            </div>
                            <div class="es-widget-body pl-0 pl-5 pr-5">
                                <div id="fd" class="js-eq-height es mod-es-events <?php
                                if (!$events) {
                                    echo 'empty-module';
                                }
                                ?>">
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
                                            <div class="col-xs-7 col-sm-10 col-md-9">
                                                <span></span>
                                                <span></span>
                                            </div>
                                            <div class="col-xs-4 col-sm-2 col-md-3">
                                                <a class="ds-all-events" href="<?php echo FRoute::events(array('uid' => $user->getAlias(true, true), 'type' => SOCIAL_TYPE_USER)); ?>"><?php echo JText::_('COM_EASYSOCIAL_READMORE'); ?>
                                                </a>
                                            </div>
                                        </div>
                                    <?php } else { ?>
                                        <div class="message"><?php echo JText::_('APP_EMPTY_CONTENT'); ?></div>
                                    <?php } ?>    
                                </div>

                            </div>
                        </div>

                        <div class="col-xs-12 col-sm-12 col-md-6 sort-it" data-sort="8">
                            <!-- Projects -->
                            <div class="dashboard_header">
                                <div class="dashboard-box-title cprojects">
                                    <span>Current</span>
                                    <span>Projects</span>
                                    <?php if ($this->my->id == $user->id) { ?>
                                        <a class="btn-add-project pull-right" href="<?= FRoute::apps(array('layout' => 'canvas', 'clayout' => 'form', 'id' => $pjapp->getAlias())) ?>"><i class="fa fa-plus" aria-hidden="true"></i>  Add</a>
                                    <?php } ?>
                                </div>
                            </div>
                            <div class="es-projects projects-dashboard projects-<?php echo count($projects); ?> es-stream-projects <?php
                            if (!count($projects)) {
                                echo 'empty-module';
                            };
                            ?> js-eq-height">
                                 <?php
                                 if ($projects) {
                                     $pi = 1;
                                     ?>
                                    <ul class="es-projects-list fd-reset-list">
                                        <?php
                                        foreach ($projects as $project) {
                                            ?>
                                            <li>
                                                <div class="es-project-meta pull-left">
        <?php echo '0' . $pi . '.'; ?>
                                                </div>
                                                <div class="es-project-object">
                                                    <a class="project-title" href="<?php echo $project->getPermalink('detail'); ?>"><?php echo $project->getTitle(); ?></a>
                                                </div>
                                            </li>
                                            <?php
                                            $pi++;
                                        }
                                        ?>
                                    </ul>
                                    <div class="box-bottom">
                                        <div class="col-xs-7 col-sm-10 col-md-9">
                                            <span></span>
                                            <span></span>
                                        </div>
                                        <?php
                                        $Audioapp = FD::table('app');
                                        $Audioapp->loadByElement('audios', SOCIAL_APPS_GROUP_USER, 'apps');
                                        ?>
                                        <div class="col-xs-4 col-sm-2 col-md-3">
                                            <a class="ds-all-projects" href="<?= FRoute::apps(array('layout' => 'canvas', 'id' => $pjapp->getAlias(), 'uid' => $user->getAlias(), 'type' => SOCIAL_TYPE_USER)) ?>"><?php echo JText::_('COM_EASYSOCIAL_READMORE'); ?>
                                            </a>
                                        </div>
                                    </div>
                                <?php } else { ?>
                                    <div class="message"><?php echo JText::_('APP_EMPTY_CONTENT'); ?></div>
<?php } ?>
                            </div>
                        </div>


                    </div>

                </div>

            </div>

<?php echo $this->render('module', 'es-dashboard-after-contents'); ?>
        </div>

        <div class="sorted-container hidden-sm hidden-md hidden-lg dashboard-boxes"></div>
    </div>
</div>
