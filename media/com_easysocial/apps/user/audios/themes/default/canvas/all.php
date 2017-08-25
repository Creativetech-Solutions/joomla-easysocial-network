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
<div class="es-audio-list clearfix<?php echo!$audios ? ' is-empty' : ''; ?> js-eq-height-container">
    <?php if ($audios) { ?>
        <?php foreach ($audios as $audio) { ?>
            <div class="es-audio-item" data-apps-audios-item data-id="<?php echo $audio->id;?>"
                 >
                <div class="es-audio-content">

                    <?php echo $this->loadTemplate('apps/user/audios/default.player', array('audio' => $audio, 'eqheight' => true)); ?>
                    <div class="clearfix audiosbottom">
                        <div class="es-audio-title">
                            <?php echo $audio->getTitle(); ?>
                        </div>
                        <div class="time-lapsed">
                            <?php
                            echo FD::date($audio->getCreatedDate())->toLapsed();
                            ?>
                        </div>
                        <div class="es-stream-control pull-right btn-group">
                            <a class="control-button" href="javascript:void(0);" data-bs-toggle="dropdown">
                                <i class="fa fa-ellipsis-h" aria-hidden="true"></i>
                            </a>
                            <ul class="dropdown-menu fd-reset-list">
                                <li>
                                    <a href="<?= $audio->getEditLink() ?>">
                                        <?php echo JText::_('APP_AUDIOS_EDIT_BUTTON'); ?>
                                    </a>
                                </li>
                                <li>
                                    <a href="javascript:void(0);" data-apps-audios-delete data-id="<?php echo $audio->id; ?>">
                                        <?php echo JText::_('APP_AUDIOS_DELETE_BUTTON'); ?>
                                    </a>
                                </li>
                                <li>
                                    <a href="javascript:void(0);" data-apps-audios-feature data-id="<?php echo $audio->id; ?>">
                                        <?php $featureText = !$audio->isFeatured() ? 'APP_AUDIOS_FEATURE_BUTTON' : 'APP_AUDIOS_UNFEATURE_BUTTON';
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
            <div><?php echo JText::_('COM_EASYSOCIAL_NO_AUDIOS_AVAILABLE_CURRENTLY'); ?></div>
        </div>
    <?php } ?>
</div>
