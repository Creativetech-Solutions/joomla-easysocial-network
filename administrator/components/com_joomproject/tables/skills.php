<?php
/**

* @package   JE shop

* @copyright Copyright (C) 2009-2010 Joomlaextensions.co.in All rights reserved.

* @license   http://www.gnu.org/licenses/lgpl.html GNU/LGPL, see LICENSE.php

* Contact to : emailtohardik@gmail.com, joomextensions@gmail.com

**/ 


defined( '_JEXEC' ) or die( 'Restricted access' );

jimport('joomla.application.component.model');

class Tableskills extends JTable
{
	var $id 		= null;
	var $skill_title 		= null;
	var $skill_desc 	= null;
	var $published 	= 1;
		
	function Tableskills(& $db) 
	{
	  $this->_table_prefix = '#__joomproject_';
			
		parent::__construct($this->_table_prefix.'skills', 'id', $db);
	}
	
	function bind($array, $ignore = '')
	{
		if (key_exists( 'params', $array ) && is_array( $array['params'] )) {
			$registry = new JRegistry();
			$registry->loadArray($array['params']);
			$array['params'] = $registry->toString();
		}

		return parent::bind($array, $ignore);
	}
	
}
?>
