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
<div class="stream-audios">
	<h4 class="es-stream-content-title">
		<a href="<?php echo FRoute::apps( array( 'layout' => 'canvas' , 'id' => $audio->getAppId(), 'cid' => $audio->id , 'userid' => $actor->getAlias() ) );?>"><b><?php echo $audio->get( 'title' );?></b></a>
	</h4>

	<p class="audio-content">
		<?php if( $params->get( 'stream_truncate' , true ) ){ ?>
			<?php echo $this->html( 'string.truncater' , $audio->get( 'content' ) , $params->get( 'stream_truncate_length' , 250 ) ); ?>
		<?php } else { ?>
			<?php echo $audio->getContent(); ?>
		<?php } ?>
	</p>
</div>
