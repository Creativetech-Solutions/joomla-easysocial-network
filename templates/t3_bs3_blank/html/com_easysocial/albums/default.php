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

$subview = $this->input->get('subview','', 'cmd');
if($subview=="all"){
    echo $this->output( 'site/albums/all'); 
}
else{
    
require_once(JPATH_SITE.'/components/com_easysocial/helpers/photos.php');
/*if ($this->my->id == 0 || $uid == 0) {
	ES::requireLogin();
}
if($uid == 0 && $this->my->id != 0){
	$uid = $this->my->id;
}
$mid = ES::user($uid)->id;
$user = ES::user($mid);*/


$uid = $this->input->get('uid', null, 'int');
$type = $this->input->get('type', SOCIAL_TYPE_USER, 'cmd');

$user 	= FD::user();
// If this is a user type, we will want to get a list of albums the current logged in user created
if ($type == SOCIAL_TYPE_USER) {
	$user 	= FD::user($uid);
	$uid 	= $user->id;
}

$currentProfile = $user->getProfile()->get('title');
// Retrieve user's step
$usersModel = ES::model('Users');
// Get the active step
$activeStep = 0;
$privacy = $this->my->getPrivacy();

// Get the list of available steps on the user's profile
$steps = $usersModel->getAbout($user, $activeStep);
$layout = JRequest::getVar('layout');
if($layout != 'item' && $layout != 'form'){
	JFactory::getDocument()->addScriptDeclaration($collapseScript);
}
?>
<?php //echo $lib->heading(); ?>

<div class="es-container es-media-browser layout-album"
	data-layout="album"
	data-album-browser="<?php echo $uuid; ?>">

	<a href="javascript:void(0);" class="btn btn-block btn-es-inverse btn-sidebar-toggle" data-sidebar-toggle>
		<i class="fa fa-grid-view  mr-5"></i> <?php echo JText::_( 'COM_EASYSOCIAL_SIDEBAR_TOGGLE' );?>
	</a>
    <?php
	if(JRequest::getVar('subview') != 'all-photos' && JRequest::getVar('subview') != 'all-albums' && $layout != 'item' && $layout != 'form' && $type == SOCIAL_TYPE_USER){
	?>
    <div class="es-albums userProfile" data-id="<?php echo $user->id;?>" data-albums>

	<?php echo $this->render( 'widgets' , 'user' , 'profile' , 'aboveHeader' , array( $user ) ); ?>

	<?php echo $this->render( 'module' , 'es-profile-before-header' ); ?>

	<!-- Include cover section -->
	<?php echo $this->includeTemplate( 'site/albums/default.header', array('currentProfile' => $currentProfile,'user'=>$user,'cover' => $cover,'steps' => $steps,'privacy' => $privacy ) ); ?>

	<?php echo $this->render( 'module' , 'es-profile-after-header' ); ?>
    
	</div>
    <?php
	}else{
            echo $lib->heading();
        }
	?>
    <div class="es-container">
        <div data-album-browser-content class="es-content albums-container">
        	
            <?php
			//layout item
			if($layout == 'item' && $layout != 'form'){
				echo $content;
			}else{
			?>
        
        	<div class="es-photo-listing">
				<?php
                if($layout != 'form'){
                ?>
                <div class="add-album-container">
                    <?php if ($lib->canCreateAlbums() && $this->my->id == $user->id) { ?>
                        <div class="es-widget-create pull-right">
                            <a href="<?php echo $lib->getCreateLink();?>" class="btn-upload-photo"><i class="fa fa-cloud-upload" aria-hidden="true"></i>  <?php echo JText::_("COM_EASYSOCIAL_ALBUMS_ADD_PHOTOS"); ?></a>
                        </div>
                    <?php } ?>
                </div>
                <?php } ?>
                <?php echo $this->render( 'module' , 'es-albums-before-contents' ); ?>
                <?php
                if($layout == '' && JRequest::getVar('subview') != 'all-photos' && JRequest::getVar('subview') != 'all-albums'){
                    $options['limit'] = 1;
                    $options['featured'] = 1;
                    $options['uid'] = $uid;
                    $featuredPhotos = array();
                    $featuredPhotos = EasySocialPhotosHelper::getPhotos($options);
                    ?>
                    <div class="mt-20">
                        <?php
                        if ($featuredPhotos) { ?>
                            <?php echo $this->output('site/albums/default.featured.items', array('featuredPhotos' => $featuredPhotos)); ?>
                        <?php } ?>                  
                    </div>
                    <?php
                    $options2['limit'] = 4;
                    $options2['uid'] = $uid;
					$options2['featured'] = 0;
					$options2['ordering'] = 'created';
                    $latestPhotos = array();
                    $latestPhotos = EasySocialPhotosHelper::getPhotos($options2);
					 ?>
                    <div class="other-photos">
                        <?php
                        if ($latestPhotos) { ?>
                            <?php 
							echo $this->output('site/albums/default.latest.items', array('user'=>$user,'photos' => $latestPhotos)); ?>
                        <?php } ?>                  
                    </div>
                    <?php
                    $options3['limit'] = 6;
					$options3['order'] 		= 'a.assigned_date';
					$options3['direction'] 	= 'DESC';
					$options3['core'] 	= true;
					$uid3 = $this->input->get('uid', null, 'int');
					$type3 = $this->input->get('type', SOCIAL_TYPE_USER, 'cmd');
					
					if ($type3 == SOCIAL_TYPE_USER && $uid3 == null) {
						$user3 	= FD::user($uid3);
						$uid3 	= $user3->id;
					}
					
                    $photoAlbums = array();
					// Get albums model
					$model 	= FD::model( 'Albums' );
					$model->initStates();
					$model->setState( 'limitstart', 0);
					$photoAlbums 	= $model->getAlbums($uid3, $type3, $options3);
					 ?>
                    <div class="other-photos">
                        <?php
                        if ($photoAlbums) { ?>
                            <?php 
							echo $this->output('site/albums/default.photo.albums', array('user'=>$user,'albums' => $photoAlbums)); ?>
                        <?php } ?>                  
                    </div>                    
                    <?php
                }else{
                    echo $content;
                }
				?>
                <?php echo $this->render( 'module' , 'es-albums-after-contents' ); ?>
            </div>
            <?php
			}
			?>
        </div>
	</div>
    
	<i class="loading-indicator fd-small"></i>
</div>
<?php 
}
