<?php
/**
 * @package      Crowdfunding
 * @subpackage   Modules
 * @author       Todor Iliev
 * @copyright    Copyright (C) 2015 Todor Iliev <todor@itprism.com>. All rights reserved.
 * @license      GNU General Public License version 3 or later; see LICENSE.txt
 */

// no direct access
defined('_JEXEC') or die;
?>
<div class="cf-modreporting<?php echo $moduleclassSfx; ?>">
    <?php if ($params->get('text')) { ?>
    <p><?php echo $params->get('text'); ?></p>
    <?php } ?>
    <a href="<?php echo JRoute::_(CrowdfundingHelperRoute::getReportRoute($projectId)); ?>" class="btn btn-inverse">
        <span class="fa fa-flag"></span>
        <?php echo JText::_('MOD_CROWDFUNDINGREPORTING_REPORT_CAMPAIGN'); ?>
    </a>
</div>