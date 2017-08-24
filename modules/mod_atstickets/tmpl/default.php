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

// Protect from unauthorized access
defined('_JEXEC') or die();

?>
<div class="mod_atstickets akeeba-bootstrap">
	<?php if($params->get('show_open', 1)): ?>
		<div class="mod_atstickets-open">
		<span class="badge badge-info">
			<?php echo (int)$openTickets ?>
		</span>
			<?php echo JText::_('MOD_ATSTICKETS_LBL_OPENTICKETS'); ?>
		</div>
	<?php endif; ?>

	<?php if($params->get('show_pending', 1)): ?>
		<div class="mod_atstickets-pending">
		<span class="badge badge-warning">
			<?php echo (int)$pendingTickets ?>
		</span>
			<?php echo JText::_('MOD_ATSTICKETS_LBL_PENDINGTICKETS'); ?>
		</div>
	<?php endif; ?>

	<?php if($params->get('show_closed', 1)): ?>
		<div class="mod_atstickets-closed">
		<span class="badge badge-success">
			<?php echo (int)$closedTickets ?>
		</span>
			<?php echo JText::_('MOD_ATSTICKETS_LBL_CLOSEDTICKETS'); ?>
		</div>
	<?php endif; ?>

	<?php if($params->get('show_mytickets', 1)): ?>
		<div class="mod_atstickets-mytickets">
			<a href="<?php echo JRoute::_('index.php?option=com_ats&view=Mies')?>" class="btn btn-primary btn-small">
				<?php echo JText::_('MOD_ATSTICKETS_LBL_MYTICKETS'); ?>
			</a>
		</div>
	<?php endif; ?>

</div>
