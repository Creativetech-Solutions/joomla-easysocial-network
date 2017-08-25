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
<?php if ($this->template->get('registration_progress')) { ?>
    <?php echo $this->includeTemplate('site/registration/default.progress', array('currentStep' => $currentStep, 'totalSteps' => $totalSteps, 'steps' => $steps, 'registration' => $registration)); ?>
<?php } ?>

<div class="dowalo-header">
    <img class="" src="<?php echo $profile->getAvatar(SOCIAL_AVATAR_MEDIUM); ?>" title="<?php echo $this->html('string.escape', $profile->getTitle()); ?>" />
    <div class="heading<?php echo ' '.strtolower($profile->getTitle());?>"><?php echo $profile->get('title'). ' Sign Up'; ?></div>
</div>

<form class="form-horizontal has-privacy reg-steps-<?php echo JRequest::getVar('step') ?>" enctype="multipart/form-data" method="post" action="<?php echo JRoute::_('index.php'); ?>" id="registrationForm" data-registration-form>
    <?php
    if ($this->config->get('oauth.facebook.registration.enabled') && $this->config->get('registrations.enabled') && (
            ($this->config->get('oauth.facebook.secret') && $this->config->get('oauth.facebook.app')) || ($this->config->get('oauth.facebook.jfbconnect.enabled'))
            ) && ((JRequest::getVar('step') <= 1 && strtolower($profile->getTitle()) != 'creative') || (JRequest::getVar('step') <= 1 && strtolower($profile->getTitle()) != 'business') || strtolower($profile->getTitle()) == 'fan')
    ) {
        ?>
        <div class="text-center es-signin-social es-signup-social">
           <?php 
            $facebook = FD::oauth('Facebook');
            echo $facebook->getLoginButton(FRoute::registration(array('layout' => 'oauthDialog', 'client' => 'facebook', 'external' => true), false)); ?>
            <?php /*?><div class="or_spacer">
                <strong><?php echo JText::_('COM_EASYSOCIAL_CUSTOM_OR'); ?></strong>
            </div><?php */?>
        </div>
    <?php } ?>
    <!-- Custom fields -->
    <?php if ($fields) { ?>
        <?php foreach ($fields as $field) { ?>
            <?php echo $this->loadTemplate('site/registration/steps.field', array('field' => $field, 'errors' => $errors)); ?>
        <?php } ?>
    <?php } ?>

    <!--	<div class="form-group">
                    <div class="col-sm-8 col-sm-offset-3 fd-small">
    <?php echo JText::_('COM_EASYSOCIAL_REGISTRATIONS_REQUIRED'); ?>
                    </div>
            </div>-->

    <!-- Actions -->
    <div class="clearfix">
        <?php if ($currentStep != 1) { ?>
            <?php /* ?><button class="btn btn-es btn-medium pull-left" data-registration-previous><?php echo JText::_('COM_EASYSOCIAL_PREVIOUS_BUTTON'); ?></button><?php */ ?>
            <br />
        <?php } ?>
        <button class="btn btn-medium btn-black" data-registration-submit><?php echo $currentIndex === $totalSteps || $totalSteps < 2 ? JText::_('COM_EASYSOCIAL_CONTINUE_BUTTON') : JText::_('COM_EASYSOCIAL_CONTINUE_BUTTON'); ?></button>
    </div>

    <?php echo JHTML::_('form.token'); ?>
    <input type="hidden" name="currentStep" value="<?php echo $currentIndex; ?>" />
    <input type="hidden" name="controller" value="registration" />
    <input type="hidden" name="task" value="saveStep" />
    <input type="hidden" name="option" value="com_easysocial" />
    <input type="hidden" name="profileId" value="<?php echo $profile->id; ?>" />
</form>
