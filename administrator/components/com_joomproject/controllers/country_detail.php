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
 
class country_detailController extends JControllerForm {

	function __construct($default = array()) {
		parent::__construct ( $default );
		$this->registerTask ( 'add', 'edit' );
	}
	
	function edit($key = NULL, $urlVar = NULL) {
		JRequest::setVar ( 'view', 'country_detail' );
		JRequest::setVar ( 'layout', 'default' );
		JRequest::setVar ( 'hidemainmenu', 1 );
		parent::display ();
	}
	
	function save($key = NULL, $urlVar = NULL) { 
		
		$post = JRequest::get ( 'post' );
	
		$country_name = JRequest::getVar( 'country_name', '', 'post', 'string', JREQUEST_ALLOWRAW );
		$country_3_code = JRequest::getVar( 'country_3_code', '', 'post', 'string', JREQUEST_ALLOWRAW );
		$country_2_code = JRequest::getVar( 'country_2_code', '', 'post', 'string', JREQUEST_ALLOWRAW );
		$post["country_name"]=$country_name;
		$post["country_3_code"]=$country_3_code;	
		$post["country_2_code"]=$country_2_code;
		
		$option = JRequest::getVar('option','','request','string');
		
		$cid = JRequest::getVar ( 'cid', array (0 ), 'post', 'array' );
		
		$post ['country_id'] = $cid [0];
		
		/*echo "<pre>";
		print_r($post);
		exit;*/
		$model = $this->getModel ( 'country_detail' );
		
		if ($model->store ( $post )) {
			
			$msg = JText::_ ( 'DOWALO_COUNTRY_DETAIL_SAVED' );
		
		} else {
			
			$msg = JText::_ ( 'ERROR_SAVING_COUNTRY_DETAIL' );
		}
		 
		$this->setRedirect ( 'index.php?option=' . $option . '&view=country', $msg );
	}
	
	function remove() {
		
		$option = JRequest::getVar('option','','request','string');
		
		$cid = JRequest::getVar ( 'cid', array (0 ), 'post', 'array' );
		
		if (! is_array ( $cid ) || count ( $cid ) < 1) {
			JError::raiseError ( 500, JText::_ ( 'SELECT_AN_ITEM_TO_DELETE' ) );
		}
		
		$model = $this->getModel ( 'country_detail' );
		if (! $model->delete ( $cid )) {
			echo "<script> alert('" . $model->getError ( true ) . "'); window.history.go(-1); </script>\n";
		}
		$msg = JText::_ ( 'DOWALO_COUNTRY_DETAIL_DELETED_SUCCESSFULLY' );
		$this->setRedirect ( 'index.php?option='.$option.'&view=country',$msg );
	}
	
	function publish() {
		
		$option = JRequest::getVar('option','','request','string');
		
		$cid = JRequest::getVar ( 'cid', array (0 ), 'post', 'array' );
		if (! is_array ( $cid ) || count ( $cid ) < 1) {
			JError::raiseError ( 500, JText::_ ( 'SELECT AN ITEM TO PUBLISH' ) );
		}
		
		$model = $this->getModel ( 'country_detail' );
		if (! $model->publish ( $cid, 1 )) {
			echo "<script> alert('" . $model->getError ( true ) . "'); window.history.go(-1); </script>\n";
		}
		$msg = JText::_ ( 'DOWALO_COUNTRY_DETAIL_PUBLISHED_SUCCESSFULLY' );
		$this->setRedirect ( 'index.php?option='.$option.'&view=country',$msg );
	}
	
	function unpublish() {
		
		$option = JRequest::getVar('option','','request','string');
		
		$cid = JRequest::getVar ( 'cid', array (0 ), 'post', 'array' );
		if (! is_array ( $cid ) || count ( $cid ) < 1) {
			JError::raiseError ( 500, JText::_ ( 'SELECT AN ITEM TO UNPUBLISH' ) );
		}
		
		$model = $this->getModel ( 'country_detail' );
		if (! $model->publish ( $cid, 0 )) {
			echo "<script> alert('" . $model->getError ( true ) . "'); window.history.go(-1); </script>\n";
		}
		$msg = JText::_ ( 'DOWALO_COUNTRY_DETAIL_UNPUBLISHED_SUCCESSFULLY' );
		$this->setRedirect ( 'index.php?option='.$option.'&view=country',$msg );
	}
	
	function cancel($key = NULL) {
		$option = JRequest::getVar('option','','request','string');
		$msg = JText::_ ( 'DOWALO_COUNTRY_DETAIL_EDITING_CANCELLED' );
		$this->setRedirect ( 'index.php?option='.$option.'&view=country',$msg );
	}	 

}
