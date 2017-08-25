<?php
/**
 * @package ats
 * @copyright Copyright (c)2011-2016 Nicholas K. Dionysopoulos / AkeebaBackup.com
 * @license GNU GPL v3 or later
 */

/** @var FOF30\View\DataView\Html $this */
defined('_JEXEC') or die;

$tickets = $this->input->get('ats_ticket_id', array(), 'array');

// Server-side check to ensure that I have some tickets to add to a bucket
if(!$tickets)
{
	JFactory::getApplication()->enqueueMessage(JText::_('COM_ATS_BUCKETS_NO_TICKET_SELECTED'), 'notice');

	return;
}

JText::script('COM_ATS_BUCKETS_CHOOSE_ONE');
JText::script('COM_ATS_BUCKETS_CHOOSE_ONLY_ONE');

$container = $this->getContainer();
$container->template->addJS('media://com_ats/js/adm_buckets_choose.js', false, false, $container->mediaVersion);
?>
<div>
	<div style="margin-bottom:10px;text-align:right">
		<button id="addTickets" class="btn"><?php echo JText::_('COM_ATS_BUCKETS_ADD_TICKETS')?></button>
	</div>
<?php

// Sometimes happens to have duplicates inside the ticket array, let's remove them!
$tickets      = array_unique($tickets);
$otherFields  = '<input type="hidden" name="layout" value="choose" />';
$otherFields .= '<input type="hidden" name="tmpl" value="component" />';

foreach ($tickets as $ticket)
{
	$otherFields .= '<input type="hidden" name="ats_ticket_id[]" value="'.$ticket.'" />';
}

$task	= $this->input->getCmd('task', 'browse');
?>
    <form action="<?php echo JRoute::_('index.php?option=com_ats&view=Buckets'); ?>" method="post" name="adminForm" id="adminForm">
        <input type="hidden" name="task" value="" />
        <input type="hidden" name="boxchecked" value="0" />
        <input type="hidden" name="filter_order" value="<?php echo $this->lists->order; ?>" />
        <input type="hidden" name="filter_order_Dir" value="<?php echo $this->lists->order_Dir; ?>" />
        <input type="hidden" name="<?php echo JFactory::getSession()->getFormToken();?>" value="1" />
        <?php echo $otherFields;?>

        <div id="filter-bar" class="btn-toolbar">
            <div class="btn-group pull-right hidden-phone">
                <label for="limit" class="element-invisible"><?php echo JText::_('JFIELD_PLG_SEARCH_SEARCHLIMIT_DESC') ?></label>
                <?php echo $this->getPagination()->getLimitBox(); ?>
            </div>
            <?php
            $asc_sel	= ($this->lists->order_Dir == 'asc') ? 'selected="selected"' : '';
            $desc_sel	= ($this->lists->order_Dir == 'desc') ? 'selected="selected"' : '';
            ?>
            <div class="btn-group pull-right hidden-phone">
                <label for="directionTable" class="element-invisible"><?php echo JText::_('JFIELD_ORDERING_DESC') ?></label>
                <select name="directionTable" id="directionTable" class="input-medium" onchange="Joomla.orderTable()">
                    <option value=""><?php echo JText::_('JFIELD_ORDERING_DESC') ?></option>
                    <option value="asc" <?php echo $asc_sel ?>><?php echo JText::_('JGLOBAL_ORDER_ASCENDING') ?></option>
                    <option value="desc" <?php echo $desc_sel ?>><?php echo JText::_('JGLOBAL_ORDER_DESCENDING') ?></option>
                </select>
            </div>
            <div class="btn-group pull-right">
                <label for="sortTable" class="element-invisible"><?php echo JText::_('JGLOBAL_SORT_BY') ?></label>
                <select name="sortTable" id="sortTable" class="input-medium" onchange="Joomla.orderTable()">
                    <option value=""><?php echo JText::_('JGLOBAL_SORT_BY') ?></option>
                    <?php echo JHtml::_('select.options', $sortFields, 'value', 'text', $this->lists->order) ?>
                </select>
            </div>
        </div>
        <div class="clearfix"> </div>

        <table class="adminlist table table-striped">
            <thead>
            <tr>
                <th width="1%">
                    <input type="checkbox" name="checkall-toggle" value="" title="<?php echo JText::_('JGLOBAL_CHECK_ALL'); ?>" onclick="Joomla.checkAll(this)" />
                </th>
                <th width="1%" class="nowrap">
                    <?php echo JHtml::_('grid.sort', 'JGRID_HEADING_ID', 'id', $this->lists->order_Dir, $this->lists->order, $task) ?>
                </th>
                <th>
                    <?php echo JHtml::_('grid.sort', 'COM_ATS_BUCKETS_HEADING_TITLE', 'title', $this->lists->order_Dir, $this->lists->order, $task) ?>
                </th>
                <th width="4%" class="nowrap">
                    <?php echo JHtml::_('grid.sort', 'COM_ATS_BUCKETS_HEADING_CREATED', 'created_on', $this->lists->order_Dir, $this->lists->order, $task) ?>
                </th>
                <th width="4%" class="nowrap">
                    <?php echo JHtml::_('grid.sort', 'COM_ATS_BUCKETS_HEADING_MODIFIED', 'modified_on', $this->lists->order_Dir, $this->lists->order, $task) ?>
                </th>
            </tr>
            <tr>
                <td></td>
                <td></td>
                <td>
                    <input type="text" name="title" id="title"
                           value="<?php echo $this->escape($this->getModel()->getState('title',''));?>"
                           class="input-large" onchange="document.adminForm.submit();"
                           placeholder="<?php echo JText::_('COM_ATS_BUCKETS_HEADING_TITLE'); ?>"
                        />

                    <button class="btn btn-mini" type="submit"><?php echo JText::_('JSEARCH_FILTER_SUBMIT'); ?></button>
                    <button class="btn btn-mini" type="button" onclick="akeeba.jQuery('#title').val('');this.form.submit();"><?php echo JText::_('JSEARCH_FILTER_CLEAR'); ?></button>

                </td>
                <td></td>
                <td></td>
            </tr>
            </thead>
            <tfoot>
            <tr>
                <td colspan="20">
                    <?php if($this->pagination->total > 0) echo $this->pagination->getListFooter() ?>
                </td>
            </tr>
            </tfoot>
            <tbody>
            <?php if($count = count($this->items)): ?>
                <?php $i = -1 ?>
                <?php foreach ($this->items as $i => $item) :
                    $i++;
                    $item->published = $item->enabled;

                    ?>
                    <tr class="row<?php echo $i % 2; ?>">
                        <td class="center">
                            <?php echo JHtml::_('grid.id', $i, $item->ats_bucket_id); ?>
                        </td>
                        <td class="center">
                            <?php echo $item->ats_bucket_id; ?>
                        </td>
                        <td>
                            <?php echo $this->escape($item->title); ?>
                        </td>
                        <td>
                            <?php echo JHtml::_('date',$item->created_on, JText::_('DATE_FORMAT_LC4')); ?>
                        </td>
                        <td>
                            <?php if($item->modified_on == '0000-00-00 00:00:00'): ?>
                                &mdash;
                            <?php else: ?>
                                <?php echo JHtml::_('date',$item->modified_on, JText::_('DATE_FORMAT_LC4')); ?>
                            <?php endif; ?>
                        </td>

                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr>
                    <td colspan="20">
                        <?php echo  JText::_('COM_ATS_COMMON_NORECORDS') ?>
                    </td>
                </tr>
            <?php endif; ?>
            </tbody>
        </table>
    </form>
</div>