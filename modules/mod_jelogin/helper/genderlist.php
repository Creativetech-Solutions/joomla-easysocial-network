<?php

/**

* @package   EK Rishta

* @copyright Copyright (C) 2009 - 2010 Open Source Matters. All rights reserved.

* @license   http://www.gnu.org/licenses/lgpl.html GNU/LGPL, see LICENSE.php

* Contact to : emailtohardik@gmail.com, joomextensions@gmail.com

* Visit : http://www.joomlaextensions.co.in/

**/ 
 
defined('_JEXEC') or die( 'Restricted access' );
class JFormFieldJecategory extends JFormFieldList
{
	protected $type = 'jecategory';
	function getInput()
	{
		$db 	=  JFactory::getDBO();
		$query= 'SELECT c_id AS value, category_name AS text FROM #__hm_category WHERE published=1 ORDER BY c_id ASC';
		$db->setQuery($query);
		$catdata = $db->loadObjectlist();		
		$val_name = $this->name.'[]';		
		
	   return   JHTML::_('select.genericlist',$catdata,$val_name, 'class="inputtext" multiple="multiple"', 'value', 'text',$this->value);  
	}	
}


?>
