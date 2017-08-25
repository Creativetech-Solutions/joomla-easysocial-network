<?php
$jConfig = FD::jconfig();
$tmp = $jConfig->editor;
$editor = JEditor::getInstance($tmp);
?>

<div class="form-group">
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

<?php /*?><div class="form-group">
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
</div><?php */?>
