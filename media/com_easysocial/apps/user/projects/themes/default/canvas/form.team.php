<?php
//$jConfig = FD::jconfig();
//$tmp = $jConfig->editor;
//$editor = JEditor::getInstance($tmp);
?>
<div class="form-group">
    <div class="col-md-12">
        <a href="javascript:void(0);" data-action-invite-team-members class="btn btn-sm btn-es-primary team-invite-connection">Invite Connections</a>
        <a href="javascript:void(0);" data-action-email-invite class="btn btn-sm btn-es-primary team-invite-email">Send Email Invite</a>
    </div>
</div>
<br>
<div class="form-group mt-20">
    <label class="col-sm-12 control-label">
        <?php echo JText::_('COM_EASYSOCIAL_PROJECT_FORM_TEAM_MEMBERS'); ?>
    </label>
</div>
<div class="form-group project_team">
    <div class="col-md-12">
        <div class="col-md-5">
            <?php
            if ($owner) {
                //print_r($owner);
                echo '<img src="' . $owner->getAvatar() . '" />';
                echo '<h4 class="member_name"><b>' . $owner->getName() . '</b></h4>';
                echo '<span>project owner</span>';
            }
            ?>

        </div>
        <div class="col-md-4"></div>
    </div>
    <?php
    if ($members) {

        foreach ($members as $member) {
            $team_member = FD::user($member->user_id);
            $admin_id = (array) json_decode($project->admin_ids);
            
            //print_r($admin_id);
            ?>
            <div class="col-md-12 mt-20">
                <div class="col-md-5">
                    <?php
                    if ($team_member) {
                        echo '<img src="' . $team_member->getAvatar() . '" />';
                        echo '<h4 class="member_name"><b>' . $team_member->getName() . '</b></h4>';
                        echo ($member->role) ? '<span>' . $member->role . '</span>' : '';
                    }
                    ?>
                </div>
                <div class="col-md-4">
                    <label>Role (optional)</label>
                    <input type="text" class="form-control input-sm" name="team_role[<?php echo $team_member->id ?>]" value="<?php echo ($member->role) ? $member->role : ''; ?>" />
                </div>
                <div class="col-md-3 remove-team-member">
                    <a data-action-remove-member data-id="<?php echo $member->id ?>" data-memid="<?php echo $team_member->id ?>" class="btn btn-danger" href="javascript:void(0)">Remove</a>
                    <label class="is_admin_label">
                        Admin
                        <input type="checkbox" <?php echo (in_array($team_member->id,$admin_id) && $member->is_admin == 1) ? 'checked="checked"' :''; ?> name="admin_ids[]" value="<?php echo $team_member->id ?>" />
                    </label>
                </div>
            </div>
            <?php
        }
    }
    ?>
</div>
<div class="form-group mt-20">
    <label class="col-sm-12 control-label">
        <?php echo JText::_('COM_EASYSOCIAL_PROJECT_FORM_AVAILABLE_POSITIONS'); ?>
    </label>
    <p><?php echo JText::_('COM_EASYSOCIAL_PROJECT_FORM_AVAILABLE_POSITIONS_DESC'); ?></p>
</div>
<div class="form-group project_jobs">
    <?php
        if ($jobs) {
            ?>
            <div class="col-md-12 jobs-listing">
                <?php
                echo '<ul>';
                foreach ($jobs as $job) {
                    echo '<li>';
                    echo '<span>'
                    . '<img src="'.ES_SOCIAL_MEDIA_PROJECTS_APP_URI . '/assets/icons/project_job_icon.png" />'
                            . '</span>'
                            . '<a href="javascript:void(0)" data-action-edit-job data-job-id="'.$job->id.'">'.$job->title.'</a>'
                            . '<span><i class="fa fa-times" data-action-delete-job data-job-id="'.$job->id.'"></i></span>';
                    echo '</li>';
                }
                echo '</ul>';
                ?>
            </div>
            <?php
        }
        ?>
    <div class="clearfix"></div>
    <div class="col-md-9 center-block">
        <div>
            <img src="<?php echo ES_SOCIAL_MEDIA_PROJECTS_APP_URI . '/assets/icons/project_job_icon.png'; ?>" />
        </div>

        <div class="mt-20">
            <?php if (empty($jobs)) { ?>
                <p>You have no jobs posted for this project yet.</p>
            <?php } ?>
        </div>

        <div class="add-job-container">
            <a href="javascript:void(0)" data-add-job>Add new Job</a>
        </div>
        
    </div>
</div>
<?php /* ?><div class="form-group">
  <label class="col-sm-12 control-label">
  <?php echo JText::_('COM_EASYSOCIAL_PROJECT_FORM_PROJECT_FEATURED_VIDEO_URL'); ?>
  </label>
  <div class="col-xs-12 col-sm-5 col-md-9 form-input-fields data" data-content="">
  <div data-field-textbox="">
  <input type="text" value="<?php echo ($project->video_url) ? $project->video_url : ''; ?>" name="video_url" class="form-control input-sm" data-field-textbox-input="">
  </div>
  </div>
  <div class="clearfix"></div>
  <div class="col-xs-12 col-sm-12 col-md-12">
  <p><?php echo JText::_('COM_EASYSOCIAL_PROJECT_FORM_PROJECT_FEATURED_VIDEO_URL_DESC'); ?></p>
  </div>
  </div>

  <div class = "form-group">
  <label class = "col-sm-12 control-label">
  <?php echo JText::_('COM_EASYSOCIAL_PROJECT_FORM_PROJECT_DESCRIPTION');
  ?>
  </label>
  <div class="col-xs-12 col-sm-8 col-md-10 form-input-fields data" data-content="">
  <div data-field-textbox="">
  <?php
  echo $editor->display('description', $project->description, '100%', '100px', '60', '10', false);
  ?>
  </div>
  </div>
  <div class="clearfix"></div>
  </div>

  <div class="form-group">
  <label class="col-sm-12 control-label">
  <?php echo JText::_('COM_EASYSOCIAL_PROJECT_FORM_PROJECT_VIDEO_GALLERY'); ?>
  </label>
  <div class="col-xs-12 col-sm-12 col-md-12">
  <p><?php echo JText::_('COM_EASYSOCIAL_PROJECT_FORM_PROJECT_VIDEO_GALLERY_DESC'); ?></p>
  </div>
  <div class="col-xs-12 col-sm-5 col-md-9 form-input-fields data" data-content="">
  <div data-field-textbox="">
  <input type="text" value="<?php echo ($project->video_gallery) ? $project->video_gallery : ''; ?>" name="video_gallery" class="form-control input-sm" data-field-textbox-input="">
  </div>
  </div>
  <div class="clearfix"></div>
  </div><?php */ ?>
