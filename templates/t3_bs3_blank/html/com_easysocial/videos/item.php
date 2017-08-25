<?php
/**
* @package      EasySocial
* @copyright    Copyright (C) 2010 - 2015 Stack Ideas Sdn Bhd. All rights reserved.
* @license      GNU/GPL, see LICENSE.php
* EasySocial is free software. This version may have been modified pursuant
* to the GNU General Public License, and as distributed it includes or
* is derivative of works licensed under the GNU General Public License or
* other free or open source software licenses.
* See COPYRIGHT.php for copyright notices and details.
*/
defined('_JEXEC') or die('Unauthorized Access');
$script = '
jQuery(document).ready(function($) {
	var vheight = jQuery(".es-video-content-body").height();
	$("body").prepend("<div class=\"video-bg\" style=\"background: #000;position: absolute;width: 100%;height:"+vheight+"px;z-index: -1;\"></div>");
	
	var com_form = $(".es-video-comments .es-comments-form");
	var loadmore = $(".es-video-comments .es-comments-control");
	
	$(".es-video-comments .es-comments-form").remove();
	$(".es-video-comments .es-comments-control").remove();
	
	$("ul.fd-reset-list.es-comments").parent().prepend(com_form);
	$("ul.fd-reset-list.es-comments").parent().append(loadmore);
	
});
';
JFactory::getDocument()->addScriptDeclaration($script);
?>
<?php //echo $video->getMiniHeader();?>

<div class="es-container es-videos" data-video-item data-id="<?php echo $video->id;?>">
    <div class="es-content">

        <?php echo $this->render('module' , 'es-videos-before-video'); ?>

        <div class="es-video-single es-responsive">

            <div class="es-video-content-body">
                <?php if ($video->isPendingProcess()) { ?>
                <div class="alert alert-info">
                    <?php echo JText::_('COM_EASYSOCIAL_VIDEOS_ITEM_PENDING_INFO');?>
                </div>
                <?php } ?>

                <div class="video-container">
                    <?php echo $video->getEmbedCodes(); ?>
                </div>
            </div>

            <?php echo $this->render('module' , 'es-videos-after-video'); ?>

            <div class="es-video-content-brief">
            	
                <div class="es-video-manage row-table mb-15 mt-15">
                    <div class="col-cell">
                        <a href="<?php echo FRoute::videos(array('uid' => $video->getAuthor()->getAlias(), 'type' => SOCIAL_TYPE_USER)) ?>">&larr; <?php echo JText::_('COM_EASYSOCIAL_VIDEOS_BACK_TO_VIDEOS'); ?></a>
                    </div>
    
                    <div class="col-cell">
                        <?php if ($video->canFeature() || $video->canUnfeature() || $video->canDelete() || $video->canEdit()) { ?>
                        <span class="es-video-manage dropdown_ pull-right pl-10">
                            <a href="javascript:void(0);" class="dropdown-toggle_ video-edit-btn" data-bs-toggle="dropdown"><?php //echo JText::_('COM_EASYSOCIAL_MANAGE');?></a>
                            <ul class="dropdown-menu dropdown-arrow-topright">
                                <?php if ($video->canFeature() || (!$video->table->isFeatured() && $video->canEdit())) { ?>
                                <li>
                                    <a href="javascript:void(0);" data-video-feature><?php echo JText::_('COM_EASYSOCIAL_VIDEOS_FEATURE_VIDEO');?></a>
                                </li>
                                <?php } ?>
    
                                <?php if ($video->canUnfeature() || ($video->table->isFeatured() && $video->canEdit())) { ?>
                                <li>
                                    <a href="javascript:void(0);" data-video-unfeature><?php echo JText::_('COM_EASYSOCIAL_VIDEOS_UNFEATURE_VIDEO');?></a>
                                </li>
                                <?php } ?>
    
                                <?php if ($video->canEdit()) { ?>
                                <li>
                                    <a href="<?php echo $video->getEditLink();?>"><?php echo JText::_('COM_EASYSOCIAL_EDIT'); ?></a>
                                </li>
                                <?php } ?>
    
                                <?php if ($video->canDelete()) { ?>
                                <li>
                                    <a href="javascript:void(0);" data-video-delete><?php echo JText::_('COM_EASYSOCIAL_DELETE');?></a>
                                </li>
                                <?php } ?>
                            </ul>
                        </span>
                        <?php } ?>
                    </div>
                </div>
                
                <div class="mt-20">
                	<div class="es-snackbar">
                    	<span>
							<?php echo JText::_("COM_EASYSOCIAL_GROUPS_VIDEOS_STRING_SINGULAR");?>
                        </span>
                        <span>
                            <?php echo JText::_("COM_EASYSOCIAL_VIDEOS_DETAILS");?>
                        </span>
                    </div>
                    
                    <div class="video-single-conent-body">
                        <h2 class="es-video-title single"><?php echo $video->getTitle();?></h2>
                        <div class="es-video-meta mt-5">
                            <div>
                                <div class="es-avatar es-avatar-sm es-stream-avatar pull-left" data-comments-item-avatar="">
                                    <?php if ($video->getAuthor()) { ?>
                                        <?php echo $this->loadTemplate('site/avatar/default', array('user' => $video->getAuthor())); ?>						
                                    <?php } else { ?>
                                        <img src="<?php echo $video->getAuthor()->getAvatar();?>" alt="<?php echo $this->html('string.escape', $video->getAuthor()->getName());?>" />
                                    <?php } ?>
                                </div>
                                <a href="<?php echo $video->getAuthor()->getPermalink();?>" class="video-author">
                                    <?php echo $video->getAuthor()->getName();?>
                                </a>
                                <?php
								if( !$video->getAuthor()->isViewer()){ ?>
                                	<div class="video-follow-user">
									<?php if( FD::get( 'Subscriptions' )->isFollowing( $video->getAuthor()->id , SOCIAL_TYPE_USER ) ){ ?>
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
                            <?php
                            echo FD::date($video->getCreatedDate())->toLapsed();
                            ?>
                        </span>

						<?php echo $this->render('module' , 'es-videos-before-video-description'); ?>
    
                        <div class="es-video-brief mt-10"><?php echo $video->getDescription();?></div>
    
                        <?php echo $this->render('module' , 'es-videos-after-video-description'); ?>
    
                        <?php if ($this->config->get('video.layout.item.duration')) { ?>
                        <?php /*?><div class="es-video-duration mt-10">
                            <?php echo JText::_('COM_EASYSOCIAL_VIDEOS_VIDEO_DURATION');?>
                            <?php echo $video->getDuration();?>
                        </div><?php */?>
                        <?php } ?>
    
                        <?php if ($video->hasLocation()) { ?>
                        <?php /*?><div class="es-video-location mt-20">
                            <?php echo $this->html('html.map', $video->getLocation(), true);?>
                        </div><?php */?>
                        <?php } ?>
    
                        <?php if ($this->config->get('video.layout.item.tags')) { ?>
                            <?php /*?><hr class="es-hr">
    
                            <div class="es-video-tagging">
                                <b><?php echo JText::_('COM_EASYSOCIAL_VIDEOS_PEOPLE_IN_THIS_VIDEO');?></b>
                                <?php if ($video->canAddTag()) { ?>
                                <span class="ml-5 text-muted">
                                    &ndash;
                                    <a href="javascript:void(0);" data-video-tag><?php echo JText::_('COM_EASYSOCIAL_TAG_PEOPLE');?></a>
                                </span>
                                <?php } ?>
                                <ul class="es-video-tag-friends fd-reset-list<?php echo !$tags ? ' is-empty' : '';?>" data-video-tag-wrapper>
                                    <?php echo $this->output('site/videos/tags'); ?>
                                    <li class="empty" data-tags-empty>
                                        <?php echo JText::_('COM_EASYSOCIAL_VIDEOS_NO_TAGS_AVAILABLE'); ?>
                                    </li>
                                </ul>
                            </div><?php */?>
                        <?php } ?>
                        <?php echo $this->render('module' , 'es-videos-after-video-tags'); ?>
                     </div>
                     <div class="es-video-response">
                        <div class="es-action-wrap pr-20">
                            <ul class="fd-reset-list es-action-feedback">
                            	<li class="arepost">
									<?php
                                    $repost = FD::get('Repost', $video->id , SOCIAL_TYPE_VIDEOS, SOCIAL_APPS_GROUP_USER );
                                    ?>
                                    <a data-stream-action-repost href="javascript:void(0);" class="fd-small arepost"><?php echo $repost->getButton(); ?></a>
                                </li>
                                <li>
                                    <a href="javascript:void(0)" class="acomments"></a>
                                </li>
                                <li class="alikes">
                                    <?php echo $video->getLikes('create')->button();?>
                                </li>
                                
                                <li>
                                    <span class="ahits">
                                        <?php echo $video->getHits(); ?>
                                    </span>
                                </li>
                            </ul>
                        </div>
        
                        <div class="es-stream-actions video-likes">
                            <?php echo $likes->html();?>
                        </div>
                    </div>
                </div>
            </div>

            <hr class="es-hr" />
            
            <div class="es-video-comments">
            	<div class="es-stream-actions">
                    <?php echo $video->getComments('create')->getHTML();?>
                </div>
            </div>

            <?php echo $this->render('module' , 'es-videos-before-other-videos'); ?>

            <?php if ($this->config->get('video.layout.item.recent') && $otherVideos) { ?>
            <?php /*?><div class="es-video-other">
                <div class="es-snackbar"><?php echo JText::_('COM_EASYSOCIAL_VIDEOS_OTHER_VIDEOS');?></div>

                <div class="es-related-videos es-video-list">
                    <?php foreach ($otherVideos as $otherVideo) { ?>
                    <div class="es-video-item">
                        <div class="es-video-thumbnail">
                            <a href="<?php echo $otherVideo->getPermalink();?>">
                                <div class="es-video-cover" style="background-image: url('<?php echo $otherVideo->getThumbnail();?>')"></div>

                                <div class="es-video-time">
                                    <?php echo $otherVideo->getDuration();?>
                                </div>
                            </a>
                        </div>

                        <div class="es-video-content">
                            <div class="es-video-title">
                                <a href="<?php echo $otherVideo->getPermalink();?>"><?php echo $otherVideo->getTitle();?></a>
                            </div>

                            <div class="es-video-meta mt-5">
                                <div>
                                    <a href="<?php echo $otherVideo->getAuthor()->getPermalink();?>">
                                        <i class="fa fa-user mr-5"></i> <?php echo $otherVideo->getAuthor()->getName();?>
                                    </a>
                                </div>

                                <div>
                                    <a href="<?php echo $otherVideo->getCategory()->getPermalink();?>">
                                        <i class="fa fa-folder mr-5"></i> <?php echo JText::_($otherVideo->getCategory()->title);?>
                                    </a>
                                </div>
                            </div>

                            <div class="es-video-stat mt-10">
                                <?php if ($this->config->get('video.layout.item.hits')) { ?>
                                <div>
                                    <i class="fa fa-eye"></i> <?php echo $otherVideo->getHits();?>
                                </div>
                                <?php } ?>

                                <div>
                                    <i class="fa fa-heart"></i> <?php echo $otherVideo->getLikesCount();?>
                                </div>

                                <div>
                                    <i class="fa fa-comment"></i> <?php echo $otherVideo->getCommentsCount();?>
                                </div>
                            </div>
                        </div>
                    </div>
                    <?php } ?>
                </div>
            </div><?php */?>
            <?php } ?>

            <?php echo $this->render('module' , 'es-videos-after-other-videos'); ?>
        </div>
    </div>
</div>
