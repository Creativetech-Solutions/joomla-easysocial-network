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
<dialog>
    <width>400</width>
    <height>200</height>
    <selectors type="json">
        {
            "{closeButton}" : "[data-close-button]",
            "{saveJob}"  : "[data-add-job-button]",
            "{errorMessage}": "[data-error-message]",
            "{jobCategory}" : "select[name=\"category_id\"]",
            "{jobTitle}": "[data-field-job-title]",
            "{jobDesc}"  : "[data-field-job-description]",
            "{jobLoc}"  : "[data-location-textfield]",
            "{jobPosition}"  : "input[name=\"position\"]:checked"
        }
    </selectors>
    <bindings type="javascript">
        {
            init: function()
            {
                EasySocial.require().script('apps/fields/user/address/maps').done(function($) {
                        $('[data-field-address-popup]').addController('EasySocial.Controller.Field.Address.Maps', {
                        id: '',
                        latitude: "",
                        longitude: "",
                        address: '',
                        zoom:  2,
                        required: false
                    });
                });
            },
            "{closeButton} click": function()
            {
                this.parent.close();
            },
            "{saveJob} click" : function()
            {
                this.errorMessage().hide();

                EasySocial.dialog({
                    content: '<div class="fd-loading"><span><?php echo JText::_('COM_EASYSOCIAL_LOADING'); ?></span></div>'
                });

                EasySocial.ajax('apps/user/projects/controllers/projects/addJob', {
                    pid: <?php echo $project->id; ?>,
                    title: this.jobTitle().val(),
                    job_description: this.jobDesc().val(),
                    category_id: this.jobCategory().val(),
                    location: this.jobLoc().val(),
                    position: this.jobPosition().val()
                }).done(function(content) {
                    console.log(content);
                    EasySocial.dialog({
                        content: content.msg
                    });

                    setTimeout(function() {
                        EasySocial.dialog().close();
                    }, 2000);
                }).fail(function(error) {
                    //console.log(error);
                    EasySocial.dialog({
                        content: error
                    });
                });
            }
        }
    </bindings>
    <title><?php echo JText::_('COM_EASYSOCIAL_PROJECT_DIALOG_ADD_JOB_TITLE'); ?></title>
    <content>
        <p class="mt-5">
            <?php echo JText::_('COM_EASYSOCIAL_PROJECT_JOB_FORM_ADD_JOB_TITLE'); ?>
        </p>
        <p class="mt-5">
            <input type="text" value="" name="title" class="form-control input-sm" data-field-job-title>
        </p>
        <p class="mt-5">
            <?php echo JText::_('COM_EASYSOCIAL_PROJECT_JOB_FORM_FIELD_OF_WORK'); ?>
        </p>
        <p class="mt-5">
            <?php echo $categories; ?>
        </p>
        <p class="mt-5">
            <?php echo JText::_('COM_EASYSOCIAL_PROJECT_JOB_FORM_JOB_DESCRIPTION'); ?>
        </p>
        <div>
            <textarea data-field-job-description class="form-control input-sm" rows="5" name="job_description" ></textarea>
        </div>
        
        <div class="location-div mt-5" data-field-address-popup>
            <label class="col-sm-12 control-label">
                <?php echo JText::_('COM_EASYSOCIAL_PROJECT_FORM_PROJECT_LOCATION'); ?>
            </label>
            <div class="col-xs-12 col-sm-7 col-md-7 form-input-fields data" data-content="">
                <div class="es-locations" data-location-base>
                    <div class="es-location-form es-field-location-form has-border" data-location-form>
                        <div class="es-location-textbox" data-location-textbox data-language="<?php echo FD::config()->get('general.location.language'); ?>">
                            <input type="text" name="location" class="input-sm form-control" placeholder="<?php echo JText::_('PLG_FIELDS_ADDRESS_SET_A_LOCATION'); ?>" autocomplete="off" data-location-textfield value="" />
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
                    <input type="hidden" name="location_json" data-location-source value="" />
                </div>
            </div>
            <div class="clearfix"></div>
        </div>

        <div class="mt-5">
            <p class="mt-5">
                <input type="radio" name="position" value="0" />
                <?php echo JText::_('COM_EASYSOCIAL_PROJECT_JOB_FORM_POSITION_VOLUNTEER'); ?>
            </p>
            <p class="mt-5">
                <input type="radio" name="position" value="1" />
                <?php echo JText::_('COM_EASYSOCIAL_PROJECT_JOB_FORM_POSITION_PAID'); ?>
            </p>
        </div>
    </content>
    <buttons>
        <button data-close-button type="button" class="btn btn-sm btn-es"><?php echo JText::_('COM_EASYSOCIAL_CLOSE_BUTTON'); ?></button>
        <button data-add-job-button type="button" class="btn btn-sm btn-es-primary"><?php echo JText::_('COM_EASYSOCIAL_SAVE_BUTTON'); ?></button>
    </buttons>
</dialog>