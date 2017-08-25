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
<div data-photo-item="<?php echo $photo->uuid(); ?>"
	 data-photo-id="<?php echo $photo->id; ?>"
	 data-es-photo="<?php echo $photo->id; ?>"
     class="layout-<?php echo $options['layout'];?> es-media-item es-photo-item"
     data-es-photo-disabled="<?php echo $options['openInPopup'] ? 0 : 1; ?>">

	<div>
		<div>
			<div data-photo-header class="es-media-header es-photo-header">
				<?php if ($options['showToolbar']) { ?>
				<div class="media">
					<div class="media-object pull-left">
						<div class="es-avatar">
							<img src="<?php echo $photo->getCreator()->getAvatar(); ?>" />
						</div>
					</div>
					<div class="media-body">
						<div data-photo-owner class="es-photo-owner"><a href="<?php echo $photo->getCreator()->getPermalink(); ?>"><?php echo $photo->getCreator()->getName(); ?></a></div>
						<div data-photo-album class="es-photo-album"><?php echo JText::_("COM_EASYSOCIAL_PHOTOS_FROM_ALBUM"); ?> <a href="<?php echo $album->getPermalink(); ?>"><?php echo $album->get( 'title' ); ?></a></div>
					</div>
				</div>
				<?php } ?>
				<?php echo $this->render( 'module' , 'es-photos-before-info'); ?>
			</div>

			<div data-photo-content class="es-photo-content">
				<?php echo $this->render('module', 'es-photos-before-photo'); ?>
				<div class="es-photo <?php echo $options['resizeUsingCss'] ? 'css-resizing' : ''; ?>">
					<a 
					   href="<?php echo $photo->getPermalink();?>"
					   title="<?php echo $this->html('string.escape', $photo->title . (($photo->caption!=='') ? ' - ' . $photo->caption : '')); ?>">
						<u data-photo-viewport>
							<b data-mode="<?php echo $options['resizeMode']; ?>"
							   data-threshold="<?php echo $options['resizeThreshold'] ?>">
								<img data-photo-image
									 src="<?php echo $photo->getSource($options['size']); ?>"
									 data-thumbnail-src="<?php echo $photo->getSource('thumbnail'); ?>"
									 data-featured-src="<?php echo $photo->getSource('featured'); ?>"
									 data-large-src="<?php echo $photo->getSource('large'); ?>"
									 data-width="<?php echo $photo->getWidth(); ?>"
									 data-height="<?php echo $photo->getHeight(); ?>"
									 onload="window.ESImage ? ESImage(this) : (window.ESImageList || (window.ESImageList=[])).push(this);" />
							</b>
							<em class="es-photo-image" style="background-image: url('<?php echo $photo->getSource($options['size']); ?>');" data-photo-image-css></em>
						</u>
					</a>
				</div>
				<i class="loading-indicator fd-small"></i>
				<?php echo $this->render('module', 'es-photos-after-photo'); ?>
			</div>
			<div class="es-media-loader"></div>
		</div>
	</div>
</div>
