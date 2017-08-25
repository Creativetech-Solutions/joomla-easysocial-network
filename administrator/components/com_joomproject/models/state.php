<?php
 /**
* @package   Dewalo
* @copyright Copyright (C) 2009 - 2010 Open Source Matters. All rights reserved.
* @license   http://www.gnu.org/licenses/lgpl.html GNU/LGPL, see LICENSE.php
* Contact to : emailtohardik@gmail.com, joomextensions@gmail.com
* Visit : http://www.joomlaextensions.co.in/
**/ 

defined( '_JEXEC' ) or die( 'Restricted access' );
jimport('joomla.application.component.model');

class stateModelstate extends JModelLegacy
{
	var $_data = null;
	var $_total = null;
	var $_pagination = null;
	var $_table_prefix = null;
	
	function __construct()
	{
		parent::__construct();
		$mainframe = JFactory::getApplication();
		$context='state';
	  	$this->_table_prefix = '#__joomproject_';			
		$limit			= $mainframe->getUserStateFromRequest( $context.'limit', 'limit', $mainframe->getCfg('list_limit'), 0);
		$limitstart = $mainframe->getUserStateFromRequest( $context.'limitstart', 'limitstart', 0 );
		
		$state     = $mainframe->getUserStateFromRequest( $context.'state','state',0);
		$filter     = $mainframe->getUserStateFromRequest( $context.'filter','filter',0);
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
		$search_word  = JRequest::getVar('search_word','','request','string');
		$where = "";
		if($search_word!=''){
			$where .= " AND (state_name LIKE '%".$search_word."%')";
		}    
		
		$orderby	= $this->_buildContentOrderBy();
		 $query = ' SELECT * '.'FROM '.$this->_table_prefix.'state WHERE 1=1 '.$where.$orderby;
		return $query;
	}
	
	function _buildContentOrderBy()
	{
		$mainframe = JFactory::getApplication();
		$context = "state";
	
		
		$filter_order     = $mainframe->getUserStateFromRequest( $context.'filter_order', 'filter_order', 'ordering' ); 
		$filter_order_Dir = $mainframe->getUserStateFromRequest( $context.'filter_order_Dir', 'filter_order_Dir', '' );		
	    $orderby 	= ' ORDER BY '.$filter_order.' '.$filter_order_Dir;	
		return $orderby;
	}

}
?>