<?php
/**
 * @package      Crowdfunding
 * @subpackage   Components
 * @author       Todor Iliev
 * @copyright    Copyright (C) 2015 Todor Iliev <todor@itprism.com>. All rights reserved.
 * @license      GNU General Public License version 3 or later; see LICENSE.txt
 */

// no direct access
defined('_JEXEC') or die;
?>
<div class="row-fluid">
    <div class="span6 form-horizontal">
        <form action="<?php echo JRoute::_('index.php?option=com_crowdfundingdata'); ?>" method="post" name="adminForm" id="adminForm" class="form-validate">

                <?php echo $this->form->getControlGroup('name'); ?>
                <?php echo $this->form->getControlGroup('email'); ?>
                <?php echo $this->form->getControlGroup('address'); ?>
                <?php echo $this->form->getControlGroup('country_id'); ?>
                <?php echo $this->form->getControlGroup('id'); ?>

            <input type="hidden" name="task" value=""/>
            <?php echo JHtml::_('form.token'); ?>
        </form>
    </div>
</div>