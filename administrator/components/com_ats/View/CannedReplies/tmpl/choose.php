<?php
/**
 * @package ats
 * @copyright Copyright (c)2011-2016 Nicholas K. Dionysopoulos / AkeebaBackup.com
 * @license GNU GPL v3 or later
 */
use Akeeba\TicketSystem\Admin\Helper\Bbcode;

/** @var FOF30\View\DataView\Html $this */
// No direct access
defined('_JEXEC') or die;

$container = $this->getContainer();

// Load the required CSS
$container->template->addCSS('media://com_ats/css/frontend.css', $container->mediaVersion);
$container->template->addJS('media://com_ats/js/cannedreply-keyboard.js', false, false, $container->mediaVersion);

?>

<div class="akeeba-bootstrap">

<form action="<?php echo JRoute::_('index.php?option=com_ats&view=CannedReplies') ?>" method="post" class="adminform" name="adminForm" id="adminForm">
    <input type="hidden" name="<?php echo JFactory::getSession()->getFormToken();?>" value="1" />

	<table class="table table-striped" id="ats-cannedreplies" tabindex="0" width="100%">
		<thead>
			<tr>
				<th width="60px">
					<?php echo JText::_('COM_ATS_COMMON_ID')?>
				</th>
				<th>
					<?php echo JHTML::_('grid.sort', 'COM_ATS_CANNEDREPLIES_FIELD_TITLE', 'title', $this->lists->order_Dir, $this->lists->order, 'browse') ?>
				</th>
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
			<?php if(count($this->items)): ?>
			<?php $m = 1; $i = 0; ?>
			<?php foreach($this->items as $item):?>
			<?php
				$i++;
				$m = 1 - $m;
			?>
			<tr class="ats-cannedreply-row row<?php echo $m?>" data-cannedreplyid="<?php echo $item->ats_cannedreply_id ?>" data-cannedreplysequence="<?php echo $i ?>">
				<td>
					<?php echo $item->ats_cannedreply_id ?>
				</td>
				<td>
					<a href="javascript:atsUseReply(<?php echo $item->ats_cannedreply_id ?>)">
						<?php echo $this->escape($item->title) ?>
					</a><br/>
					<a href="javascript:atsExpandReply(<?php echo $item->ats_cannedreply_id ?>)">[ + ]</a>
					<div id="atsreply<?php echo $item->ats_cannedreply_id ?>" class="ats-cannedreply-raw">
						<?php echo Bbcode::parseBBCode($item->reply) ?>
					</div>
					<div id="atsrawreply<?php echo $item->ats_cannedreply_id ?>" class="ats-cannedreply-raw"><?php echo $item->reply ?></div>
				</td>
			</tr>
			<?php endforeach; ?>
			<?php else: ?>
			<tr>
				<td colspan="20">
					<?php echo JText::_('COM_ATS_COMMON_NORECORDS') ?>
				</td>
			</tr>
			<?php endif; ?>
		</tbody>
	</table>
</form>

<script type="text/javascript">
function atsExpandReply(id)
{
	(function($) {
		var divID = '#atsreply' + id;
		$(divID).toggle('fast');
	})(akeeba.jQuery)
}

function atsUseReply(id) {
	var divID = '#atsrawreply' + id;
	var HTMLdivID = '#atsreply' + id;
	var replyText = akeeba.jQuery(divID).html();
	var replyHTML = akeeba.jQuery(HTMLdivID).html();
	window.parent.useCannedReply(replyText, replyHTML);
}
</script>

</div>