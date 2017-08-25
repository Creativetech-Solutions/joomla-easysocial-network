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
defined( '_JEXEC' ) or die( 'Unauthorized Access' );
?>
<?php if ($this->my->isSiteAdmin() && $user->isBlock()) { ?>
<div class="es-user-banned alert alert-info">
    <?php echo JText::_('COM_EASYSOCIAL_PROFILE_USER_IS_BANNED');?>
</div>
<?php } ?>

<?php echo $this->output('site/dashboard/profile-header'); ?>

