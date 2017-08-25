<?php
/**
 * @package         JFBConnect
 * @copyright (c)   2009-2015 by SourceCoast - All Rights Reserved
 * @license         http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
 * @version         Release v7.1.1
 * @build-date      2016/11/18
 */

if (!(defined('_JEXEC') || defined('ABSPATH'))) {
    die('Restricted access');
};
jimport('joomla.html.sliders');
JHTML::_('behavior.tooltip');

?>
<style type="text/css">
    .sourcecoast input[type="checkbox"] {
        float: left;
        margin-right: 10px;
    }
</style>
<div class="sourcecoast">
    <form method="post" id="adminForm" name="adminForm">
        <div class="row-fluid">
            <div class="span6">
                <div class="well">
                    <legend><?php echo JText::_('COM_JFBCONNECT_OPENGRAPH_ACTION_SETTINGS'); ?></legend>
                    <div class="control-group">

                        <label class="hasTip"
                               title="<?php echo JText::_('COM_JFBCONNECT_OPENGRAPH_ACTION_TITLE_DESC'); ?>"
                               for="display_name"><?php echo JText::_('COM_JFBCONNECT_OPENGRAPH_ACTION_TITLE_LABEL'); ?></label>

                        <div class="controls">
                            <input id="display_name" type="text" size="20" name="display_name"
                                   value="<?php echo $this->action->display_name; ?>"
                                   placeholder="<?php echo JText::_('COM_JFBCONNECT_OPENGRAPH_ACTION_TITLE_PLACEHOLDER'); ?>">
                        </div>
                    </div>
                    <div class="control-group">
                        <label class="hasTip"
                               title="<?php echo JText::_('COM_JFBCONNECT_OPENGRAPH_ACTION_ACTION_DESC'); ?>"
                               for="action"><?php echo JText::_('COM_JFBCONNECT_OPENGRAPH_ACTION_ACTION_LABEL'); ?></label>

                        <div class="controls">
                            <input id="action" type="text" size="20" name="action"
                                   value="<?php echo $this->action->action; ?>"
                                   placeholder="<?php echo JText::_('COM_JFBCONNECT_OPENGRAPH_ACTION_ACTION_PLACEHOLDER'); ?>">
                        </div>
                    </div>
                    <div class="control-group">
                        <label class="hasTip"
                               title="<?php echo JText::_('COM_JFBCONNECT_OPENGRAPH_ACTION_PUBLISHED_DESC'); ?>"><?php echo JText::_('COM_JFBCONNECT_OPENGRAPH_ACTION_PUBLISHED_LABEL'); ?></label>

                        <div class="controls">
                            <select name="published">
                                <option
                                    value="1" <?php echo $this->action->published ? 'selected' : "" ?> ><?php echo JText::_('JPUBLISHED'); ?></option>
                                <option
                                    value="0" <?php echo $this->action->published ? "" : 'selected' ?> ><?php echo JText::_('JUNPUBLISHED'); ?></option>
                            </select>
                        </div>

                    </div>
                    <div class="control-group">
                        <div class="controls">
                            <input id="fb_built_in" type="checkbox" name="fb_built_in"
                                   value="1" <?php echo $this->action->fb_built_in ? "checked" : "" ?> />
                            <label class="hasTip"
                                   title="<?php echo JText::_('COM_JFBCONNECT_OPENGRAPH_ACTION_BUILTIN_DESC'); ?>"
                                   for="fb_built_in"><?php echo JText::_('COM_JFBCONNECT_OPENGRAPH_ACTION_BUILTIN_LABEL'); ?></label>

                        </div>

                    </div>
                </div>
                <div class="well">
                    <legend><?php echo JText::_('COM_JFBCONNECT_OPENGRAPH_ACTION_CAPABILITIES_LABEL'); ?></legend>
                    <p><?php echo JText::_('COM_JFBCONNECT_OPENGRAPH_ACTION_CAPABILITIES_DESC'); ?></p>

                    <div class="control-group">
                        <div class="controls">
                            <input id="allows_tags" type="checkbox" name="params[og_capabilities][tags]"
                                   value="1" <?php echo $this->action->params->get('og_capabilities.tags', 0) == "1" ? "checked" : ""; ?> >
                        </div>
                        <label class="hasTip"
                               title="<?php echo JText::_('COM_JFBCONNECT_OPENGRAPH_ACTION_FRIEND_TAGS_TITLE'); ?>"
                               for="allows_tags"><?php echo JText::_('COM_JFBCONNECT_OPENGRAPH_ACTION_FRIEND_TAGS_LABEL'); ?></label>
                    </div>
                    <div class="control-group">
                        <div class="controls">
                            <input id="allows_messages" type="checkbox" name="params[og_capabilities][messages]"
                                   value="1" <?php echo $this->action->params->get('og_capabilities.messages', 0) == "1" ? "checked" : ""; ?>>
                        </div>
                        <label class="hasTip"
                               title="<?php echo JText::_('COM_JFBCONNECT_OPENGRAPH_ACTION_USER_MESSAGES_TITLE'); ?>"
                               for="allows_messages"><?php echo JText::_('COM_JFBCONNECT_OPENGRAPH_ACTION_USER_MESSAGES_LABEL'); ?></label>
                    </div>
                    <div class="control-group">
                        <div class="controls">
                            <input id="allows_places" type="checkbox" name="params[og_capabilities][places]"
                                   value="1" <?php echo $this->action->params->get('og_capabilities.places', 0) == "1" ? "checked" : ""; ?>>
                        </div>
                        <label class="hasTip"
                               title="<?php echo JText::_('COM_JFBCONNECT_OPENGRAPH_ACTION_PLACE_TITLE'); ?>"
                               for="allows_places"><?php echo JText::_('COM_JFBCONNECT_OPENGRAPH_ACTION_PLACE_LABEL'); ?></label>

                    </div>
                    <div class="control-group">
                        <div class="controls">
                            <input id="explicitly_shared" type="checkbox"
                                   name="params[og_capabilities][explicitly_shared]"
                                   value="1" <?php echo $this->action->params->get('og_capabilities.explicitly_shared', 0) == "1" ? "checked" : ""; ?>>
                        </div>
                        <label class="hasTip"
                               title="<?php echo JText::_('COM_JFBCONNECT_OPENGRAPH_ACTION_EXPLICITLY_SHARED_TITLE'); ?>"
                               for="explicitly_shared"><?php echo JText::_('COM_JFBCONNECT_OPENGRAPH_ACTION_EXPLICITLY_SHARED_LABEL'); ?></label>
                    </div>
                </div>

                <div class="well">

                    <legend><?php echo JText::_('COM_JFBCONNECT_OPENGRAPH_ACTION_OBJECT_ASSOCIATIONS_LABEL'); ?></legend>
                    <p><?php echo JText::_('COM_JFBCONNECT_OPENGRAPH_ACTION_OBJECT_ASSOCIATIONS_DESC'); ?></p>
                    <?php foreach ($this->objects as $object) {
                        $checked = $this->action->isAssociatedTo($object) ? 'checked="checked"' : "";
                        echo '<div class="control-group">';
                        echo '<div class="controls"><input id="objects_' . $object->id . '" type="checkbox" name="objects[]" value="' . $object->id . '" ' . $checked . ' /></div>';
                        echo '<label style="clear:none" for="objects_' . $object->id . '" > <strong>' . $object->display_name . '</strong> [' . $object->plugin . ' - ' . $object->system_name . ']</label>';
                        echo '</div>';
                    } ?>
                </div>
            </div>
            <div class="span6">
                <?php
                #echo JHtml::_('sliders.start', 'ogaction-sliders-' . $this->action->id, array('useCookie' => 1));
                #echo JHtml::_('sliders.panel', JText::_('COM_JFBCONNECT_OPENGRAPH_ACTION_BASIC_SETTINGS'), 'Action');
                ?>
                <div class="well">
                    <legend><?php echo JText::_('COM_JFBCONNECT_OPENGRAPH_ACTION_BASIC_SETTINGS'); ?></legend>
                    <div class="control-group">
                        <label class="hasTip"
                               title="<?php echo JText::_('COM_JFBCONNECT_OPENGRAPH_ACTION_UNIQUE_DESC'); ?>"><?php echo JText::_('COM_JFBCONNECT_OPENGRAPH_ACTION_UNIQUE_LABEL'); ?></label>
                        <select name="params[og_unique_action]">
                            <option
                                value="1" <?php echo $this->action->params->get('og_unique_action') == "1" ? "selected" : ""; ?> ><?php echo JText::_('COM_JFBCONNECT_OPENGRAPH_ACTION_UNIQUE_SELECT_ONETIME'); ?></option>
                            <option
                                value="0" <?php echo $this->action->params->get('og_unique_action') == "0" ? "selected" : ""; ?> ><?php echo JText::_('COM_JFBCONNECT_OPENGRAPH_ACTION_UNIQUE_SELECT_MULTIPLE'); ?></option>
                        </select>
                    </div>
                    <div class="control-group">

                        <label class="hasTip"
                               title="<?php echo JText::_('COM_JFBCONNECT_OPENGRAPH_ACTION_MULTIPLE_ACTION_FREQ_DESC'); ?>"><?php echo JText::_('COM_JFBCONNECT_OPENGRAPH_ACTION_MULTIPLE_ACTION_FREQ_LABEL'); ?></label>
                        <input type="text" size="4"
                               name="params[og_interval_duration]"
                               value="<?php echo $this->action->params->get('og_interval_duration'); ?>"
                               style="width:30px">
                        <select style="width:157px" name="params[og_interval_type]">
                            <option
                                value="SECOND" <?php echo $this->action->params->get('og_interval_type') == "SECOND" ? "selected" : ""; ?> ><?php echo JText::_('COM_JFBCONNECT_OPENGRAPH_ACTION_MULTIPLE_ACTION_FREQ_SELECT_SECONDS'); ?></option>
                            <option
                                value="MINUTE" <?php echo $this->action->params->get('og_interval_type') == "MINUTE" ? "selected" : ""; ?> ><?php echo JText::_('COM_JFBCONNECT_OPENGRAPH_ACTION_MULTIPLE_ACTION_FREQ_SELECT_MINUTES'); ?></option>
                            <option
                                value="HOUR" <?php echo $this->action->params->get('og_interval_type') == "HOUR" ? "selected" : ""; ?> ><?php echo JText::_('COM_JFBCONNECT_OPENGRAPH_ACTION_MULTIPLE_ACTION_FREQ_SELECT_HOURS'); ?></option>
                            <option
                                value="DAY" <?php echo $this->action->params->get('og_interval_type') == "DAY" ? "selected" : ""; ?> ><?php echo JText::_('COM_JFBCONNECT_OPENGRAPH_ACTION_MULTIPLE_ACTION_FREQ_SELECT_DAYS'); ?></option>
                            <option
                                value="WEEK" <?php echo $this->action->params->get('og_interval_type') == "WEEK" ? "selected" : ""; ?> ><?php echo JText::_('COM_JFBCONNECT_OPENGRAPH_ACTION_MULTIPLE_ACTION_FREQ_SELECT_WEEKS'); ?></option>
                            <option
                                value="MONTH" <?php echo $this->action->params->get('og_interval_type') == "MONTH" ? "selected" : ""; ?> ><?php echo JText::_('COM_JFBCONNECT_OPENGRAPH_ACTION_MULTIPLE_ACTION_FREQ_SELECT_MONTHS'); ?></option>
                        </select>
                    </div>
                    <div class="control-group">

                        <label class="hasTip"
                               title="<?php echo JText::_('COM_JFBCONNECT_OPENGRAPH_ACTION_CAN_DISABLE_DESC'); ?>"><?php echo JText::_('COM_JFBCONNECT_OPENGRAPH_ACTION_CAN_DISABLE_LABEL'); ?></label>
                        <select type="radio" name="can_disable">
                            <option
                                value="1" <?php echo $this->action->can_disable == "1" ? "selected" : ""; ?> ><?php echo JText::_('JYES'); ?></option>
                            <option
                                value="0" <?php echo $this->action->can_disable == "0" ? "selected" : ""; ?> ><?php echo JText::_('JNO'); ?></option>
                        </select>
                    </div>

                </div>
                <?php
                #echo JHtml::_('sliders.panel', JText::_('COM_JFBCONNECT_OPENGRAPH_ACTION_AUTOMATIC_ACTIONS'), 'Action');
                ?>
                <div class="well">
                    <legend><?php echo JText::_('COM_JFBCONNECT_OPENGRAPH_ACTION_AUTOMATIC_ACTIONS'); ?></legend>
                    <div class="control-group">

                        <label class="hasTip"
                               title="<?php echo JText::_('COM_JFBCONNECT_OPENGRAPH_ACTION_AUTOTYPE_DESC'); ?>"><?php echo JText::_('COM_JFBCONNECT_OPENGRAPH_ACTION_AUTOTYPE_LABEL'); ?></label>
                        <select name="params[og_auto_type]">
                            <option
                                value="none" <?php echo $this->action->params->get('og_auto_type') == "none" ? "selected" : ""; ?>><?php echo JText::_('COM_JFBCONNECT_OPENGRAPH_ACTION_AUTOTYPE_SELECT_MANUAL'); ?></option>
                            <option
                                value="page_load" <?php echo $this->action->params->get('og_auto_type') == "page_load" ? "selected" : ""; ?>><?php echo JText::_('COM_JFBCONNECT_OPENGRAPH_ACTION_AUTOTYPE_SELECT_ONPAGELOAD'); ?></option>
                        </select>
                    </div>
                    <div class="control-group">

                        <label class="hasTip"
                               title="<?php echo JText::_('COM_JFBCONNECT_OPENGRAPH_ACTION_ONPAGELOAD_DESC'); ?>"><?php echo JText::_('COM_JFBCONNECT_OPENGRAPH_ACTION_ONPAGELOAD_LABEL'); ?></label>
                        <input type="text" name="params[og_auto_timer]"
                               value="<?php echo $this->action->params->get('og_auto_timer'); ?>"/></dd>
                    </div>
                </div>
                <?php
                ?>
            </div>
        </div>
        <input type="hidden" name="plugin" value="<?php echo $this->action->plugin ?>"/>
        <input type="hidden" name="system_name" value="<?php echo $this->action->system_name ?>"/>

        <input type="hidden" name="id" value="<?php echo $this->action->id; ?>"/>
        <input type="hidden" name="option" value="com_jfbconnect"/>
        <input type="hidden" name="controller" value="opengraph"/>
        <input type="hidden" name="task" value=""/>
        <input type="hidden" name="formtype" value="action"/>
        <?php echo JHTML::_('form . token'); ?>

    </form>
</div>