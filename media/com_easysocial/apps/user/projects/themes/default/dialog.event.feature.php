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
$title = !$featured ? 'APP_PROJECTS_DIALOG_EVENT_FEATURE_CONFIRMATION_TITLE' : 'APP_PROJECTS_DIALOG_EVENT_UNFEATURE_CONFIRMATION_TITLE';
$content = !$featured ? 'APP_PROJECTS_DIALOG_EVENT_FEATURE_CONFIRMATION_DESC' : 'APP_PROJECTS_DIALOG_EVENT_UNFEATURE_CONFIRMATION_DESC';
$button = !$featured ? 'APP_PROJECTS_FEATURE_BUTTON' : 'APP_PROJECTS_UNFEATURE_BUTTON';
?>
<dialog>
	<width>400</width>
	<height>150</height>
	<selectors type="json">
	{
		"{featuredButton}"	: "[data-feature-button]",
                "{event_id}"          : "[data-field-event-id]",
                "{callback}"          : "[data-field-callback]",
		"{cancelButton}"	: "[data-cancel-button]"
	}
	</selectors>
	<bindings type="javascript">
	{
		"{cancelButton} click": function() {
			this.parent.close();
		}
	}
	</bindings>
	<title><?php echo JText::_( $title ); ?></title>
	<content>
		<p><?php echo JText::_( $content ); ?></p>
                <input type="hidden" value="<?php echo $id; ?>" name="event_id" data-field-event-id>
                <input type="hidden" value="<?php echo $callback; ?>" name="callback" data-field-callback>
	</content>
	<buttons>
		<button data-cancel-button type="button" class="btn btn-es btn-sm"><?php echo JText::_('COM_EASYSOCIAL_CANCEL_BUTTON'); ?></button>
		<button data-feature-button type="button" class="btn btn-es-danger btn-sm"><?php echo JText::_( $button ); ?></button>
	</buttons>
</dialog>
