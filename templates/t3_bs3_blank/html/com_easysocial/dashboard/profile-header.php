<?php
$cover = $user->getCoverData();
$input = JFactory::getApplication()->input;
$subview = $input->get('subview', '');
$view = $input->get('view', '');
$script = '
    
jQuery(document).ready(function($) {
	$("body").prepend("<div class=\"header-full-cover\" style=\"background-image   : url(' . $cover->getSource() . ');\"></div>");

});
';
//ignore views
if ($subview == 'all' && ($view == "videos" OR $view == "events")) {
    
} else {
    JFactory::getDocument()->addScriptDeclaration($script);
}
$usersModel = ES::model('Users');

$steps = $usersModel->getAbout($user, $activeStep);
foreach ($steps as $step) {
    foreach ($step->fields as $field) {
        if ($field->unique_key == 'ADDRESS') {
            if ($field->data['address']) {
                $address = $field->data['address'];
            }
        }
    }
}

//get business name or artist name
$fieldsModel = ES::model('Fields');
$artist_name = $fieldsModel->getCustomFields(array('data' => true, 'dataId' => $user->id, 'dataType' => SOCIAL_TYPE_USER, 'key' => 'artist-name', 'profile_id' => $user->getProfile()->id));
//print_r($artist_name);
$business_name = $fieldsModel->getCustomFields(array('data' => true, 'dataId' => $user->id, 'dataType' => SOCIAL_TYPE_USER, 'key' => 'business-name', 'profile_id' => $user->getProfile()->id));
//print_r($business_name);
//echo 'artist nam: '.$fjemail[0]->data;
?>

<div class="es-profile-header" data-profile-header data-id="<?php echo $user->id; ?>" data-name="<?php echo $this->html('string.escape', $user->getName()); ?>" data-avatar="<?php echo $user->getAvatar(); ?>">
    <div class="es-profile-header-heading <?php echo $this->template->get('profile_cover') ? ' with-cover' : ' without-cover'; ?>">
        <?php if ($this->template->get('profile_cover') && (!isset($showCover) || $showCover)) { ?>
            <?php //echo $this->includeTemplate("site/profile/cover"); ?>
            <div
            <?php if ($cover->photo_id && $cover->getPhoto()->album_id) { ?>
                    data-es-photo-group="album:<?php echo $cover->getPhoto()->album_id; ?>"
                <?php } ?>
                >
                <div data-profile-cover
                <?php echo $cover->photo_id ? 'data-es-photo="' . $cover->photo_id . '"' : ''; ?>
                     class="es-profile-header-cover es-flyout <?php echo $user->hasCover() ? '' : 'no-cover'; ?> <?php echo!empty($newCover) ? "editing" : ""; ?>"
                     style="
                     background-image   : url(<?php echo $cover->getSource(); ?>);
                     background-position: <?php echo $cover->getPosition(); ?>;
                     ">

                    <div class="es-cover-container">
                        <div class="es-cover-viewport">
                            <div
                                data-cover-image
                                class="es-cover-image"
                                <?php if (!empty($newCover)) { ?>
                                    data-photo-id="<?php echo $newCover->id; ?>"
                                    style="background-image: url(<?php echo $newCover->getSource('large'); ?>);"
                                <?php } ?>

                                <?php if ($cover->id) { ?>
                                    data-photo-id="<?php echo $cover->getPhoto()->id; ?>"
                                    style="background-image: url(<?php echo $cover->getSource(); ?>);"
                                <?php } ?>
                                >
                            </div>

                            <div class="es-cover-hint">
                                <span>
                                    <span class="fd-loading"><?php echo JText::_('COM_EASYSOCIAL_PHOTOS_COVER_LOADING'); ?></span>
                                    <span class="es-cover-hint-text"><?php echo JText::_('COM_EASYSOCIAL_PHOTOS_COVER_DRAG_HINT'); ?></span>
                                </span>
                            </div>

                            <div class="es-cover-loading-overlay"></div>

                            <?php if ($user->id == $this->my->id) { ?>
                                <div class="es-flyout-content">

                                    <div class="dropdown_ pull-right es-cover-menu" data-cover-menu>
                                        <a href="javascript:void(0);" data-bs-toggle="dropdown" class="dropdown-toggle_ es-flyout-button">
                                            <i class="fa fa-cog"></i><?php echo JText::_('COM_EASYSOCIAL_PHOTOS_EDIT_COVER'); ?></span>
                                        </a>
                                        <ul class="dropdown-menu">
                                            <li data-cover-upload-button>
                                                <a href="javascript:void(0);"><?php echo JText::_("COM_EASYSOCIAL_PHOTOS_UPLOAD_COVER"); ?></a>
                                            </li>
                                            <li data-cover-select-button>
                                                <a href="javascript:void(0);"><?php echo JText::_('COM_EASYSOCIAL_PHOTOS_SELECT_COVER'); ?></a>
                                            </li>
                                            <li data-cover-edit-button>
                                                <a href="javascript:void(0);"><?php echo JText::_('COM_EASYSOCIAL_PHOTOS_REPOSITION_COVER'); ?></a>
                                            </li>
                                            <li class="divider for-cover-remove-button"></li>
                                            <li data-cover-remove-button>
                                                <a href="javascript:void(0);"><?php echo JText::_("COM_EASYSOCIAL_PHOTOS_REMOVE_COVER"); ?></a>
                                            </li>
                                        </ul>
                                    </div>

                                    <a href="javascript:void(0);"
                                       class="es-cover-done-button es-flyout-button"
                                       data-cover-done-button><i class="fa fa-check"></i><?php echo JText::_("COM_EASYSOCIAL_PHOTOS_COVER_DONE"); ?></a>

                                    <a href="javascript:void(0);"
                                       class="es-cover-cancel-button es-flyout-button"
                                       data-cover-cancel-button><i class="fa fa-remove"></i><?php echo JText::_("COM_EASYSOCIAL_PHOTOS_COVER_CANCEL"); ?></a>
                                </div>
                            <?php } ?>

                        </div>
                    </div>
                </div>
            </div>
        <?php } ?>

        <?php echo $this->includeTemplate("site/profile/avatar"); ?>

        <?php echo $this->render('widgets', 'user', 'profile', 'afterAvatar', array($user)); ?>
    </div>

    <div class="es-profile-header-body fd-cf">


        <div>
            <?php echo $this->render('module', 'es-profile-before-name'); ?>
            <?php echo $this->render('widgets', 'user', 'profile', 'beforeName', array($user)); ?>

            <h2 class="es-profile-header-title">
                <a href="<?php echo $user->getPermalink(); ?>">
                    <?php
                    if (is_array($artist_name) && $artist_name[0] != '' && $artist_name[0]->data != '') {
                        echo $artist_name[0]->data;
                    } elseif (is_array($business_name) && $business_name[0] != '' && $business_name[0]->data != '') {
                        echo $business_name[0]->data;
                    } else {
                        echo $user->getName();
                    }
                    ?>
                </a>
                <span class="profile-switch-dropdown">
                    <img src="<?php echo $user->getProfile()->getAvatar(); ?>" class="es-avatar" data-popbox
                         data-popbox-id="fd"
                         data-popbox-component="es"
                         data-popbox-type="switch-profile"
                         data-popbox-toggle="click"
                         data-popbox-position="<?php echo JFactory::getDocument()->getDirection() == 'rtl' ? 'bottom-left' : 'bottom-right'; ?>"
                         data-popbox-target=".switch-profile-popbox" />
                         <?php if ($user->id == $this->my->id) { ?>
                        <div style="display:none;" class="switch-profile-popbox">
                            <ul class="popbox-dropdown-menu dropdown-menu-user" style="display: block;">
                                <li>
                                    <a href="<?php echo FRoute::profile(array('layout' => 'edit', 'subview' => 'settings')); ?>">Change Profile Type</a>
                                </li>
                            </ul>
                        </div>
                    <?php } ?>
                </span>
            </h2>



            <div class="first_speciality">
                <?php
                foreach ($steps as $step) {
                    if ($step->title == 'Skills & Specialties') {
                        ?>
                        <?php if (!$step->hide) { ?>
                            <?php
                            if ($step->fields) {
                                $empty = true;
                                ?>
                                <ul>
                                    <?php
                                    foreach ($step->fields as $field) {
                                        if ($field->unique_key == 'crfone_speciality' && $field->data != '') {
                                            echo '<li>' . $field->data . '</li>';
                                        }
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

            <?php echo $this->render('fields', 'user', 'profile', 'afterName', array('HEADLINE', $user)); ?>

            <?php echo $this->render('widgets', 'user', 'profile', 'afterName', array($user)); ?>

            <?php echo $this->render('module', 'es-profile-after-name'); ?>

            <?php echo $this->render('widgets', 'user', 'profile', 'beforeBadges', array($user)); ?>

            <?php if ($this->config->get('badges.enabled') && $user->badgesViewable(FD::user()->id) && $user->getBadges() && $this->template->get('profile_badges')) { ?>
                <div class="mt-5 es-teaser-about">
                    <ul class="fd-reset-list es-badge-list">
                        <?php foreach ($user->getBadges() as $badge) { ?>
                            <li class="es-badge-item">
                                <a href="<?php echo $badge->getPermalink(); ?>" class="badge-link" data-es-provide="tooltip" data-placement="top" data-original-title="<?php echo $this->html('string.escape', $badge->get('title')); ?>">
                                    <img class="es-badge-icon" alt="<?php echo $this->html('string.escape', $badge->get('title')); ?>" src="<?php echo $badge->getAvatar(); ?>"></a>
                            </li>
                        <?php } ?>
                    </ul>
                </div>
            <?php } ?>

            <?php echo $this->render('widgets', 'user', 'profile', 'afterBadges', array($user)); ?>

            <?php if ($this->template->get('profile_age', true)) { ?>
                <?php /* ?><div class="mt-5 es-teaser-about">
                  <div class="fd-small">
                  <?php echo $this->render( 'fields' , 'user' , 'profile' , 'profileHeaderA' , array( 'BIRTHDAY' , $user ) ); ?>
                  </div>
                  </div><?php */ ?>
            <?php } ?>

            <?php if ($this->template->get('profile_gender', true)) { ?>
                <?php /* ?><div class="mt-5 es-teaser-about">
                  <div class="fd-small">
                  <?php echo $this->render( 'fields' , 'user' , 'profile' , 'profileHeaderA' , array( 'GENDER' , $user ) ); ?>
                  </div>
                  </div><?php */ ?>
            <?php } ?>

            <?php if ($this->template->get('profile_lastlogin', true)) { ?>
                <?php /* ?><div class="mt-5 es-teaser-about">
                  <div class="fd-small"><?php echo $this->render( 'fields' , 'user' , 'profile' , 'profileHeaderA' , array( 'JOOMLA_LASTLOGIN' , $user ) ); ?></div>
                  </div><?php */ ?>
            <?php } ?>

            <?php if ($this->template->get('profile_joindate', true)) { ?>
                <?php /* ?><div class="mt-5 es-teaser-about">
                  <div class="fd-small"><?php echo $this->render( 'fields' , 'user' , 'profile' , 'profileHeaderA' , array( 'JOOMLA_JOINDATE' , $user ) ); ?></div>
                  </div><?php */ ?>
            <?php } ?>

            <?php if ($this->template->get('profile_address', true)) {
                ?>

                <div class="mt-5 es-teaser-about">
                    <div class="fd-small header-add-location"><?= $address ?></div>
                </div>
            <?php } ?>

            <?php if ($this->template->get('profile_website', true)) { ?>
                <?php /* ?><div class="mt-5 es-teaser-about">
                  <div class="fd-small">
                  <?php echo $this->render( 'fields' , 'user' , 'profile' , 'profileHeaderD' , array( 'URL' , $user ) ); ?>
                  </div>
                  </div><?php */ ?>
            <?php } ?>

            <?php echo $this->render('module', 'es-profile-before-info'); ?>




            <?php echo $this->render('module', 'es-profile-after-info'); ?>

            <?php echo $this->render('widgets', 'user', 'profile', 'afterInfo', array($user)); ?>

            <?php /* ?><div class="user_followers">
              <ul>
              <li>
              <?php echo $user->getTotalFollowers();?> <?php echo JText::_( FD::string()->computeNoun( 'COM_EASYSOCIAL_FOLLOWERS' , $user->getTotalFollowers() ) ); ?>
              </li>
              </ul>
              </div><?php */ ?>
        </div>
    </div>
    <div class="es-profile-header-action own-profile pull-right">

        <?php
        if ($user->id == $this->my->id) {
            ?>
            <a class="btn-border hidden-xs" href="<?php echo FRoute::profile(array('id' => $user->getAlias(), 'layout' => 'timeline')); ?>"><i class="fa fa-rss" aria-hidden="true"></i> <?php echo JText::_('COM_EASYSOCIAL_DASHBOARD_SIDEBAR_NEWSFEEDS'); ?></a>
            <a class="btn-border visible-xs" href="<?php echo FRoute::profile(array('id' => $user->getAlias(), 'layout' => 'timeline')); ?>"><i class="fa fa-rss" aria-hidden="true"></i></a>

            <?php
        }
        ?>

    </div>
    <div class="es-profile-header-action pull-right">
        <?php echo $this->render('widgets', 'user', 'profile', 'beforeActions', array($user)); ?>

        <?php echo $this->render('module', 'es-profile-before-actions'); ?>
        <?php if ($user->id != $this->my->id) { ?>

            <?php if ($privacy->validate('profiles.post.message', $user->id) && $this->config->get('conversations.enabled') && $this->access->allowed('conversations.create')) { ?>
                <div class="user-message">
                    <?php echo $this->loadTemplate('site/profile/button.conversations.new'); ?>
                </div>
            <?php } ?>

            <?php if ($this->my->isSiteAdmin()) { ?>
                <div class="followAction admin-tools"
                     data-id="<?php echo $user->id; ?>"
                     style="position:relative;"
                     >
                         <?php echo $this->loadTemplate('site/profile/default.header.admin', array('user' => $user)); ?>
                </div>
            <?php } ?>

            <?php if (!$user->isBlockedBy($this->my->id)) { ?>
                <?php $privacy = $this->my->getPrivacy(); ?>
                <?php if ($privacy->validate('friends.request', $user->id)) { ?>
                    <div class="friendsAction"
                         data-id="<?php echo $user->id; ?>"
                         data-callback="<?php echo base64_encode(JRequest::getURI()); ?>"
                         data-profile-friends
                         data-friend="<?php echo $user->getFriend($this->my->id)->id; ?>"
                         >
                             <?php echo $this->loadTemplate('site/profile/default.header.friends', array('user' => $user)); ?>

                    </div>
                <?php } ?>

                <?php if ($this->config->get('followers.enabled')) { ?>
                    <div class="followAction hidden-xs"
                         data-id="<?php echo $user->id; ?>"
                         data-profile-followers
                         style="position:relative;"
                         >
                             <?php if (FD::get('Subscriptions')->isFollowing($user->id, SOCIAL_TYPE_USER)) { ?>
                                 <?php echo $this->loadTemplate('site/profile/button.followers.unfollow'); ?>
                             <?php } else { ?>
                                 <?php echo $this->loadTemplate('site/profile/button.followers.follow'); ?>
                             <?php } ?>
                    </div>
                <?php } ?>

            <?php } ?>

        <?php } else { ?>
            <?php /* ?><div>
              <a href="<?php echo FRoute::profile( array( 'layout' => 'edit' ));?>" class="btn btn-clean btn-block btn-sm">
              <i class="fa fa-cog mr-5"></i>
              <?php echo JText::_( 'COM_EASYSOCIAL_PROFILE_UPDATE_PROFILE' );?>
              </a>
              </div><?php */ ?>
        <?php } ?>

        <?php if ($this->template->get('profile_points', true) && $this->config->get('points.enabled')) { ?>
            <div>
                <a href="<?php echo FRoute::points(array('userid' => $user->getAlias(), 'layout' => 'history')); ?>" class="btn btn-clean btn-block">

                    <div class="text-center fd-small">
                        <strong><?php echo JText::_('COM_EASYSOCIAL_PROFILE_POINTS'); ?></strong>
                    </div>

                    <div class="text-center">
                        <span style="font-size: 26px;font-weight:700;line-height:21px"><?php echo $user->getPoints(); ?></span>
                    </div>
                </a>
            </div>
        <?php } ?>

        <?php echo $this->render('module', 'es-profile-after-actions'); ?>

        <?php echo $this->render('widgets', 'user', 'profile', 'afterActions', array($user)); ?>
    </div>

    <?php echo $this->output('site/dashboard/header-navigation'); ?>

</div>