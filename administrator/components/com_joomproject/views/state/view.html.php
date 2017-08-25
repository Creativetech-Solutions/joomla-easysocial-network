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
 
class stateViewstate extends JViewLegacy
{
	function __construct( $config = array())
	{
		 parent::__construct( $config );
	}
    
	function display($tpl = null)
	{	
		$mainframe = JFactory::getApplication();
		$context = "state";

		
		$document =  JFactory::getDocument();
		$document->setTitle( JText::_('STATE') );
   		 
   		JToolBarHelper::title(   JText::_( 'STATE_MANAGEMENT' ) );   		
   		
   		
   		  
 		JToolBarHelper::addNew();
 		JToolBarHelper::editList();
		JToolBarHelper::deleteList();		
		JToolBarHelper::publishList();
		JToolBarHelper::unpublishList();
	   
	   	
		$uri	= JFactory::getURI();
		
		$filter_order     = $mainframe->getUserStateFromRequest( $context.'filter_order',      'filter_order', 	  'ordering' );
		$filter_order_Dir = $mainframe->getUserStateFromRequest( $context.'filter_order_Dir',  'filter_order_Dir', '' );
		
		//$state = $mainframe->getUserStateFromRequest( $context.'state','state',0 );

		 
		  
		$lists['order'] 		= $filter_order;  
		$lists['order_Dir'] = $filter_order_Dir;
		$state			=  $this->get( 'Data');
		$total			=  $this->get( 'Total');
		$pagination =  $this->get( 'Pagination' );
		
	
     $this->assignRef('lists',		$lists);    
  	$this->assignRef('state',		$state); 		
    $this->assignRef('pagination',	$pagination);
   $requesturl = $uri->toString();
$this->assignRef('request_url',	$requesturl);
    	parent::display($tpl);
  }
}
?>
