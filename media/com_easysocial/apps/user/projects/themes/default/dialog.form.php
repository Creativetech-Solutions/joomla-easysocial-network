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
	<width>600</width>
	<height>450</height>
	<selectors type="json">
	{
		"{createButton}"	: "[data-create-button]",
		"{cancelButton}"	: "[data-cancel-button]",
		"{content}"			: "[data-apps-projects-form-content]",
		"{projectTitle}"		: "[data-apps-projects-form-title]",
		"{stream}"			: "[data-apps-projects-publish-stream]"
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
		<?php if( $project->id ){ ?>
			<?php echo JText::_( 'APP_PROJECTS_EDIT_PROJECT_DIALOG_TITLE' ); ?>
		<?php } else { ?>
			<?php echo JText::_( 'APP_PROJECTS_NEW_PROJECT_DIALOG_TITLE' ); ?>
		<?php } ?>
	</title>
	<content>

		<div class="controls-group-wrap">

			<div class="control-group">
				<label class="control-label"><?php echo JText::_( 'APP_PROJECTS_FORM_TITLE' );?>:</label>
				<div class="controls">
					<input type="text"
						placeholder="<?php echo JText::_( 'APP_PROJECTS_FORM_TITLE_PLACEHOLDER' );?>"
						value="<?php echo $project->get( 'title' );?>"
						name="title"
						class="input-sm form-control"
						data-apps-projects-form-title
					>
				</div>
			</div>

			<div class="control-group">
				<label class="control-label"><?php echo JText::_( 'APP_PROJECTS_FORM_CONTENTS' );?>:</label>
				<div class="controls" style="min-height: 200px;">
					<textarea class="input-sm form-control"
						placeholder="<?php echo JText::_( 'APP_PROJECTS_FORM_CONTENTS_PLACEHOLDER' );?>"
						data-apps-projects-form-content
					><?php echo $project->get( 'content' );?></textarea>
				</div>
			</div>

			<?php if( ( $params->get( 'stream_create' , true ) && !$project->id ) || ( $params->get( 'stream_update' , true ) && $project->id ) ){ ?>
			<div class="control-group">
				<div class="es-checkbox">
					<input type="checkbox" name="publish_stream" value="1" checked="checked" class="mr-5" id="data-apps-projects-publish-stream" data-apps-projects-publish-stream />
					<label for="data-apps-projects-publish-stream"><?php echo JText::_( 'APP_PROJECTS_FORM_PUBLISH_STREAM' );?></label>
				</div>
			</div>
			<?php } ?>

		</div>

	</content>
	<buttons>
		<button data-cancel-button type="button" class="btn btn-es btn-sm"><?php echo JText::_('COM_EASYSOCIAL_CANCEL_BUTTON'); ?></button>
		<button data-create-button type="button" class="btn btn-es-primary btn-sm"><?php echo JText::_( 'APP_PROJECTS_PUBLISH_PROJECT_BUTTON' ); ?></button>
	</buttons>
</dialog>
