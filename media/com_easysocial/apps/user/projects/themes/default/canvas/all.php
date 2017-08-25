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
$uid = $this->input->get('uid', null, 'int');
$type = $this->input->get('type', SOCIAL_TYPE_USER, 'cmd');

$user = FD::user();
// If this is a user type, we will want to get a list of albums the current logged in user created
/* if ($type == SOCIAL_TYPE_USER) {
  $user = FD::user($uid);
  $uid = $user->id;
  } */
if ($type == SOCIAL_TYPE_USER && $uid != $user->id) {
    $allowCreation = false;
} else {
    $allowCreation = true;
}
if($uid){
    $user = FD::user($uid);
}

$sort = 'latest';
if (JRequest::getVar('sort')) {
    $sort = JRequest::getVar('sort');
}
$script = '
jQuery(document).ready(function($) {
	
	$(".es-project-item").each(function(){
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
<div data-es-projects class="es-container es-projects" data-projects-listing>
    <div class="es-container">
        <div class="es-content">
            <div class="dashboard_header">
                <h1> <span><?php echo $user->getName() . '\'s </span> <span>Projects</span>'; ?> </h1>
                <div class="nav-back">
                    <a href="<?php echo FRoute::apps(array('layout' => 'canvas', 'id' => $app->getAlias(), 'uid' => $user->getAlias(), 'type' => SOCIAL_TYPE_USER)) ?>">Back</a> to <?php echo '<span>' . $user->getName() . '\'s</span> Profile'; ?>
                </div>
            </div>
            <div class="es-project-list clearfix<?php echo!$projects ? ' is-empty' : ''; ?> js-eq-height-container">
                <!-- Nav tabs -->
                <ul class="nav nav-tabs" role="tablist">
                    <li role="presentation" class="active"> <a href="#projects" role="tab" data-toggle="tab"> <span> <?php echo count($projects); ?> </span> Projects </a> </li>
                    <li role="presentation"> <a href="#featured" aria-controls="profile" role="tab" data-toggle="tab"> <span> <?php echo count($featuredProjects); ?> </span> Featured </a> </li>
                </ul>

                <!--add video if logged in user viewing his own-->
                <?php if ($allowCreation) { ?>
                    <div class="es-widget-create mr-10 add-project-container">
                        <a class="btn-add-project pull-right" href="<?php echo FRoute::apps(array('layout' => 'canvas', 'clayout' => 'form', 'id' => $app->getAlias())); ?>">
                            <i class="fa fa-cloud-upload" aria-hidden="true"></i>  Add Project
                        </a>
                    </div>
                <?php } ?>
                <!--sorting container-->
                <div class="projects_sort">
                    <ul class="">
                        <li>
                            <span>Sort: </span>
                        </li>
                        <li class="<?php echo ($sort == 'latest') ? 'active' : ''; ?>" data-popdown-option="latest"> 
                            <a href="<?= FRoute::apps(array('layout' => 'canvas', 'clayout' => 'all', 'id' => $app->getAlias(), 'uid' => $user->getAlias(), 'type' => SOCIAL_TYPE_USER, 'sort' => 'latest')) ?>" data-sorting="" data-filter="all" data-type="latest">Date
                            </a>
                        </li>
                        <li class="<?php echo ($sort == 'likes') ? 'active' : ''; ?>" data-popdown-option="latest"> 
                            <a href="<?= FRoute::apps(array('layout' => 'canvas', 'clayout' => 'all', 'id' => $app->getAlias(), 'uid' => $user->getAlias(), 'type' => SOCIAL_TYPE_USER, 'sort' => 'likes')) ?>" data-sorting="" data-filter="all" data-type="likes">Likes
                            </a>
                        </li>
                        <li class="<?php echo ($sort == 'alphabetical') ? 'active' : ''; ?>" data-popdown-option="latest"> 
                            <a href="<?= FRoute::apps(array('layout' => 'canvas','uid' => $user->getAlias(), 'type' => SOCIAL_TYPE_USER, 'clayout' => 'all', 'id' => $app->getAlias(), 'sort' => 'alphabetical')) ?>" data-sorting="" data-filter="all" data-type="alphabetical">Title
                            </a>
                        </li>
                    </ul>
                </div>
                <!-- Tab panes -->
                <div class="tab-content">
                    <div role="tabpanel" class="tab-pane active" id="projects">
                        <?php if ($projects) { ?>
                            <?php
                            foreach ($projects as $project) {
                                $streamId = $project->getStreamId('create');

                                // Retrieve the comments library
                                $comments = $project->getComments(); //changed create to add. hmmmm 
                                // Retrieve the likes library
                                $likes = $project->getLikes('create', $streamId);
                                ?>
                                <div class="es-project-item" data-apps-projects-item data-id="<?php echo $project->id; ?>"
                                     >
                                    <div class="col-md-5 js-eq-height">
                                        <div class="es-project-thumbnail"> <a href="<?php echo $project->getPermalink('detail'); ?>">
                                                <div class="es-project-cover" style="background-image: url('<?php echo $project->getThumbnail(); ?>')"></div>
                                            </a> </div>
                                    </div>
                                    <div class="col-md-7 js-eq-height">
                                        <div class="content-wrapper">
                                            <div class="es-project-content content-box">
                                                <div class="es-project-title content-title">
                                                    <a href="<?php echo $project->getPermalink('detail'); ?>"><?php echo $project->getTitle(); ?></a>
                                                </div>
                                                <div class="es-project-meta  content-avatar-area">
                                                    <div class="es-avatar es-avatar-sm es-stream-avatar pull-left" data-comments-item-avatar="">
                                                        <?php if ($project->getAuthor()) { ?>
                                                            <?php echo $this->loadTemplate('site/avatar/default', array('user' => $project->getAuthor())); ?>						
                                                        <?php } else { ?>
                                                            <img src="<?php echo $project->getAuthor()->getAvatar(); ?>" alt="<?php echo $this->html('string.escape', $project->getAuthor()->getName()); ?>" />
                                                        <?php } ?>
                                                    </div>
                                                    <a href="<?php echo $project->getAuthor()->getPermalink(); ?>" class="actor-column">
                                                        <?php echo $project->getAuthor()->getName(); ?>
                                                    </a>
                                                </div>
                                                <span class="time-span">
                                                    <?php
                                                    echo FD::date($project->getCreatedDate())->toLapsed();
                                                    ?>
                                                </span>
                                                <div class="es-project-brief content-description mt-10"><?php echo $project->getShortSummary(); ?></div>
                                                <?php if ($project->getCategory()) { ?>
                                                    <div class="category-box"><i class="fa fa-tag" aria-hidden="true"></i> <?= $project->category->getTitle() ?></div>
                                                <?php } ?>
                                            </div>
                                            <div class="content-bottom">

                                                <div class="es-action-wrap pr-20">
                                                    <ul class="fd-reset-list es-action-feedback">
                                                        <?php
                                                        $repost = FD::get('Repost', $project->id, 'projects', SOCIAL_APPS_GROUP_USER);
                                                        ?>
                                                        <li class="action-title-repost streamAction">
                                                            <span>
                                                                <a data-stream-action-repost href="javascript:void(0);" class="fd-small arepost iconitem-parent"><?php echo $repost->getButton(); ?></a>
                                                            </span>
                                                            <span class="numcount"><?php
                                                                if (isset($repost)) {
                                                                    echo $repost->getCount();
                                                                }
                                                                ?>
                                                            </span>
                                                        </li>
                                                        <li class="action-title-comments streamAction"
                                                            data-key="comments"
                                                            data-streamItem-actions
                                                            >
                                                            <span class="iconitem-parent"><a data-stream-action-comments href="javascript:void(0);" class="fd-small acomments"><?php echo JText::_('COM_EASYSOCIAL_STREAM_COMMENT'); ?></a></span>
                                                            <span class="numcount"><?php
                                                                if (isset($comments)) {
                                                                    echo $comments->getCount();
                                                                }
                                                                ?>
                                                            </span>
                                                        </li>
                                                        <li class="action-title-likes">
                                                            <?php echo $likes->button(); ?>
                                                        </li>
                                                    </ul>
                                                </div>

                                                <div class="es-stream-control pull-right btn-group">
                                                    <a class="control-buton" href="javascript:void(0);" data-bs-toggle="dropdown">
                                                        <i class="fa fa-ellipsis-h" aria-hidden="true"></i>
                                                    </a>
                                                    <ul class="dropdown-menu fd-reset-list">
                                                        <li>
                                                            <a href="<?= $project->getEditLink() ?>">
                                                                <?php echo JText::_('APP_PROJECTS_EDIT_BUTTON'); ?>
                                                            </a>
                                                        </li>
                                                        <li>
                                                            <a href="javascript:void(0);" data-apps-projects-delete data-id="<?php echo $project->id; ?>">
                                                                <?php echo JText::_('APP_PROJECTS_DELETE_BUTTON'); ?>
                                                            </a>
                                                        </li>
                                                        <li>
                                                            <a href="javascript:void(0);" data-apps-projects-feature data-id="<?php echo $project->id; ?>">
                                                                <?php
                                                                $featureText = !$project->isFeatured() ? 'APP_PROJECTS_FEATURE_BUTTON' : 'APP_PROJECTS_UNFEATURE_BUTTON';
                                                                echo JText::_($featureText);
                                                                ?>
                                                            </a>
                                                        </li>
                                                    </ul>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="es-project-comments content-wrapper">
                                        <div class="es-stream-actions">
                                            <?php echo $project->getComments('create')->getHTML(); ?>
                                        </div>
                                    </div>
                                </div>
                            <?php } ?>

                        <?php } else { ?>
                            <div class="empty empty-hero">
                                <i class="fa fa-film"></i>
                                <div><?php echo JText::_('COM_EASYSOCIAL_NO_PROJECTS_AVAILABLE_CURRENTLY'); ?></div>
                            </div>
                        <?php } ?>
                    </div>
                    <div role="tabpanel" class="tab-pane" id="featured">
                        <?php if ($featuredProjects) { ?>
                            <?php
                            foreach ($featuredProjects as $project) {
                                $streamId = $project->getStreamId('create');

                                // Retrieve the comments library
                                $comments = $project->getComments(); //changed create to add. hmmmm 
                                // Retrieve the likes library
                                $likes = $project->getLikes('create', $streamId);
                                ?>
                                <div class="es-project-item" data-apps-projects-item data-id="<?php echo $project->id; ?>"
                                     >
                                    <div class="col-md-5 js-eq-height">
                                        <div class="es-project-thumbnail"> <a href="<?php echo $project->getPermalink('detail'); ?>">
                                                <div class="es-project-cover" style="background-image: url('<?php echo $project->getThumbnail(); ?>')"></div>
                                            </a> </div>
                                    </div>
                                    <div class="col-md-7 js-eq-height">
                                        <div class="content-wrapper">
                                            <div class="es-project-content content-box">
                                                <div class="es-project-title content-title">
                                                    <a href="<?php echo $project->getPermalink('detail'); ?>"><?php echo $project->getTitle(); ?></a>
                                                </div>
                                                <div class="es-project-meta  content-avatar-area">
                                                    <div class="es-avatar es-avatar-sm es-stream-avatar pull-left" data-comments-item-avatar="">
                                                        <?php if ($project->getAuthor()) { ?>
                                                            <?php echo $this->loadTemplate('site/avatar/default', array('user' => $project->getAuthor())); ?>						
                                                        <?php } else { ?>
                                                            <img src="<?php echo $project->getAuthor()->getAvatar(); ?>" alt="<?php echo $this->html('string.escape', $project->getAuthor()->getName()); ?>" />
                                                        <?php } ?>
                                                    </div>
                                                    <a href="<?php echo $project->getAuthor()->getPermalink(); ?>" class="actor-column">
                                                        <?php echo $project->getAuthor()->getName(); ?>
                                                    </a>
                                                </div>
                                                <span class="time-span">
                                                    <?php
                                                    echo FD::date($project->getCreatedDate())->toLapsed();
                                                    ?>
                                                </span>
                                                <div class="es-project-brief content-description mt-10"><?php echo $project->getShortSummary(); ?></div>
                                                <?php if ($project->getCategory()) { ?>
                                                    <div class="category-box"><i class="fa fa-tag" aria-hidden="true"></i> <?= $project->category->getTitle() ?></div>
                                                <?php } ?>
                                            </div>
                                            <div class="content-bottom">

                                                <div class="es-action-wrap pr-20">
                                                    <ul class="fd-reset-list es-action-feedback">
                                                        <?php
                                                        $repost = FD::get('Repost', $project->id, 'projects', SOCIAL_APPS_GROUP_USER);
                                                        ?>
                                                        <li class="action-title-repost streamAction">
                                                            <span>
                                                                <a data-stream-action-repost href="javascript:void(0);" class="fd-small arepost iconitem-parent"><?php echo $repost->getButton(); ?></a>
                                                            </span>
                                                            <span class="numcount"><?php
                                                                if (isset($repost)) {
                                                                    echo $repost->getCount();
                                                                }
                                                                ?>
                                                            </span>
                                                        </li>
                                                        <li class="action-title-comments streamAction"
                                                            data-key="comments"
                                                            data-streamItem-actions
                                                            >
                                                            <span class="iconitem-parent"><a data-stream-action-comments href="javascript:void(0);" class="fd-small acomments"><?php echo JText::_('COM_EASYSOCIAL_STREAM_COMMENT'); ?></a></span>
                                                            <span class="numcount"><?php
                                                                if (isset($comments)) {
                                                                    echo $comments->getCount();
                                                                }
                                                                ?>
                                                            </span>
                                                        </li>
                                                        <li class="action-title-likes">
                                                            <?php echo $likes->button(); ?>
                                                        </li>
                                                    </ul>
                                                </div>

                                                <div class="es-stream-control pull-right btn-group">
                                                    <a class="control-buton" href="javascript:void(0);" data-bs-toggle="dropdown">
                                                        <i class="fa fa-ellipsis-h" aria-hidden="true"></i>
                                                    </a>
                                                    <ul class="dropdown-menu fd-reset-list">
                                                        <li>
                                                            <a href="<?= $project->getEditLink() ?>">
                                                                <?php echo JText::_('APP_PROJECTS_EDIT_BUTTON'); ?>
                                                            </a>
                                                        </li>
                                                        <li>
                                                            <a href="javascript:void(0);" data-apps-projects-delete data-id="<?php echo $project->id; ?>">
                                                                <?php echo JText::_('APP_PROJECTS_DELETE_BUTTON'); ?>
                                                            </a>
                                                        </li>
                                                        <li>
                                                            <a href="javascript:void(0);" data-apps-projects-feature data-id="<?php echo $project->id; ?>">
                                                                <?php
                                                                $featureText = !$project->isFeatured() ? 'APP_PROJECTS_FEATURE_BUTTON' : 'APP_PROJECTS_UNFEATURE_BUTTON';
                                                                echo JText::_($featureText);
                                                                ?>
                                                            </a>
                                                        </li>
                                                    </ul>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            <?php } ?>

                        <?php } else { ?>
                            <div class="empty empty-hero">
                                <i class="fa fa-film"></i>
                                <div><?php echo JText::_('COM_EASYSOCIAL_NO_PROJECTS_AVAILABLE_CURRENTLY'); ?></div>
                            </div>
                        <?php } ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
