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
/*$script = '
jQuery(document).ready(function($) {
	var vheight = jQuery(".es-video-content-body").height();
	$("body").prepend("<div class=\"video-bg\" style=\"background: #000;position: absolute;width: 100%;height:"+vheight+"px;z-index: -1;\"></div>");
});
';
JFactory::getDocument()->addScriptDeclaration($script);*/
$uri    = JURI::getInstance();
$url    = $uri->root();
?>
<?php echo $audio->getMiniHeader();?>

<div class="es-container es-audios" data-audio-item data-id="<?php echo $audio->id;?>">
    <div class="es-content">

        <?php //echo $this->render('module' , 'es-videos-before-video'); ?>

        <div class="es-audio-single es-responsive">

            <div class="es-audio-content-body">
                <?php if ($audio->isPendingProcess()) { ?>
                <div class="alert alert-info">
                    <?php echo JText::_('COM_EASYSOCIAL_VIDEOS_ITEM_PENDING_INFO');?>
                </div>
                <?php } ?>

                <div class="audio-container">
                    <?php //echo $audio->getEmbedCodes(); ?>
                </div>
            </div>

            <?php echo $this->render('module' , 'es-videos-after-video'); ?>

            <div class="es-audio-content-brief">
            	
                <div class="es-audio-manage row-table mb-15 mt-15">
                    <div class="col-cell">
                        <a href="<?php echo $backLink;?>">&larr; <?php echo JText::_('Back to Tracks'); ?></a>
                    </div>
    
                </div>
                
                <div class="mt-20">
                	<div class="es-snackbar"><?php echo 'Track Details';?></div>
                    
                    <div class="audio-single-conent-body">
                        <h2 class="es-audio-title single"><?php echo $audio->getTitle();?></h2>
                        <div class="es-audio-meta mt-10 mb-10">
                            <?php if ($audio->table->isFeatured()) { ?>
                            <span>
                                <i class="fa fa-star mr-5"></i><b class="text-success"><?php echo JText::_('COM_EASYSOCIAL_VIDEOS_FEATURED');?></b>
                            </span>
                            <?php } ?>
                            <span>
                                <i class="fa fa-user mr-5"></i><?php echo $this->html('html.user', $audio->getAuthor());?>
                            </span>
                            <?php echo $this->output('site/audio/player', array('audio' => $audio,'url' => $url)); ?>
                            <?php /*?><span>
                                <i class="fa fa-folder mr-5"></i>
                                <a href="<?php echo $audio->getCategory()->getPermalink(true, $audio->uid, $audio->type);?>"><?php echo JText::_($audio->getCategory()->title);?></a>
                            </span><?php */?>
                            <span>
                                <i class="fa fa-clock-o"></i> <?php echo $audio->getCreatedDate()->format(JText::_('COM_EASYSOCIAL_VIDEOS_DATE_FORMAT'));
                                ?>
                            </span>
                        </div>

						<?php echo $this->render('module' , 'es-videos-before-video-description'); ?>
    
                        <div class="es-audio-brief mt-10"><?php echo $audio->getDescription();?></div>
    
                        <?php echo $this->render('module' , 'es-videos-after-video-description'); ?>
    
                        <?php if ($this->config->get('video.layout.item.duration')) { ?>
                        <div class="es-audio-duration mt-10">
                            <?php echo JText::_('COM_EASYSOCIAL_VIDEOS_VIDEO_DURATION');?>
                            <?php echo $audio->getDuration();?>
                        </div>
                        <?php } ?>
    
                        
    
                        
                        <?php echo $this->render('module' , 'es-videos-after-video-tags'); ?>
                     </div>
                     <div class="es-audio-response">
                        <div class="es-action-wrap pr-20">
                            <ul class="fd-reset-list es-action-feedback">
                            	<li class="arepost">
									<?php
                                    $repost = FD::get('Repost', $audio->id , 'audios', SOCIAL_APPS_GROUP_USER );
                                    ?>
                                    <a data-stream-action-repost href="javascript:void(0);" class="fd-small arepost"><?php echo $repost->getButton(); ?></a>
                                </li>
                                <li>
                                    <a href="javascript:void(0)" class="acomments"></a>
                                </li>
                                <li class="alikes">
                                    <?php echo $audio->getLikes('create')->button();?>
                                </li>
                                
                                <li>
                                    <span class="ahits">
                                        <?php echo $audio->getHits(); ?>
                                    </span>
                                </li>
                                
                            </ul>
                        </div>
        
                        <div class="es-stream-actions audio-likes">
                            <?php echo $likes->html();?>
                        </div>
                    </div>
                </div>
            </div>

            <hr class="es-hr" />
            
            <div class="es-audio-comments">
            	<div class="es-stream-actions">
                    <?php echo $comments->getHTML();?>
                </div>
            </div>

            <?php echo $this->render('module' , 'es-videos-before-other-videos'); ?>

            <?php echo $this->render('module' , 'es-videos-after-other-videos'); ?>
        </div>
    </div>
</div>
