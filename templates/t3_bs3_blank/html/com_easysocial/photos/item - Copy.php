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

$uid = $this->input->get('uid', null, 'int');
$type = $this->input->get('type', SOCIAL_TYPE_USER, 'cmd');

$user 	= FD::user($uid);
// If this is a user type, we will want to get a list of albums the current logged in user created
if ($type == SOCIAL_TYPE_USER && $uid == null) {
	$user 	= FD::user($uid);
	$uid 	= $user->id;
}
?>
<div class="es-container" data-photo-item="<?php echo $photo->uuid(); ?>" data-id="<?php echo $photo->id;?>">
    <div class="es-content" data-photo-browser-content>
    	<div class="es-photo-single es-responsive">
        	<div class="es-photo-content-body">
                <div class="photo-container">
                   <img data-photo-image
                       src="<?php echo $photo->getSource('large'); ?>"
                       data-thumbnail-src="<?php echo $photo->getSource('thumbnail'); ?>"
                       data-featured-src="<?php echo $photo->getSource('featured'); ?>"
                       data-large-src="<?php echo $photo->getSource('large'); ?>"
                       data-width="<?php echo $photo->getWidth(); ?>"
                       data-height="<?php echo $photo->getHeight(); ?>" />
                </div>
            </div>
            
            <div class="es-photo-content-brief">
            	<div class="es-photo-manage row-table mb-15 mt-15">
                    <div class="col-xs-3 col-sm-3 col-md-3">
                        <?php /*?><a href="<?php echo $lib->getAlbumLink(); ?>">&larr; <?php echo JText::_('COM_EASYSOCIAL_PHOTOS_BACK_TO_ALBUM'); ?></a><?php */?>
                        <a class="photo-back-link" href="<?php echo JRoute::_("index.php?option=com_easysocial&view=albums&layout=all&uid=".$user->getAlias()."&type=".SOCIAL_TYPE_USER) ?>"><?php echo JText::_('COM_EASYSOCIAL_PHOTOS_BACK_TO_PHOTOS'); ?></a>
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
                    <div class="photo-single-conent-body">
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
                     </div>
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
            <hr class="es-hr" />
            
            <div class="es-photo-comments">
            	<div class="es-stream-actions">
                    <?php echo $lib->comments()->getHTML(array( 'hideEmpty' => false, 'deleteable' => $lib->isMine()));?>
                </div>
            </div>
        </div>    
    </div>
</div>