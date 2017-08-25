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
<?php foreach( $albums as $album ){ ?>
        <div
            data-album-item="<?php echo $album->uuid(); ?>"
            data-album-id="<?php echo $album->id; ?>"
            class="es-album-item">
        
            <div data-album-header class="es-media-header es-album-header">
            	<a href="<?php echo $album->getPermalink();?>">
                    <div data-album-info class="es-media-info es-album-info">
                        <div data-album-title class="es-media-title es-album-title">
                            <?php echo $album->get('title'); ?>
                        </div>   
                        <div class="es-album-photos">
                        	<?php echo $album->getTotalPhotos().' '.JText::_("COM_EASYSOCIAL_PHOTOS");?>
                            -
                            <?php echo $album->hits.' views'; ?>
                        </div>                
                        <i data-album-cover class="es-album-cover" <?php if ($album->hasCover()) { ?>style="background-image: url(<?php echo $album->getCover( 'thumbnail' ); ?>);"<?php } ?>></i>
                    
                    </div>
                </a>
            </div>
        
        </div>
	<?php } ?>