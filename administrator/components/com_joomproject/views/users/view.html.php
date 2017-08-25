<?php
/**
* @package   JE Category component
* @copyright Copyright (C) 2009-2010 Joomlaextensions.co.in All rights reserved.
* @license   http://www.gnu.org/licenses/lgpl.html GNU/LGPL, see LICENSE.php
* Contact to : emailtohardik@gmail.com, joomextensions@gmail.com
**/

defined( '_JEXEC' ) or die( 'Restricted access' );
jimport( 'joomla.application.component.view' );
 
class categoryViewcategory extends JViewLegacy
{
	function __construct( $config = array())
	{
		 parent::__construct( $config );
	}
	function display($tpl = null)
	{	
		$mainframe = JFactory::getApplication();
		global $context;
		$context='cname';
		$document = JFactory::getDocument();
		$document->setTitle( JText::_('CATEGORY') );
   		 
   		JToolBarHelper::title(   JText::_( 'CATEGORY_MANAGEMENT' ) );   		
   		
   		JToolBarHelper::addNew();
 		JToolBarHelper::editList();
		JToolBarHelper::deleteList();		
		JToolBarHelper::publishList();
		JToolBarHelper::unpublishList();
		
		
		//JToolBarHelper::custom('assign_rcm_user', 'icon', 'icon over', 'Button text', false, false);
		//JToolBarHelper::custom('assign_rcm_user', '', '', JText::_('ASSIGN_USER'), false, false);
		//JToolBarHelper::preferences('com_jetour');
	   	 
	   	$uri	= JFactory::getURI();
		
		
		$filter_order     = $mainframe->getUserStateFromRequest( $context.'filter_order',      'filter_order', 	  'cname' );
		$filter_order_Dir = $mainframe->getUserStateFromRequest( $context.'filter_order_Dir',  'filter_order_Dir', '' );
		$plan = $mainframe->getUserStateFromRequest( $context.'tname','tname',0 );
	 	
		//++++++++++++++++++++++++++++++Published  Combo +++++++++++++++++++++++++++++++++++++++++++++//
		$publish_op = array();
		$publish_op[]   	= JHTML::_('select.option', '-1',JText::_('SELECT_STATE'));
		$publish_op[]   	= JHTML::_('select.option', '1',JText::_('PUBLISHED'));
		$publish_op[]   	= JHTML::_('select.option', '0', JText::_('UNPUBLISHED'));
		$published	= JRequest::getVar('published', '-1','request','int');
		$searchlists['published'] = JHTML::_('select.genericlist',$publish_op,'published','class="inputbox" size="1" onchange="selectsearch(this.value)" ','value','text' ,$published);
		
		$lists['order'] 		= $filter_order;  
		$lists['order_Dir'] = $filter_order_Dir;
		$tour			= $this->get( 'Data');
		
		$total			= $this->get( 'Total');
		$pagination = $this->get( 'Pagination' );

		$this->assignRef('searchlists',		$searchlists); 
     	$this->assignRef('lists',		$lists);    
  		$this->assignRef('tour',		$tour); 		
    	$this->assignRef('pagination',	$pagination);
    	//$this->assignRef('request_url',	$uri->toString());    	
    	parent::display($tpl);
	}
	 // ================================= For Ordering =======================================================//

	  protected function getSortFields(){

			return array(

				'ordering' => JText::_('JGRID_HEADING_ORDERING'),

				'published' => JText::_('JSTATUS'),

				'cname' => JText::_('JGLOBAL_TITLE')

			);

		}

   

   // ================================= For Ordering =======================================================//	
}
?>
