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
		"{content}"			: "[data-apps-audios-form-content]",
		"{audioTitle}"		: "[data-apps-audios-form-title]",
		"{stream}"			: "[data-apps-audios-publish-stream]"
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
		<?php if( $audio->id ){ ?>
			<?php echo JText::_( 'APP_AUDIOS_EDIT_AUDIO_DIALOG_TITLE' ); ?>
		<?php } else { ?>
			<?php echo JText::_( 'APP_AUDIOS_NEW_AUDIO_DIALOG_TITLE' ); ?>
		<?php } ?>
	</title>
	<content>

		<div class="controls-group-wrap">

			<div class="control-group">
				<label class="control-label"><?php echo JText::_( 'APP_AUDIOS_FORM_TITLE' );?>:</label>
				<div class="controls">
					<input type="text"
						placeholder="<?php echo JText::_( 'APP_AUDIOS_FORM_TITLE_PLACEHOLDER' );?>"
						value="<?php echo $audio->get( 'title' );?>"
						name="title"
						class="input-sm form-control"
						data-apps-audios-form-title
					>
				</div>
			</div>

			<div class="control-group">
				<label class="control-label"><?php echo JText::_( 'APP_AUDIOS_FORM_CONTENTS' );?>:</label>
				<div class="controls" style="min-height: 200px;">
					<textarea class="input-sm form-control"
						placeholder="<?php echo JText::_( 'APP_AUDIOS_FORM_CONTENTS_PLACEHOLDER' );?>"
						data-apps-audios-form-content
					><?php echo $audio->get( 'content' );?></textarea>
				</div>
			</div>

			<?php if( ( $params->get( 'stream_create' , true ) && !$audio->id ) || ( $params->get( 'stream_update' , true ) && $audio->id ) ){ ?>
			<div class="control-group">
				<div class="es-checkbox">
					<input type="checkbox" name="publish_stream" value="1" checked="checked" class="mr-5" id="data-apps-audios-publish-stream" data-apps-audios-publish-stream />
					<label for="data-apps-audios-publish-stream"><?php echo JText::_( 'APP_AUDIOS_FORM_PUBLISH_STREAM' );?></label>
				</div>
			</div>
			<?php } ?>

		</div>

	</content>
	<buttons>
		<button data-cancel-button type="button" class="btn btn-es btn-sm"><?php echo JText::_('COM_EASYSOCIAL_CANCEL_BUTTON'); ?></button>
		<button data-create-button type="button" class="btn btn-es-primary btn-sm"><?php echo JText::_( 'APP_AUDIOS_PUBLISH_AUDIO_BUTTON' ); ?></button>
	</buttons>
</dialog>
