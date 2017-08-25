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
JHtml::_('jquery.framework');
//JHtml::script('media/editors/tinymce/tinymce.min.js', false, false, false, false, false);

$lib = ES::alert();
$alerts = $lib->getUserSettings($this->my->id);

$privacyLib = ES::privacy($this->my->id);
$result = $privacyLib->getData();

$privacy = array();

// Update the privacy data with proper properties.
foreach ($result as $group => $items) {

    // We do not want to show field privacy rules here because it does not make sense for user to set a default value
    // Most of the fields only have 1 and it is set in Edit Profile page
    if ($group === 'field') {
        continue;
    }

    // Only display such privacy rules if photos is enabled
    if (($group == 'albums' || $group == 'photos') && !$this->config->get('photos.enabled')) {
        continue;
    }

    // Only display videos privacy if videos is enabled.
    if ($group == 'videos' && !$this->config->get('video.enabled')) {
        continue;
    }

    // Do not display badges / achievements in privacy if badges is disabled
    if ($group == 'achievements' && !$this->config->get('badges.enabled')) {
        continue;
    }

    // Do not display followers privacy item
    if ($group == 'followers' && !$this->config->get('followers.enabled')) {
        continue;
    }

    foreach ($items as &$item) {

        // Conversations rule should only appear if it is enabled.
        if (($group == 'profiles' && $item->rule == 'post.message') && !$this->config->get('conversations.enabled')) {
            unset($item);
        }

        $rule = JString::str_ireplace('.', '_', $item->rule);
        $rule = strtoupper($rule);

        $groupKey = strtoupper($group);

        // Determines if this has custom
        $item->hasCustom = $item->custom ? true : false;

        // If the rule is a custom rule, we need to set the ids
        $item->customIds = array();
        $item->customUsers = array();

        if ($item->hasCustom) {
            foreach ($item->custom as $friend) {
                $item->customIds[] = $friend->user_id;

                $user = ES::user($friend->user_id);
                $item->customUsers[] = $user;
            }
        }

        // Try to find an app element that is related to the privacy type
        $app = ES::table('App');
        $appExists = $app->load(array('element' => $item->type));

        if ($appExists) {
            $app->loadLanguage();
        }

        // Go through each options to get the selected item
        $item->selected = '';

        foreach ($item->options as $option => $value) {
            if ($value) {
                $item->selected = $option;
            }

            // We need to remove "Everyone" if the site lockdown is enabled
            if ($this->config->get('general.site.lockdown.enabled') && $option == SOCIAL_PRIVACY_0) {
                unset($item->options[$option]);
            }
        }

        $item->groupKey = $groupKey;
        $item->label = JText::_('COM_EASYSOCIAL_PRIVACY_LABEL_' . $groupKey . '_' . $rule);
        $item->tips = JText::_('COM_EASYSOCIAL_PRIVACY_TIPS_' . $groupKey . '_' . $rule);
    }

    $privacy[$group] = $items;
}
// Get a list of blocked users for this user
$blockModel = ES::model('Blocks');
$blocked = $blockModel->getBlockedUsers($this->my->id);
?>
<script type="text/javascript">
    /*tinyMCE.init({
     mode : "textareas",
     theme : "modern",
     selector : ".story_about textarea"
     });*/
</script>

<div class="es-container es-profile-edit" data-profile-edit>
    <div class="es-sidebar collapse" id="es-sidebar" data-sidebar>

        <h4 class="edit-profile">
            <i class="fa fa-cog" aria-hidden="true"></i>
            Account <span>Settings</span>
        </h4>

        <?php echo $this->render('module', 'es-profile-edit-sidebar-top', 'site/dashboard/sidebar.module.wrapper'); ?>

        <div class="es-widget es-widget-borderless">
            <div class="es-widget-body">
                <ul class="fd-nav fd-nav-stacked feed-items">
                    <?php /* ?><?php $i = 0; ?>
                      <?php foreach ($steps as $step){ ?>
                      <li data-for="<?php echo $step->id;?>" class="step-item<?php echo $i == 0 ? ' active' :'';?>" data-profile-edit-fields-step>
                      <a href="javascript:void(0);"><?php echo $step->get('title'); ?></a>
                      </li>
                      <?php $i++; ?>
                      <?php } ?><?php */ ?>
                    <li data-for="1" class="step-item active" data-profile-edit-fields-step>
                        <a href="javascript:void(0);" class="fd-small"><?php echo JText::_('Account'); ?></a>
                    </li>
                    <li data-for="2" class="step-item" data-profile-edit-fields-step>
                        <a href="javascript:void(0);" class="fd-small"><?php echo JText::_('Password'); ?></a>
                    </li>
                    <li data-for="3" class="step-item" data-profile-edit-fields-step>
                        <a href="javascript:void(0);" class="fd-small"><?php echo JText::_('COM_EASYSOCIAL_TOOLBAR_NOTIFICATION_SETTINGS'); ?></a>
                    </li>
                    <li data-for="4" class="step-item" data-profile-edit-fields-step>
                        <a href="javascript:void(0);" class="fd-small"><?php echo JText::_('COM_EASYSOCIAL_TOOLBAR_PRIVACY_SETTINGS'); ?></a>
                    </li>
                    <li data-for="5" class="step-item" data-profile-edit-fields-step>
                        <a href="javascript:void(0);" class="fd-small"><?php echo JText::_('COM_EASYSOCIAL_DELETE_YOUR_PROFILE_BUTTON'); ?></a>
                    </li>
                </ul>
            </div>
            <hr  />
            <ul class="newsfeed-sidebar">
                <li class="settings">
                    <a href="<?php echo FRoute::profile(array('layout' => 'edit')); ?>">
                        <i class="fa fa-pencil-square-o"></i>
                        <?php echo JText::_('COM_EASYSOCIAL_PROFILE_UPDATE_PROFILE'); ?>
                    </a>
                </li>
                <li class="dashboard">
                    <a href="<?php echo FRoute::dashboard(); ?>">
                        <i class="fa fa-dashboard"></i>
                        <?php echo JText::_('COM_EASYSOCIAL_USER_DASHBOARD'); ?>
                    </a>
                </li>
            </ul>
        </div>

        <?php if ($this->config->get('users.display.profiletype', true) && $this->my->hasCommunityAccess()) { ?>
            <?php /* ?><div class="es-widget es-widget-borderless">
              <div class="es-widget-head">
              <div class="widget-title pull-left">
              <?php echo JText::_('COM_EASYSOCIAL_PROFILE_SIDEBAR_YOUR_PROFILE');?>
              </div>
              </div>

              <div class="es-widget-body">
              <?php echo JText::sprintf('COM_EASYSOCIAL_PROFILE_SIDEBAR_YOUR_PROFILE_INFO', '<a href="' . $profile->getPermalink() . '">' . $profile->getTitle() . '</a>');?>
              </div>
              </div><?php */ ?>
        <?php } ?>

        <?php if ($showSocialTabs) { ?>
            <div class="es-widget es-widget-borderless">
                <div class="es-widget-head"><?php echo JText::_('COM_EASYSOCIAL_PROFILE_SIDEBAR_SOCIALIZE'); ?></div>

                <div class="es-widget-body">
                    <ul class="fd-nav fd-nav-stacked feed-items">
                        <?php if ($associatedFacebook) { ?>
                            <li data-for="facebook" data-profile-edit-fields-step data-profile-edit-facebook>
                                <a href="javascript:void(0);"><?php echo JText::_('COM_EASYSOCIAL_PROFILE_SIDEBAR_SOCIALIZE_FACEBOOK'); ?></a>
                            </li>
                        <?php } ?>
                    </ul>
                </div>
            </div>
        <?php } ?>

        <?php if ($this->my->deleteable()) { ?>
            <?php /* ?><div class="es-widget es-widget-borderless">
              <div class="es-widget-head"><?php echo JText::_('COM_EASYSOCIAL_PROFILE_SIDEBAR_DELETE');?></div>

              <div class="es-widget-body">
              <a href="javascript:void(0);" class="fd-small" data-profile-edit-delete><?php echo JText::_('COM_EASYSOCIAL_DELETE_YOUR_PROFILE_BUTTON');?></a>
              </div>
              </div><?php */ ?>
        <?php } ?>

        <?php echo $this->render('module', 'es-profile-edit-sidebar-bottom', 'site/dashboard/sidebar.module.wrapper'); ?>
    </div>

    <a data-target="#es-sidebar" class="profile-sidebar-toggle" data-toggle="collapse">
        <?php echo '<img src="' . JURI::base() . 'images/new_side_button.png" />' ?>
    </a>

    <div class="es-content">

        <?php
        echo $this->render('module', 'es-profile-edit-before-contents');
        $fieldsModel = ES::model('Fields');
        $fieldsO = ES::fields();
        $callbackO = array($fieldsO->getHandler(), 'getOutput');

        $fjemail = $fieldsModel->getCustomFields(array('data' => true, 'dataId' => $this->my->id, 'dataType' => SOCIAL_TYPE_USER, 'visible' => 'edit', 'element' => 'joomla_email', 'profile_id' => $this->my->getProfile()->id, 'group' => SOCIAL_FIELDS_GROUP_USER));

        $fjpass = $fieldsModel->getCustomFields(array('data' => true, 'dataId' => $this->my->id, 'dataType' => SOCIAL_TYPE_USER, 'visible' => 'edit', 'element' => 'joomla_password', 'profile_id' => $this->my->getProfile()->id, 'group' => SOCIAL_FIELDS_GROUP_USER));
        
        $switchprofile = $fieldsModel->getCustomFields(array('data' => true, 'dataId' => $this->my->id, 'dataType' => SOCIAL_TYPE_USER, 'visible' => 'edit', 'element' => 'switchprofile', 'profile_id' => $this->my->getProfile()->id, 'group' => SOCIAL_FIELDS_GROUP_USER));

        //$fout = $fhtml->getData($this->my->id,SOCIAL_TYPE_USER);
        //echo $fhtml->output;
        ?>

        <div class="profile-wrapper" data-profile-edit-fields>
            <form method="post" action="<?php echo JRoute::_('index.php?option=com_easysocial&view=profile&layout=edit&subview=settings'); ?>" class="form-horizontal" data-profile-fields-form autocomplete="off">
                <div class="edit-form">
                    <div class="tab-content profile-content">
                        <div class="step-content step-1 active" data-profile-edit-fields-content data-id="1">
                            <div data-element="header" class="container_HEADER div_header">
                                <legend>Account</legend>
                            </div>
                            <div data-profile-edit-fields-item data-element="<?php echo $switchprofile->element; ?>" data-id="<?php echo $switchprofile->id; ?>" class="container_<?php echo $switchprofile->unique_key; ?>" data-required="<?php echo $switchprofile->required; ?>" data-fieldname="<?php echo SOCIAL_FIELDS_PREFIX . $switchprofile->id; ?>">
                                <?php
                                if (!empty($switchprofile)) {
                                    ?>
                                    <?php
                                    $post = JRequest::get('post');
                                    $args = array(&$post, &$this->my, null);
                                    $fieldsO->trigger('onEdit', SOCIAL_FIELDS_GROUP_USER, $switchprofile, $args, $callback);
                                }
                                ?>
                            </div>
                            
                            <div data-profile-edit-fields-item data-element="<?php echo $fjemail->element; ?>" data-id="<?php echo $fjemail->id; ?>" class="container_<?php echo $fjemail->unique_key; ?>" data-required="<?php echo $fjemail->required; ?>" data-fieldname="<?php echo SOCIAL_FIELDS_PREFIX . $fjemail->id; ?>">
                                <?php
                                if (!empty($fjemail)) {
                                    ?>
                                    <label class="col-sm-12 control-label pl-0 pr-0" for="es-fields-156">

                                        Email:
                                    </label>
                                    <?php
                                    $post = JRequest::get('post');
                                    $args = array(&$post, &$this->my, null);
                                    $fieldsO->trigger('onEdit', SOCIAL_FIELDS_GROUP_USER, $fjemail, $args, $callback);
                                }
                                ?>
                            </div>

                        </div>
                        <div class="step-content step-2" data-profile-edit-fields-content data-id="2">
                            <div data-element="header" class="container_HEADER div_header">
                                <legend>Password</legend>
                            </div>
                            <div data-profile-edit-fields-item data-element="<?php echo $fjpass->element; ?>" data-id="<?php echo $fjpass->id; ?>" class="container_<?php echo $fjpass->unique_key; ?>" data-required="<?php echo $fjpass->required; ?>" data-fieldname="<?php echo SOCIAL_FIELDS_PREFIX . $fjpass->id; ?>">
                                <?php
                                if (!empty($fjpass)) {
                                    $post = JRequest::get('post');
                                    $args = array(&$post, &$this->my, null);
                                    $fieldsO->trigger('onEdit', SOCIAL_FIELDS_GROUP_USER, $fjpass, $args, $callback);
                                }
                                ?>
                            </div>
                        </div>

                        <div class="step-content step-3" data-profile-edit-fields-content data-id="3">
                            <div data-element="header" class="container_HEADER div_header">
                                <legend>Email Notifications</legend>
                            </div>
                            <div class="container_notifications">
                                <?php
                                foreach (array('system', 'others') as $group) {
                                    if (isset($alerts[$group])) {
                                        ?>
                                        <?php foreach ($alerts[$group] as $element => $alert) { ?>

                                            <?php $display = $i > 0 ? 'style="display: none;"' : ''; ?>
                                            <div class="mt-15 mb-15 mr-15 ml-15 notification-content-<?php echo $element; ?>" data-notification-content data-alert-element="<?php echo $element; ?>">
                                                <div class="h5 es-title-font"><?php echo $alert['title']; ?></div>
                                                <hr />
                                                <table width="100%">
                                                    <tr>
                                                        <td width="70%">&nbsp;</td>
                                                        <td width="5%">&nbsp;</td>
                                                        <td width="1%" style="text-align:center;"></td>
                                                        <td width="20%" style="text-align:center;"><?php echo JText::_('COM_EASYSOCIAL_PROFILE_NOTIFICATION_EMAIL'); ?></td>
                                                    </tr>
                                                    <?php foreach ($alert['data'] as $rule) { ?>
                                                        <tr>
                                                            <td><span class="fd-small"><?php echo $rule->getTitle(); ?></span></td>
                                                            <td>
                                                                <i class="fa fa-question-circle" <?php echo $this->html('bootstrap.popover', $rule->getTitle(), $rule->getDescription(), 'bottom'); ?>></i>
                                                            </td>
                                                            <td class="pa-5 text-center" style="visibility: hidden;width: 1px;display: inline-block;"><?php echo $rule->system >= 0 ? $this->html('grid.boolean', 'system[' . $rule->id . ']', $rule->system) : JText::_('COM_EASYSOCIAL_PROFILE_NOTIFICATION_NOT_APPLICABLE'); ?></td>
                                                            <td class="pa-5 text-center"><?php echo $rule->email >= 0 ? $this->html('grid.boolean', 'emailnotf[' . $rule->id . ']', $rule->email) : JText::_('COM_EASYSOCIAL_PROFILE_NOTIFICATION_NOT_APPLICABLE'); ?></td>
                                                        </tr>
                                                    <?php } ?>
                                                </table>
                                            </div>

                                        <?php } ?>
                                        <?php
                                    }
                                }
                                ?>
                            </div>
                        </div>
                        <div class="step-content step-5" data-profile-edit-fields-content data-id="5">
                            <div data-element="header" class="container_HEADER div_header">
                                <legend>Delete Account</legend>
                            </div>
                            <div class="container_delete_profile">
                                <b><?php echo JText::_('COM_EASYSOCIAL_DELETE_HEADING_TEXT'); ?></b>
                                <p><?php echo JText::_('COM_EASYSOCIAL_DELETE_ACCOUNT_MESSAGE'); ?></p>

                                <a href="javascript:void(0);" class="btn btn-sm btn-es-danger" data-profile-edit-delete><?php echo JText::_('COM_EASYSOCIAL_DELETE_YOUR_PROFILE_BUTTON'); ?></a>
                            </div>
                        </div>

                        <div class="step-content step-4" data-profile-edit-fields-content data-id="4">
                            <div data-element="header" class="container_HEADER div_header">
                                <legend>Privacy Settings</legend>
                            </div>
                            <div class="container_notifications">
                                <?php if ($privacy) { ?>
                                    <?php foreach ($privacy as $group => $items) { ?>
                                        <div class="privacy-contents privacy-content-<?php echo $group; ?>" data-privacy-content data-group="<?php echo $group; ?>">

                                            <div class="h4 es-title-font"><?php echo JText::_('COM_EASYSOCIAL_PRIVACY_GROUP_' . strtoupper($group)); ?></div>
                                            <hr />

                                            <p class="fd-small mb-20">
                                                <?php echo JText::_('COM_EASYSOCIAL_PRIVACY_GROUP_' . strtoupper($group) . '_DESC'); ?>
                                            </p>

                                            <?php foreach ($items as $item) { ?>
                                                <div class="form-group" data-privacy-item>

                                                    <i class="fa fa-question-circle pull-right" <?php echo $this->html('bootstrap.popover', $item->label, $item->tips, 'bottom'); ?>></i>

                                                    <label class="col-sm-3 control-label"><?php echo $item->label; ?></label>

                                                    <div class="col-sm-8">
                                                        <select autocomplete="off" class="form-control input-sm privacySelection" name="privacy[<?php echo $item->groupKey; ?>][<?php echo $item->rule; ?>]" data-privacy-select>
                                                            <?php foreach ($item->options as $option => $value) { ?>
                                                                <option value="<?php echo $option; ?>"<?php echo $value ? ' selected="selected"' : ''; ?>>
                                                                    <?php echo JText::_('COM_EASYSOCIAL_PRIVACY_OPTION_' . strtoupper($option)); ?>
                                                                </option>
                                                            <?php } ?>
                                                        </select>

                                                        <a href="javascript:void(0);" style="<?php echo!$item->hasCustom ? 'display:none;' : ''; ?>" data-privacy-custom-edit-button>
                                                            <i class="icon-es-settings"></i>
                                                        </a>

                                                        <div class="dropdown-menu dropdown-arrow-topleft privacy-custom-menu" style="display:none;" data-privacy-custom-form>
                                                            <div class="fd-small mb-10 row">
                                                                <div class="col-md-12">
                                                                    <?php echo JText::_('COM_EASYSOCIAL_PRIVACY_CUSTOM_DIALOG_NAME'); ?>
                                                                    <a href="javascript:void(0);" class="pull-right" data-privacy-custom-hide-button>
                                                                        <i class="fa fa-remove " title="<?php echo JText::_('COM_EASYSOCIAL_PRIVACY_CUSTOM_DIALOG_HIDE', true); ?>"></i>
                                                                    </a>
                                                                </div>
                                                            </div>
                                                            <div class="textboxlist" data-textfield>
                                                                <?php if ($item->hasCustom) { ?>
                                                                    <?php foreach ($item->customUsers as $friend) { ?>
                                                                        <div class="textboxlist-item" data-id="<?php echo $friend->id; ?>" data-title="<?php echo $friend->getName(); ?>" data-textboxlist-item>
                                                                            <span class="textboxlist-itemContent" data-textboxlist-itemContent><?php echo $friend->getName(); ?><input type="hidden" name="items" value="<?php echo $friend->id; ?>" /></span>
                                                                            <a class="textboxlist-itemRemoveButton" href="javascript: void(0);" data-textboxlist-itemRemoveButton></a>
                                                                        </div>
                                                                    <?php } ?>
                                                                <?php } ?>
                                                                <input type="text" class="textboxlist-textField" data-textboxlist-textField placeholder="<?php echo JText::_('COM_EASYSOCIAL_PRIVACY_CUSTOM_DIALOG_ENTER_NAME'); ?>" autocomplete="off" />
                                                            </div>
                                                        </div>

                                                        <input type="hidden" name="privacyID[<?php echo $item->groupKey; ?>][<?php echo $item->rule; ?>]" value="<?php echo $item->id . '_' . $item->mapid; ?>" />
                                                        <input type="hidden" name="privacyOld[<?php echo $item->groupKey; ?>][<?php echo $item->rule; ?>]" value="<?php echo $item->selected; ?>" />
                                                        <input type="hidden" data-hidden-custom name="privacyCustom[<?php echo $item->groupKey; ?>][<?php echo $item->rule; ?>]" value="<?php echo implode(',', $item->customIds); ?>" />
                                                    </div>
                                                </div>
                                            <?php } ?>
                                        </div>
                                    <?php } ?>
                                <?php } ?>

                                <div class="privacy-contents privacy-content-blocked" data-privacy-content data-group="blocked">
                                    <div class="h4 es-title-font"><?php echo JText::_('COM_EASYSOCIAL_MANAGE_BLOCKED_USERS'); ?></div>
                                    <hr />

                                    <?php if ($blocked) { ?>
                                        <div class="es-block-users">
                                            <ul class="es-item-grid es-item-grid_1col" data-users-listing>
                                                <?php foreach ($blocked as $block) { ?>
                                                    <li class="es-item" data-blocked-user-<?php echo $block->user->id; ?> >
                                                        <div class="es-avatar-wrap pull-left">
                                                            <a href="<?php echo $block->user->getPermalink(); ?>" class="es-avatar pull-left">
                                                                <img src="<?php echo $block->user->getAvatar(SOCIAL_AVATAR_MEDIUM); ?>" alt="<?php echo $this->html('string.escape', $block->user->getName()); ?>" />
                                                            </a>

                                                            <?php echo $this->loadTemplate('site/utilities/user.online.state', array('online' => $block->user->isOnline(), 'size' => 'small')); ?>
                                                        </div>

                                                        <div class="es-item-body">
                                                            <div class="es-item-detail">
                                                                <div class="es-item-title">
                                                                    <a href="<?php echo $block->user->getPermalink(); ?>"><?php echo $block->user->getName(); ?></a>
                                                                </div>

                                                                <div class="es-block-reason">
                                                                    <?php echo $block->reason; ?>
                                                                </div>

                                                                <div class="user-actions">
                                                                    <?php echo FD::blocks()->getForm($block->user->id); ?>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </li>
                                                <?php } ?>
                                            </ul>
                                        </div>
                                    <?php } else { ?>
                                        <div class="is-empty">
                                            <div class="empty center">
                                                <i class="fa fa-users"></i>
                                                <?php echo JText::_('COM_EASYSOCIAL_PRIVACY_BLOCKED_NO_USERS_CURRENTLY'); ?>
                                            </div>
                                        </div>
                                    <?php } ?>
                                </div>
                                <div class="ml-20 fd-small" data-form-actions>
                                    <div class="checkbox">
                                        <label class="fd-small">
                                            <input type="checkbox" value="1" name="privacyReset" /> <?php echo JText::_('COM_EASYSOCIAL_PRIVACY_RESET_DESCRIPTION'); ?>
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
                <div class="form-actions">

                    <?php if ($this->my->hasCommunityAccess()) { ?>
                        <div class="pull-left">
                            <a href="<?php echo $this->my->getPermalink(); ?>" class="btn btn-sm btn-es-danger"><?php echo JText::_('COM_EASYSOCIAL_CANCEL_BUTTON'); ?></a>
                        </div>
                    <?php } ?>

                    <div class="pull-right">
                        <button type="button" class="btn btn-sm btn-es-primary" data-profile-fields-settings-save><?php echo JText::_('COM_EASYSOCIAL_SAVE_BUTTON'); ?></button>

                        <?php if ($this->my->hasCommunityAccess()) { ?>
                            <button type="button" class="btn btn-sm btn-es-primary save-next" data-profile-fields-save-settings><?php echo JText::_('COM_EASYSOCIAL_SAVE_AND_NEXT_BUTTON'); ?></button>

                            <button type="button" class="btn btn-sm btn-es-primary save-exit" style="display:none" data-profile-fields-save-settings><?php echo JText::_('COM_EASYSOCIAL_SAVE_AND_EXIT_BUTTON'); ?></button>
                        <?php } ?>
                    </div>
                </div>

                <input type="hidden" name="Itemid" value="<?php echo JRequest::getVar('Itemid'); ?>" />
                <input type="hidden" name="option" value="com_easysocial" />
                <input type="hidden" name="controller" value="profilenew" />
                <input type="hidden" name="task" value="saveSettings" />
                <input type="hidden" name="setting_save" value="" />
                <input type="hidden" name="all_steps" id="all_steps_input" value="5" />
                <input type="hidden" name="current_step" id="current_step_input" value="1" />
                <input type="hidden" name="<?php echo FD::token(); ?>" value="1" />
            </form>
        </div>

        <?php echo $this->render('module', 'es-profile-edit-after-contents'); ?>
    </div>
</div>
