<?php
/**
 * @package   AkeebaTicketSystem
 * @copyright Copyright (c)2011-2016 Nicholas K. Dionysopoulos
 * @license   GNU General Public License version 3, or later
 */

/** @var $this FOF30\View\DataView\Html */
defined('_JEXEC') or die;

$container = $this->getContainer();
$container->template->addCSS('media://com_ats/css/backend.css', $container->mediaVersion);

JHTML::_('behavior.tooltip');
JHTML::_('behavior.modal');

?>

<form action="index.php" method="post" name="adminForm" id="adminForm" enctype="multipart/form-data" class="form form-horizontal">
	<input type="hidden" name="option" value="com_ats" />
	<input type="hidden" name="view" value="CreditTransactions" />
	<input type="hidden" name="task" value="" />
	<input type="hidden" name="ats_credittransaction_id" value="<?php echo $this->item->ats_credittransaction_id ?>" />
	<input type="hidden" name="<?php echo $container->session->getFormToken()?>" value="1" />

	<div class="control-group">
		<label for="user_id" class="control-label">
				<?php echo  JText::_('COM_ATS_CREDITTRANSACTIONS_FIELD_USER_ID_LBL'); ?>
		</label>
		<div class="controls">
				<?php echo \Akeeba\TicketSystem\Admin\Helper\Html::modalChooseUser('user_id', 'user_id', $this->item->user_id)?>
		</div>
	</div>
	<div class="control-group">
		<label for="transaction_date" class="control-label">
				<?php echo  JText::_('COM_ATS_CREDITTRANSACTIONS_FIELD_TRANSACTION_DATE_LBL'); ?>
		</label>
		<div class="controls">
			<?php echo JHTML::_('calendar', $this->item->transaction_date, 'transaction_date', 'transaction_date'); ?>
		</div>
	</div>
	<div class="control-group">
		<label for="type" class="control-label">
				<?php echo  JText::_('COM_ATS_CREDITTRANSACTIONS_FIELD_TYPE_LBL'); ?>
		</label>
		<div class="controls">
			<input type="text" class="input-large" name="type" value="<?php echo $this->item->type?>"
				   placeholder="<?php echo JText::_('COM_ATS_CREDITTRANSACTIONS_FIELD_TYPE_LBL') ?>" />
		</div>
	</div>
	<div class="control-group">
		<label for="unique_id" class="control-label">
				<?php echo  JText::_('COM_ATS_CREDITTRANSACTIONS_FIELD_UNIQUE_ID_LBL'); ?>
		</label>
		<div class="controls">
			<input type="text" class="input-large" name="unique_id" value="<?php echo $this->item->unique_id?>"
				   placeholder="<?php echo JText::_('COM_ATS_CREDITTRANSACTIONS_FIELD_UNIQUE_ID_LBL') ?>" />
		</div>
	</div>
	<div class="control-group">
		<label for="value" class="control-label">
				<?php echo  JText::_('COM_ATS_CREDITTRANSACTIONS_FIELD_VALUE_LBL'); ?>
		</label>
		<div class="controls">
			<input type="text" class="input-small" name="value" value="<?php echo $this->item->value?>"
				   placeholder="<?php echo JText::_('COM_ATS_CREDITTRANSACTIONS_FIELD_VALUE_LBL') ?>" />
		</div>
	</div>
</form>