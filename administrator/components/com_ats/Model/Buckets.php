<?php
/**
 * @package   AkeebaTicketSystem
 * @copyright Copyright (c)2011-2016 Nicholas K. Dionysopoulos
 * @license   GNU General Public License version 3, or later
 */

namespace Akeeba\TicketSystem\Admin\Model;

use FOF30\Container\Container;
use FOF30\Model\DataModel;

defined('_JEXEC') or die;

/**
 * Class Buckets
 *
 * @property    int     ats_bucket_id   Primary key
 * @property    string  title           Bucket title
 * @property    string  status          Bucket status
 *
 * @property-read  \Akeeba\TicketSystem\Admin\Model\Tickets[]    $tickets
 *
 * Filters:
 *
 * @method   $this   status($status)    Filter by status
 *
 */
class Buckets extends DefaultDataModel
{
    public function __construct(Container $container, array $config = array())
    {
        parent::__construct($container, $config);

        $this->addBehaviour('Filters');
        $this->hasMany('tickets');
    }

    public function check()
    {
        $this->assertNotEmpty($this->title, 'COM_ATS_BUCKETS_ERR_TITLE_EMPTY');

        $this->fieldsSkipChecks = array('modified_on', 'created_on');

        parent::check();
    }

    /**
     * Reply to all tickets in the bucket
     *
     * @param array $data
     *
     * @return bool
     */
    public function reply($data = array())
    {
        if(empty($data))
        {
            $data = $this->input->getData();
        }

        $bucket_id = $data['ats_bucket_id'];

        if(!$bucket_id)
        {
            return false;
        }

        $content      = isset($data['content']) ? $data['content'] : null;
        $content_html = isset($data['content_html']) ? $data['content_html'] : null;

        // If I can't find the bucket, let's throw an exception and stop here
        $bucket = $this->findOrFail($bucket_id);

        $result = true;

        /** @var \Akeeba\TicketSystem\Admin\Model\Tickets $ticket */
        foreach($bucket->tickets as $ticket)
        {
            // Reply if ticket is not closed
            if($ticket->status == 'C')
            {
                continue;
            }

            $post = array(
                'ats_ticket_id'		=> $ticket->ats_ticket_id,
                'content'			=> $content,
                'content_html'  	=> $content_html,
                'enabled'			=> 1
            );

            // Let's use the save method so it will fire every event
            $ticket->getNew('posts')->save($post);
        }

        return $result;
    }
}