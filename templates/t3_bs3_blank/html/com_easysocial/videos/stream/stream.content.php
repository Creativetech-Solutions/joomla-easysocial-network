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
defined('_JEXEC') or die('Unauthorized Access');

//figure out if a user shared a video and commented on it
if($stream->content && $video->description){
    $video->description = $stream->content.'<span class="video-original-desc">'.$video->description.'</span>';
}

?>
<?php /*if ($stream->content) { ?>
    <div class="stream-contents mb-10">
        <?php echo $stream->content; ?>
    </div>
<?php } */ ?>

<div class="stream-links">


    <div class="links-content" data-video-wrapper>

        <div class="es-stream-preview">
            <div class="video-container">
                <?php echo $video->getEmbedCodes(); ?>
            </div>
            <div class="es-stream-meta">
                <div class="media">
                    <div class="media-object pull-left">
                        <?php if ($this->config->get('stream.pin.enabled')) { ?>
                            <div class="es-stream-sticky-label" data-es-provide="tooltip" data-original-title="<?php echo JText::_('COM_EASYSOCIAL_STREAM_YOU_HAVE_PINNED_THIS_STREAM'); ?>"><i class="fa fa-star"></i></div>
                        <?php } ?>
                        <div class="es-avatar es-avatar-sm es-stream-avatar" data-comments-item-avatar="">
                            <?php if ($stream->actor->id) { ?>
                                <?php echo $this->loadTemplate('site/avatar/default', array('user' => $stream->actor)); ?>						
                            <?php } else { ?>
                                <img src="<?php echo $stream->actor->getAvatar(); ?>" alt="<?php echo $this->html('string.escape', $stream->actor->getName()); ?>" />
                            <?php } ?>
                        </div>
                    </div>
                    <span class="actor-column">
                        <?php echo $stream->actor->getName(); ?>
                    </span>
                    <?php if ($this->config->get('stream.timestamp.enabled')) { ?>
                        <span class="pull-right datecolumn">
                            <a href="<?php echo FRoute::stream(array('id' => $stream->uid, 'layout' => 'item')); ?>"><?php echo $stream->friendlyDate; ?></a>
                        </span>
                    <?php } ?>
                </div>
                
            </div>
            <p class="preview-desc"><?php echo $video->description; ?></p>
        </div>
    </div>
    <h4 class="es-stream-content-title has-info">
        <a href="<?php echo $video->getPermalink(); ?>"><?php echo $video->title; ?></a>
    </h4>
</div>
