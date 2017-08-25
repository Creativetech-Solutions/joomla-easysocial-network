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
if($displayOn == 'friends'){
?>
<div class="es-widget">
	<div class="es-widget-body<?php echo !$friendsData ? ' is-empty' : '';?>">
		<?php if( $friendsData ){ ?>
		<ul class="widget-list-grid">
			<?php foreach( $friendsData as $friend ){ ?>
			<li>
				<div class="es-avatar-wrap">
					<a href="<?php echo $friend->getPermalink();?>"
						class="es-avatar es-avatar-sm "
						data-popbox="module://easysocial/profile/popbox"
						data-user-id="<?php echo $friend->id;?>"
					>
						<img alt="<?php echo $this->html( 'string.escape' , $friend->getName() );?>" src="<?php echo $friend->getAvatar();?>" />
					</a>
				</div>
			</li>
			<?php } ?>
		</ul>
		<?php } else { ?>
		<div class="fd-small empty">
			<?php echo JText::_( 'APP_FRIENDS_NO_FRIENDS_CURRENTLY' ); ?>
		</div>
		<?php } ?>

		<?php if( $friendsTotal > 0 ){ ?>
		<div class="all-connections">
			<a href="<?php echo FRoute::friends( array( 'userid' => $this->my->getAlias() ) );?>" class="fd-small"><?php echo JText::_( 'APP_FRIENDS_VIEW_ALL' );?></a>
		</div>
		<?php } ?>
	</div>
</div>
<?php
}else{
?>
<div class="es-widget es-widget-borderless">
	<?php if( $module->showtitle ){ ?>
	<div class="es-widget-head">
		<?php echo JText::_( $module->title ); ?>
	</div>
	<?php } ?>

	<div class="es-widget-body pl-0 pl-5 pr-5">
		<?php echo $contents; ?>
	</div>
</div>
<?php
}
?>