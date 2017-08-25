<?php if(isset($audio)):?>
EasySocial.require()
.script('<?php echo rtrim( JURI::root() , ' / ' );?>/media/com_easysocial/apps/user/audios/assets/js/cropper.min.js')
.script('<?php echo rtrim( JURI::root() , ' / ' );?>/media/com_easysocial/apps/user/audios/themes/default/canvas/cropper.js')
.script('<?php echo rtrim( JURI::root() , ' / ' );?>/media/com_easysocial/apps/user/audios/themes/default/canvas/editAudio.js')
.done(function($)
{
});

<?php 
else:
?>
EasySocial.require()
.script('<?php echo rtrim( JURI::root() , ' / ' );?>/media/com_easysocial/apps/user/audios/assets/js/cropper.min.js')
.script('<?php echo rtrim( JURI::root() , ' / ' );?>/media/com_easysocial/apps/user/audios/themes/default/canvas/cropper.js')
.script('<?php echo rtrim( JURI::root() , ' / ' );?>/media/com_easysocial/apps/user/audios/themes/default/canvas/newAudio.js')
.script('<?php echo rtrim( JURI::root() , ' / ' );?>/media/com_easysocial/apps/user/audios/themes/default/canvas/editAudio.js')
.done(function($)
{
});

<?php 
endif;
?>
