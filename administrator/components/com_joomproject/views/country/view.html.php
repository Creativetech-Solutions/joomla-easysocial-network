<?php
/**
* @package   JE Auto
* @copyright Copyright (C) 2009 - 2010 Open Source Matters. All rights reserved.
* @license   http://www.gnu.org/licenses/lgpl.html GNU/LGPL, see LICENSE.php
* Contact to : emailtohardik@gmail.com, joomextensions@gmail.com
**/
defined( '_JEXEC' ) or die( 'Restricted access' );


jimport( 'joomla.application.component.view' );
 
class countryViewcountry extends JViewLegacy
{
	function __construct( $config = array())
	{
		 parent::__construct( $config );
	}
    
	function display($tpl = null)
	{	
		$mainframe = JFactory::getApplication();
		$context='id';
		
		$document =  JFactory::getDocument();
		$document->setTitle( JText::_('REGISTER') );
   		 
   		JToolBarHelper::title(   JText::_( 'REGISTER_MANAGEMENT' ) );   		
   		
 		JToolBarHelper::addNew();
 		JToolBarHelper::editList();
		JToolBarHelper::deleteList();		
		JToolBarHelper::publishList();
		JToolBarHelper::unpublishList();
	   
	   	
		$uri	= JFactory::getURI();
		
	$filter_order     = $mainframe->getUserStateFromRequest( $context.'filter_order', 'filter_order',  'ordering' );
	$filter_order_Dir = $mainframe->getUserStateFromRequest( $context.'filter_order_Dir',  'filter_order_Dir', '' );
	$plan = $mainframe->getUserStateFromRequest( $context.'register','register',0 );
	 
	$lists['order'] = $filter_order;  
	$lists['order_Dir'] = $filter_order_Dir;
	$country	=  $this->get( 'Data');
	// echo "<pre>"; print_r($country); exit;
	$total			=  $this->get( 'Total');
	$pagination =  $this->get( 'Pagination' );
	
              $tostring = $uri->toString();
		//$this->assignRef('searchlists',		$searchlists); 
		$this->assignRef('searchlists',		$searchlists); 
	$this->assignRef('lists',		$lists);   
  	$this->assignRef('country',		$country); 		
    	$this->assignRef('pagination',	$pagination);
    	//$this->assignRef('request_url',	$uri->toString());
		$requesturl = $uri->toString();
        $this->assignRef('request_url', $requesturl);
    	
    	parent::display($tpl);
  }
    // ================================= For Ordering =======================================================//
	  protected function getSortFields(){
			return array(
		        'ordering' => JText::_('JGRID_HEADING_ORDERING'),
				'published' => JText::_('JSTATUS'),
				'catparent' => JText::_('JCATPARENT'),
				'name' => JText::_('JGLOBAL_TITLE')
			);
		}
   
   // ================================= For Ordering =======================================================//	
}
?>
