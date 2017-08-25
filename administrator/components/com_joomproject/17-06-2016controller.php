<?php
/**
* @package   Dowalo
* @copyright Copyright (C) 2009-2010 Joomlaextensions.co.in All rights reserved.
* @license   http://www.gnu.org/licenses/lgpl.html GNU/LGPL, see LICENSE.php
* Contact to : emailtohardik@gmail.com, joomextensions@gmail.com
**/

jimport('joomla.application.component.controller');

 
$l['skl']		= "SKILLS";
$l['int']		= "INTERESTS";
$l['c']			= "COUNTRY";
$l['s']			= "STATE";



$view	= JRequest::getVar( 'view', '', '', 'string', JREQUEST_ALLOWRAW );
	if ($view == '' || $view == 'skills') 
	{
		JSubMenuHelper::addEntry(JText::_($l['skl']), 'index.php?option=com_joomproject&view=skills',true);
 		JSubMenuHelper::addEntry(JText::_($l['int']), 'index.php?option=com_joomproject&view=interest');
 		JSubMenuHelper::addEntry(JText::_($l['c']), 'index.php?option=com_joomproject&view=country');
 		JSubMenuHelper::addEntry(JText::_($l['s']), 'index.php?option=com_joomproject&view=state');
 	
	}
 
 	if ($view == 'interest') 
 	{
		JSubMenuHelper::addEntry(JText::_($l['skl']), 'index.php?option=com_joomproject&view=skills');	
		JSubMenuHelper::addEntry(JText::_($l['int']), 'index.php?option=com_joomproject&view=interest',true);
		JSubMenuHelper::addEntry(JText::_($l['c']), 'index.php?option=com_joomproject&view=country');
		JSubMenuHelper::addEntry(JText::_($l['s']), 'index.php?option=com_joomproject&view=state');
		
	} 

	if ($view == 'country') 
 	{
		JSubMenuHelper::addEntry(JText::_($l['skl']), 'index.php?option=com_joomproject&view=skills');	
		JSubMenuHelper::addEntry(JText::_($l['int']), 'index.php?option=com_joomproject&view=interest');
		JSubMenuHelper::addEntry(JText::_($l['c']), 'index.php?option=com_joomproject&view=country',true);
		JSubMenuHelper::addEntry(JText::_($l['s']), 'index.php?option=com_joomproject&view=state');
	} 


	if ($view == 'state') 
 	{
		JSubMenuHelper::addEntry(JText::_($l['skl']), 'index.php?option=com_joomproject&view=skills');	
		JSubMenuHelper::addEntry(JText::_($l['int']), 'index.php?option=com_joomproject&view=interest');
		JSubMenuHelper::addEntry(JText::_($l['c']), 'index.php?option=com_joomproject&view=country');
		JSubMenuHelper::addEntry(JText::_($l['s']), 'index.php?option=com_joomproject&view=state',true);
		
	} 

?>
