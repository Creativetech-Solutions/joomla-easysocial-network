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
<div class="es-video-list clearfix<?php echo !$videos ? ' is-empty' : '';?>">
    <?php if ($videos) { ?>
        <?php foreach ($videos as $video) { 
		?>
        <div class="es-video-item col-md-4 col-sm-6" data-video-item
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
            <div class="es-video-content">
                <div class="es-video-title">
                    <a href="<?php echo $video->getPermalink();?>"><?php echo $video->getTitle();?></a>
                </div>
				<?php
				echo FD::date($video->getCreatedDate())->toLapsed();
				?>
            </div>
    
        </div>
        <?php } ?>

    <?php } else { ?>
    <div class="empty empty-hero">
        <i class="fa fa-film"></i>
        <div><?php echo JText::_('COM_EASYSOCIAL_VIDEOS_NO_VIDEOS_AVAILABLE_CURRENTLY');?></div>
    </div>
    <?php } ?>
</div>
