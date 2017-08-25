<?php
 /**
* @package   Dowalo
* @copyright Copyright (C) 2009 - 2010 Open Source Matters. All rights reserved.
* @license   http://www.gnu.org/licenses/lgpl.html GNU/LGPL, see LICENSE.php
* Contact to : emailtohardik@gmail.com, joomextensions@gmail.com
* Visit : http://www.joomlaextensions.co.in/
**/ 
defined( '_JEXEC' ) or die( 'Restricted access' );
jimport('joomla.application.component.model');

class Tablecountry extends JTable
{
	var $country_id= null;
	var $country_name = null;
	var $country_3_code = null;
	var $country_2_code = null;
	var $published = null;
		
	function Tablecountry(& $db) 
	{
	  $this->_table_prefix = '#__joomproject_';
			
		parent::__construct($this->_table_prefix.'countries', 'country_id', $db);
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
