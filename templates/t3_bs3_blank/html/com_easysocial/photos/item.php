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

if($options['layout'] == 'dialog'){
	?>
    <div data-photo-item="<?php echo $photo->uuid(); ?>"
         data-photo-id="<?php echo $photo->id; ?>"
         data-es-photo="<?php echo $photo->id; ?>"
         class="layout-<?php echo $options['layout'];?> es-media-item es-photo-item<?php echo $photo->isFeatured() ? ' featured' : '';?>"
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
                            <?php echo $this->includeTemplate('site/photos/menu'); ?>
                        </div>
                    </div>
                    <?php } ?>
    
                    <?php echo $this->render( 'module' , 'es-photos-before-info'); ?>
    
                    <?php if ($options['showInfo']) { ?>
                        <?php echo $this->includeTemplate('site/photos/info'); ?>
                    <?php } ?>
    
                    <?php if ($options['showForm'] && $album->editable()) { ?>
                    <?php echo $this->includeTemplate('site/photos/form'); ?>
                    <?php } ?>
                </div>
    
                <div data-photo-content class="es-photo-content">
                    <?php echo $this->render('module', 'es-photos-before-photo'); ?>
                    <div class="es-photo <?php echo $options['resizeUsingCss'] ? 'css-resizing' : ''; ?>">
                        <a data-photo-image-link
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
                                <?php if ($options['showNavigation']) { ?>
                                    <?php echo $this->includeTemplate('site/photos/navigation'); ?>
                                <?php } ?>
                            </u>
                        </a>
                        <?php if ($lib->taggable()) { ?>
                        <div class="es-photo-hint tag-hint alert">
                            <?php echo JText::_("COM_EASYSOCIAL_PHOTOS_TAGS_HINT"); ?>
                            <button class="btn btn-es" href="javascript: void(0);" data-photo-tag-button="disable"><i class="fa fa-check"></i> <span><?php echo JText::_("COM_EASYSOCIAL_PHOTOS_TAGS_DONE"); ?></span></button>
                        </div>
                        <?php } ?>
                    </div>
                    <?php if ($options['showTags']) { ?>
                        <?php echo $this->includeTemplate('site/photos/tags'); ?>
                    <?php } ?>
    
                    <i class="loading-indicator fd-small"></i>
                    <?php echo $this->render('module', 'es-photos-after-photo'); ?>
                </div>
    
                <div data-photo-footer class="es-photo-footer">
                    <?php if ($options['showStats']) { ?>
                        <?php echo $this->includeTemplate('site/photos/stats'); ?>
                    <?php } ?>
                    <?php echo $this->render( 'module' , 'es-photos-after-stats' ); ?>
    
                    <div class="es-photo-interaction row">
                        <div class="col-md-8">
                        <?php if ($options['showResponse']) { ?>
                            <?php echo $this->includeTemplate('site/photos/response'); ?>
                        <?php } ?>
                        </div>
                        <div class="col-md-4">
                            <?php echo $this->render( 'module' , 'es-photos-before-tags' ); ?>
    
                            <?php if ($options['showTags']) { ?>
                                <?php echo $this->includeTemplate('site/photos/taglist'); ?>
                            <?php } ?>
    
                            <?php echo $this->render( 'module' , 'es-photos-after-tags' ); ?>
                        </div>
                    </div>
                </div>
                <div class="es-media-loader"></div>
            </div>
        </div>
    </div>
    <?php
}
if($options['layout'] != 'dialog'){
	
$uid = $this->input->get('uid', null, 'int');
$type = $this->input->get('type', SOCIAL_TYPE_USER, 'cmd');

$user 	= FD::user($uid);
// If this is a user type, we will want to get a list of albums the current logged in user created
if ($type == SOCIAL_TYPE_USER && $uid == null) {
	$user 	= FD::user($uid);
	$uid 	= $user->id;
}
?>
<div 
	data-photo-item="<?php echo $photo->uuid(); ?>"
	 data-photo-id="<?php echo $photo->id; ?>"
	 data-es-photo="<?php echo $photo->id; ?>"
     class="es-container layout-<?php echo $options['layout'];?> es-media-item es-photo-item<?php echo $photo->isFeatured() ? ' featured' : '';?>"
     data-es-photo-disabled="<?php echo $options['openInPopup'] ? 0 : 1; ?>"

data-photo-item="<?php echo $photo->uuid(); ?>" data-id="<?php echo $photo->id;?>">
    <div class="es-content" data-photo-browser-content>
    	<div class="es-photo-single es-responsive">
        	<div class="es-photo-content-body">
                <div class="photo-container">
                   <u data-photo-viewport>
							<b data-mode="<?php echo 'cover'; ?>"
							   data-threshold="128">
								<img data-photo-image
									 src="<?php echo $photo->getSource('large'); ?>"
									 data-thumbnail-src="<?php echo $photo->getSource('thumbnail'); ?>"
									 data-featured-src="<?php echo $photo->getSource('featured'); ?>"
									 data-large-src="<?php echo $photo->getSource('large'); ?>"
									 data-width="<?php echo $photo->getWidth(); ?>"
									 data-height="<?php echo $photo->getHeight(); ?>"
									 onload="window.ESImage ? ESImage(this) : (window.ESImageList || (window.ESImageList=[])).push(this);" />
							</b>
							<em class="es-photo-image" style="background-image: url('<?php echo $photo->getSource('large'); ?>');" data-photo-image-css></em>
						</u>
                </div>
            </div>
            
            <div class="es-photo-content-brief">
            	<div class="es-photo-manage row-table mb-15 mt-15">
                    <div class="col-xs-3 col-sm-3 col-md-3">
                        <?php /*?><a href="<?php echo $lib->getAlbumLink(); ?>">&larr; <?php echo JText::_('COM_EASYSOCIAL_PHOTOS_BACK_TO_ALBUM'); ?></a><?php */?>
                        <a class="photo-back-link" href="<?php echo FRoute::albums(array('uid' => $user->getAlias(), 'type' => SOCIAL_TYPE_USER)) ?>">&larr; <?php echo JText::_('COM_EASYSOCIAL_PHOTOS_BACK_TO_PHOTOS'); ?></a>
                    </div>
                    <?php if( $lib->editable() ){ ?>
                    <div class="col-xs-3 col-sm-3 col-md-3 pull-right">
                    	<?php echo $this->includeTemplate('site/photos/menu'); ?>
                    </div>
                    <?php } ?>	
                </div>
                <div class="mt-20">
                	<div class="es-snackbar">
                    	<span>
							<?php echo JText::_("COM_EASYSOCIAL_PHOTO");?>
                        </span>
                        <span>
                            <?php echo JText::_("COM_EASYSOCIAL_PHOTO_DETAILS");?>
                        </span>
                    </div>
                    <?php echo $this->includeTemplate('site/photos/info'); ?>
                    <?php /*?><div class="photo-single-conent-body">
                        <h2 class="es-photo-title single"><?php echo $photo->get('title'); ?></h2>
                        <div class="es-photo-meta mt-5">
                            <div>
                                <div class="es-avatar es-avatar-sm es-stream-avatar pull-left" data-comments-item-avatar="">
                                    <?php if ($photo->getCreator()) { ?>
                                        <?php echo $this->loadTemplate('site/avatar/default', array('user' => $photo->getCreator())); ?>						
                                    <?php } else { ?>
                                        <img src="<?php echo $photo->getCreator()->getAvatar();?>" alt="<?php echo $this->html('string.escape', $photo->getCreator()->getName());?>" />
                                    <?php } ?>
                                </div>
                                <a href="<?php echo $photo->getCreator()->getPermalink();?>" class="photo-author">
                                    <?php echo $photo->getCreator()->getName();?>
                                </a>
                                <?php
								if( !$photo->isMine()){ ?>
                                	<div class="photo-follow-user">
									<?php if( FD::get( 'Subscriptions' )->isFollowing( $photo->getCreator()->id , SOCIAL_TYPE_USER ) ){ ?>
										<?php echo $this->loadTemplate( 'site/profile/button.followers.unfollow' ); ?>
                                    <?php } else { ?>
                                        <?php echo $this->loadTemplate( 'site/profile/button.followers.follow' ); ?>
                                    <?php } ?>	
                                    </div>
								<?php 
								}
								?>
                            </div>
                        </div>
                        <span class="time-span">
                            <?php echo FD::date( $photo->created )->toLapsed(); ?>
                        </span>
    
                        <div class="es-photo-brief mt-10">
							<?php echo $photo->get('caption'); ?>
                        </div>
                     </div><?php */?>
                    <div class="es-photo-response">
                        <div class="es-action-wrap pr-20">
                            <ul class="fd-reset-list es-action-feedback">
                            	<li class="arepost">
									<?php
                                   $repost = FD::get('Repost', $photo->id , SOCIAL_TYPE_PHOTO, 'user', $uid, $type);
                                    ?>
                                    <a data-stream-action-repost href="javascript:void(0);" class="fd-small arepost"><?php echo $repost->getButton(); ?></a>
                                </li>
                                <li>
                                    <a href="javascript:void(0)" class="acomments"></a>
                                </li>
                                <li class="alikes">
                                    <?php echo $lib->likes()->button();?>
                                </li>
                                <?php /*?><li>
                                    <span class="ahits">
                                        <?php echo $video->getHits(); ?>
                                    </span>
                                </li><?php */?>
                            </ul>
                        </div>
        
                        <div class="es-stream-actions photo-likes">
                            <?php echo $lib->likes()->toHTML();?>
                        </div>
                    </div>
                </div>                
            </div>
            <!--<hr class="es-hr" />-->
            
            <div class="es-photo-comments">
            	<br /><br />
                <div class="es-snackbar">
                    <span>
                        <?php echo JText::_("COM_EASYSOCIAL_SH404_TYPE_USER");?>
                    </span>
                    <span>
                        <?php echo JText::_("COM_EASYSOCIAL_COMMENTS_PLURAL");?>
                    </span>
                </div>
            	<div class="es-stream-actions">
                    <?php echo $lib->comments()->getHTML(array( 'hideEmpty' => false, 'deleteable' => $lib->isMine()));?>
                </div>
            </div>
        </div>  
        <?php if ($options['showForm'] && $album->editable()) { ?>
		<?php echo $this->includeTemplate('site/photos/form'); ?>
        <?php } ?>  
    </div>
</div>
<?php
}