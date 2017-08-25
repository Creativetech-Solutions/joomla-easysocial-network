<?php
/**
* @package   JE category component
* @copyright Copyright (C) 2009-2010 Joomlaextensions.co.in All rights reserved.
* @license   http://www.gnu.org/licenses/lgpl.html GNU/LGPL, see LICENSE.php
* Contact to : emailtohardik@gmail.com, joomextensions@gmail.com
**/

defined('_JEXEC') or die('Restricted access');
JHTML::_('behavior.tooltip');
$editor = JFactory::getEditor();
jimport ( 'joomla.application.component.controller' );
jimport( 'joomla.filesystem.file' );
 
class category_detailController extends JControllerForm {
	
	function __construct($default = array()) {
		parent::__construct ( $default );
		$this->registerTask ( 'add', 'edit' );
	}
	
	function edit($key = NULL, $urlVar = NULL) {
		JRequest::setVar ( 'view', 'category_detail' );
		JRequest::setVar ( 'layout', 'default' );
		JRequest::setVar ( 'hidemainmenu', 1 );
		parent::display ();
	}
	
	function save($key = NULL, $urlVar = NULL) { 
		$post = JRequest::get ( 'post' );
		$option = JRequest::getVar('option','','request','string');
		$cid = JRequest::getVar ( 'cid', array (0), 'post', 'array' );
		$post ['id'] = $cid [0];
		 
	 
		$model 	= $this->getModel ( 'category_detail' );
		$row = $model->store ( $post );
		
		
		//----------------------------- Ordering ----------------------------------------------//

		 if($cid[0]==0){	

			$sql = "SELECT max(ordering) As ordering FROM #__jequoteproduct_category ";
			$db		= JFactory::getDBO();
			$db->setQuery($sql);
			$max  = $db->loadObject();		

			if($max->ordering)
				$order = $max->ordering + 1;
			else
				$order = 1;		

			$query = "UPDATE #__jequoteproduct_category SET ordering = ".$order." WHERE id = ".$row; 
			$db->setQuery($query);
			$db->query();

		} 

		//----------------------------- Ordering ----------------------------------------------//
		
		
		if($row) 
		{			
			$msg = JText::_( 'CATEGORY_DETAIL_SAVED' );		
		}  
		 else
		 {
			$msg = JText::_( 'ERROR_SAVING_CATEGORY_DETAIL' );
		  }	
					 
		
		$cmd = JRequest::getCmd('task'); 
		
		if($cmd == 'apply')
		{
			$this->setRedirect ( 'index.php?option=' . $option . '&view=category_detail&task=edit&cid[]='.$row , $msg );
		} else {
			$this->setRedirect ( 'index.php?option=' . $option . '&view=category', $msg );
		}
		
 	
	
 }
	
	function remove() {
		$option = JRequest::getVar('option','','request','string');
		$cid = JRequest::getVar ( 'cid', array (0 ), 'post', 'array' );
		if (! is_array ( $cid ) || count ( $cid ) < 1) {
			JError::raiseError ( 500, JText::_ ( 'SELECT_CATEGORY_TO_DELETE' ) );
		}
		$model = $this->getModel ( 'category_detail' );
 		 
	 	if (! $model->delete ( $cid )) {
			echo "<script> alert('" . $model->getError ( true ) . "'); window.history.go(-1); </script>\n";
		}
		 
		
		$msg = JText::_ ( 'JE_CATEGORY_DETAIL_DELETED_SUCCESSFULLY' );
		$this->setRedirect ( 'index.php?option='.$option.'&view=category',$msg );
	}
	
	function publish() {
		$option = JRequest::getVar('option','','request','string');
		$cid = JRequest::getVar ( 'cid', array (0 ), 'post', 'array' );
		if (! is_array ( $cid ) || count ( $cid ) < 1) {
			JError::raiseError ( 500, JText::_ ( 'SELECT_PERIOD_TO_PUBLISH  ' ) );
		}
		$model = $this->getModel ( 'category_detail' );
		if (! $model->publish ( $cid, 1 )) {
			echo "<script> alert('" . $model->getError ( true ) . "'); window.history.go(-1); </script>\n";
		}
		$msg = JText::_ ( 'JE_CATEGORY_DETAIL_PUBLISHED_SUCCESSFULLY' );
		$this->setRedirect ( 'index.php?option='.$option.'&view=category',$msg );
	}
	
	function unpublish() {
		$option = JRequest::getVar('option','','request','string');
		$cid = JRequest::getVar ( 'cid', array (0 ), 'post', 'array' );
		if (! is_array ( $cid ) || count ( $cid ) < 1) {
			JError::raiseError ( 500, JText::_ ( 'SELECT_CATEGORY_TO_UNPUBLISH ' ) );
		}
		$model = $this->getModel ( 'category_detail' );
		if (! $model->publish ( $cid, 0 )) {
			echo "<script> alert('" . $model->getError ( true ) . "'); window.history.go(-1); </script>\n";
		}
		$msg = JText::_ ( 'JE_CATEGORY_DETAIL_UNPUBLISHED_SUCCESSFULLY' );
		$this->setRedirect ( 'index.php?option='.$option.'&view=category',$msg );
	}
	
	function cancel($key = NULL) {
		$option = JRequest::getVar('option','','request','string');
		$msg = JText::_ ( 'JE_CATEGORY_DETAIL_EDITING_CANCELLED' );
		$this->setRedirect ( 'index.php?option='.$option.'&view=category',$msg );
	}	 
	 
	
}
?>