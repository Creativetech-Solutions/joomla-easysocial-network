<?php
/**
 * @package ats
 * @copyright Copyright (c)2011-2016 Nicholas K. Dionysopoulos / AkeebaBackup.com
 * @license GNU GPL v3 or later
 */

defined('_JEXEC') or die;

$response = array('labels' => array(), 'data' => array());
$data     = array();

foreach($this->items as $ticket)
{
    $assigned = $ticket->poster_name ? $ticket->poster_name : JText::_('COM_ATS_TICKETS_UNASSIGNED');

    if(!in_array($assigned, $response['labels']))
    {
        $response['labels'][] = $assigned;
    }

    $data[] = (float)$ticket->tot_timespent;
}

$response['data'] = array($data);

echo json_encode($response);