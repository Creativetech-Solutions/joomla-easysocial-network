<?php
 /**
* @package   Dowalo
* @copyright Copyright (C) 2009 - 2010 Open Source Matters. All rights reserved.
* @license   http://www.gnu.org/licenses/lgpl.html GNU/LGPL, see LICENSE.php
* Contact to : emailtohardik@gmail.com, joomextensions@gmail.com
* Visit : http://www.joomlaextensions.co.in/
**/ 

defined( '_JEXEC' ) or die( 'Restricted access' );
jimport('joomla.application.component.model');

class state_detailModelstate_detail extends JModelLegacy
{
	var $_id = null;
	var $_data = null;
	var $_region = null;
	var $_table_prefix = null;
	
	function __construct()
	{
		parent::__construct();
		$this->_table_prefix = '#__joomproject_';		
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
	
	function getcountry()
	{
		$mainframe = JFactory::getApplication();
		$query = "SELECT country_id as value, country_name as text FROM  ".$this->_table_prefix."countries ";
		$this->_country = $this->_getList( $query );
		return $this->_country;
	} 
	
	function _loadData()
	{
		if (empty($this->_data))
		{
			$query = 'SELECT * FROM '.$this->_table_prefix.'state  WHERE state_id = '. $this->_id;
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
			$detail->state_id 			= 0;
			$detail->country_id		= 0;
			$detail->state_name		= null;
			$detail->state_3_code	= null;
			$detail->state_2_code	= null;
			$detail->published	= 0;
			$this->_data		 	= $detail;
			return (boolean) $this->_data;
		}
		return true;
	}
	
  	function store($data)
	{ 
		$row =$this->getTable('state');
		
		if (!$row->bind($data)) {
			$this->setError($this->_db->getErrorMsg());
			return false;
		}
		if (!$row->store()) {
			$this->setError($this->_db->getErrorMsg());
			return false;
		}
		
		return true;
	}

	function delete($cid = array())
	{
		if (count( $cid ))
		{
			$cids = implode( ',', $cid );
			
			$query = 'DELETE FROM '.$this->_table_prefix.'state WHERE state_id IN ( '.$cids.' )';
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
		if (count( $cid ))
		{
			$cids = implode( ',', $cid );
			
			 $query = 'UPDATE '.$this->_table_prefix.'state'
				. ' SET published = ' . intval( $publish )
				. ' WHERE state_id IN ( '.$cids.' )';
			
			$this->_db->setQuery( $query );
			if (!$this->_db->query()) {
				$this->setError($this->_db->getErrorMsg());
				return false;
			}
		}

		return true;
	}
}
?>
