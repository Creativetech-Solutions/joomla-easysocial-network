<?php
defined('_JEXEC') or die('Restricted access');
jimport('joomla.html.editor');
$prid = JFactory::getApplication()->input->get("prid", 0, 'INT');
$edit = $prid;
$projectThumb = false;
$coverPosition = false;
$projectOriginal = false;

$document = JFactory::getDocument();
$document->addStyleSheet(rtrim(JURI::root(), '/') . '/media/com_easysocial/apps/user/projects/assets/css/cropper.min.css');
$owner = FD::user();
if ($edit) {
    $projectThumb = $project->getThumbnailURL();
    $owner = FD::user($project->user_id);
    
    $projectOriginal = $project->getOriginal();
    
    $coverPosition = $project->getCoverPosition();
}

?>
<div class="es-container es-project-edit" data-project-edit>
    <div class="es-sidebar" id="es-sidebar" data-sidebar>

        <h4 class="edit-project">
            <i class="fa fa-briefcase" aria-hidden="true"></i>
            <?php if ($edit): ?>
                Edit<span> Project</span>
            <?php else: ?>
                Add<span> Project</span>
            <?php endif ?>
        </h4>
        <div class="es-widget es-widget-borderless">
            <div class="es-widget-body">
                <ul class="fd-nav fd-nav-stacked feed-items">
                    <li data-for="1" class="step-item active" data-project-edit-fields-step>
                        <a href="javascript:void(0);" class="fd-small"><?php echo JText::_('Basic'); ?></a>
                    </li>
                    <li data-for="2" class="step-item" data-project-edit-fields-step>
                        <a href="javascript:void(0);" class="fd-small"><?php echo JText::_('Story'); ?></a>
                    </li>
                    <li data-for="3" class="step-item" data-project-edit-fields-step>
                        <a href="javascript:void(0);" class="fd-small"><?php echo JText::_('Team'); ?></a>
                    </li>
                </ul>
            </div>
            <hr />
            <?php 
            if($project->id){
                echo '<a class="view-my-project" href="'.$project->getPermalink('detail').'">View my project</a>';
            }
            ?>
        </div>
    </div>
    <a data-target="#es-sidebar" class="profile-sidebar-toggle" data-toggle="collapse">
        <?php echo '<img src="' . JURI::base() . 'images/new_side_button.png" />' ?>
    </a>
    <div class="es-content">
        <div class="project-wrapper" data-project-edit-fields>  
            <div class="tab-content projects-content">
                <form id="project-form" class="box edit-form" method="post" action="<?php echo JRoute::_('index.php'); ?>" enctype="multipart/form-data" data-project-fields-form>
                    <div class="step-content step-1 active" data-project-edit-fields-content data-id="1">

                        <div class="col-sm-12">
                            <h3><?php echo JText::_('COM_EASYSOCIAL_PROJECT_FORM_BASIC_HEADING'); ?></h3>
                            <p><?php echo JText::_('COM_EASYSOCIAL_PROJECT_FORM_BASIC_DESC'); ?></p>
                        </div>
                        <div class="col-sm-12">
                            <hr>
                        </div>
                        <div class="col-sm-12">
                            <div class="form-group">
                                <label class="col-sm-12 control-label">
                                    <?php echo JText::_('COM_EASYSOCIAL_PROJECT_FORM_PROJECT_TITLE'); ?>
                                </label>
                                <div class="col-xs-12 col-sm-5 col-md-9 form-input-fields data" data-content="">
                                    <div data-field-textbox="">
                                        <input type="text" value="<?php echo ($project->title) ? $project->title : ''; ?>" name="title" class="form-control input-sm" data-field-textbox-input="">
                                    </div>
                                </div>
                                <div class="clearfix"></div>
                                <div class="col-xs-12 col-sm-12 col-md-12">
                                    <p><?php echo JText::_('COM_EASYSOCIAL_PROJECT_FORM_PROJECT_TITLE_DESC'); ?></p>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-12 control-label">
                                    <?php echo JText::_('COM_EASYSOCIAL_PROJECT_FORM_PROJECT_SUMMARY'); ?>
                                </label>
                                <div class="col-xs-12 col-sm-7 col-md-8 form-input-fields data" data-content="">
                                    <div data-field-textbox="">
                                        <textarea cols="6" rows="6" class="form-control input-sm" name="summary"><?php echo ($project->summary) ? $project->summary : ''; ?></textarea>
                                    </div>
                                </div>
                                <div class="clearfix"></div>
                                <div class="col-xs-12 col-sm-12 col-md-12">
                                    <p><?php echo JText::_('COM_EASYSOCIAL_PROJECT_FORM_PROJECT_SUMMARY_DESC'); ?></p>
                                </div>
                            </div>
                            
                            <?php /* ?><div class="form-group" data-field-project-image>
                                <label class="col-sm-12 control-label">
                                    <?php echo JText::_('COM_EASYSOCIAL_PROJECT_FORM_PROJECT_IMAGE'); ?>
                                </label>
                                <div class="col-xs-12 col-sm-7 col-md-7 form-input-fields data" data-content="">
                                    <div class="project-image-field" <?php //echo ($projectThumb) ? 'style="background:url('.$projectThumb.');"' : ''        ?>>
                                        <input data-field-project-file class="box__file" type="file" name="project_image" id="project_image" /> <!-- multiple -->
                                        <label for="project_image" id="upload" class="es-project-image-button">Upload an image</label>
                                        <p><?php echo JText::_('COM_EASYSOCIAL_PROJECT_FORM_PROJECT_IMAGE_DESC'); ?></p>
                                    </div>
                                </div>
                                <?php if ($projectThumb) { ?>
                                    <div class="col-xs-4 col-sm-3 col-md-4">
                                        <img id="project_image_preview" src="<?php echo $projectThumb ?>" />
                                    </div>
                                <?php } ?>
                                <div class="clearfix"></div>
                            </div><?php */ ?>
                            
                            <div data-field-cover data-field-project-image class="data-field-cover form-group">
                                <label class="col-sm-12 control-label">
                                    <?php echo JText::_('COM_EASYSOCIAL_PROJECT_FORM_PROJECT_IMAGE'); ?>
                                </label>
                                <ul class="input-vertical list-unstyled col-xs-12 col-sm-12 col-md-12 form-input-fields data">
                                        <li>
                                                <div class="cover-image-wrap">
                                                    
                                                        <div data-field-cover-image class="cover-image cover-move" style="background-image: url(<?php echo $projectOriginal; ?>);background-position: <?php echo !empty( $coverPosition ) ? $coverPosition : ''; ?>;"></div>
                                                    
                                                        <div class="project-image-field" <?php echo ($projectOriginal) ? 'style="visibility: hidden;"' : '' ?>>
                                                            <label for="project_image" id="upload" class="es-project-image-button">Upload an image</label>
                                                            <p><?php echo JText::_('COM_EASYSOCIAL_PROJECT_FORM_PROJECT_IMAGE_DESC'); ?></p>
                                                        </div>
                                                    
                                                        <div class="cover-remove">
                                                                <a href="javascript:void(0);" data-field-cover-remove-button <?php if( !$projectThumb ) { ?>style="display: none;"<?php } ?>>×</a>
                                                        </div>
                                                        <i class="loading-indicator fd-small" data-field-cover-loader style="display: none;"></i>
                                                </div>

                                                <div class="mb-10" data-field-cover-revert style="display: none;">
                                                        <a href="javascript:void(0);" data-field-cover-revert-button><?php echo JText::_( 'PLG_FIELDS_COVER_REVERT_COVER' ); ?></a>
                                                </div>

                                                <div class="mb-10" data-field-cover-note ></div>


                                                <div class="input-group input-group-sm">
                                                        <span class="input-group-btn">
                                                                <span class="btn btn-es-primary btn-file">
                                                                        <?php echo JText::_('FIELDS_USER_COVER_BROWSE_FILE'); ?>&hellip; <input type="file" name="project_image" data-field-cover-file />
                                                                </span>
                                                        </span>
                                                        <input class="form-control" type="text" readonly />
                                                </div>


                                                <div class="text-danger" data-field-cover-error></div>
                                        </li>
                                </ul>
                                <input type="hidden" name="project_image_position" data-field-cover-position <?php if( !empty( $coverPosition ) ) { ?>value="<?php echo $coverPosition; ?>"<?php } ?> />
                                <input type="hidden" name="project_image_data" data-field-cover-data />
                            </div>

                            <div class="form-group">
                                <label class="col-sm-12 control-label">
                                    <?php echo JText::_('COM_EASYSOCIAL_PROJECT_FORM_PROJECT_WEBSITE'); ?>
                                </label>
                                <div class="col-md-12 mb-10">
                                    <button class="add_links_button btn btn-sm btn-es-primary">Add More Links</button>
                                </div>
                                <div class="col-xs-12 col-sm-5 col-md-9 form-input-fields data websites_wrapper" data-content="">
                                    <?php if (!$project->website) { ?>
                                        <div data-field-textbox class="mb-10">
                                            <input type="text" value="<?php echo ($project->website) ? $project->website : ''; ?>" name="website[]" class="form-control input-sm" data-field-textbox-input="">
                                        </div>
                                        <?php
                                    } else {
                                        $websites = json_decode($project->website);
                                        foreach ($websites as $key => $website) {
                                            ?>
                                            <div data-field-textbox class="mb-10">
                                                <input type="text" value="<?php echo ($website) ? $website : ''; ?>" name="website[]" class="form-control input-sm" data-field-textbox-input="">
                                                <?php if ($key > 0) { ?>
                                                    <a href="#" class="remove_link_field btn btn-sm btn-es-danger">Remove</a>
                                                <?php } ?>

                                            </div>
                                            <?php
                                        }
                                    }
                                    ?>
                                </div>
                                <div class="clearfix"></div>
                                <div class="col-xs-12 col-sm-12 col-md-12">
                                    <p><?php echo JText::_('COM_EASYSOCIAL_PROJECT_FORM_PROJECT_WEBSITE_DESC'); ?></p>
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="col-sm-12 control-label">
                                    <?php echo JText::_('COM_EASYSOCIAL_PROJECT_FORM_PROJECT_CATEGORY'); ?>
                                </label>
                                <div class="col-xs-12 col-sm-7 col-md-7 form-input-fields data" data-content="">
                                    <div data-field-textbox="">
                                        <?php echo $categories; ?>
                                    </div>
                                </div>
                                <div class="clearfix"></div>
                            </div>

                            <div class="form-group location-div" data-field-address>
                                <label class="col-sm-12 control-label">
                                    <?php echo JText::_('COM_EASYSOCIAL_PROJECT_FORM_PROJECT_LOCATION'); ?>
                                </label>
                                <div class="col-xs-12 col-sm-7 col-md-7 form-input-fields data" data-content="">
                                    <div class="es-locations" data-location-base>
                                        <div class="es-location-form es-field-location-form has-border" data-location-form>
                                            <div class="es-location-textbox" data-location-textbox data-language="<?php echo FD::config()->get('general.location.language'); ?>">
                                                <input type="text" name="location" class="input-sm form-control" placeholder="<?php echo JText::_('PLG_FIELDS_ADDRESS_SET_A_LOCATION'); ?>" autocomplete="off" data-location-textfield <?php
                                                if (!empty($address)) {
                                                    $fulladdress = !empty($address->address) ? $address->address : $address->toString();
                                                }
                                                if (!empty($fulladdress)) {
                                                    ?>value="<?php echo $fulladdress; ?>"<?php } ?> />
                                                <div class="es-location-autocomplete has-shadow is-sticky" data-location-autocomplete>
                                                    <b><b></b></b>
                                                    <div class="es-location-suggestions" data-location-suggestions>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="es-location-buttons">
                                                <i class="fd-loading"></i>
                                                <a class="es-location-remove-button" href="javascript: void(0);" data-location-remove><i class="fa fa-remove"></i></a>
                                            </div>
                                        </div>
                                        <input type="hidden" name="location_json" data-location-source value="<?php echo ($address) ? FD::string()->escape($address->toJson()) : ''; ?>" />
                                    </div>
                                </div>
                                <div class="clearfix"></div>
                            </div>
                            <?php
                            if ($edit) {
                                echo '<hr />';
                                ?>
                                <div class="">
                                    <a href="javascript:void(0);" class="fd-small" data-project-delete><?php echo JText::_('COM_EASYSOCIAL_PROJECT_FORM_PROJECT_DELETE_BUTTON'); ?></a>
                                </div>
                                <?php
                            }
                            ?>
                        </div>
                    </div>

                    <div class="step-content step-2" data-project-edit-fields-content data-id="2">
                        <div class="col-sm-12">
                            <h3><?php echo JText::_('COM_EASYSOCIAL_PROJECT_FORM_STORY_HEADING'); ?></h3>
                            <p><?php echo JText::_('COM_EASYSOCIAL_PROJECT_FORM_STORY_DESC'); ?></p>
                        </div>
                        <div class="col-sm-12">
                            <hr>
                        </div>

                        <div class="col-sm-12">
                            <?php
                            echo $this->loadTemplate('apps/user/projects/canvas/form.story', array('project' => $project));
                            ?>
                        </div>
                    </div>

                    <div class="step-content step-3" data-project-edit-fields-content data-id="3">
                        <div class="col-sm-12">
                            <h3><?php echo JText::_('COM_EASYSOCIAL_PROJECT_FORM_TEAM_HEADING'); ?></h3>
                            <p><?php echo JText::_('COM_EASYSOCIAL_PROJECT_FORM_TEAM_DESC'); ?></p>
                        </div>
                        <div class="col-sm-12">
                            <hr>
                        </div>

                        <div class="col-sm-12">
                            <?php
                            echo $this->loadTemplate('apps/user/projects/canvas/form.team', array('project' => $project, 'owner' => $owner, 'members' => $members, 'jobs' => $jobs));
                            ?>
                        </div>
                    </div>
                    <div class="clearfix"></div>
                    <div class="form-actions">

                        <div class="pull-left">
                            <a href="<?php echo $projectURL; ?>" class="btn btn-sm btn-es-danger"><?php echo JText::_('COM_EASYSOCIAL_CANCEL_BUTTON'); ?></a>
                        </div>

                        <div class="pull-right">
                            <button type="button" class="btn btn-sm btn-es-primary" data-project-fields-save><?php echo JText::_('COM_EASYSOCIAL_SAVE_BUTTON'); ?></button>
                            <button type="button" class="btn btn-sm btn-es-primary save-next" data-project-fields-save-next><?php echo JText::_('COM_EASYSOCIAL_SAVE_AND_NEXT_BUTTON'); ?></button>
                            <button type="button" class="btn btn-sm btn-es-primary save-exit" style="display:none" data-project-fields-save><?php echo JText::_('COM_EASYSOCIAL_SAVE_AND_EXIT_BUTTON'); ?></button>

                        </div>
                    </div>
                    <input type="hidden" name="Itemid" value="<?php echo JRequest::getVar('Itemid'); ?>" />
                    <input type="hidden" name="option" value="com_easysocial" />
                    <input type="hidden" name="controller" value="apps" />
                    <input type="hidden" name="all_steps" id="all_steps_input" value="3" />
                    <input type="hidden" name="current_step" id="current_step_input" value="1" />
                    <input type="hidden" name="task" value="controller" />
                    <input type="hidden" name="thumbUpload" data-project-thumb-upload value="false" />
                    <input type="hidden" name="appController" value="projects" />
                    <input type="hidden" name="appTask" value="store" />
                    <input type="hidden" name="appId" value="<?php echo $app->id; ?>" />
                    <input type="hidden" name="prid" value="<?= $prid ?>"/>
                    <input type="hidden" name="<?php echo FD::token(); ?>" value="1" />
                </form>
            </div>
        </div>
    </div>
</div>