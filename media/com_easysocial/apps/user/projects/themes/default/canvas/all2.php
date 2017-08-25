<?php
/**
 * @package		EasySocial
 * @copyright	Copyright (C) 2010 - 2015 Stack Ideas Sdn Bhd. All rights reserved.
 * @license		GNU/GPL, see LICENSE.php
 * EasySocial is free software. This version may have been modified pursuant
 * to the GNU General Public License, and as distributed it includes or
 * is derivative of works licensed under the GNU General Public License or
 * other free or open source software licenses.
 * See COPYRIGHT.php for copyright notices and details.
 */
defined('_JEXEC') or die('Unauthorized Access');
?>
<div class="es-snackbar">
    <span>
        <?php echo JText::_("COM_EASYSOCIAL_ROUTER_APPS_SORT_RECENT"); ?>
    </span>
    <span>
        <?php echo JText::_("COM_EASYSOCIAL_UPLOADS"); ?>
    </span>
</div>
<div class="es-project-list clearfix<?php echo!$projects ? ' is-empty' : ''; ?> js-eq-height-container">
    <?php if ($projects) { ?>
        <?php foreach ($projects as $project) { ?>
            <div class="es-project-item" data-apps-projects-item data-id="<?php echo $project->id;?>"
                 >
                <div class="es-project-content">

                    <?php echo $this->loadTemplate('apps/user/projects/default.player', array('project' => $project, 'eqheight' => true)); ?>
                    <div class="clearfix projectsbottom">
                        <div class="es-project-title">
                            <?php echo $project->getTitle(); ?>
                        </div>
                        <div class="time-lapsed">
                            <?php
                            echo FD::date($project->getCreatedDate())->toLapsed();
                            ?>
                        </div>
                        <div class="es-stream-control pull-right btn-group">
                            <a class="control-button" href="javascript:void(0);" data-bs-toggle="dropdown">
                                <i class="fa fa-ellipsis-h" aria-hidden="true"></i>
                            </a>
                            <ul class="dropdown-menu fd-reset-list">
                                <li>
                                    <a href="<?= $project->getEditLink() ?>">
                                        <?php echo JText::_('APP_PROJECTS_EDIT_BUTTON'); ?>
                                    </a>
                                </li>
                                <li>
                                    <a href="javascript:void(0);" data-apps-projects-delete data-id="<?php echo $project->id; ?>">
                                        <?php echo JText::_('APP_PROJECTS_DELETE_BUTTON'); ?>
                                    </a>
                                </li>
                                <li>
                                    <a href="javascript:void(0);" data-apps-projects-feature data-id="<?php echo $project->id; ?>">
                                        <?php $featureText = !$project->isFeatured() ? 'APP_PROJECTS_FEATURE_BUTTON' : 'APP_PROJECTS_UNFEATURE_BUTTON';
                                        echo JText::_($featureText); ?>
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </div>

                </div>
            </div>
        <?php } ?>

    <?php } else { ?>
        <div class="empty empty-hero">
            <i class="fa fa-film"></i>
            <div><?php echo JText::_('COM_EASYSOCIAL_NO_PROJECTS_AVAILABLE_CURRENTLY'); ?></div>
        </div>
    <?php } ?>
</div>
