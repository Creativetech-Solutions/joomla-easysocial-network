<?php
/**
* @package		EasySocial
* @copyright	Copyright (C) 2010 - 2015 Stack Ideas Sdn Bhd. All rights reserved.
* @license		GNU/GPL, see LICENSE.php
* EasySocial is free software. This version may have been modified pursuant
* to the GNU General Public License, and as distributed it includes or
* is derivative of works licensed under the GNU General Public License or
* other free or open source software licenses.
* See COPYRIGHT.php for copyright notices and details.
*/
defined( '_JEXEC' ) or die( 'Unauthorized Access' );
$myvideso = array();
$videos = array_merge($featuredVideos,$videos);
?>
<?php if ($videos) {
	foreach ($videos as $video) { 
		array_push($myvideso,$video->id);
	}
}
// Retrieve user's photos
$videosModel = FD::model('Videos');
$othervideos = $videosModel->getVideos(array('limit' => 5,'type' => SOCIAL_TYPE_USER, 'exclusion' => $myvideso));
?>
<div class="es-snackbar">
	<?php echo 'More from other users';?>
</div>
<div class="es-video-list clearfix<?php echo !$videos ? ' is-empty' : '';?>">
	<?php if ($othervideos) { ?>
        <?php foreach ($othervideos as $video) { ?>
        <div class="es-video-item" data-video-item
            data-id="<?php echo $video->id;?>"
        >                    
            <?php if ($video->table->isFeatured()) { ?>
            <div class="es-video-featured-label">
                <span><?php echo JText::_('COM_EASYSOCIAL_FEATURED');?></span>
            </div>
            <?php } ?>

            <div class="es-video-thumbnail">
                <a href="<?php echo $video->getPermalink();?>">
                    <div class="es-video-cover" style="background-image: url('<?php echo $video->getThumbnail();?>')"></div>
                    <div class="es-video-time"><?php echo $video->getDuration();?></div>
                </a>
            </div>
        </div>
        <?php } ?>
    <?php } ?>
</div>