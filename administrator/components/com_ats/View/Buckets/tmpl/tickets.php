<?php
/**
 * @package   AkeebaTicketSystem
 * @copyright Copyright (c)2011-2016 Nicholas K. Dionysopoulos
 * @license   GNU General Public License version 3, or later
 */

namespace Akeeba\TicketSystem\Admin\Layouts\Bucket;

use Akeeba\TicketSystem\Admin\Helper\Editor;
use Akeeba\TicketSystem\Admin\Helper\Format;
use Akeeba\TicketSystem\Admin\Helper\Html;
use FOF30\Container\Container;
use JDate;
use JFactory;
use JText;
use JUser;

defined('_JEXEC') or die;

/** @var $model \Akeeba\TicketSystem\Admin\Model\Buckets */
if(!($tickets = $model->tickets))
{
    echo '<div class="alert alert-info">'.JText::_('COM_ATS_BUCKETS_NO_TICKETS_INFO').'</div>';
    return;
}

$container = Container::getInstance('com_ats');
?>

<div>
    <div class="tabbable">
        <ul class="nav nav-tabs">
            <li class="active">
                <a href="#ats_bucket_reply" data-toggle="tab">
                    <?php echo JText::_('COM_ATS_BUCKETS_LEGEND_REPLY') ?>
                </a>
            </li>
            <li>
                <a href="#ats_bucket_tickets" data-toggle="tab">
                    <?php echo JText::_('COM_ATS_BUCKETS_LEGEND_TICKETLIST') ?>
                </a>
            </li>
        </ul>
        <div class="tab-content">
            <div class="tab-pane active" id="ats_bucket_reply">
                <h3><?php echo JText::_('COM_ATS_POSTS_HEADING_REPLYAREA'); ?></h3>

                <form action="index.php?option=com_ats&view=Buckets" method="post" enctype="multipart/form-data">
                    <input type="hidden" name="option" value="com_ats" />
                    <input type="hidden" name="view" value="Buckets" />
                    <input type="hidden" name="task" value="newreply" />
                    <input type="hidden" name="ats_bucket_id" value="<?php echo $model->ats_bucket_id ?>" />
                    <input type="hidden" name="<?php echo $container->session->getFormToken();?>" value="1" />

                    <div class="ats-ticket-replyarea-content bbcode">
                        <?php
                        if(Editor::isEditorBBcode())
                        {
                            $name = 'content'; $id = 'bbcode';
                        }
                        else
                        {
                            $name = 'content_html'; $id = 'ats-content';
                        }

                        // The helper directly outpus the HTML code
                        Editor::showEditor($name, $id, '', '95%', 400, 80, 20);
                        ?>
                    </div>

                    <div class="ats-ticket-replyarea-attachment">
                        <p><?php echo JText::_('COM_ATS_POSTS_MSG_ADDATTACHMENT'); ?></p>
                        <input type="file" name="attachedfile" size="10" />
                    </div>
                    <br />
                    <div class="ats-ticket-replyarea-postbutton">
                        <input class="btn btn-primary" type="submit" value="<?php echo JText::_('COM_ATS_POSTS_MSG_POST') ?>" />
                    </div>
                </form>
            </div>

            <div class="tab-pane" id="ats_bucket_tickets">
                <h3><?php echo JText::_('COM_ATS_BUCKETS_HEADING_TICKETLIST'); ?></h3>

                <table class="table table-striped">
                    <tbody>
                    <?php
                    foreach($tickets as $ticket)
                    {
                        $createdOn = Format::date2($ticket->created_on, '', true);
                        $createdBy = JUser::getInstance($ticket->created_by)->username;

                        $modifiedOn = new JDate($ticket->modified_on);

                        if($modifiedOn->toUnix() > 90000)
                        {
                            $lastOn = Format::date2($ticket->modified_on, '', true);
                            $lastBy = JUser::getInstance($ticket->modified_by)->username;
                        }
                        else
                        {
                            $lastOn = $createdOn;
                            $lastBy = $createdBy;
                        }

                        $assigned_to = $ticket->assigned_name ? $ticket->assigned_name : JText::_('COM_ATS_TICKETS_UNASSIGNED');
                        ?>
                        <tr id="ats-ticket-<?php echo $ticket->ats_ticket_id ?>">
                            <td>
                                <h4>
                                    <div class="pull-right">
                                        <span class="ats-status label <?php echo Html::getStatusClass($ticket->status)?> pull-right">
				                            <?php echo Html::decodeStatus($ticket->status)?>
			                            </span>
                                    </div>

                                    <?php if ($ticket->priority > 5): ?>
                                        <span class="ats-priority pull-left badge badge-info" style="margin-right: 2px">
                                            <span class="icon-arrow-down icon-white"></span>
                                        </span>
                                    <?php elseif (($ticket->priority > 0) && ($ticket->priority < 5)): ?>
                                        <span class="ats-priority pull-left badge badge-important" style="margin-right: 2px">
                                            <span class="icon-arrow-up icon-white"></span>
                                        </span>
                                    <?php else: ?>
                                    <?php endif; ?>

                                    <span class="ats-visibility pull-left badge <?php echo $ticket->public ? 'badge-warning' : 'badge-success'?>" style="margin-right: 2px">
                                        <span class="<?php echo $ticket->public ? 'icon-eye-open' : 'icon-eye-close'?> icon-white"></span>
                                    </span>

                                    <?php if(ATS_PRO):?>
                                        <span class="pull-right badge assigned_to <?php echo $ticket->assigned_to ? 'badge-info': ''?>" style="margin-right:5px">
                                            <?php echo $assigned_to?>
                                        </span>
                                    <?php endif;?>
                                    <a href="index.php?option=com_ats&view=Ticket&id=<?php echo $ticket->ats_ticket_id ?>">
                                        #<?php echo $ticket->ats_ticket_id ?>:
                                        <?php echo $this->escape($ticket->title) ?>
                                    </a>
                                </h4>
                                <div class="clearfix"></div>
                                <div>
                                    <span class="small pull-right">
                                        <?php echo JText::sprintf('COM_ATS_TICKETS_MSG_LASTPOST', $lastBy, $lastOn) ?>
                                    </span>
                                    <span class="small pull-left">
                                        <?php echo JText::sprintf('COM_ATS_TICKETS_MSG_CREATED', $createdOn, $createdBy) ?>
                                    </span>
                                </div>
                            </td>
                        </tr>
                        <?php
                    }
                    ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>