<?php
/**

* @package   JE shop

* @copyright Copyright (C) 2009-2010 Joomlaextensions.co.in All rights reserved.

* @license   http://www.gnu.org/licenses/lgpl.html GNU/LGPL, see LICENSE.php

* Contact to : emailtohardik@gmail.com, joomextensions@gmail.com

**/ 


defined ( '_JEXEC' ) or die ( 'Restricted access' );

jimport ( 'joomla.application.component.controller' );

jimport ('joomla.filesystem.file');
 
class interest_detailController extends JControllerLegacy {
	var $_table_prefix=null;
	function __construct($default = array()) {
		$this->_table_prefix='#__joomproject_';
		parent::__construct ( $default );
		$this->registerTask ( 'add', 'edit' );
	}
	function edit() {
		JRequest::setVar ( 'view', 'interest_detail' );
		JRequest::setVar ( 'layout', 'default' );
		JRequest::setVar ( 'hidemainmenu', 1 );
		parent::display ();
	
	}
	function save() { 
	//	echo "lost something"; exit;
		$db=  JFactory :: getDBO();
		$post = JRequest::get ( 'post' );
		$option = JRequest::getVar('option','','request','string');
		$post["int_desc"] = JRequest::getVar( 'int_desc', '', 'post', 'string', JREQUEST_ALLOWRAW );
		$intitemlist= JRequest::getVar ( 'intitem', array (), 'post', 'array' );
		$intitemidlist= JRequest::getVar ( 'intitemid', array (), 'post', 'array' );
		$model = $this->getModel ( 'interest_detail' );
		$post["int_desc"]=addslashes($post["int_desc"]);
	
		//	$mylink = 'index.php?option='.$post['option'].'&view='.$post['view'].'&task=edit&cid[]='.$post['id'];
		//	$msg = JText::_ ( 'SHOP_PLEASE_UPLOAD_VALID_IMAGE_FILE' );
			//$mainframe->redirect( $mylink,$msg );	
			

	
	//===================================================================================================	
		
		
		$insertid = $model->store ( $post,'interest');
		
		if ($insertid) 
		{
			
				for($i=0;$i<count($intitemidlist);$i++)
				{
					$int=array();
					$int['int_name']=$intitemlist[$i];
					$int['id']=$intitemidlist[$i];
					$int['int_id']=$insertid;
					echo "id".$int['id'].'<br>';
					echo "int_id".$int['int_id'].'<br>';
					echo "int_name".$int['int_name'].'<br>';
					
					$model->store ( $int,'interest_detail');
				}
			
					
						$msg = JText::_ ( 'INTEREST_DETAIL_SAVED' );
		
				
		}
				$this->setRedirect ( 'index.php?option='.$option.'&view=interest_detail',$msg );
		
}

function deleteint() {

		
		$db=  JFactory :: getDBO();
		$option = JRequest::getVar('option','','request','string');
		
	 	$intid = JRequest::getVar ( 'intid',0, 'request', 'string' );
	
		$query="DELETE FROM ".$this->_table_prefix."interest_detail WHERE id=".$intid;
		
		
		$db->setQuery($query);
		if(!$db->Query())
		{
			$msg = JText::_ ( 'INTEREST_DELETED_FAIL' );
			$this->setRedirect ( 'index.php?option='.$option.'&view=interest_detail',$msg );
			
		}
		exit;
	}





	function remove() {
		
		$option = JRequest::getVar('option','','request','string');
		
	 $cid = JRequest::getVar ( 'cid', array (0 ), 'post', 'array' );
	
		
		if (! is_array ( $cid ) || count ( $cid ) < 1) {
			JError::raiseError ( 500, JText::_ ( 'JE_SELECT_AN_ITEM_TO_DELETE' ) );
		}
		
		$model = $this->getModel ( 'interest_detail' );
		if (! $model->delete ( $cid )) {
			echo "<script> alert('" . $model->getError ( true ) . "'); window.history.go(-1); </script>\n";
		}
		$msg = JText::_ ( 'INTEREST_DETAIL_DELETED_SUCCESSFULLY' );
		$this->setRedirect ( 'index.php?option='.$option.'&view=interest',$msg );
	}
	
	function publish() {
		
		$option = JRequest::getVar('option','','request','string');
		
		 $cid = JRequest::getVar ( 'cid', array (0 ), 'post', 'array' );
		if (! is_array ( $cid ) || count ( $cid ) < 1) {
			JError::raiseError ( 500, JText::_ ( 'JE_SELECT_AN_ITEM_TO_PUBLISH' ) );
		}
		
		$model = $this->getModel ( 'interest_detail' );


		if (! $model->publish ( $cid, 1 )) {
			echo "<script> alert('" . $model->getError ( true ) . "'); window.history.go(-1); </script>\n";
		}
		$msg = JText::_ ( 'INTEREST_DETAIL_PUBLISHED_SUCCESSFULLY' );
		$this->setRedirect ( 'index.php?option='.$option.'&view=interest',$msg );
	}
	
	function unpublish() {
		
		$option = JRequest::getVar('option','','request','string');
		
		$cid = JRequest::getVar ( 'cid', array (0 ), 'post', 'array' );
		
		if (! is_array ( $cid ) || count ( $cid ) < 1) {
			JError::raiseError ( 500, JText::_ ( 'JE_SELECT_AN_ITEM_TO_UNPUBLISH' ) );
		}
		
		$model = $this->getModel ( 'interest_detail' );
		if (! $model->publish ( $cid, 0 )) {
			echo "<script> alert('" . $model->getError ( true ) . "'); window.history.go(-1); </script>\n";
		}
		$msg = JText::_ ( 'INTEREST_DETAIL_UNPUBLISHED_SUCCESSFULLY' );
		$this->setRedirect ( 'index.php?option='.$option.'&view=interest',$msg );
	}

	function cancel() {
		
		$option = JRequest::getVar('option','','request','string');
		$msg = JText::_ ( 'INTEREST_DETAIL_EDITING_CANCELLED' );
		$this->setRedirect ( 'index.php?option='.$option.'&view=interest',$msg );
	}	
	function display($cachable = false, $urlparams = Array()) {
		
		parent::display($cachable = false, $urlparams = Array());
		
	}	 
}

?>
