<?php
 /**
* @package   Dowalo
* @copyright Copyright (C) 2009 - 2010 Open Source Matters. All rights reserved.
* @license   http://www.gnu.org/licenses/lgpl.html GNU/LGPL, see LICENSE.php
* Contact to : emailtohardik@gmail.com, joomextensions@gmail.com
* Visit : http://www.joomlaextensions.co.in/
**/ 

defined( '_JEXEC' ) or die( 'Restricted access' );


jimport( 'joomla.application.component.view' );

class country_detailViewcountry_detail extends JViewLegacy
{
	function display($tpl = null)
	{
		$document =  JFactory::getDocument();
		$document->setTitle( JText::_('COUNTRY') );

		$uri 		= JFactory::getURI();
		
		$this->setLayout('default');

		$lists = array();

		$detail	= $this->get('data');
		
					
		
	 	
		$isNew		= ($detail->country_id < 1);

		$text = $isNew ? JText::_( 'NEW' ) : JText::_( 'EDIT' );
		JToolBarHelper::title(   JText::_( 'COUNTRY' ).': <small><small>[ ' . $text.' ]</small></small>' );
	
		 
		JToolBarHelper::save();
		
		if ($isNew)  {
			JToolBarHelper::cancel();
		} else {
		
			JToolBarHelper::cancel( 'cancel', 'Close' );
		}
		 
		$this->assignRef('lists',		$lists);
		$this->assignRef('detail',		$detail);
		$requesturl = $uri->toString();
$this->assignRef('request_url',	$requesturl);

		parent::display($tpl);
	}
	
}
?>