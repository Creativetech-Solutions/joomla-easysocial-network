<?php
/**
 * @package   AkeebaTicketSystem
 * @copyright Copyright (c)2011-2016 Nicholas K. Dionysopoulos
 * @license   GNU General Public License version 3, or later
 */

defined('_JEXEC') or die;

/** @var $this FOF30\View\DataView\Form */
// First load the XML form
echo $this->getRenderedForm();

// Then add our custom layout to reply to all tickets in the bucket
echo $this->loadAnyTemplate('admin:com_ats/Buckets/tickets', array('model' => $this->getModel()));

