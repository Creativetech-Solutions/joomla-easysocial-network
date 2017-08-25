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
$uid = $this->input->get('uid', $this->my->id, 'int');
$user = ES::user($uid);
?>
<div data-album-item="<?php echo $album->uuid(); ?>" data-album-id="<?php echo $album->id; ?>" data-album-nextstart="<?php echo isset($nextStart) ? $nextStart : '-1' ; ?>" data-album-layout="<?php echo $options['layout']; ?>" data-album-uid="<?php echo $lib->uid;?>" data-album-type="<?php echo $lib->type;?>" class="es-album-item  es-media-group <?php echo (empty($photos)) ? '' : 'has-photos'; ?> <?php echo 'layout-' . $options['layout']; ?>">

	<div data-album-header class="es-media-header es-album-header album-detail-page">

		<?php if ($options['showToolbar']) { ?>
		<?php } ?>

		<?php echo $this->render( 'module' , 'es-albums-before-info' ); ?>

		<?php if ($options['showInfo']) { ?>
			<?php echo $this->includeTemplate('site/albums/info'); ?>
		<?php } ?>

	</div>
    <?php if ($options['layout'] != 'dialog') { ?>
    <div class="es-album-manage row-table mb-15 mt-15">
        <div class="col-xs-3 col-sm-3 col-md-3">
            <?php /*?><a href="<?php echo $lib->getAlbumLink(); ?>">&larr; <?php echo JText::_('COM_EASYSOCIAL_PHOTOS_BACK_TO_ALBUM'); ?></a><?php */?>
            <a class="album-back-link" href="<?php echo FRoute::albums(array('uid' => $user->getAlias(), 'type' => SOCIAL_TYPE_USER)) ?>">&larr; <?php echo JText::_('COM_EASYSOCIAL_PHOTOS_BACK_TO_PHOTOS'); ?></a>
        </div>
        <?php if( $lib->editable() ){ ?>
        <div class="col-xs-3 col-sm-3 col-md-3 pull-right">
            <?php echo $this->includeTemplate('site/albums/menu'); ?>
        </div>
        <?php } ?>	
    </div>
	<?php } ?>
	<div data-album-content class="es-album-content mt-20" data-es-photo-group="album:<?php echo $album->id; ?>">
		<?php echo $this->render( 'module' , 'es-albums-before-photos' ); ?>
		<?php if ($options['showPhotos']) { ?>
		<?php echo $this->includeTemplate('site/albums/photos'); ?>
		<?php } ?>
		<?php echo $this->render( 'module' , 'es-albums-after-photos' ); ?>
	</div>

	<?php if( $options[ 'view' ] != 'all' ){ ?>
	<div data-album-footer class="es-album-footer row">
		<?php if ($options['showStats']) { ?>
			<?php echo $this->includeTemplate('site/albums/stats'); ?>
		<?php } ?>

		<div class="es-album-interaction">

			<div class="es-album-showresponse col-md-8">
			<?php if ($options['showResponse']) { ?>
				<?php echo $this->includeTemplate('site/albums/response'); ?>
			<?php } ?>
			</div>

			<?php if($options['showTags']){ ?>
			<div class="es-album-showtag col-md-4">
				<?php echo $this->includeTemplate('site/albums/taglist'); ?>
			</div>
			<?php } ?>
		</div>
	</div>
	<?php } ?>

	<div class="es-media-loader"></div>
</div>

