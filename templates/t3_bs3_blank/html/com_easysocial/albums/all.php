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
require_once(JPATH_SITE.'/components/com_easysocial/helpers/photos.php');

$uid = $this->input->get('uid', null, 'int');
$type = $this->input->get('type', SOCIAL_TYPE_USER, 'cmd');

$user 	= FD::user($uid);
// If this is a user type, we will want to get a list of albums the current logged in user created
if ($type == SOCIAL_TYPE_USER && $uid == null) {
	$user 	= FD::user($uid);
	$uid 	= $user->id;
}

// Load up the albums library
$lib = FD::albums($uid, $type);

$options = array(
	'order' => 'a.assigned_date',
	'direction' => 'DESC',
	'core' => true
);

$model 	= FD::model( 'Albums' );
$model->initStates();
$model->setState( 'limitstart', 0);
$albums = $model->getAlbums($uid, $type, $options);

/*if( !empty( $albums ) ){
	$allphotos = 0;
	foreach( $albums as $album ){
		$albumPhotos = isset($photos[$album->id]) ? $photos[$album->id] : array();
		$allphotos = $allphotos + count($albumPhotos);
	}
}*/

$options2['pagination'] = false;
$options2['uid'] = $user->id;
$options2['ordering'] = 'created';
$options2['noavatar'] = false;
$options2['nocover'] = false;
$allphotos = array();
$phelper = new EasySocialPhotosHelper();
$allphotos = $phelper->getPhotos($options2);
?>
<div class="es-container">
	<div class="es-content">
        <div class="dashboard_header">
            <h1> <span><?php echo $user->getName().'\'s </span> <span>Photos</span>';?> </h1>
            <div class="nav-back">
                <a href="<?php echo $lib->getViewAlbumsLink();?>">Back</a> to <?php echo '<span>'.$user->getName().'\'s</span> Profile';?>
            </div>
        </div>
        
        <div class="es-photo-list all-items-layout clearfix">
            <div class="es-photo-list-container"> 
                    <ul class="nav nav-tabs" role="tablist">
                      <li role="presentation" class="active"> <a href="#photos" role="tab" data-toggle="tab"> <span> <?php echo count($allphotos); ?> </span> Photos </a> </li>
                      <li role="presentation"> <a href="#albums" aria-controls="profile" role="tab" data-toggle="tab"> <span> <?php echo count($albums); ?> </span> Albums </a> </li>
                    </ul>
                    <?php if( $lib->canCreateAlbums() ){ ?>
                    <div class="col-md-12 add-album-container">
                        <a class="btn-add-album pull-right" href="<?php echo $lib->getCreateLink();?>">
                            <?php echo JText::_('COM_EASYSOCIAL_ALBUMS_CREATE_ALBUM');?>
                        </a>
                    </div>
                    <?php } ?>
                    <div class="tab-content">
                        <div role="tabpanel" class="tab-pane active" id="photos">
                            <?php echo $this->includeTemplate( 'site/albums/all.photos' , array('photos' => $allphotos)); ?>
                        </div>
                        <div role="tabpanel" class="tab-pane" id="albums">
                            <?php echo $this->includeTemplate( 'site/albums/all.items' , array('albums' => $albums)); ?>
                        </div>
                    </div>
            </div>
        </div>
	</div>
</div>

