<?php
 /**
* @package   Dowalo
* @copyright Copyright (C) 2009 - 2010 Open Source Matters. All rights reserved.
* @license   http://www.gnu.org/licenses/lgpl.html GNU/LGPL, see LICENSE.php
* Contact to : emailtohardik@gmail.com, joomextensions@gmail.com
* Visit : http://www.joomlaextensions.co.in/
**/ 

defined ( '_JEXEC' ) or die ( 'Restricted access' );

jimport ( 'joomla.application.component.controller' );
 
class state_detailController extends JControllerLegacy {
	function __construct($default = array()) {
		parent::__construct ( $default );
		$this->registerTask ( 'add', 'edit' ); $option = JRequest::getVar('option','','request','string');
		
	
	}
	function edit($key = NULL, $urlVar = NULL) {
		JRequest::setVar ( 'view', 'state_detail' );
		JRequest::setVar ( 'layout', 'default' );
		JRequest::setVar ( 'hidemainmenu', 1 );
		parent::display ();
	
	}
	function save($key = NULL, $urlVar = NULL)  { 
		//$post = JRequest::get ( 'post' );
		
		
		$post = JRequest::get ( 'post' );
		$state_name = JRequest::getVar( 'state_name', '', 'post', 'string', JREQUEST_ALLOWRAW );
		$state_3_code = JRequest::getVar( 'state_3_code', '', 'post', 'string', JREQUEST_ALLOWRAW );
		$state_2_code = JRequest::getVar( 'state_2_code', '', 'post', 'string', JREQUEST_ALLOWRAW );
		$country = JRequest::getVar( 'country', '', 'post', 'string',JREQUEST_ALLOWRAW );
		
		$post["state_name"]=$state_name;
		$post["state_3_code"]=$state_3_code;	
		$post["state_2_code"]=$state_2_code;	
		$post["country"]=$country;
		
		$option = JRequest::getVar('option','','request','string');
		$cid = JRequest::getVar ( 'cid', array (0 ), 'post', 'array' );
		
		$post ['state_id'] = $cid [0];
		
		$model = $this->getModel ( 'state_detail' );
		
		if ($model->store ( $post )) {
			
			$msg = JText::_ ( 'DOWALO_STATE_DETAIL_SAVED' );
		
		} else {
			
			$msg = JText::_ ( 'ERROR_SAVING_STATE_DETAIL' );
		}
		 
		$this->setRedirect ( 'index.php?option=' . $option . '&view=state', $msg );
	}
	
	function remove() {
		
		$option = JRequest::getVar('option','','request','string');
		
		$cid = JRequest::getVar ( 'cid', array (0 ), 'post', 'array' );
		
		if (! is_array ( $cid ) || count ( $cid ) < 1) {
			JError::raiseError ( 500, JText::_ ( 'SELECT_AN_ITEM_TO_DELETE' ) );
		}
		
		$model = $this->getModel ( 'state_detail' );
		if (! $model->delete ( $cid )) {
			echo "<script> alert('" . $model->getError ( true ) . "'); window.history.go(-1); </script>\n";
		}
		$msg = JText::_ ( 'DOWALO_STATE_DETAIL_DELETED_SUCCESSFULLY' );
		$this->setRedirect ( 'index.php?option='.$option.'&view=state',$msg );
	}
	
	function cancel($key = NULL) {
		
		$option = JRequest::getVar('option','','request','string');
		$msg = JText::_ ( 'DOWALO_STATE_DETAIL_EDITING_CANCELLED' );
		$this->setRedirect ( 'index.php?option='.$option.'&view=state',$msg );
	}	 


function publish() {
		
		
		$option = JRequest::getVar('option','','request','string');
		
		$cid = JRequest::getVar ( 'cid', array (0 ), 'post', 'array' );
		
		if (! is_array ( $cid ) || count ( $cid ) < 1) {
			JError::raiseError ( 500, JText::_ ( 'SELECT AN ITEM TO PUBLISH' ) );
		}
		
		$model = $this->getModel ( 'state_detail' );
		if (! $model->publish ( $cid, 1 )) {
			echo "<script> alert('" . $model->getError ( true ) . "'); window.history.go(-1); </script>\n";
		}
		$msg = JText::_ ( 'DOWALO_STATE_DETAIL_PUBLISHED_SUCCESSFULLY' );
		$this->setRedirect ( 'index.php?option='.$option.'&view=state',$msg );
	}
	function unpublish() {
			
		$option = JRequest::getVar('option','','request','string');
		
		$cid = JRequest::getVar ( 'cid', array (0 ), 'post', 'array' );
		
		if (! is_array ( $cid ) || count ( $cid ) < 1) {
			JError::raiseError ( 500, JText::_ ( 'SELECT AN ITEM TO UNPUBLISH' ) );
		}
		
		$model = $this->getModel ( 'state_detail' );
		if (! $model->publish ( $cid, 0 )) {
			echo "<script> alert('" . $model->getError ( true ) . "'); window.history.go(-1); </script>\n";
		}
		$msg = JText::_ ( 'DOWALO_STATE_DETAIL_UNPUBLISHED_SUCCESSFULLY' );
		$this->setRedirect ( 'index.php?option='.$option.'&view=state',$msg );
	}
}