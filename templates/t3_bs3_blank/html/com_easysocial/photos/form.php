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
//$photoT = ES::table("Photo");
//$photoT->load($photo->id);
// Load up the photo lib
//$lib = ES::photo($photoT->uid, $photoT->type, $photoT);
//$options = array('exclusion' => $photoT->album_id, 'core' => false);
$aoptions = array('core' => false);
$model = ES::model("Albums");
$photosalbums = $model->getAlbums($photo->uid, SOCIAL_TYPE_USER);

$tags = $photo->getTags(true);
//$photosalbums = $lib->getAlbums($photo->uid,'',$aoptions);
?>
<div
	data-photo-form
	class="es-photo-form">
	<div class="es-photo-form-fields">
		<input
			class="es-photo-title-field"
			data-photo-title-field
			type="text"
			placeholder="<?php echo JText::_("COM_EASYSOCIAL_ENTER_PHOTO_TITLE"); ?>"
			value="<?php echo $this->html( 'string.escape' , $photo->title ); ?>" />

		<textarea
			class="es-photo-caption-field"
			data-photo-caption-field
			placeholder="<?php echo JText::_("COM_EASYSOCIAL_ENTER_PHOTO_CAPTION"); ?>"><?php echo $photo->caption; ?></textarea>
            
        <div class="mt-20">
			<select data-album-selection name="photo-album">
				<?php foreach ($photosalbums as $album) { ?>
					<option value="<?php echo $album->id; ?>" <?php echo ($photo->album_id == $album->id) ? 'selected="selected"' : ''; ?>><?php echo $album->get('title'); ?></option>
				<?php } ?>
			</select>
		</div>  
        
       <div class="mt-20"> 
       		<label for="es-fields-85" class="col-sm-3 control-label">
				<?php echo JText::_('COM_EASYSOCIAL_VIDEOS_FORM_PEOPLE_IN_THIS_PHOTO');?>
            </label>
       		<div class="textboxlist disabled" data-mentions>

				<?php if ($tags) { ?>
                    <?php foreach ($tags as $tag) { 
					$isUser = !empty($tag->uid);
					if ($isUser) {
						$tuser = FD::user($tag->uid);
					}
					?>
                        <div class="textboxlist-item" data-id="<?php echo $tag->id; ?>" data-title="<?php echo $tuser->getName(); ?>" data-textboxlist-item>
                            <span class="textboxlist-itemContent" data-textboxlist-itemContent>
                                <img width="16" height="16" src="<?php echo $tuser->getAvatar(SOCIAL_AVATAR_SMALL);?>" />
                                <?php echo $tuser->getName(); ?>
                                <input type="hidden" name="items" value="<?php echo $tag->uid; ?>" />
                            </span>
                            <div class="textboxlist-itemRemoveButton" data-textboxlist-itemRemoveButton>
                                <i class="fa fa-remove"></i>
                            </div>
                        </div>
                    <?php } ?>
                <?php } ?>
    
                <input type="text" autocomplete="off"
                    class="textboxlist-textField"
                    data-textboxlist-textField
                    placeholder="<?php echo JText::_('COM_EASYSOCIAL_CONVERSATIONS_START_TYPING');?>"
                />
            </div>
       </div>  

		<div data-photo-meta-field class="es-photo-meta-field sentence">
			<?php /*?><div data-photo-location class="es-photo-location words">
				<i class="fa fa-map-marker"></i>
				<span
					data-photo-location-caption
					data-bs-toggle="dropdown"
					class="with-data <?php if ($photo->getLocation()) { echo 'has-data'; }?>">
					<?php if ($photo->getLocation()) { ?>
						<?php echo $photo->getLocation()->getAddress(); ?>
					<?php } ?>
				</span>
				<span data-photo-addLocation-button
					  data-bs-toggle="dropdown"
				      class="without-data">
				    <?php echo JText::_("COM_EASYSOCIAL_ADD_LOCATION"); ?>
				</span>
				<div data-photo-location-form
				     class="es-photo-location-form dropdown-menu dropdown-static dropdown-arrow-topleft">
					<?php echo $this->html( 'grid.location', $photo->getLocation() ); ?>
				</div>
			</div><?php */?>

			<?php /*?><div data-photo-date class="es-photo-date words has-data">
				<i class="fa fa-clock-o"></i>
				<span data-photo-date-caption
				      data-bs-toggle="dropdown"
				      class="with-data">
					<?php echo $this->html( 'string.date', $photo->getAssignedDate() , "COM_EASYSOCIAL_PHOTOS_DATE_FORMAT", $photo->hasAssignedDate() ? false : true); ?>
				</span>
				<span data-photo-addDate-button
				      data-bs-toggle="dropdown"
				      class="without-data">
				    <?php echo JText::_("COM_EASYSOCIAL_ADD_DATE"); ?>
				</span>
				<div class="es-photo-date-form dropdown-menu dropdown-static dropdown-arrow-topright">
					<?php echo $this->html('grid.dateform', 'date-form', $photo->getAssignedDate(), '', '', $photo->hasAssignedDate() ? false : true); ?>
				</div>
			</div><?php */?>
		</div>

		<?php if( $lib->hasPrivacy() ){ ?>
		<?php /*?><div data-photo-privacy class="es-photo-privacy solid">
			<?php echo $privacy->form( $photo->id, SOCIAL_TYPE_PHOTO, $photo->uid, 'photos.view' ); ?>
		</div><?php */?>
		<?php } ?>

	</div>
    <?php if ( $album->editable() ){ ?>
		<?php echo $this->includeTemplate('site/photos/menu/form'); ?>
    <?php } ?>
</div>
