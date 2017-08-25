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
<?php if (isset($filter) && $filter == 'pending') { ?>
    <div class="es-snackbar"><?php echo JText::_('COM_EASYSOCIAL_VIDEOS_PENDING_TITLE');?></div>
    <p class="pending-info"><?php echo JText::_('COM_EASYSOCIAL_VIDEOS_PENDING_INFO');?></p>
    <hr />
<?php } ?>

<?php if ((isset($isFeatured) && $isFeatured) || $filter == 'featured') { ?>
    <div class="es-snackbar">
        <?php echo JText::_("COM_EASYSOCIAL_VIDEOS_FEATURED_VIDEOS");?>
    </div>
<?php } else { ?>
    <div class="es-snackbar">
       <span>
        	<?php echo JText::_("COM_EASYSOCIAL_ROUTER_APPS_SORT_RECENT");?>
        </span>
        <span>
        	<?php echo JText::_("COM_EASYSOCIAL_VIDEOS");?>
        </span>
        <a style="float: right;" href="<?php echo JRoute::_("index.php?option=com_easysocial&view=videos&uid=".$user->getAlias()."&type=".SOCIAL_TYPE_USER."&subview=all") ?>">See All</a>
    </div>
<?php } ?>
    

<div class="es-video-list clearfix<?php echo !$videos ? ' is-empty' : '';?>">
    <?php if ($videos) { ?>
        <?php foreach ($videos as $video) {
		?>
        <div class="es-video-item col-md-4 col-sm-6" data-video-item
            data-id="<?php echo $video->id;?>"
        >
            <?php /* ?><?php if ($video->canFeature() || $video->canUnfeature() || $video->canDelete() || $video->canEdit()) { ?>
            <div class="es-video-item-action">
                <div class="pull-right dropdown_">
                    <a href="javascript:void(0);" class="btn btn-es btn-sm dropdown-toggle_" data-bs-toggle="dropdown"><i class="fa fa-cog"></i></a>
                    <ul class="dropdown-menu">
                        <?php if ($video->canFeature() || (!$video->table->isFeatured() && $video->canEdit())) { ?>
                        <li>
                            <a href="javascript:void(0);" data-video-feature data-return="<?php echo $returnUrl;?>"><?php echo JText::_('COM_EASYSOCIAL_VIDEOS_FEATURE_VIDEO');?></a>
                        </li>
                        <?php } ?>

                        <?php if ($video->canUnfeature() || ($video->table->isFeatured() && $video->canEdit())) { ?>
                        <li>
                            <a href="javascript:void(0);" data-video-unfeature data-return="<?php echo $returnUrl;?>"><?php echo JText::_('COM_EASYSOCIAL_VIDEOS_UNFEATURE_VIDEO');?></a>
                        </li>
                        <?php } ?>

                        <?php if ($video->canEdit()) { ?>
                        <li>
                            <a href="<?php echo $video->getEditLink();?>"><?php echo JText::_('COM_EASYSOCIAL_VIDEOS_EDIT_VIDEO'); ?></a>
                        </li>
                        <?php } ?>

                        <?php if ($video->canDelete()) { ?>
                        <li class="divider"></li>

                        <li>
                            <a href="javascript:void(0);" data-video-delete><?php echo JText::_('COM_EASYSOCIAL_VIDEOS_DELETE_VIDEO');?></a>
                        </li>
                        <?php } ?>
                    </ul>
                </div>
            </div>
            <?php } ?><?php */ ?>



            <div class="es-video-thumbnail">
                <a href="<?php echo $video->getPermalink();?>">
                    <div class="es-video-cover" style="background-image: url('<?php echo $video->getThumbnail();?>')"></div>
                    <div class="es-video-time"><?php echo $video->getDuration();?></div>
                </a>
            </div>
            <div class="es-video-content">
                <div class="es-video-title">
                    <a href="<?php echo $video->getPermalink();?>"><?php echo $video->getTitle();?></a>
                </div>
				<?php
				echo FD::date($video->getCreatedDate())->toLapsed();
				?>
                <?php if($video->canEdit()){ ?>
                    <div class="es-stream-control pull-right btn-group">
                        <a class="control-button" href="javascript:void(0);" data-bs-toggle="dropdown">
                            <i class="fa fa-ellipsis-h" aria-hidden="true"></i>
                        </a>
                        <ul class="dropdown-menu fd-reset-list" data-video-extras data-id="<?php echo $video->id; ?>">

                            <?php if (!$video->table->isFeatured() && $video->canEdit()){ ?>
                                <li>
                                    <a href="javascript:void(0);" data-cvideo-feature data-return="<?php echo $returnUrl;?>"><?php echo JText::_('COM_EASYSOCIAL_VIDEOS_FEATURE_VIDEO');?></a>
                                </li>
                            <?php } ?>

                            <?php if ($video->table->isFeatured() && $video->canEdit()){ ?>
                                <li>
                                    <a href="javascript:void(0);" data-cvideo-unfeature data-return="<?php echo $returnUrl;?>"><?php echo JText::_('COM_EASYSOCIAL_VIDEOS_UNFEATURE_VIDEO');?></a>
                                </li>
                            <?php } ?>

                            <?php if ($video->canEdit()) { ?>
                                <li>
                                    <a href="<?php echo $video->getEditLink();?>"><?php echo JText::_('COM_EASYSOCIAL_VIDEOS_EDIT_VIDEO'); ?></a>
                                </li>
                            <?php } ?>

                            <?php if ($video->canDelete()) { ?>
                                <li class="divider"></li>

                                <li>
                                    <a href="javascript:void(0);" data-video-delete><?php echo JText::_('COM_EASYSOCIAL_VIDEOS_DELETE_VIDEO');?></a>
                                </li>
                            <?php } ?>
                        </ul>
                    </div>
                <?php } ?>

            </div>
    
        </div>
        <?php } ?>

    <?php } else { ?>
    <div class="empty empty-hero">
        <i class="fa fa-film"></i>
        <div><?php echo JText::_('COM_EASYSOCIAL_VIDEOS_NO_VIDEOS_AVAILABLE_CURRENTLY');?></div>
    </div>
    <?php } ?>
</div>

<?php /*?><?php if ($videos && isset($pagination)) { ?>
<div class="mt-20 text-center es-pagination">
    <?php echo $pagination->getListFooter('site');?>
</div>
<?php } ?><?php */?>
