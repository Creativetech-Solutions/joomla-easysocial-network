<?php
/**
 * @package ats
 * @copyright Copyright (c)2011-2016 Nicholas K. Dionysopoulos / AkeebaBackup.com
 * @license GNU GPL v3 or later
 */

/** @var $this \Akeeba\TicketSystem\Admin\View\Tickets\Html */
use Akeeba\TicketSystem\Admin\Helper\Select;

defined('_JEXEC') or die;

JHtml::_('behavior.modal');

$container = $this->getContainer();
$tags      = array();

if($this->item->created_by)
{
    /** @var \Akeeba\TicketSystem\Admin\Model\UserTags $usertags */
    $usertags = $container->factory->model('UserTags')->tmpInstance();
    $tags     = $usertags->loadTagsByUser($this->item->created_by);
}

?>
<div class="control-group">
    <label class="control-label"><?php echo JText::_('COM_ATS_COMMON_USERSELECT_LBL')?></label>
    <div class="controls">
	    <?php echo \Akeeba\TicketSystem\Admin\Helper\Html::modalChooseUser('created_by', 'created_by', $this->item->created_by)?>
    </div>
</div>
<div class="control-group">
    <label class="control-label"><?php echo JText::_('COM_ATS_COMMON_USER_TAGS')?></label>
    <div class="controls">
        <?php echo Select::usertags('usertags[]', $tags, array('class' => 'advancedSelect', 'multiple' => 'multiple', 'size' => 5))?>
    </div>
</div>
