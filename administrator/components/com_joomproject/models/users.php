<?php 
/**
* @package   JE category component
* @copyright Copyright (C) 2009-2010 Joomlaextensions.co.in All rights reserved.
* @license   http://www.gnu.org/licenses/lgpl.html GNU/LGPL, see LICENSE.php
* Contact to : emailtohardik@gmail.com, joomextensions@gmail.com
**/

defined ('_JEXEC') or die ('Restricted access');
jimport('joomla.application.component.model');

class categoryModelcategory extends JModelLegacy
{
	var $_data = null;
	var $_total = null;
	var $_pagination = null;
	var $_table_prefix = null;
	
	function __construct()
	{
		parent::__construct();
		$mainframe = JFactory::getApplication();
		global $context;
		$context='cname';
	  	$this->_table_prefix = '#__jequoteproduct_';
					
		$limit			= $mainframe->getUserStateFromRequest( $context.'limit', 'limit', $mainframe->getCfg('list_limit'), 0);
		$limitstart 	= $mainframe->getUserStateFromRequest( $context.'limitstart', 'limitstart', 0 );
		
		$state    	    = $mainframe->getUserStateFromRequest( $context.'state','state',0);
		$filter    	    = $mainframe->getUserStateFromRequest( $context.'filter','filter',0);
		
		$this->setState('limit', $limit);
		$this->setState('limitstart', $limitstart);
		$this->setState('state', $state);
		$this->setState('filter', $filter);
	}
	
	function getData()
	{		
		if (empty($this->_data))
		{
			$query = $this->_buildQuery();
			$this->_data = $this->_getList($query, $this->getState('limitstart'), $this->getState('limit'));
		}
		return $this->_data;
	}
	
	function getTotal()
	{
		if (empty($this->_total))
		{
			$query = $this->_buildQuery();
			$this->_total = $this->_getListCount($query);
		}
		return $this->_total;
	}
	
	function getPagination()
	{
		if (empty($this->_pagination))
		{
			jimport('joomla.html.pagination');
			$this->_pagination = new JPagination( $this->getTotal(), $this->getState('limitstart'), $this->getState('limit') );
		}

		return $this->_pagination;
	}
  	
	function _buildQuery()
	{
		$where = '';
		$squery = '';
		$where = 'WHERE 1=1';
		$tname	= JRequest::getVar('serach', '','request','string');
		$published = JRequest::getVar('published','-1');
		
		if($tname!='') { 
			$squery .= " AND cname Like '%".$tname."%'";
		}
		if($published!='-1'){
			$squery .= ' AND published='.$published;
		}
		$orderby	= $this->_buildContentOrderBy();
	    	$query = ' SELECT * FROM '.$this->_table_prefix.'category WHERE 1=1'.$squery.$orderby.'    ';  
		return $query;
	}
	
	function _buildContentOrderBy()
	{
		$mainframe = JFactory::getApplication();
		global $context;
		$context='cname';
		$filter_order     = $mainframe->getUserStateFromRequest( $context.'filter_order',      'filter_order', 	  'id' );
		$filter_order_Dir = $mainframe->getUserStateFromRequest( $context.'filter_order_Dir',  'filter_order_Dir', '' );
		$orderby 	= ' ORDER BY '.$filter_order.' '.$filter_order_Dir;			
		return $orderby;
	}
	// ================================= Ordering Function =======================================================//

		function saveorder(){

			$mainframe = JFactory::getApplication();

			$db = JFactory::getDBO();

			$cid = JRequest::getVar('cid', array(0), 'post', 'array');

			$total = count($cid);

			$order = JRequest::getVar('order', array(0), 'post', 'array');

			JArrayHelper::toInteger($order, array(0));

			$row = JTable::getInstance('category', 'Table');

			$groupings = array();

			for ($i = 0; $i < $total; $i++){

				$row->load((int)$cid[$i]);

				$groupings[] = $row->name;

				if ($row->ordering != $order[$i]){

					$row->ordering = $order[$i];

					if (!$row->store()){

						JError::raiseError(500, $db->getErrorMsg());

					}

				}

			}

		   

			$groupings = array_unique($groupings);

			foreach ($groupings as $group){

				$row->reorder('catid = '.(int)$group);

			}

			$cache = JFactory::getCache('com_jequoteproduct');

			$cache->clean();

			return true;

		}

	// ================================= End Ordering Function =======================================================//
	
	
}
	