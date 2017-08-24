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

use Akeeba\TicketSystem\Admin\Helper\Credits;

// Default support time text for users who are not logged in.
$timeText = '&nbsp;';

if (!JFactory::getUser()->guest)
{
	// Get the total time spent supporting the user
	$time = \Akeeba\TicketSystem\Admin\Helper\Html::getTimeSpentPerUser(JFactory::getUser()->id);

	// The support time text to show for logged in users. Uses the MOD_ATSCREDITS_TIME language string.
	$timeText = JText::sprintf('MOD_ATSCREDITS_TIME', $time);
}
?>

<div class="atsUserSupportTime">
	<?php echo $timeText ?>
</div>

