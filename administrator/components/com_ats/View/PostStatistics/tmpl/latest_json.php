<?php
/**
 * @package ats
 * @copyright Copyright (c)2011-2016 Nicholas K. Dionysopoulos / AkeebaBackup.com
 * @license GNU GPL v3 or later
 */

defined('_JEXEC') or die;

// I have to manipulate the data before echoing it
$rows = array();

foreach($this->getItems() as $item)
{
    $rows[] = array('date' => $item->date, 'posts' => $item->posts);
}

echo json_encode($rows);