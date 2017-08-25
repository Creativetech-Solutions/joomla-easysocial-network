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
<div class="app-audios" data-profile-user-apps-audios data-app-id="<?php echo $app->id;?>">

	<div class="app-contents<?php echo !$audios ? ' is-empty' : '';?>">
		<ul class="list-unstyled audios-list" data-article-lists>
			<?php if( $audios ){ ?>
				<?php foreach( $audios as $audio ){ ?>
					<?php echo $this->loadTemplate( 'apps/user/audios/profile/item' , array( 'audio' => $audio , 'user' => $user , 'appId' => $app->id ) ); ?>
				<?php } ?>
			<?php } ?>
		</ul>

		<div class="empty">
			<i class="fa fa-info-circle"></i>
			<?php echo JText::sprintf('APP_AUDIOS_EMPTY_AUDIOS_PROFILE', $user->getName()); ?>
		</div>
	</div>

</div>
