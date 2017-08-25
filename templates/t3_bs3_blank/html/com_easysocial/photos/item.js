<?php
/**
* @package 		EasySocial
* @copyright	Copyright (C) 2010 - 2014 Stack Ideas Sdn Bhd. All rights reserved.
* @license 		Proprietary Use License http://stackideas.com/licensing.html
* @author 		Stack Ideas Sdn Bhd
*/
defined( '_JEXEC' ) or die( 'Unauthorized Access' );

$tags = $photo->getTags(true);
$tagItemList = array();

if ($tags) {
	foreach($tags as $tag) {
		$tagItemList[] = $tag->uid;
	}
}
?>

EasySocial.require()
	.script("photos/item")
	.done(function($){

		$("[data-photo-item=<?php echo $photo->uuid(); ?>]")
			.addController(
				"EasySocial.Controller.Photos.Item",
				{
					<?php if ($options['showForm']) { ?>
					editable: <?php echo ($album->editable()) ? 1 : 0; ?>,
					taggable: <?php echo ($photo->taggable()) ? 1 : 0; ?>,
					<?php } ?>
					<?php if ($options['showNavigation']) { ?>
					navigation: true
					<?php } ?>
				});
	});

EasySocial.require()
.script('photos/form')
.done(function($) {

    $('[data-photo-form]').implement(EasySocial.Controller.Photos.Form, {

    <?php if ($tagItemList) { ?>
    "tagsExclusion": <?php echo FD::json()->encode($tagItemList); ?>
    <?php } ?>

    });
});