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
<div class="es-registration">
    <div class="text-center">
        <h1><?php echo JText::_('COM_EASYSOCIAL_CUSTOM_REGISTER_HEADER'); ?></h1>
        <div class="spacer50"></div>
        <div class="alreadymember"><?php echo JText::_('COM_EASYSOCIAL_CUSTOM_REGISTER_ALRAEDY_MEMBER'); ?> <a href="<?=JRoute::_("index.php?option=com_easysocial&view=login")?>" data-es-provide="tooltip" data-original-title="<?= JText::_("COM_EASYSOCIAL_TOOLBAR_SIGN_IN") ?>" data-placement="top" class="dropdown-toggle_ loginLink"><?= JText::_("COM_EASYSOCIAL_TOOLBAR_SIGN_IN") ?></a></div>
    </div>
    <div class="spacer70"></div>


    <!-- Profiles listing -->
    <?php if ($profiles) { ?>
        <div class="row">
            <div class="profile-row clearfix">
                <?php foreach ($profiles as $profile) { ?>
                    <?php echo $this->loadTemplate('site/registration/default.profiles', array('profile' => $profile)); ?>
                <?php } ?>    
            </div>
        </div>

    <?php } else { ?>
        <div>
            <?php echo JText::_('COM_EASYSOCIAL_REGISTRATIONS_NO_PROFILES_CREATED_YET'); ?>
        </div>
    <?php } ?>
</div>
