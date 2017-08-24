<?php
/**
 *  @package	ats
 *  @copyright	Copyright (c)2010-2016 Nicholas K. Dionysopoulos / AkeebaBackup.com
 *  @license	GNU GPLv3 <http://www.gnu.org/licenses/gpl.html> or later
 *  @version 	$Id$
 *
 *  This program is free software: you can redistribute it and/or modify
 *  it under the terms of the GNU General Public License as published by
 *  the Free Software Foundation, either version 3 of the License, or
 *  (at your option) any later version.
 *
 *  This program is distributed in the hope that it will be useful,
 *  but WITHOUT ANY WARRANTY; without even the implied warranty of
 *  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *  GNU General Public License for more details.
 *
 *  You should have received a copy of the GNU General Public License
 *  along with this program.  If not, see <http://www.gnu.org/licenses/>.
 */

// no direct access
defined('_JEXEC') or die('');

// PHP version check
if(defined('PHP_VERSION')) {
	$version = PHP_VERSION;
} elseif(function_exists('phpversion')) {
	$version = phpversion();
} else {
	// No version info. I'll lie and hope for the best.
	$version = '5.0.0';
}
// Old PHP version detected. EJECT! EJECT! EJECT!
if(!version_compare($version, '5.4.0', '>=')) return;

if (!defined('FOF30_INCLUDED') && !@include_once(JPATH_LIBRARIES . '/fof30/include.php'))
{
    return;
}

// Let's load the container so our autoloader gets registered
$container = FOF30\Container\Container::getInstance('com_ats');

$user = JFactory::getUser();
if($user->guest)
{
	echo '&nbsp;';
	return;
}

/** @var \Akeeba\TicketSystem\Site\Model\Tickets $ticketsModel */
$ticketsModel = $container->factory->model('Tickets')->tmpInstance();
$ticketsModel
	->created_by($user->id)
	->enabled(1);

$allTickets     = $ticketsModel->count();
$pendingTickets = $ticketsModel->reset()->status('P')->count();
$closedTickets  = $ticketsModel->reset()->status('C')->count();
$openTickets    = $allTickets - ($pendingTickets + $closedTickets);

require JModuleHelper::getLayoutPath('mod_atstickets', $params->get('layout', 'default'));
