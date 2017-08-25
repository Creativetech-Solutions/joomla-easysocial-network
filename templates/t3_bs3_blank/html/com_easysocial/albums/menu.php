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
<div class="es-album-menu es-media-item-menu es-album-menu-item">

	<?php if( $lib->editable() ){ ?>
	<div class="btn-group btn-group-xs">

		<?php if(($lib->editable() && $lib->isOwner() )|| $lib->deleteable() ){ ?>
		<div class="btn btn-media dropdown_" data-item-actions-menu>
			<?php /*?><a href="javascript:void(0);" data-bs-toggle="dropdown" class="dropdown-toggle_"><i class="fa fa-cog"></i> <span><?php echo JText::_('COM_EASYSOCIAL_ALBUMS_EDIT'); ?></span> </a><?php */?>
            <a href="javascript: void(0);" data-bs-toggle="dropdown" class="album-edit-btn dropdown-toggle_">
			</a>
			<ul class="dropdown-menu">
				<?php if( $lib->editable() && $lib->isOwner()){ ?>
				<li data-album-edit-button class="album-edit-btn">
					<a href="<?php echo $album->getEditPermalink();?>" title="<?php echo $lib->getPageTitle('item');?>"><?php echo JText::_( 'COM_EASYSOCIAL_ALBUMS_EDIT_ALBUM' ); ?></a>
				</li>
				<?php } ?>

				<?php if( $lib->deleteable() ){ ?>
				<li class="divider"></li>
				<li data-album-delete-button>
					<a href="javascript:void(0);"><?php echo JText::_("COM_EASYSOCIAL_ALBUMS_DELETE_ALBUM"); ?></a>
				</li>
				<?php } ?>
			</ul>
		</div>
		<?php } ?>
	</div>
	<?php } ?>
</div>


