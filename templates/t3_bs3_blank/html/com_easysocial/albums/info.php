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
<div data-album-info class="es-media-info es-album-info">

	<div data-album-title class="es-media-title es-album-title<?php echo ($lib->editable()) ? ' is-editable' : ''; ?>">
		<a href="javascript:void(0)"><?php echo $album->get('title'); ?></a>
	</div>

	<?php if( $options[ 'view' ] != 'all' ){ ?>
	<div data-album-caption class="es-media-caption es-album-caption<?php echo ($lib->editable()) ? ' is-editable' : ''; ?>">
		<?php echo $this->html( 'string.truncater' , $album->get( 'caption' ) , 250 ); ?>
	</div>
	<?php } ?>
    
    <?php if ($options['showForm'] && $lib->editable()) { ?>
		<?php echo $this->includeTemplate('site/albums/form'); ?>
    <?php } ?>
    
    <div data-album-photo-count class="es-media-caption es-album-photo-count">
    <?php if (!empty($photos)) {
		echo count($photos).' '.JText::_("COM_EASYSOCIAL_PHOTOS");
	} ?>
    </div>

	<div data-album-owner class="es-album-owner"><?php echo JText::_("COM_EASYSOCIAL_ALBUMS_UPLOADED_BY").":"; ?> <a href="<?php echo $album->getCreator()->getPermalink(); ?>"><?php echo $album->getCreator()->getName(); ?></a></div>

	<i data-album-cover class="es-album-cover" <?php if ($album->hasCover()) { ?>style="background-image: url(<?php echo $album->getCover( 'large' ); ?>);"<?php } ?>><b></b><b></b></i>

</div>
