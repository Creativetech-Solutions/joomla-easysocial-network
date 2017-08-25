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
$sort = 'latest';
if(JRequest::getVar('sort')){
	$sort = JRequest::getVar('sort');
}
$script = '
jQuery(document).ready(function($) {
	
	$(".es-video-item").each(function(){
		var com_form = $(this).find(".es-comments-form");
		var loadmore = $(this).find(".es-comments-control");
		
		$(this).find(".es-comments-form").remove();
		$(this).find(".es-comments-control").remove();
		
		$(this).find("ul.fd-reset-list.es-comments").parent().prepend(com_form);
		$(this).find("ul.fd-reset-list.es-comments").parent().append(loadmore);
	});
});
';
JFactory::getDocument()->addScriptDeclaration($script);
?>

<div class="dashboard_header">
	<h1> <span><?php echo $user->getName().'\'s </span> <span>Videos</span>';?> </h1>
    <div class="nav-back">
    	<a href="<?php echo base64_decode($returnUrl) ?>">Back</a> to <?php echo '<span>'.$user->getName().'\'s</span> Profile';?>
    </div>
</div>


<div class="es-video-list all-items-layout clearfix<?php echo !$videos ? ' is-empty' : '';?>">
  <div class="es-video-list-container"> 
    
    <!-- Nav tabs -->
    <ul class="nav nav-tabs" role="tablist">
      <li role="presentation" class="active"> <a href="#videos" role="tab" data-toggle="tab"> <span> <?php echo count($videos) + count($featuredVideos); ?> </span> Videos </a> </li>
      <li role="presentation"> <a href="#featured" aria-controls="profile" role="tab" data-toggle="tab"> <span> <?php echo count($featuredVideos); ?> </span> Featured </a> </li>
    </ul>
    
    <!--add video if logged in user viewing his own-->
    <?php if ($allowCreation) { ?>
    <div class="es-widget-create mr-10 add-video-container">
        <a class="btn-add-video pull-right" href="<?php echo $createLink;?>">
            <?php echo JText::_('COM_EASYSOCIAL_VIDEOS_ADD_VIDEO');?>
        </a>
    </div>
    <?php } ?>
    <!--sorting container-->
    <div class="videos_sort">
          <ul class="">
          	<li>
            	<span>Sort: </span>
            </li>
            <li class="<?php echo ($sort == 'latest') ? 'active' : ''; ?>" data-popdown-option="latest"> 
                <a href="<?php echo JRoute::_("index.php?option=com_easysocial&view=videos&uid=".$user->getAlias()."&type=".SOCIAL_TYPE_USER."&subview=all&sort=latest") ?>" data-sorting="" data-filter="all" data-type="latest">Date
                </a>
            </li>
            <li class="<?php echo ($sort == 'alphabetical') ? 'active' : ''; ?>" data-popdown-option="alphabetical">
                <a href="<?php echo JRoute::_("index.php?option=com_easysocial&view=videos&uid=".$user->getAlias()."&type=".SOCIAL_TYPE_USER."&subview=all&sort=alphabetical") ?>" data-sorting="" data-filter="all" data-type="alphabetical">Title
                </a>
            </li>
            <li class="<?php echo ($sort == 'popular') ? 'active' : ''; ?>" data-popdown-option="popular">
                <a href="<?php echo JRoute::_("index.php?option=com_easysocial&view=videos&uid=".$user->getAlias()."&type=".SOCIAL_TYPE_USER."&subview=all&sort=popular") ?>" data-sorting="" data-filter="all" data-type="popular">Plays
                </a>
            </li>
            <li class="<?php echo ($sort == 'likes') ? 'active' : ''; ?>" data-popdown-option="likes">
                <a href="<?php echo JRoute::_("index.php?option=com_easysocial&view=videos&uid=".$user->getAlias()."&type=".SOCIAL_TYPE_USER."&subview=all&sort=likes") ?>" data-sorting="" data-filter="all" data-type="likes">Likes
                </a>
            </li>
          </ul>
    </div>
    <!-- Tab panes -->
    <div class="tab-content">
      <div role="tabpanel" class="tab-pane active" id="videos">
        <?php if ($videos) {
			if($featuredVideos)	{
				$videos = array_merge($videos,$featuredVideos);	
			}
		?>
        <?php foreach ($videos as $video) { 
                ?>
        <div class="es-video-item" data-video-item
                    data-id="<?php echo $video->id;?>"
                >
          <div class="col-md-3">
              <?php /*?><?php if ($video->canFeature() || $video->canUnfeature() || $video->canDelete() || $video->canEdit()) { ?>
            <div class="es-video-item-action">
              <div class="pull-right dropdown_"> <a href="javascript:void(0);" class="btn btn-es btn-sm dropdown-toggle_" data-bs-toggle="dropdown"><i class="fa fa-cog"></i></a>
                <ul class="dropdown-menu">
                  <?php if ($video->canFeature()) { ?>
                  <li> <a href="javascript:void(0);" data-video-feature data-return="<?php echo $returnUrl;?>"><?php echo JText::_('COM_EASYSOCIAL_VIDEOS_FEATURE_VIDEO');?></a> </li>
                  <?php } ?>
                  <?php if ($video->canUnfeature()) { ?>
                  <li> <a href="javascript:void(0);" data-video-unfeature data-return="<?php echo $returnUrl;?>"><?php echo JText::_('COM_EASYSOCIAL_VIDEOS_UNFEATURE_VIDEO');?></a> </li>
                  <?php } ?>
                  <?php if ($video->canEdit()) { ?>
                  <li> <a href="<?php echo $video->getEditLink();?>"><?php echo JText::_('COM_EASYSOCIAL_VIDEOS_EDIT_VIDEO'); ?></a> </li>
                  <?php } ?>
                  <?php if ($video->canDelete()) { ?>
                  <li class="divider"></li>
                  <li> <a href="javascript:void(0);" data-video-delete><?php echo JText::_('COM_EASYSOCIAL_VIDEOS_DELETE_VIDEO');?></a> </li>
                  <?php } ?>
                </ul>
              </div>
            </div>
            <?php } ?><?php */?>
            <div class="es-video-thumbnail"> <a href="<?php echo $video->getPermalink();?>">
              <div class="es-video-cover" style="background-image: url('<?php echo $video->getThumbnail();?>')"></div>
              <div class="es-video-time"><?php echo $video->getDuration();?></div>
              </a> </div>
          </div>
          <div class="col-md-9">
            <div class="es-video-content">
              <div class="es-video-title"> <a href="<?php echo $video->getPermalink();?>"><?php echo $video->getTitle();?></a> </div>
              <div class="es-video-meta mt-5">
                <div>
                  <a href="<?php echo $video->getAuthor()->getPermalink();?>" class="video-author"> <?php echo 'from '.$video->getAuthor()->getName();?> </a> </div>
              </div>
              <span class="time-span">
              <?php
                                echo FD::date($video->getCreatedDate())->toLapsed();
                                ?>
              </span>
              <div class="es-video-brief mt-10"><?php echo $video->getDescription();?></div>
              <div class="video-catergory"> <a href="<?php echo $video->getCategory()->getPermalink();?>"> <i class="fa fa-folder mr-5"></i> <?php echo JText::_($video->getCategory()->title);?> </a> </div>
              <?php /*?><div class="es-stream-actions"> <?php echo $video->getComments('create')->getHTML();?> </div><?php */?>
            </div>
            <div class="es-video-response">
              <div class="es-action-wrap pr-20">
                <ul class="fd-reset-list es-action-feedback">
                  <li class="arepost">
                    <?php
                                        $repost = FD::get('Repost', $video->id , SOCIAL_TYPE_VIDEOS, SOCIAL_APPS_GROUP_USER );
                                        ?>
                    <a data-stream-action-repost href="javascript:void(0);" class="fd-small arepost"><?php echo $repost->getButton(); ?></a> </li>
                  <li> <a href="javascript:void(0)" class="acomments"></a> </li>
                  <li class="alikes"> <?php echo $video->getLikes('create')->button();?> </li>
                  <li> <span class="ahits"> <?php echo $video->getHits(); ?> </span> </li>
                  
                  <!--<li class="video-sharing">
                                        <?php echo $video->getSharing()->html(false); ?>
                                    </li>-->
                </ul>
              </div>
                <?php if($video->canEdit()){ ?>
                    <div class="es-stream-control pull-right btn-group">
                        <a class="control-button" href="javascript:void(0);" data-bs-toggle="dropdown">
                            <i class="fa fa-ellipsis-h" aria-hidden="true"></i>
                        </a>
                        <ul class="dropdown-menu fd-reset-list">

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
                <?php } ?>
            </div>
          </div>
            <div class="es-video-comments">
                <div class="es-stream-actions">
                     <?php echo $video->getComments('create')->getHTML();?>
                </div>
            </div>
            <div class="clearfix"></div>
        </div>
        <?php } ?>
        <?php } else { ?>
        <div class="empty empty-hero"> <i class="fa fa-film"></i>
          <div><?php echo JText::_('COM_EASYSOCIAL_VIDEOS_NO_VIDEOS_AVAILABLE_CURRENTLY');?></div>
        </div>
        <?php } ?>
      </div>
      <div role="tabpanel" class="tab-pane" id="featured">
        <?php if ($featuredVideos) { ?>
        <?php foreach ($featuredVideos as $video) { 
                ?>
        <div class="es-video-item" data-video-item
                    data-id="<?php echo $video->id;?>"
                >
          <div class="col-md-3">
              <?php /* ?><?php if ($video->canFeature() || $video->canUnfeature() || $video->canDelete() || $video->canEdit()) { ?>
            <div class="es-video-item-action">
              <div class="pull-right dropdown_"> <a href="javascript:void(0);" class="btn btn-es btn-sm dropdown-toggle_" data-bs-toggle="dropdown"><i class="fa fa-cog"></i></a>
                <ul class="dropdown-menu">
                  <?php if ($video->canFeature()) { ?>
                  <li> <a href="javascript:void(0);" data-video-feature data-return="<?php echo $returnUrl;?>"><?php echo JText::_('COM_EASYSOCIAL_VIDEOS_FEATURE_VIDEO');?></a> </li>
                  <?php } ?>
                  <?php if ($video->canUnfeature()) { ?>
                  <li> <a href="javascript:void(0);" data-video-unfeature data-return="<?php echo $returnUrl;?>"><?php echo JText::_('COM_EASYSOCIAL_VIDEOS_UNFEATURE_VIDEO');?></a> </li>
                  <?php } ?>
                  <?php if ($video->canEdit()) { ?>
                  <li> <a href="<?php echo $video->getEditLink();?>"><?php echo JText::_('COM_EASYSOCIAL_VIDEOS_EDIT_VIDEO'); ?></a> </li>
                  <?php } ?>
                  <?php if ($video->canDelete()) { ?>
                  <li class="divider"></li>
                  <li> <a href="javascript:void(0);" data-video-delete><?php echo JText::_('COM_EASYSOCIAL_VIDEOS_DELETE_VIDEO');?></a> </li>
                  <?php } ?>
                </ul>
              </div>
            </div>
            <?php } ?><?php */ ?>
            <div class="es-video-thumbnail"> <a href="<?php echo $video->getPermalink();?>">
              <div class="es-video-cover" style="background-image: url('<?php echo $video->getThumbnail();?>')"></div>
              <div class="es-video-time"><?php echo $video->getDuration();?></div>
              </a> </div>
          </div>
          <div class="col-md-9">
            <div class="es-video-content">
              <div class="es-video-title"> <a href="<?php echo $video->getPermalink();?>"><?php echo $video->getTitle();?></a> </div>
              <div class="es-video-meta mt-5">
                <div>
                  <div class="es-avatar es-avatar-sm es-stream-avatar pull-left" data-comments-item-avatar="">
                    <?php if ($video->getAuthor()) { ?>
                    <?php echo $this->loadTemplate('site/avatar/default', array('user' => $video->getAuthor())); ?>
                    <?php } else { ?>
                    <img src="<?php echo $video->getAuthor()->getAvatar();?>" alt="<?php echo $this->html('string.escape', $video->getAuthor()->getName());?>" />
                    <?php } ?>
                  </div>
                  <a href="<?php echo $video->getAuthor()->getPermalink();?>" class="video-author"> <?php echo $video->getAuthor()->getName();?> </a> </div>
              </div>
              <span class="time-span">
              <?php
                                echo FD::date($video->getCreatedDate())->toLapsed();
                                ?>
              </span>
              <div class="es-video-brief mt-10"><?php echo $video->getDescription();?></div>
              <div class="video-catergory"> <a href="<?php echo $video->getCategory()->getPermalink();?>"> <i class="fa fa-folder mr-5"></i> <?php echo JText::_($video->getCategory()->title);?> </a> </div>
              <div class="es-stream-actions"> <?php echo $video->getComments('create')->getHTML();?> </div>
            </div>
            <div class="es-video-response">
              <div class="es-action-wrap pr-20">
                <ul class="fd-reset-list es-action-feedback">
                  <li class="arepost">
                    <?php
                                        $repost = FD::get('Repost', $video->id , SOCIAL_TYPE_VIDEOS, SOCIAL_APPS_GROUP_USER );
                                        ?>
                    <a data-stream-action-repost href="javascript:void(0);" class="fd-small arepost"><?php echo $repost->getButton(); ?></a> </li>
                  <li> <a href="javascript:void(0)" class="acomments"></a> </li>
                  <li class="alikes"> <?php echo $video->getLikes('create')->button();?> </li>
                  <li> <span class="ahits"> <?php echo $video->getHits(); ?> </span> </li>
                  
                  <!--<li class="video-sharing">
                                        <?php echo $video->getSharing()->html(false); ?>
                                    </li>-->
                </ul>
              </div>
                <?php if($video->canEdit()){ ?>
                    <div class="es-stream-control pull-right btn-group">
                        <a class="control-button" href="javascript:void(0);" data-bs-toggle="dropdown">
                            <i class="fa fa-ellipsis-h" aria-hidden="true"></i>
                        </a>
                        <ul class="dropdown-menu fd-reset-list">

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
                <?php } ?>
            </div>
          </div>
            <div class="es-video-comments">
                <div class="es-stream-actions">
                    <?php echo $video->getComments('create')->getHTML();?>
                </div>
            </div>
            <div class="clearfix"></div>
        </div>
        <?php } ?>
        <?php } else { ?>
        <div class="empty empty-hero"> <i class="fa fa-film"></i>
          <div><?php echo JText::_('COM_EASYSOCIAL_VIDEOS_NO_VIDEOS_AVAILABLE_CURRENTLY');?></div>
        </div>
        <?php } ?>
      </div>
    </div>
  </div>
</div>
<?php /*?><?php if ($videos && isset($pagination)) { ?>
<div class="mt-20 text-center es-pagination">
    <?php echo $pagination->getListFooter('site');?>
</div>
<?php } ?><?php */?>
