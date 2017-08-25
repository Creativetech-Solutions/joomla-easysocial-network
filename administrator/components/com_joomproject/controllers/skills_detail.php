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
 
class skills_detailController extends JControllerLegacy {
	var $_table_prefix=null;
	function __construct($default = array()) {
		$this->_table_prefix='#__joomproject_';
		parent::__construct ( $default );
		$this->registerTask ( 'add', 'edit' );
	}
	function edit() {
		JRequest::setVar ( 'view', 'skills_detail' );
		JRequest::setVar ( 'layout', 'default' );
		JRequest::setVar ( 'hidemainmenu', 1 );
		parent::display ();
	
	}
	function save() { 
	//	echo "lost something"; exit;
		$db=  JFactory :: getDBO();
		$post = JRequest::get ( 'post' );
		$option = JRequest::getVar('option','','request','string');
		$post["skill_desc"] = JRequest::getVar( 'skill_desc', '', 'post', 'string', JREQUEST_ALLOWRAW );
		$skilllist= JRequest::getVar ( 'skillitem', array (), 'post', 'array' );
		$skilllistid= JRequest::getVar ( 'skillitemid', array (), 'post', 'array' );
		$model = $this->getModel ( 'skills_detail' );
		$post["skill_desc"]=addslashes($post["skill_desc"]);
	
		//	$mylink = 'index.php?option='.$post['option'].'&view='.$post['view'].'&task=edit&cid[]='.$post['id'];
		//	$msg = JText::_ ( 'JE_PLEASE_UPLOAD_VALID_IMAGE_FILE' );
			//$mainframe->redirect( $mylink,$msg );	
			

	
	//===================================================================================================	
		
		
		$insertid = $model->store ( $post,'skills');
		
		if ($insertid) 
		{
			
				for($i=0;$i<count($skilllistid);$i++)
				{
					$skil=array();
					$skil['skill_name']=$skilllist[$i];
					$skil['id']=$skilllistid[$i];
					$skil['skill_id']=$insertid;
					echo "id".$skil['id'].'<br>';
					echo "skill_id".$skil['skill_id'].'<br>';
					echo "skill_name".$skil['skill_name'].'<br>';
					
					$model->store ( $skil,'skill_detail');
				}
			
					
						$msg = JText::_ ( 'SKILLS_DETAIL_SAVED' );
		
				
		}
				$this->setRedirect ( 'index.php?option='.$option.'&view=skills',$msg );
		
}

function deleteskill() {

		
		$db=  JFactory :: getDBO();
		$option = JRequest::getVar('option','','request','string');
		
	 	$skid = JRequest::getVar ( 'skid',0, 'request', 'string' );
	
		$query="DELETE FROM ".$this->_table_prefix."skill_detail WHERE id=".$skid;
		
		
		$db->setQuery($query);
		if(!$db->Query())
		{
			$msg = JText::_ ( 'SKILLS_DELETED_FAIL' );
			$this->setRedirect ( 'index.php?option='.$option.'&view=skills_detail',$msg );
			
		}
		exit;
	}





	function remove() {
		
		$option = JRequest::getVar('option','','request','string');
		
	 $cid = JRequest::getVar ( 'cid', array (0 ), 'post', 'array' );
	
		
		if (! is_array ( $cid ) || count ( $cid ) < 1) {
			JError::raiseError ( 500, JText::_ ( 'JE_SELECT_AN_ITEM_TO_DELETE' ) );
		}
		
		$model = $this->getModel ( 'skills_detail' );
		if (! $model->delete ( $cid )) {
			echo "<script> alert('" . $model->getError ( true ) . "'); window.history.go(-1); </script>\n";
		}
		$msg = JText::_ ( 'SKILLS_DETAIL_DELETED_SUCCESSFULLY' );
		$this->setRedirect ( 'index.php?option='.$option.'&view=skills',$msg );
	}
	
	function publish() {
		
		$option = JRequest::getVar('option','','request','string');
		
		 $cid = JRequest::getVar ( 'cid', array (0 ), 'post', 'array' );
		if (! is_array ( $cid ) || count ( $cid ) < 1) {
			JError::raiseError ( 500, JText::_ ( 'JE_SELECT_AN_ITEM_TO_PUBLISH' ) );
		}
		
		$model = $this->getModel ( 'skills_detail' );


		if (! $model->publish ( $cid, 1 )) {
			echo "<script> alert('" . $model->getError ( true ) . "'); window.history.go(-1); </script>\n";
		}
		$msg = JText::_ ( 'SKILLS_DETAIL_PUBLISHED_SUCCESSFULLY' );
		$this->setRedirect ( 'index.php?option='.$option.'&view=skills',$msg );
	}
	
	function unpublish() {
		
		$option = JRequest::getVar('option','','request','string');
		
		$cid = JRequest::getVar ( 'cid', array (0 ), 'post', 'array' );
		
		if (! is_array ( $cid ) || count ( $cid ) < 1) {
			JError::raiseError ( 500, JText::_ ( 'JE_SELECT_AN_ITEM_TO_UNPUBLISH' ) );
		}
		
		$model = $this->getModel ( 'skills_detail' );
		if (! $model->publish ( $cid, 0 )) {
			echo "<script> alert('" . $model->getError ( true ) . "'); window.history.go(-1); </script>\n";
		}
		$msg = JText::_ ( 'SKILLS_DETAIL_UNPUBLISHED_SUCCESSFULLY' );
		$this->setRedirect ( 'index.php?option='.$option.'&view=skills',$msg );
	}

	function cancel() {
		
		$option = JRequest::getVar('option','','request','string');
		$msg = JText::_ ( 'SKILL_DETAIL_EDITING_CANCELLED' );
		$this->setRedirect ( 'index.php?option='.$option.'&view=skills',$msg );
	}	
	function display($cachable = false, $urlparams = Array()) {
		
		parent::display($cachable = false, $urlparams = Array());
		
	}	 
}

?>
