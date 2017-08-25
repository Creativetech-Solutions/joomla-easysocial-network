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
	.script("albums/browser")
	.done(function($){

		$("[data-album-browser=<?php echo $uuid; ?>]")
			.addController(
				"EasySocial.Controller.Albums.Browser",
				{
					uid		: "<?php echo $lib->uid;?>",
					type	: "<?php echo $lib->type; ?>"
				});
	});
	
EasySocial.require()
	.script( 'avatar' , 'cover' )
	.done(function($)
	{
		$( '[data-profile-avatar]' ).implement( EasySocial.Controller.Avatar ,
			{
				"uid"	: "<?php echo $user->id;?>",
				"type"	: "<?php echo SOCIAL_TYPE_USER;?>",
				"redirectUrl" : "<?php echo $user->getPermalink( false );?>"
			}
		);

		$( '[data-profile-cover]' ).implement( EasySocial.Controller.Cover , 
			{
				"uid"	: "<?php echo $user->id;?>",
				"type"	: "<?php echo SOCIAL_TYPE_USER;?>"
			}
		);
	});
