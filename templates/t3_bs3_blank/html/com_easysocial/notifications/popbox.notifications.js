EasySocial.require().script('site/toolbar/system').done(function($) {
	$('[data-notificationsystem]').addController(EasySocial.Controller.Notifications.System.Popbox);
});

EasySocial.require()
.script('<?php echo rtrim( JURI::root() , ' / ' );?>/media/com_easysocial/apps/user/projects/assets/js/projects.js')
.done(function ($)
{
        console.log('project js loaded');
        $('[data-popbox-project-item]').addController('EasySocial.Controller.Canvas.User.Apps.Projects.Notifications.Item', {
        });
});
