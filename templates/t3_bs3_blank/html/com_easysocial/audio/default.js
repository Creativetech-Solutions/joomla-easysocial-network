<?php
/**
* @package 		EasySocial
* @copyright	Copyright (C) 2010 - 2014 Stack Ideas Sdn Bhd. All rights reserved.
* @license 		Proprietary Use License http://stackideas.com/licensing.html
* @author 		Stack Ideas Sdn Bhd
*/
defined( '_JEXEC' ) or die( 'Unauthorized Access' );
$user = ES::user($uid);
?>
EasySocial.require()
	.script( 'avatar' , 'cover' )
	.done(function($)
	{
		$( '[data-profile-avatar]' ).implement( EasySocial.Controller.Avatar ,
			{
				"uid"	: "<?php echo $uid;?>",
				"type"	: "<?php echo $type;?>",
				"redirectUrl" : "<?php echo $user->getPermalink( false );?>"
			}
		);

		$( '[data-profile-cover]' ).implement( EasySocial.Controller.Cover , 
			{
				"uid"	: "<?php echo $uid; ?>",
				"type"	: "<?php echo $type;?>"
			}
		);
	});