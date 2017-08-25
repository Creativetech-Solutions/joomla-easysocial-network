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
<li class="audio-item" data-apps-audios-item data-id="<?php echo $audio->id;?>">

	<div class="clearfix">
		<h4 class="pull-left">
			<a href="<?php echo FRoute::apps( array( 'layout' => 'canvas' , 'id' => $app->alias , 'cid' => $audio->id , 'uid' => $user->getAlias() , 'type' => SOCIAL_TYPE_USER ) );?>" class="audio-title"><?php echo $audio->title; ?></a>
		</h4>
		<div class="pull-right btn-group">
			<a href="javascript:void(0);" data-bs-toggle="dropdown" class="dropdown-toggle_ loginLink btn btn-dropdown">
				<i class="icon-es-dropdown"></i>
			</a>

			<ul class="dropdown-menu dropdown-menu-user messageDropDown">
				<li>
					<a href="javascript:void(0);" data-apps-audios-edit>
						<?php echo JText::_( 'APP_AUDIOS_EDIT_BUTTON' );?>
					</a>
				</li>
				<li data-friends-unfriend="">
					<a href="javascript:void(0);" data-apps-audios-delete data-id="<?php echo $audio->id;?>">
						<?php echo JText::_( 'APP_AUDIOS_DELETE_BUTTON' );?>
					</a>
				</li>
			</ul>
		</div>
	</div>

	<hr />

	<div class="muted small audio-date">
		<time datetime="<?php echo $this->html( 'string.date' , $audio->created ); ?>" class="audio-date">
			<i class="fa fa-calendar"></i>&nbsp; <?php echo $this->html( 'string.date' , $audio->created , JText::_( 'DATE_FORMAT_LC1' ) ); ?>
		</time>
	</div>
</li>
