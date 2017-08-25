<?php
/**

* @package   JE shop

* @copyright Copyright (C) 2009-2010 Joomlaextensions.co.in All rights reserved.

* @license   http://www.gnu.org/licenses/lgpl.html GNU/LGPL, see LICENSE.php

* Contact to : emailtohardik@gmail.com, joomextensions@gmail.com

**/ 


defined( '_JEXEC' ) or die( 'Restricted access' );

jimport('joomla.application.component.model');


class skills_detailModelskills_detail extends JModelLegacy
{
	var $_id = null;
	var $_data = null;
	var $_table_prefix = null;
	var $_fielddata = null;
	function __construct()
	{	
		
		parent::__construct();

		$this->_table_prefix = '#__joomproject_';		
	  
		$array = JRequest::getVar('cid', 1, '', 'array');
		
		$this->setId((int)$array[0]);
		
		$this->catlist = null;
		
	}
	function setId($id)
	{
		
		$this->_id		= $id;
		$this->_data	= null;
		
	}

	function &getData()
	{
	 	
		if ($this->_loadData())
		{
			
		}else  $this->_initData();

	   	return $this->_data;	
	}
	
	function _loadData()
	{
		
		if (empty($this->_data))
		{
 			$query = 'SELECT * FROM '.$this->_table_prefix.'skills WHERE id = '. $this->_id;
			$this->_db->setQuery($query);
			$this->_data= $this->_db->loadObject();
		/*	echo "<pre>Qry:".$query;
			print_r ($this->_data);
			exit;
		*/	return (boolean) $this->_data;
			
		}
		 return true;
		
	}
	function getskills($id)
	{
		if(empty($id) || $id=='')
		{
			$id=0;
		}
	
		$db=  JFactory :: getDBO();
		
		$query = 'SELECT * FROM '.$this->_table_prefix.'skill_detail WHERE skill_id='.$id;
			
		$db->setQuery($query);
		$skilllist = $db->LoadObjectList();
		
	
		return $skilllist;
	}
	
	
	function _initData()
	{
		if (empty($this->_data))
		{
			$detail = new stdClass();
			$detail->id			= 0;
			$detail->skill_title= null;
			$detail->skill_desc	= null;
			$detail->published	= '1';		
			$this->_data= $detail;
			
			return (boolean) $this->_data;
			
			}
		return true;
	}
  function store($data,$tab)
	{
						
		$row = $this->getTable($tab);

	 	$option = JRequest::getVar('option','','','string');
		
		if (!$row->bind($data))
		 {
		 	
			$this->setError($this->_db->getErrorMsg());
			return false;
		}
		if (!$row->store()) 
		{
			
			
			$this->setError($this->_db->getErrorMsg());
			return false;
		}

		return $row->id;
	}
	
	
	function delete($cid = array())
	{
		if (count( $cid ))
		{
			$cids = implode( ',', $cid );
			
			$query = 'DELETE FROM '.$this->_table_prefix.'skills WHERE id IN ( '.$cids.' )';
			$this->_db->setQuery( $query );
			if(!$this->_db->query()) {
				$this->setError($this->_db->getErrorMsg());
				return false;
			}
			$query = 'DELETE FROM '.$this->_table_prefix.'skill_detail WHERE skill_id IN ( '.$cids.' )';
			$this->_db->setQuery( $query );
			if(!$this->_db->query()) {
				$this->setError($this->_db->getErrorMsg());
				return false;
			}
			
		}

		return true;
	}

	function publish($cid = array(), $publish = 1)
	{		
		/*echo "<pre>";print_r($cid);
		echo $publish;exit;*/
		if (count( $cid ))
		{
			$cids = implode( ',', $cid );
			
			$query = 'UPDATE '.$this->_table_prefix.'skills'
				. ' SET published = ' . intval( $publish )
				. ' WHERE id IN ( '.$cids.' )';
			$this->_db->setQuery( $query );
			
			if (!$this->_db->query()) {
				$this->setError($this->_db->getErrorMsg());
				return false;
			}
		}

		return true;
	}
	
	function getconfigration()
	{
		$db= & JFactory :: getDBO();
		
		$query = 'SELECT * FROM #__je_shop_setting WHERE id=1';
			
		$db->setQuery($query);
		
		return $db->LoadObject();
	
	}
	
		
}

?>
