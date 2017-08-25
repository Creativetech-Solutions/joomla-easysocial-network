<?php
/**
 * @package   AkeebaTicketSystem
 * @copyright Copyright (c)2011-2016 Nicholas K. Dionysopoulos
 * @license   GNU General Public License version 3, or later
 */

namespace Akeeba\TicketSystem\Admin\Controller;

use Akeeba\TicketSystem\Admin\Helper\Permissions;
use Akeeba\TicketSystem\Admin\Model\Buckets;
use FOF30\Container\Container;
use FOF30\Controller\DataController;
use JText;

defined('_JEXEC') or die;

class Bucket extends DataController
{
    public function __construct(Container $container, array $config = array())
    {
        parent::__construct($container, $config);

        $this->registerTask('choosebucket', 'browse');
        $this->registerTask('blank', 'browse');
    }

    public function newreply()
    {
        // CSRF prevention
        $this->csrfProtection();

        /** @var \Akeeba\TicketSystem\Admin\Model\Buckets $model */
        $model  = $this->getModel();
        $data   = $this->input->getData();
        $result = $model->reply($data);

        if($result)
        {
            $msg  = JText::_('COM_ATS_BUCKET_POST_ADDED');
            $type = 'message';
        }
        else
        {
            $msg  = JText::_('COM_ATS_BUCKET_POST_ADD_ERROR');
            $type = 'error';
        }

        $url = 'index.php?option=com_ats&view=Buckets&task=edit&id='.$data['ats_bucket_id'];

        if($customURL = $this->input->getString('returnurl',''))
        {
            $url = base64_decode($customURL);
        }

        $this->setRedirect($url, $msg, $type);
    }

    public function close()
    {
        $this->csrfProtection();

        /** @var Buckets $bucket */
        $bucket = $this->getModel();

        if(!$bucket->getId())
        {
            $this->getIDsFromRequest($bucket);
        }

        $data = $bucket->getData();
        $data['status'] = 'C';

        $url = 'index.php?option=com_ats&view=Bucket&id='.$bucket->ats_bucket_id.$this->getItemidURLSuffix();

        if($customURL = $this->input->getBase64('returnurl', ''))
        {
            $url = base64_decode($customURL);
        }

        try
        {
            $bucket->save($data);

            $this->setRedirect($url);
        }
        catch(\Exception $e)
        {
            $this->setRedirect($url, $e->getMessage(), 'error');
        }

        return true;
    }

    public function reopen()
    {
        $this->csrfProtection();

        /** @var Buckets $bucket */
        $bucket = $this->getModel();

        if(!$bucket->getId())
        {
            $this->getIDsFromRequest($bucket);
        }

        $data = $bucket->getData();
        $data['status'] = 'O';

        $url = 'index.php?option=com_ats&view=Bucket&id='.$bucket->ats_bucket_id.$this->getItemidURLSuffix();

        if($customURL = $this->input->getBase64('returnurl', ''))
        {
            $url = base64_decode($customURL);
        }

        try
        {
            $bucket->save($data);

            $this->setRedirect($url);
        }
        catch(\Exception $e)
        {
            $this->setRedirect($url, $e->getMessage(), 'error');
        }

        return true;
    }

    public function addtickets()
    {
        $tickets = $this->input->get('ats_ticket_id', array(), 'array');
        $bucket  = $this->input->get('cid', array(), 'array');

        if($bucket)
        {
            $bucket = array_shift($bucket);
        }

        if(!$bucket)
        {
            $url  = 'index.php?option=com_ats&view=Buckets&tmpl=component&task=choosebucket&layout=choose&ats_ticket_id[]=';
            $url .= implode('&ats_ticket_id[]=', $tickets);
            $this->setRedirect($url, JText::_('COM_ATS_BUCKETS_CHOOSE_ONE'), 'notice');
            $this->redirect();
        }

        if(!$tickets)
        {
            $msg  = JText::_('COM_ATS_BUCKETS_NO_TICKET_SELECTED');
            $type = 'notice';
        }
        else
        {
            /** @var \Akeeba\TicketSystem\Admin\Model\Tickets $ticketModel */
            $ticketModel = $this->container->factory->model('Tickets')->tmpInstance();
            $result      = $ticketModel->addTicketsToBucket($tickets, $bucket);

            if($result)
            {
                $msg  = JText::_('COM_ATS_BUCKETS_TICKETS_ADDED');
                $type = 'message';
            }
            else
            {
                $msg  = JText::_('COM_ATS_BUCKETS_TICKETS_NOT_ADDED');
                $type = 'error';
            }
        }

        $this->setRedirect('index.php?option=com_ats&view=Buckets&task=blank&tmpl=component&layout=close', $msg, $type);
    }
}