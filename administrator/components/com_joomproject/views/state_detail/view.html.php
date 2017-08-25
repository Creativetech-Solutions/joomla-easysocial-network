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

class state_detailViewstate_detail extends JViewLegacy
{
	function display($tpl = null)
	{
		$document = JFactory::getDocument();
		$document->setTitle( JText::_('STATE') );

		$uri 		= JFactory::getURI();
		
		$this->setLayout('default');

		//$lists = array();

		$detail	= $this->get('data');
							
		
	 	
		$isNew		= ($detail->state_id < 1);

		$text = $isNew ? JText::_( 'NEW' ) : JText::_( 'EDIT' );
		JToolBarHelper::title(   JText::_( 'STATE' ).': <small><small>[ ' . $text.' ]</small></small>' );
	
		 
		JToolBarHelper::save();
		
		if ($isNew)  {
			JToolBarHelper::cancel();
		} else {
		
			JToolBarHelper::cancel( 'cancel', 'Close' );
		}
		 
		//$lists['published'] = JHTML::_('select.booleanlist',  'published', 'class="inputbox"', $detail->published );		
		
		$country	= $this->get('country');
		
		$sel_country = array();
		
		$sel_country[]  = JHTML::_('select.option', '0 ', JText::_('--Select Country--'));
		
		$country=@array_merge($sel_country,$country);

		$lists['country'] 	= JHTML::_('select.genericlist',$country,  'country_id', 'class="inputtext" onchange="select_distric(this.value)"   ', 'value', 'text', $detail->country_id );

		//$model = $this->getModel ( 'state_detail' );
		//$rdata=$model->getDistrict($detail->country);
		/*$sel_dis = array();
		$sel_dis[0]->value="0";
		$sel_dis[0]->text=JText::_('----- Select District ------');
		$rdata=@array_merge($sel_dis,$rdata);
		*/
		/*$lists['district'] 	= JHTML::_('select.genericlist',$rdata,  'district', 'class="inputtext" onchange="select_distric(this.value);"   ', 'value', 'text', $detail->district );


		
		$rdata=$model->getSuburb($detail->district);
		
		$invoice =array();
		
		$invoice=$model->getInvoice($detail->state_id);
		
		$sel_dis = array();
		$sel_dis[0]->value="0";
		$sel_dis[0]->text=JText::_('----- Select Suburb ------');
		$rdata=@array_merge($sel_dis,$rdata);
		
		 

		$lists['suburb'] 	= JHTML::_('select.genericlist',$rdata,  'suburb', 'class="inputtext"  ', 'value', 'text', $detail->suburb );

		$optionstate = array();		
		$optionstate[]   	= JHTML::_('select.option', '0',JText::_('----- Select Property type ------'));
		$optionstate[]   	= JHTML::_('select.option', 'Apartment', JText::_('Apartment'));
		$optionstate[]   	= JHTML::_('select.option', 'House', JText::_('House'));		
		$optionstate[]   	= JHTML::_('select.option', 'Lifestyle block', JText::_('Lifestyle block'));
		$optionstate[]   	= JHTML::_('select.option', 'Section', JText::_('Section'));
		$optionstate[]   	= JHTML::_('select.option', 'Townhouse', JText::_('Townhouse'));
		$optionstate[]   	= JHTML::_('select.option', 'Unit', JText::_('Unit'));		

$lists['property_type'] 	= JHTML::_('select.genericlist',$optionstate,  'property_type', 'class="inputbox" size="1" ' , 'value', 'text',  $detail->property_type );*/
		
		$this->assignRef('lists',		$lists);
		//$this->assignRef('invoice',		$invoice);
		
		$this->assignRef('detail',		$detail);
		$requesturl = $uri->toString();
$this->assignRef('request_url',	$requesturl);
		parent::display($tpl);
	}
	
}
?>