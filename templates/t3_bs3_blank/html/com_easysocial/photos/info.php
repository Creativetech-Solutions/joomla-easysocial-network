<?php
/**
* @package      EasySocial
* @copyright    Copyright (C) 2010 - 2014 Stack Ideas Sdn Bhd. All rights reserved.
* @license      GNU/GPL, see LICENSE.php
* EasySocial is free software. This version may have been modified pursuant
* to the GNU General Public License, and as distributed it includes or
* is derivative of works licensed under the GNU General Public License or
* other free or open source software licenses.
* See COPYRIGHT.php for copyright notices and details.
*/
defined('_JEXEC') or die('Unauthorized Access');
?>
<div data-photo-info class="photo-single-conent-body">
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
<div data-photo-album class="es-photo-album"><?php echo JText::_("COM_EASYSOCIAL_PHOTOS_FROM_ALBUM"); ?> <a href="<?php echo $album->getPermalink(); ?>"><?php echo $album->get( 'title' ); ?></a></div>
<div class="es-photo-brief mt-10">
    <?php echo $photo->get('caption'); ?>
</div>
</div>
