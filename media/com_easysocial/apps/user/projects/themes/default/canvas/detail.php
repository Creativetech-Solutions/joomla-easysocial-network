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
	var vheight = jQuery(".es-project-content-body").height();
	$("body").prepend("<div class=\"video-bg\" style=\"background: #000;position: absolute;width: 100%;height:"+vheight+"px;z-index: -1;\"></div>");
	
	/*var com_form = $(".es-video-comments .es-comments-form");
	var loadmore = $(".es-video-comments .es-comments-control");
	
	$(".es-video-comments .es-comments-form").remove();
	$(".es-video-comments .es-comments-control").remove();
	
	$("ul.fd-reset-list.es-comments").parent().prepend(com_form);
	$("ul.fd-reset-list.es-comments").parent().append(loadmore);*/
	
});
';
JFactory::getDocument()->addScriptDeclaration($script);
$streamId = $project->getStreamId('create', $project->id);
$coverPosition = $project->getCoverPosition();
?>
<?php //echo $video->getMiniHeader();              ?>

<div class="es-container es-project layout-detail" data-project-item data-id="<?php echo $project->id; ?>">
    <div class="es-content">

        <div class="es-project-single es-responsive">

            <div class="es-project-content-body">
                <div class="project-media-container">
                    <?php //if ($project->isFeatured() && $project->video_url != '') { ?>
                    <?php if ($project->video_url != '') { ?>
                        <iframe width="1280" height="720" src="<?php echo str_replace('watch?v=', 'embed/', $project->video_url . '?feature=oembed'); ?>" frameborder="0" allowfullscreen=""></iframe>
                    <?php } else { ?>
                        <div class="project-container" style="background-image: url('<?php echo $project->getOriginal(); ?>');background-position: <?php echo !empty( $coverPosition ) ? $coverPosition : ''; ?>;">
                        </div>
                    <?php } ?>
                </div>
            </div>

            <div class="es-project-content-brief">

                <div class="es-project-manage row-table mb-15 mt-15">
                    <div class="col-cell">
                        <?php /* ?><a href="<?= FRoute::apps(array('layout' => 'canvas', 'clayout' => 'all', 'id' => $app->getAlias(), 'uid' => $project->getAuthor()->getAlias(), 'type' => SOCIAL_TYPE_USER)) ?>">&larr; <?php echo JText::_('COM_EASYSOCIAL_PROJECTS_BACK_TO_PROJECTS'); ?></a><?php */ ?>
                        <a href="javascript:history.go(-1)">&larr; <?php echo JText::_('COM_EASYSOCIAL_PROJECTS_BACK'); ?></a>
                    </div>

                    <?php /* ?><div class="col-cell">
                      <?php if ($video->canFeature() || $video->canUnfeature() || $video->canDelete() || $video->canEdit()) { ?>
                      <span class="es-video-manage dropdown_ pull-right pl-10">
                      <a href="javascript:void(0);" class="dropdown-toggle_ video-edit-btn" data-bs-toggle="dropdown"></a>
                      <ul class="dropdown-menu dropdown-arrow-topright">
                      <?php if ($video->canFeature()) { ?>
                      <li>
                      <a href="javascript:void(0);" data-video-feature><?php echo JText::_('COM_EASYSOCIAL_VIDEOS_FEATURE_VIDEO');?></a>
                      </li>
                      <?php } ?>

                      <?php if ($video->canUnfeature()) { ?>
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
                      </div><?php */ ?>
                </div>

                <div class="mt-20">
                    <?php /* ?><div class="es-snackbar">
                      <span>
                      <?php echo JText::_("COM_EASYSOCIAL_GROUPS_VIDEOS_STRING_SINGULAR");?>
                      </span>
                      <span>
                      <?php echo JText::_("COM_EASYSOCIAL_VIDEOS_DETAILS");?>
                      </span>
                      </div><?php */ ?>

                    <div class="project-single-conent-body">
                        <div class="col-md-12">
                            <div class="col-md-10">
                                <h2 class="es-project-title single">
                                    <?php echo $project->getTitle(); ?>
                                </h2>
                            </div>
                            <div class="col-md-2">
                                <?php if ($project->canEdit($user->id)) { ?>
                                    <span class="es-project-manage dropdown_ pull-right pl-10">
                                        <a href="javascript:void(0);" class="dropdown-toggle_ project-edit-btn" data-bs-toggle="dropdown"></a>
                                        <ul class="dropdown-menu dropdown-arrow-topright">
                                            <?php if (!$project->isFeatured()) { ?>
                                                <li>
                                                    <a href="javascript:void(0);" data-project-feature><?php echo JText::_('COM_EASYSOCIAL_PROJECT_FEATURE_PROJECT'); ?></a>
                                                </li>
                                            <?php } ?>

                                            <?php if (!$project->isUnfeatured()) { ?>
                                                <li>
                                                    <a href="javascript:void(0);" data-project-unfeature><?php echo JText::_('COM_EASYSOCIAL_PROJECT_UNFEATURE_PROJECT'); ?></a>
                                                </li>
                                            <?php } ?>
                                            <li>
                                                <a href="<?php echo $project->getEditLink(); ?>"><?php echo JText::_('COM_EASYSOCIAL_EDIT'); ?></a>
                                            </li>

                                        </ul>
                                    </span>
                                <?php } ?>
                            </div>
                        </div>
                        <div class="es-project-meta mt-5">
                            <div>
                                <div class="es-avatar es-avatar-sm es-stream-avatar pull-left" data-comments-item-avatar="">
                                    <?php if ($project->getAuthor()) { ?>
                                        <?php echo $this->loadTemplate('site/avatar/default', array('user' => $project->getAuthor())); ?>						
                                    <?php } else { ?>
                                        <img src="<?php echo $project->getAuthor()->getAvatar(); ?>" alt="<?php echo $this->html('string.escape', $project->getAuthor()->getName()); ?>" />
                                    <?php } ?>
                                </div>
                                <div class="project_members">
                                    <span>
                                        created by
                                        <a href="<?php echo $project->getAuthor()->getPermalink(); ?>" class="video-author">
                                            <?php echo $project->getAuthor()->getName(); ?>
                                        </a>
                                    </span>
                                    <span class="es-project-team dropdown_">
                                        <a href="javascript:void(0);" class="dropdown-toggle_ video-edit-btn" data-bs-toggle="dropdown">Team <i class="fa fa-caret-down "></i></a>
                                        <ul class="dropdown-menu dropdown-arrow-topleft">
                                            <li>
                                                <div class="es-avatar es-avatar-sm es-stream-avatar pull-left" data-comments-item-avatar="">
                                                        <?php echo $this->loadTemplate('site/avatar/default', array('user' => $project->getAuthor())); ?>
                                                </div>
                                                <span>
                                                    <?php
                                                    echo '<p>' . $project->getAuthor()->getName() . '</p>';
                                                    echo '<p class="t-role">Owner</p>';
                                                    ?>
                                                </span>
                                            </li>
                                            <?php
                                            $projectTeam = $project->getProjectTeam();
                                            if ($projectTeam) {
                                                foreach ($projectTeam as $team) {
                                                    $teamUser = ES::user($team->user_id);
                                                    ?>
                                                    <li>
                                                        <div class="es-avatar es-avatar-sm es-stream-avatar pull-left" data-comments-item-avatar="">
                                                            <?php if ($teamUser) { ?>
                                                                <?php echo $this->loadTemplate('site/avatar/default', array('user' => $teamUser)); ?>						
                                                            <?php } else { ?>
                                                                <img src="<?php echo $teamUser->getAvatar(); ?>" alt="<?php echo $this->html('string.escape', $teamUser->getName()); ?>" />
                                                            <?php } ?>
                                                        </div>
                                                        <span>
                                                            <?php
                                                            echo '<p>' . $teamUser->getName() . '</p>';
                                                            echo '<p class="t-role">' . $team->role . '</p>';
                                                            ?>
                                                        </span>
                                                    </li>
                                                    <?php
                                                }
                                            }
                                            ?>
                                        </ul>
                                    </span>
                                </div>

                                <?php if (!$project->getAuthor()->isViewer()) { ?>
                                    <div class="project-follow-user">
                                        <?php if (FD::get('Subscriptions')->isFollowing($project->getAuthor()->id, SOCIAL_TYPE_USER)) { ?>
                                            <?php echo $this->loadTemplate('site/profile/button.followers.unfollow'); ?>
                                        <?php } else { ?>
                                            <?php echo $this->loadTemplate('site/profile/button.followers.follow'); ?>
                                        <?php } ?>	
                                    </div>
                                    <?php
                                }
                                ?>
                            </div>
                        </div>
                        <span class="time-span">
                            <?php
                            echo FD::date($project->getCreatedDate())->toLapsed();
                            ?>
                        </span>

                        <div class="es-project-brief mt-10"><?php echo $project->getShortSummary(); ?></div>
                        <div class="es-project-brief mt-10">
                            <?php
                            $projectWebsites = $project->getWebsites();
                            if ($projectWebsites) {
                                foreach (json_decode($projectWebsites) as $website) {
                                    //if(strpos($website, 'http'))
                                    $arrParsedUrl = parse_url($website);
                                    //print_r($arrParsedUrl);
                                    if (empty($arrParsedUrl['scheme'])) {
                                        $websiteURL = 'http://' . $website;
                                    }
                                    echo '<a href="' . $websiteURL . '" target="_blank">' . $website . '</a><br />';
                                }
                            }
                            //print_r(json_decode($projectWebsites));
                            ?>
                        </div>
                        <div class="es-project-brief mt-10">
                            <div class="col-md-3">
                                <span class="fa fa-map-marker"></span>
                                <?php echo $project->getLocation(); ?>
                            </div>
                            <div class="col-md-5">
                                <span class="fa fa-tag"></span>
                                <?php
                                if ($project->getCategory()) {
                                    echo $project->getCategory()->title;
                                }
                                ?>
                            </div>
                        </div>
                        <div class="clearfix"></div>
                    </div>
                    <div class="es-project-response">
                        <div class="es-action-wrap pr-20">
                            <ul class="fd-reset-list es-action-feedback">
                                <li class="arepost">
                                    <?php
                                    $repost = FD::get('Repost', $project->id, SOCIAL_TYPE_VIDEOS, SOCIAL_APPS_GROUP_USER);
                                    ?>
                                    <a data-stream-action-repost href="javascript:void(0);" class="fd-small arepost"><?php echo $repost->getButton(); ?></a>
                                </li>
                                <li>
                                    <a href="javascript:void(0)" class="acomments"></a>
                                </li>

                                <li class="alikes">
                                    <?php echo $project->getLikes('create', $streamId)->button(); ?>
                                </li>

                                <?php /* ?><li>
                                  <span class="ahits">
                                  <?php echo $project->getHits(); ?>
                                  </span>
                                  </li><?php */ ?>
                            </ul>
                        </div>

                        <?php /* ?><div class="es-stream-actions video-likes">
                          <?php echo $likes->html();?>
                          </div><?php */ ?>
                    </div>
                </div>
            </div>

            <hr class="es-hr" />

            <ul class="nav nav-tabs" role="tablist">
                <li role="presentation" class="active"> <a href="#about" role="tab" data-toggle="tab"> About </a> </li>
                <li role="presentation"> <a href="#comments" aria-controls="profile" role="tab" data-toggle="tab">  Comments </a> </li>
                <li role="presentation"> <a href="#jobs" aria-controls="profile" role="tab" data-toggle="tab">  Jobs </a> </li>
            </ul>

            <div class="tab-content">
                <div role="tabpanel" class="tab-pane active" id="about">
                    <?php echo $project->getDescription(); ?>
                </div>
                <div role="tabpanel" class="tab-pane" id="comments">
                    <?php
                    $comments = $project->getComments('create', $streamId); //changed create to add. hmmmm 
                    echo $comments->getHTML();
                    ?>
                </div>
                <div role="tabpanel" class="tab-pane" id="jobs">
                    <?php
                    $projectJobs = $project->getProjectJobs();
                    if ($projectJobs) {
                        foreach ($projectJobs as $job) {
                            ?>
                            <?php /* ?>
                              <div class="modal fade" id="modal_job_<?php echo $job->id; ?>">
                              <div class="modal-dialog">
                              <div class="modal-content">
                              <form class="jobapply_form" enctype="multipart/form-data" role="form">
                              <div class="modal-header">
                              <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                              <h4 class="modal-title">Upload Photo</h4>
                              </div>
                              <div class="modal-body">
                              <div id="messages"></div>
                              <input type="file" name="file" id="file">
                              </div>
                              <div class="modal-footer">
                              <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                              <button type="submit" class="btn btn-primary">Save</button>
                              </div>
                              </form>
                              </div>
                              </div>
                              </div>
                              <?php */ ?>
                            <div class="job_container">
                                <div class="job_container_header">
                                    <h2><?php echo $job->title; ?></h2>
                                    <div class="job_apply_container pull-right">
                                        <!--<a href="javascript:void(0)" data-toggle="modal" data-target="#modal_job_<?php echo $job->id; ?>">Apply</a>-->
                                        <a href="javascript:void(0)" data-apps-projects-job-apply data-apps-projects-job-aid="<?php echo $job->id; ?>">Apply</a>
                                        <?php /* ?><button class="btn btn-primary" data-toggle="modal" data-target="#modal_job_<?php echo $job->id; ?>">Apply</button><?php */ ?>
                                    </div>
                                </div>
                                <div class="job_meta_info">
                                    <div class="job_location col-md-12">
                                        <span class="col-md-2">Location</span>
                                        <span><?php echo ($job->location) ? $job->location : 'Not provided.'; ?></span>
                                    </div>
                                    <div class="job_type col-md-12">
                                        <span class="col-md-2">Pay</span>
                                        <span><?php echo ($job->position == 1) ? 'Paid' : 'Volunteer'; ?></span>
                                    </div>
                                    <div class="job_field col-md-12">
                                        <span class="col-md-2">Field</span>
                                        <span><?php echo ($project->getJobCategory($job->category_id)->title) ? $project->getJobCategory($job->category_id)->title : 'Not provided.'; ?></span>
                                    </div>
                                </div>
                                <div class="job_description mt-20">
                                    <h2>Description</h2>
                                    <p><?php echo $job->job_description; ?></p>
                                </div>
                            </div>
                            <?php
                        }
                    }
                    ?>
                </div>
            </div>

            <?php /* ?><div class="es-video-comments">
              <div class="es-stream-actions">
              <?php echo $video->getComments('create')->getHTML();?>
              </div>
              </div><?php */ ?>
        </div>
    </div>
</div>
<?php /* ?><script>
  (function($) {
  $('.jobapply_form').submit(function (e) {

  var form = $(this);
  var formdata = false;
  if (window.FormData) {
  formdata = new FormData(form[0]);
  }

  //var formAction = form.attr('action');

  $.ajax({
  type: 'POST',
  url: 'post.php',
  cache: false,
  data: formdata ? formdata : form.serialize(),
  contentType: false,
  processData: false,

  success: function (response) {
  if (response != 'error') {
  //$('#messages').addClass('alert alert-success').text(response);
  // OP requested to close the modal
  $('#myModal').modal('hide');
  } else {
  $('#messages').addClass('alert alert-danger').text(response);
  }
  }
  });
  e.preventDefault();
  });
  })(jQuery);
  </script><?php */ ?>
