<?php
/**
* @package   JE category component
* @copyright Copyright (C) 2009-2010 Joomlaextensions.co.in All rights reserved.
* @license   http://www.gnu.org/licenses/lgpl.html GNU/LGPL, see LICENSE.php
* Contact to : emailtohardik@gmail.com, joomextensions@gmail.com
**/

defined( '_JEXEC' ) or die( 'Restricted access' );
jimport( 'joomla.application.component.view' );

class category_detailViewcategory_detail extends JViewLegacy
{
	function display($tpl = null)
	{
		$document = JFactory::getDocument();
		$document->setTitle( JText::_('CATEGORY_DETAIL') );
		$uri 		= JFactory::getURI();
		
		//$doc = JFactory::getDocument();
		$option = JRequest::getVar('option','','request','string');
		
		$this->setLayout('default');
		$lists = array();
		$detail	= $this->get('data');
		 		
		$isNew		= ($detail->id < 1);

		$text = $isNew ? JText::_( 'NEW' ) : JText::_( 'EDIT' );
		JToolBarHelper::title(   JText::_( 'CATEGORY' ).': <small><small>[ ' . $text.' ]</small></small>' );
	
		JToolBarHelper::apply(); 
		JToolBarHelper::save();
		
		if ($isNew)  {
			JToolBarHelper::cancel();
		} else {
		
			JToolBarHelper::cancel( 'cancel', 'Close' );
		}
		
		
		$lists['published'] = JHTML::_('select.booleanlist',  'published', 'class="inputbox"', $detail->published );
		
	  
		
		$this->assignRef('lists',		$lists);
		//$this->assignRef('invoice',		$invoice);
		$this->assignRef('detail',		$detail);
		//$this->assignRef('request_url',	$uri->toString());
		parent::display($tpl);
	}
	
}
?>