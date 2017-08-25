<?php
/**
 * @package ats
 * @copyright Copyright (c)2011-2016 Nicholas K. Dionysopoulos / AkeebaBackup.com
 * @license GNU GPL v3 or later
 */

namespace Akeeba\TicketSystem\Admin\View\TimeCards;

defined('_JEXEC') or die;

class Html extends \FOF30\View\DataView\Html
{
	protected function onBeforeBrowse($tpl = null)
	{
        // We don't have to fetch any data in the browse view
		return true;
	}
}
