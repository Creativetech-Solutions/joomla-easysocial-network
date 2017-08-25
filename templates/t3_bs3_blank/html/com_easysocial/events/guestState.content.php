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

<?php if ($guest->isParticipant() || ($event->isGroupEvent() && $event->getGroup()->isMember())) { ?>
    <?php if ($event->isOver()) { ?>
        <nav class="media-meta pull-right">
            <?php if ($guest->isGoing()) { ?>
                <a class="btn btn-es btn-sm btn-es-success" href="javascript:void(0);"><i class="fa fa-check"></i> <?php echo JText::_('COM_EASYSOCIAL_EVENTS_GUEST_GOING'); ?></a>
            <?php } ?>

            <?php if ($guest->isMaybe()) { ?>
                <a class="btn btn-es btn-sm btn-es-info" href="javascript:void(0);"><i class="fa fa-star-o"></i><?php echo JText::_('COM_EASYSOCIAL_EVENTS_GUEST_MAYBE'); ?></a>
            <?php } ?>

            <?php if ($guest->isNotGoing()) { ?>
                        <!--            <a class="btn btn-es btn-sm btn-es-danger" href="javascript:void(0);"><?php echo JText::_('COM_EASYSOCIAL_EVENTS_GUEST_NOTGOING'); ?></a>-->
            <?php } ?>
        </nav>
    <?php } else { ?>
        <nav class="media-meta pull-right">
            <?php if ($guest->isPending()) { ?>
                <a class="btn btn-es btn-sm" href="javascript:void(0);" data-guest-action="withdraw" data-guest-withdraw>
                    <?php echo JText::_('COM_EASYSOCIAL_EVENTS_GUEST_PENDING'); ?>
                </a>
            <?php } else { ?>
                <a class="<?php if ($guest->isGoing()) { ?>btn-es-orange<?php } ?>" href="javascript:void(0);" data-guest-action="state" data-guest-state="going"><i class="fa fa-check"></i> 
                    <?php if($guest->isGoing()){ ?>
                        <?php echo JText::_('COM_EASYSOCIAL_EVENTS_GUEST_GOING'); ?>
                    <?php } ?>
                    <?php if($guest->isMaybe()){ ?>
                        <?php echo JText::_('COM_EASYSOCIAL_EVENTS_ATTEND_THIS_EVENT'); ?>
                    <?php } ?>
                </a>

                <?php if ($event->getParams()->get('allowmaybe')) { ?>
                    <a class="<?php if ($guest->isMaybe()) { ?>btn-es-orange<?php } ?>" href="javascript:void(0);" data-guest-action="state" data-guest-state="maybe"><i class="fa fa-star-o"></i><?php echo JText::_('COM_EASYSOCIAL_EVENTS_GUEST_MAYBE'); ?></a>
                <?php } ?>

                <?php echo '<div class="event-share">' . FD::sharing(array('url' => $event->getPermalink(false, true), 'display' => 'dialog', 'text' => JText::_('COM_EASYSOCIAL_STREAM_SOCIAL'), 'css' => 'btn btn-es btn-sm'))->getHTML(true) . '</div>'; ?>

                        <!--            <a class="btn btn-es btn-sm <?php if ($guest->isNotGoing()) { ?>btn-es-danger<?php } ?>" href="javascript:void(0);" data-guest-action="state" data-guest-state="notgoing"><?php echo JText::_('COM_EASYSOCIAL_EVENTS_GUEST_NOTGOING'); ?></a>-->
            <?php } ?>
        </nav>
    <?php } ?>
<?php } else { ?>
    <?php if (!$event->isOver()) { ?>
        <nav class="media-meta pull-right">
            <?php if ($event->seatsLeft() === 0) { ?>
                <?php echo JText::_('COM_EASYSOCIAL_EVENTS_NO_SEATS_LEFT'); ?>
            <?php } else { ?>

                <?php if (!$this->my->getAccess()->get('events.allow.join') && $this->my->getAccess()->exceeded('events.join', $this->my->getTotalEvents())) { ?>
                    <?php echo JText::_('COM_EASYSOCIAL_EVENTS_EXCEEDED_JOIN_LIMIT'); ?>
                <?php } ?>

            <?php } ?>

            <?php if ($this->my->getAccess()->get('events.allow.join') && !$this->my->getAccess()->exceeded('events.join', $this->my->getTotalEvents()) && $event->seatsLeft() !== 0) { ?>
                <?php if ($event->isOpen()) { ?>
                                <!--                <a class="btn btn-es btn-sm" href="javascript:void(0);" data-guest-action="attend" data-guest-state="going"><?php echo JText::_('COM_EASYSOCIAL_EVENTS_ATTEND_THIS_EVENT'); ?></a>-->
                    <a href="javascript:void(0);" data-guest-action="attend" data-guest-state="going"><i class="fa fa-check"></i> <?php echo JText::_('COM_EASYSOCIAL_EVENTS_ATTEND_THIS_EVENT'); ?></a>

                    <?php if ($event->getParams()->get('allowmaybe')) { ?>
                        <a href="javascript:void(0);" data-guest-action="state" data-guest-state="maybe"><i class="fa fa-star-o"></i><?php echo JText::_('COM_EASYSOCIAL_EVENTS_GUEST_MAYBE'); ?></a>
                    <?php } ?>

                    <?php echo '<div class="event-share">' . FD::sharing(array('url' => $event->getPermalink(false, true), 'display' => 'dialog', 'text' => JText::_('COM_EASYSOCIAL_STREAM_SOCIAL'), 'css' => 'btn btn-es btn-sm'))->getHTML(true) . '</div>'; ?>
                <?php } ?>

                <?php if ($event->isClosed()) { ?>
                    <a href="javascript:void(0);" data-guest-action="request" data-guest-request><i class="fa fa-check"></i><?php echo JText::_('COM_EASYSOCIAL_EVENTS_REQUEST_TO_ATTEND_THIS_EVENT'); ?></a>

                    <?php echo '<div class="event-share">' . FD::sharing(array('url' => $event->getPermalink(false, true), 'display' => 'dialog', 'text' => JText::_('COM_EASYSOCIAL_STREAM_SOCIAL'), 'css' => 'btn btn-es btn-sm'))->getHTML(true) . '</div>'; ?>
                <?php } ?>
            <?php } ?>
        </nav>
    <?php } ?>
<?php } ?>
