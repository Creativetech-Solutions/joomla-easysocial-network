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
<?php if ((isset($isFeatured) && $isFeatured) || $filter == 'featured') { ?>
    <div class="es-snackbar">
        <span>
        	<?php echo JText::_("COM_EASYSOCIAL_FEATURED");?>
        </span>
        <span>
        	<?php echo JText::_("COM_EASYSOCIAL_TRACK");?>
        </span>
    </div>
<?php } ?>
    

<div class="es-audios-list clearfix<?php echo !$audios ? ' is-empty' : '';?>">
    <?php if ($audios) { ?>
        <?php foreach ($audios as $audio) { 
		?>
        <div class="es-audio-item" data-audio-item
            data-id="<?php echo $audio->id;?>"
        >
        	<div class="col-md-6">        
       			<?php echo $this->output('site/audio/player', array('audio' => $audio)); ?>
                    <?php /*?><div class="es-video-thumbnail">
                        <a href="<?php echo $audio->getPermalink();?>">
                            
                        </a>
                    </div><?php */?>
            </div>
            <div class="col-md-6">
            	<div class="es-audio-content">
                	<div class="es-audio-title">
                        <a href="<?php echo $audio->getPermalink();?>"><?php echo $audio->getTitle();?></a>
                    </div>
                    <div class="es-audio-meta mt-5">
                        <div>
                            <div class="es-avatar es-avatar-sm es-stream-avatar pull-left" data-comments-item-avatar="">
                                <?php if ($audio->getAuthor()) { ?>
                                    <?php echo $this->loadTemplate('site/avatar/default', array('user' => $audio->getAuthor())); ?>						
                                <?php } else { ?>
                                    <img src="<?php echo $audio->getAuthor()->getAvatar();?>" alt="<?php echo $this->html('string.escape', $audio->getAuthor()->getName());?>" />
                                <?php } ?>
                            </div>
                            <a href="<?php echo $audio->getAuthor()->getPermalink();?>" class="video-author">
                                <?php echo $audio->getAuthor()->getName();?>
                            </a>
                        </div>
                    </div>
                    <span class="time-span">
                        <?php
                        echo FD::date($audio->getCreatedDate())->toLapsed();
                        ?>
                    </span>
                    <div class="es-audio-brief mt-10"><?php echo $audio->getDescription();?></div>
                    <?php /*?><div class="video-catergory">
                        <a href="<?php echo $audio->getCategory()->getPermalink();?>">
                            <i class="fa fa-folder mr-5"></i> <?php echo JText::_($video->getCategory()->title);?>
                        </a>
                    </div><?php */?>
                    <?php /*?><div class="es-video-stat mt-10">
                        <?php if ($this->config->get('video.layout.item.hits')) { ?>
                        <div>
                            <i class="fa fa-eye"></i> <?php echo $video->getHits();?>
                        </div>
                        <?php } ?>
    
                        <div>
                            <i class="fa fa-heart"></i> <?php echo $video->getLikesCount();?>
                        </div>
    
                        <div>
                            <i class="fa fa-comment"></i> <?php echo $video->getCommentsCount();?>
                        </div>
                    </div><?php */?>
                    <div class="es-stream-actions">
                         <?php echo $audio->getComments('create')->getHTML();?>
                    </div>
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
                    <?php /*?><div class="es-stream-actions video-likes">
                        <?php echo $video->getLikes('create')->html();?>
                    </div><?php */?>
                </div>
            </div>
        </div>
        <?php } ?>

    <?php } else { ?>
    <div class="empty empty-hero">
        <i class="fa fa-film"></i>
        <div><?php echo JText::_('COM_EASYSOCIAL_NO_AUDIOS_AVAILABLE_CURRENTLY');?></div>
    </div>
    <?php } ?>
</div>

<?php /*?><?php if ($videos && isset($pagination)) { ?>
<div class="mt-20 text-center es-pagination">
    <?php echo $pagination->getListFooter('site');?>
</div>
<?php } ?><?php */?>
