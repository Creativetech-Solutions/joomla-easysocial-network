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

?>

    <div class="es-snackbar">
    	<span>
        	<?php echo JText::_("COM_EASYSOCIAL_APPS_RECENT_ADDED");?>
        </span>
        <span>
        	<?php echo JText::_("COM_EASYSOCIAL_PHOTOS");?>
        </span>
        <a style="float: right;" href="<?php echo FRoute::albums(array('uid' => $user->getAlias(), 'subview'=>'all', 'type' => SOCIAL_TYPE_USER));?>">See All</a>
    </div>
    

<div class="es-photo-list clearfix<?php echo !$photos ? ' is-empty' : '';?>">
    <?php if ($photos) { 
	?>
        <?php foreach ($photos as $photo) { 
		?>
        <div class="es-photo-item" data-photo-item data-id="<?php echo $photo->id;?>">
        	<div class="es-photo">
					<a data-photo-image-link href="<?php echo $photo->getPermalink();?>" title="<?php echo $this->html('string.escape', $photo->title . (($photo->caption!=='') ? ' - ' . $photo->caption : '')); ?>">

						<em class="es-photo-image" style="background-image: url('<?php echo $photo->getSource('thumbnail'); ?>');" data-photo-image-css></em>
					</a>
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

<?php /*?><?php if ($videos && isset($pagination)) { ?>
<div class="mt-20 text-center es-pagination">
    <?php echo $pagination->getListFooter('site');?>
</div>
<?php } ?><?php */?>
