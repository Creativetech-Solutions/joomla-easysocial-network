<?php
/**
* @package   JE category component
* @copyright Copyright (C) 2009-2010 Joomlaextensions.co.in All rights reserved.
* @license   http://www.gnu.org/licenses/lgpl.html GNU/LGPL, see LICENSE.php
* Contact to : emailtohardik@gmail.com, joomextensions@gmail.com
**/

defined( '_JEXEC' ) or die( 'Restricted access' );
jimport('joomla.application.component.model');

class category_detailModelcategory_detail extends JModelLegacy
{
	var $_id 			= null;
	var $_data 			= null;
	var $_region 		= null;
	var $_table_prefix 	= null;
	var $_copydata		= null;

	function __construct()
	{
		parent::__construct();
		$this->_table_prefix = '#__jequoteproduct_';		
	  	$array = JRequest::getVar('cid',  0, '', 'array');
		$this->setId((int)$array[0]);
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
			$query = 'SELECT * FROM '.$this->_table_prefix.'category WHERE id='.$this->_id ;
			$this->_db->setQuery($query);
			$this->_data = $this->_db->loadObject();
			return (boolean) $this->_data;
		}
		return true;
	}

	function _initData()
	{
		if (empty($this->_data))
		{
			$detail = new stdClass();
			$detail->id					= 0;
			$detail->cname		= null;
			$detail->ordering		= null;
	 
			$detail->published			= 1;
			
			$this->_data		 		= $detail;
			return (boolean) $this->_data;
		}
		return true;
	}
  	
	function store($data)
	{ 
		$row =&$this->getTable('category');
		if (!$row->bind($data)) {
			$this->setError($this->_db->getErrorMsg());
			return false;
		}
		if (!$row->store()) {
			$this->setError($this->_db->getErrorMsg());
			return false;
		}
		return $row->id;
	}

	function delete($cid = array())
	{
		if (count( $cid ))
		{	
			$option = JRequest::getVar('option','','request','string');
			$db=  JFactory :: getDBO();
			$cids = implode( ',', $cid );
			 
			
		//++++++++++++++++++++++++++++ Delete image from Folder ++++++++++++++++++++++++++ //
			$query = 'DELETE FROM '.$this->_table_prefix.'category WHERE id IN ( '.$cids.' )';
			$this->_db->setQuery( $query );
		// =========================== Category delete ========================================= //		
			if(!$this->_db->query()) 
			{
				$this->setError($this->_db->getErrorMsg());
				return false;
			}
	
		}
		return true;
	}
	
	function publish($cid = array(), $publish = 1)
	{		
		if (count( $cid ))
		{
			$cids = implode( ',', $cid );
			$query = 'UPDATE '.$this->_table_prefix.'category'
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
		$db=  JFactory :: getDBO();
		$query = 'SELECT * FROM '.$this->_table_prefix.'setting WHERE id=1';
		$db->setQuery($query);
		return $db->loadObject();
	}
	
}

?>