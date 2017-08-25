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
<div class="es-snackbar">
    <span>
		<?php echo JText::_("COM_EASYSOCIAL_ROUTER_APPS_SORT_RECENT");?>
    </span>
    <span>
        <?php echo JText::_("COM_EASYSOCIAL_UPLOADS");?>
    </span>
</div>
<div class="es-audio-list clearfix<?php echo !$audios ? ' is-empty' : '';?>">
    <?php if ($audios) { ?>
        <?php foreach ($audios as $audio) { ?>
        <div class="es-audio-item col-md-4 col-sm-6" data-video-item
            data-id="<?php echo $audio->id;?>"
        >
            <div class="es-audio-content">
                <div class="es-audio-title">
                    <!--<a href="<?php echo $audio->getPermalink();?>">-->
					<?php echo $audio->getTitle();?>
                    <!--</a>-->
                </div>

                <?php /*?><div class="es-audio-meta mt-5">
                    <div>
                        <a href="<?php echo $audio->getAuthor()->getPermalink();?>">
                            <i class="fa fa-user mr-5"></i> <?php echo $audio->getAuthor()->getName();?>
                        </a>
                    </div>
                </div><?php */?>
                
                 <?php echo $this->output('site/audio/player', array('audio' => $audio)); ?>
<?php /*?>
                <div class="es-audio-stat mt-10">
                    <div>
                        <i class="fa fa-eye"></i> <?php echo $audio->getHits();?>
                    </div>
                    <div>
                        <i class="fa fa-heart"></i> <?php echo $audio->getLikesCount();?>
                    </div>

                    <div>
                        <i class="fa fa-comment"></i> <?php echo $audio->getCommentsCount();?>
                    </div>
                </div><?php */?>
                <?php
				echo FD::date($audio->getCreatedDate())->toLapsed();
				?>
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
