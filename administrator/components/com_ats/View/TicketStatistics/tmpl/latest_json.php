<?php
/**
 * @package ats
 * @copyright Copyright (c)2011-2016 Nicholas K. Dionysopoulos / AkeebaBackup.com
 * @license GNU GPL v3 or later
 */

/** @var Akeeba\TicketSystem\Admin\View\TicketStatistics\Json $this */

defined('_JEXEC') or die;

// I have to manipulate the data before echoing it
$rows = array();

foreach($this->getItems() as $item)
{
    $rows[] = array('date' => $item->date, 'tickets' => $item->tickets);
}

echo json_encode($rows);