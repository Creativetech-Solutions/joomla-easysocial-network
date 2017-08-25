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
<div class="es-album-form">
	<div class="es-album-form-fields <?php echo ($album->core) ? 'core-album' : ''; ?>">

		<input
			data-album-title-field
			class="es-album-title-field"
			type="text"
			value="<?php echo $this->html('string.escape', $album->get('title')); ?>"
			placeholder="<?php echo JText::_("COM_EASYSOCIAL_ALBUMS_ENTER_ALBUM_TITLE"); ?>"
			autocomplete="off"
			<?php echo ($album->core) ? 'readonly' : ''; ?>
		/>
		<textarea
			data-album-caption-field
			class="es-album-caption-field"
			placeholder="<?php echo JText::_("COM_EASYSOCIAL_ALBUMS_ENTER_ALBUM_DESCRIPTION"); ?>"
			<?php echo ($album->core) ? 'readonly' : ''; ?>
		><?php echo $this->html('string.escape', $album->get('caption')); ?></textarea>

		<?php /*?><div
			data-album-cover-field
			<?php if ($album->hasCover()) { ?>
			class="es-album-cover-field"
			style="background-image: url(<?php echo $album->getCover( 'thumbnail' ); ?>);"
			<?php } else { ?>
			class="es-album-cover-field no-cover"
			<?php } ?>
			>
			<i class="fa fa-image"></i>
		</div><?php */?>
	</div>
    <?php if ($lib->editable()) { ?>
    <div class="es-album-menu es-media-item-menu es-album-menu-form">
        <div class="btn-group btn-group-xs">
            <div class="btn btn-es btn-media album-cancel" data-album-cancel-button>
                <a href="<?php echo $album->getPermalink();?>" title="<?php echo $lib->getPageTitle('item');?>"><?php echo JText::_("COM_EASYSOCIAL_ALBUMS_CANCEL"); ?></a>
            </div>
            <div class="btn btn-media btn-es-primary" data-album-done-button>
                <a href="<?php echo $album->getPermalink(); ?>"><i class="fa fa-check"></i> <?php echo JText::_("COM_EASYSOCIAL_ALBUMS_DONE"); ?></a>
            </div>
        </div>
        <?php /*?><div class="btn-group btn-group-xs">
            <?php if( $options['canUpload'] && $lib->canUpload() ){ ?>
            <div class="btn btn-es btn-media" data-album-upload-button>
                <a href="javascript: void(0);"><i class="fa fa-plus"></i> <?php echo JText::_("COM_EASYSOCIAL_ALBUMS_ADD_PHOTOS"); ?></a>
            </div>
            <?php } ?>
    
            <?php if( $lib->deleteable() ){ ?>
            <div class="btn btn-es btn-media <?php echo (empty($album->id)) ? 'disabled' : ''; ?>" data-album-delete-button>
                <a href="javascript:void(0);"><i class="fa fa-remove"></i> <?php echo JText::_("COM_EASYSOCIAL_ALBUMS_DELETE_ALBUM"); ?></a>
            </div>
            <?php } ?>
        </div><?php */?>
    </div>
    <?php } ?>
</div>
