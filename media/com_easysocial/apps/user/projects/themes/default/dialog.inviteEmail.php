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
?>
<dialog>
    <width>400</width>
    <height>200</height>
    <selectors type="json">
        {
            "{closeButton}" : "[data-close-button]",
            "{sendInvite}"  : "[data-invite-button]",
            "{errorMessage}": "[data-error-message]",
            "{emailField}"  : "[data-invite-emails]",
            "{inviteMessage}"  : "[data-invite-message]"
        }
    </selectors>
    <bindings type="javascript">
        {
            init: function()
            {
                
            },
            "{closeButton} click": function()
            {
                this.parent.close();
            },
            "{sendInvite} click" : function()
            {
                this.errorMessage().hide();
    
                EasySocial.dialog({
                    content: '<div class="fd-loading"><span><?php echo JText::_('COM_EASYSOCIAL_LOADING'); ?></span></div>'
                });
    
                EasySocial.ajax('apps/user/projects/controllers/projects/inviteEmail', {
                    id: <?php echo $project->id; ?>,
                    emails: this.emailField().val(),
                    message: this.inviteMessage().val()
                }).done(function(content) {
                    EasySocial.dialog({
                        content: content
                    });
    
                    setTimeout(function() {
                        EasySocial.dialog().close();
                    }, 2000);
                 }).fail(function(error) {
                    EasySocial.dialog({
                        content: error.message
                    });
                 });
            }
        }
    </bindings>
    <title><?php echo JText::sprintf('COM_EASYSOCIAL_PROJECT_DIALOG_INVITE_TO_PROJECT_TITLE', $project->getTitle()); ?></title>
    <content>
        <p class="mt-5">
            <?php echo JText::_('COM_EASYSOCIAL_FRIENDS_INVITE_EMAIL_ADDRESSES'); ?>
        </p>
        <p class="mt-5">
            <textarea data-invite-emails class="form-control input-sm" name="emails" placeholder="john@email.com"></textarea>
        </p>
        <p class="mt-5">
            <span class="help-block small mt-5">
                <strong><?php echo JText::_('COM_EASYSOCIAL_NOTE'); ?>:</strong> <?php echo JText::_('COM_EASYSOCIAL_PROJECT_INVITE_EMAIL_ADDRESSES_NOTE'); ?>
            </span>
        </p>
        <div>
            <textarea data-invite-message class="form-control input-sm" rows="5" name="email_message" ><?php echo JText::_('COM_EASYSOCIAL_FRIENDS_INVITE_MESSAGE_CONTENT'); ?></textarea>
        </div>
    </content>
    <buttons>
        <button data-close-button type="button" class="btn btn-sm btn-es"><?php echo JText::_('COM_EASYSOCIAL_CLOSE_BUTTON'); ?></button>
        <button data-invite-button type="button" class="btn btn-sm btn-es-primary"><?php echo JText::_('COM_EASYSOCIAL_SEND_INVITATIONS_BUTTON'); ?></button>
    </buttons>
</dialog>
