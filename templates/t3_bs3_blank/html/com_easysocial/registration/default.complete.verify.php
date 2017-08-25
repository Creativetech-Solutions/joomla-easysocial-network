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
defined( '_JEXEC' ) or die( 'Unauthorized Access' );
?>
<div class="es-complete-wrap mt-20">

	<span class="es-avatar es-avatar-md es-avatar-rounded">
		<img src="<?php echo JURI::base(); ?>images/template/email-sent.png" />
	</span>
    <p>
    	<?php echo JText::_( 'COM_EASYSOCIAL_REGISTRATION_LINK_SENT' );?>
    </p>
    <p style="text-align:center">
    	<?php if ($user) { ?>
    		<b><?php echo $user->email; ?></b>
        <?php } ?>
    </p>

	<?php if ($user) { ?>
		<p><?php echo JText::_('COM_EASYSOCIAL_REGISTRATION_COMPLETED_SENT_ACTIVATION_CONTENT_A');?></p>
        <p><?php echo JText::_('COM_EASYSOCIAL_REGISTRATION_COMPLETED_SENT_ACTIVATION_CONTENT_B');?></p>
	<?php } else { ?>
		<p><?php echo JText::_('COM_EASYSOCIAL_REGISTRATION_ACTIVATION_PLEASE_PROVIDE_ACTIVATION_CODE'); ?></p>
	<?php } ?>
</div>
