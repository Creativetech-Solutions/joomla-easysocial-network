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

<script type="text/javascript">
    jQuery(document).ready(function () {
        jQuery(document).on("click", "a.logout-link", function () {
            jQuery('[data-toolbar-logout-form]').submit();
        });
    });
//not sure if the script below is necessary... it was being used in the elegant template.
//    EasySocial
//            .require()
//            .script('site/toolbar/notifications', 'site/search/toolbar', 'site/layout/responsive')
//            .done(function ($) {
//                $('[data-nav-search]').implement(EasySocial.Controller.Search.Toolbar);
//
//                // disable the dropdown from closing when user click on the checkbox of the filter types
//                $('[data-nav-search-filter] .dropdown-menu input, [data-nav-search-filter] .dropdown-menu label').on('click', function (e) {
//                    e.stopPropagation();
//                });
//
//                $("[data-toolbar-logout-button]").on('click', function () {
//                    $('[data-toolbar-logout-form]').submit();
//                });
//
//                $('[data-elegant-toggle-search]').on('click', function () {
//                    $('[data-notifications]').toggleClass('show-search');
//                });
//
//                $('[data-toolbar-toggle]').on('click', function () {
//                    $('.popbox-notifications').hide();
//                })
//            });

</script>
<?php
$themeConfig = FD::themes()->getConfig();
$theme = FD::themes();
// Get the user's object.
$app = JFactory::getApplication();
$id = $app->input->get('id', 0, 'int');

// Check if there is any stream filtering or not.
$filter = $app->input->get('type', '', 'word');
//error_reporting(E_ALL);
//ini_set('display_errors', 1);
// The current logged in user might be viewing their own profile.
if ($id == 0) {
    $id = ES::user()->id;
}
$user = ES::user($id);
//$theme->set('facebook', $facebook);
$theme->set('user', $user);
//
//$profile = $user->getProfile();
//$appsModel = ES::model('Apps');
//$options = array('view' => 'profile', 'uid' => $user->id, 'key' => SOCIAL_TYPE_USER, 'inclusion' => $profile->getDefaultApps());
//$apps = $appsModel->getApps($options);
//
//$this->set('appFilters', $appFilters);
//$this->set('filterId', $appType);
//$this->set('timeline', $timeline);
//$this->set('newCover', $newCover);
//$this->set('cover', $cover);
//$this->set('contents', $contents);
//$this->set('appsLib', $appsLib);
//$this->set('apps', $apps);
//$this->set('activeApp', $appId);
//$this->set('privacy', $privacy);
//$this->set('user', $user);
//print_r($theme);
//exit;
?>
<div id="fd" class="es mod-es-menu module-menu<?php echo $suffix; ?>">
    <ul class="fd-nav pull-right">
        <?php if (!$my->guest) { ?>
            <?php echo $theme->includeTemplate('site/toolbar/default.profile'); ?>
        <?php } ?>
    </ul>

    <?php if ($params->get('show_notifications', true)) { ?>
        <div class="es-notification pull-right">
            <div class="es-menu-items">
                <?php if ($params->get('show_conversation_notifications', true)) { ?>
                    <div class="es-menu-item notice-message has-notice"
                         data-original-title="<?php echo JText::_('MOD_EASYSOCIAL_MENU_CONVERSATIONS'); ?>"
                         data-es-provide="tooltip"
                         data-placement="bottom">

                        <a href="javascript:void(0);"
                           class="<?php echo $my->getTotalNewConversations() > 0 ? 'has-notice' : ''; ?>"
                           data-popbox="module://easysocial/conversations/popbox"
                           data-popbox-toggle="click"
                           data-module-esmenu-conversations
                           data-interval="<?php echo $params->get('interval_notifications_conversations', 60); ?>"
                           data-popbox-position="<?php echo $params->get('popbox_position', 'bottom'); ?>"
                           data-popbox-collision="<?php echo $params->get('popbox_collision', 'flip'); ?>"
                           data-user-id="43">
                            <i class="fa fa-envelope-o"></i>
                            <span class="badge badge-notification" data-notificationConversation-counter><?php echo $my->getTotalNewConversations(); ?></span>
                        </a>
                    </div>
                <?php } ?>
                <?php if ($params->get('show_system_notifications', true)) { ?>
                    <div class="es-menu-item notice-recent has-notice"
                         data-original-title="<?php echo JText::_('MOD_EASYSOCIAL_MENU_NOTIFICATIONS'); ?>"
                         data-es-provide="tooltip"
                         data-placement="bottom">

                        <a href="javascript:void(0);"
                           class="<?php echo $my->getTotalNewNotifications() > 0 ? 'has-notice' : ''; ?>"
                           data-popbox="module://easysocial/notifications/popbox"
                           data-popbox-toggle="click"
                           data-module-esmenu-system
                           data-interval="<?php echo $params->get('interval_notifications_system', 60); ?>"
                           data-popbox-position="<?php echo $params->get('popbox_position', 'bottom'); ?>"
                           data-popbox-collision="<?php echo $params->get('popbox_collision', 'flip'); ?>"
                           data-user-id="43">
                            <i class="fa fa-globe"></i>
                            <span class="badge badge-notification" data-notificationSystem-counter><?php echo $my->getTotalNewNotifications(); ?></span>
                        </a>
                    </div>
                <?php } ?>
                <?php if ($params->get('show_friends_notifications', true)) { ?>
                    <div class="es-menu-item notice-friend has-notice"
                         data-original-title="<?php echo JText::_('MOD_EASYSOCIAL_MENU_FRIEND_REQUESTS'); ?>"
                         data-es-provide="tooltip"
                         data-placement="bottom">

                        <a href="javascript:void(0);"
                           class="<?php echo $my->getTotalFriendRequests() > 0 ? 'has-notice' : ''; ?>"
                           data-popbox="module://easysocial/friends/popbox"
                           data-popbox-toggle="click"
                           data-module-esmenu-friends
                           data-interval="<?php echo $params->get('interval_notifications_friends', 60); ?>"
                           data-popbox-position="<?php echo $params->get('popbox_position', 'bottom'); ?>"
                           data-popbox-collision="<?php echo $params->get('popbox_collision', 'flip'); ?>"
                           >
                            <i class="fa fa-users"></i>
                            <span class="badge badge-notification" data-notificationFriends-counter><?php echo $my->getTotalFriendRequests(); ?></span>
                        </a>
                    </div>
                <?php } ?>





            </div>
        </div>
    <?php } ?>




</div>
