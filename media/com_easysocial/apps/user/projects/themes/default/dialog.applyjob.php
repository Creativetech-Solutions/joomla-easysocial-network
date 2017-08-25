<?php
/**
* @package		EasySocial
* @copyright	Copyright (C) 2010 - 2014 Stack Ideas Sdn Bhd. All rights reserved.
* @license		GNU/GPL, see LICENSE.php
* EasySocial is free software. This version may have been modified pursuant
* to the GNU General Public License, and as distributed it includes or
* is derivative of works licensed under the GNU General Public License or
* other free or open source software licenses.
* See COPYRIGHT.php for copyright notices and details.
*/
defined( '_JEXEC' ) or die( 'Unauthorized Access' );
?>
<dialog>
	<width>400</width>
	<height>350</height>
	<selectors type="json">
	{
		"{sendButton}"          : "[data-send-button]",
		"{cancelButton}"	: "[data-cancel-button]",
                "{fullName}"            : "[data-field-full-name]",
                "{phoneNum}"            : "[data-field-phone-number]",
                "{emailAddress}"        : "[data-field-email-address]",
                "{websiteURL}"          : "[data-field-web-url]",
                "{jobid}"               : "[data-field-job-id]",
                "{message}"             : "[data-field-message]"
	}
	</selectors>
	<bindings type="javascript">
	{
		"{cancelButton} click": function() {
			this.parent.close();
		}
	}
	</bindings>
	<title>
            <?php echo $job->getTitle().' posted by '.$job->getPostedBy(); ?>
        </title>
	<content>
		<p class="mt-5">
                    <?php echo JText::_('Full Name'); ?>
                </p>
                <p class="mt-5">
                    <input type="text" value="" name="full_name" class="form-control input-sm" data-field-full-name>
                </p>
                <p class="mt-5">
                    <?php echo JText::_('Phone Number'); ?>
                </p>
                <p class="mt-5">
                    <input type="text" value="" name="phone_number" class="form-control input-sm" data-field-phone-number>
                </p>
                <p class="mt-5">
                    <?php echo JText::_('Email Address'); ?>
                </p>
                <p class="mt-5">
                    <input type="text" value="" name="email" class="form-control input-sm" data-field-email-address>
                </p>
                <p class="mt-5">
                    <?php echo JText::_('Website URL'); ?>
                </p>
                <p class="mt-5">
                    <input type="text" value="" name="website" class="form-control input-sm" data-field-web-url>
                    <input type="hidden" value="<?php echo $jobid; ?>" name="job_id" data-field-job-id>
                </p>
                <p class="mt-5">
                    <?php echo JText::_('Message'); ?>
                </p>
                <div>
                    <textarea data-field-message class="form-control input-sm" rows="5" name="message" ></textarea>
                </div>
	</content>
        
	<buttons>
		<button data-cancel-button type="button" class="btn btn-es btn-sm"><?php echo JText::_('COM_EASYSOCIAL_CANCEL_BUTTON'); ?></button>
		<button data-send-button type="button" class="btn btn-sm btn-es-primary"><?php echo JText::_( 'Send' ); ?></button>
	</buttons>
</dialog>
