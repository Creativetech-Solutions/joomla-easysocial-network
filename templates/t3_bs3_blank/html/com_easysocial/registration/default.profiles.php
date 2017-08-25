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

$reglink = FRoute::registration(array('controller' => 'registration', 'task' => 'selectType', 'profile_id' => $profile->id)); 
?>

<div class="col-xs-12 col-sm-4 <?=$profile->get('alias')?>">
    <div class="image">
        <?php if ($this->template->get('registration_profile_avatar')) { ?>
                <a href="<?php echo $reglink ?>">
                    <img class="" src="<?php echo $profile->getAvatar(SOCIAL_AVATAR_LARGE); ?>" title="<?php echo $this->html('string.escape', $profile->getTitle()); ?>" />
                </a>
        <?php } ?>
    </div>
    <div class="heading"><?php echo JText::sprintf("COM_EASYSOCIAL_CUSTOM_REGISTER_PROFILE_TITLE",$profile->get('title')); ?></div>
    <div class="description"><?php if ($this->template->get('registration_profile_desc')) { ?>
            <?php echo $profile->get('description'); ?>
        <?php } ?>
    </div>
    <div class="spacer20"></div>
    <div><a class="btn btn-grad" href="<?=$reglink?>"><?=JText::_('COM_EASYSOCIAL_CUSTOM_JOIN_FREE')?></a></div>
</div>
