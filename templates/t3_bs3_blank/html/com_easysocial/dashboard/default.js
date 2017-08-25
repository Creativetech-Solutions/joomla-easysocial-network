<?php
/**
* @package 		EasySocial
* @copyright	Copyright (C) 2010 - 2014 Stack Ideas Sdn Bhd. All rights reserved.
* @license 		Proprietary Use License http://stackideas.com/licensing.html
* @author 		Stack Ideas Sdn Bhd
*/
defined( '_JEXEC' ) or die( 'Unauthorized Access' );
?>
EasySocial.require()
.script( 'site/dashboard/dashboard','site/profile/subscriptions',
        'site/profile/friends', 'customEMod',
	'site/conversations/composer' )
.done(function($){

	$( '[data-dashboard]' ).implement( EasySocial.Controller.Dashboard ,
	{
		<?php if( JFactory::getApplication()->getMenu()->getActive() ){ ?>
			pageTitle 	: "<?php echo JFactory::getApplication()->getMenu()->getActive()->params->get( 'page_title' );?>"
		<?php } ?>
	});
        
        // Apply conversation controller
	$( '[data-profile-conversation]' ).implement( EasySocial.Controller.Conversations.Composer.Dialog ,
	{
		"recipient" :
		{
			"id"	: "<?php echo $user->id;?>",
			"name"	: "<?php echo $this->html( 'string.escape', $user->getName() );?>",
			"avatar": "<?php echo $user->getAvatar();?>"
		}
	});
        
        // Apply follow / unfollow controller
	$( '[data-profile-followers]' ).implement( EasySocial.Controller.Profile.Subscriptions );
        // Apply friend / un-friend controller
	$( '[data-profile-friends]' ).implement( EasySocial.Controller.Profile.Friends.Request );

    $( '[data-friends-block-user],[data-friends-report-user]' ).implement( EasySocial.Controller.CustomEMod );
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
