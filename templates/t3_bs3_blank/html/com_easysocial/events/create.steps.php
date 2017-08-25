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
<?php if (!$this->my->isSiteAdmin() && $this->my->getAccess()->get('events.moderate')) { ?>
    <div class="alert alert-warning">
        <?php echo JText::_('COM_EASYSOCIAL_EVENTS_SUBJECT_TO_APPROVAL'); ?>
    </div>
<?php } ?>

<?php if (!empty($group)) { ?>
    <h3 class="h3 well">
        <?php echo JText::sprintf('COM_EASYSOCIAL_GROUPS_EVENTS_EVENT_FOR_GROUP', $this->html('html.group', $group)); ?>
    </h3>
<?php } ?>

<form class="form-horizontal" enctype="multipart/form-data" method="post" action="<?php echo JRoute::_('index.php'); ?>" data-create-form>
    <div class="es-container es-events">
        <div class="dashboard_header">
            <h1> <span>Add</span> <span>New Event</span></h1>
        </div>
        <!-- Custom fields -->
        <?php if (!empty($fields)) { ?>
            <?php foreach ($fields as $field) { ?>
                <?php if (!empty($field->output)) { ?>

                    <div data-create-field data-element="<?php echo $field->element; ?>" data-id="<?php echo $field->id; ?>" data-required="<?php echo $field->required; ?>" data-fieldname="<?php echo SOCIAL_FIELDS_PREFIX . $field->id; ?>">

                        <?php echo $field->output; ?>

                        <input type="hidden" name="cid[]" value="<?php echo $field->id; ?>" />

                    </div>

                <?php } ?>
            <?php } ?>
        <?php } ?>

        <div class="form-group">
            <div class="col-sm-8 col-sm-offset-3 fd-small mt-20">
                <?php echo JText::_('COM_EASYSOCIAL_REGISTRATIONS_REQUIRED'); ?>
            </div>
        </div>

        <!-- Actions -->
        <div class="form-actions create-steps-submit">
            <?php if ($currentStep != 1) { ?>
                <button type="button" class="btn btn-es btn-medium pull-left" data-create-previous><?php echo JText::_('COM_EASYSOCIAL_PREVIOUS_BUTTON'); ?></button>
            <?php } ?>
            <button type="button" class="btn btn-es-primary btn-medium pull-right" data-create-submit><?php echo $currentIndex === $totalSteps || $totalSteps < 2 ? JText::_('COM_EASYSOCIAL_SUBMIT_BUTTON') : JText::_('COM_EASYSOCIAL_CONTINUE_BUTTON'); ?></button>
        </div>
    </div>

    <?php echo JHTML::_('form.token'); ?>
    <input type="hidden" name="currentStep" value="<?php echo $currentIndex; ?>" />
    <input type="hidden" name="controller" value="events" />
    <input type="hidden" name="task" value="saveStep" />
    <input type="hidden" name="option" value="com_easysocial" />
</form>

