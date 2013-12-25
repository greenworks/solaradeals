<?php
// no direct access
defined( '_JEXEC' ) or die( ';)' );

jimport( 'joomla.application.component.model' );


class sjmetaModelmenumeta extends JModel
{	
	var $_data;
	var $_total = null;
	var $_pagination = null;
	
	
	 // Constructor that retrieves the ID from the request  
	function __construct()
	{
		global $mainframe, $option;
		
		parent::__construct();

		$array = JRequest::getVar('cid',  0, '', 'array');
		$this->setId((int)$array[0]);
		
		// Get pagination request variables
		$limit = $mainframe->getUserStateFromRequest('global.list.limit', 'limit', $mainframe->getCfg('list_limit'), 'int');
		$limitstart = $mainframe->getUserStateFromRequest($option.'.limitstart', 'limitstart', 0, 'int');
	
		// In case limit has been changed, adjust it
		$limitstart = ($limit != 0 ? (floor($limitstart / $limit) * $limit) : 0);
	
		$this->setState('limit', $limit);
		$this->setState('limitstart', $limitstart);
		
	}

	
	// Method to set the sjmeta identifier 
	function setId($id)
	{ 
		// Set id and wipe data
		$this->_id		= $id;
		$this->_data	= null;
		
	}


	function &getMenuMetaData()
	{
		// Load the data
		if (empty( $menumeta )) {
			$query = ' SELECT s.name as title, sm.* FROM #__sjmeta_menu AS sm, #__menu AS s '.
					'  WHERE s.id = sm.menu_id AND sm.menu_id = '.$this->_id;
			$this->_db->setQuery( $query );
			$menumeta = $this->_db->loadObject();			

		}
		if (!$menumeta) {
			$menumeta = new stdClass();
			$menumeta->id = 0;
			$menumeta->menu_id = null;
			$menumeta->menu_meta_keywords = null;
			$menumeta->menu_meta_desc = null;					
		}
		
		return $menumeta;
		
	}
	
	function getMenuDetails() {
		$db = JFactory::getDBO();
		
		$query = "SELECT name as title FROM #__menu WHERE id = $this->_id";
		$db->setQuery($query);
		$menuname = $db->loadResult();
		
		return $menuname;
	}


	function store()
	{
		$row =& $this->getTable();

		$data = JRequest::get( 'post' );

		// prepare to data saving
		$row->id = $data['id'];
		$row->menu_id = $data['menu_id'];
		$row->menu_meta_keywords = $data['menu_meta_keywords'];
		$row->menu_meta_desc = $data['menu_meta_desc'];		
		
		
		// Store the table to the database
		if (!$row->store()) {
			$this->setError( $row->getErrorMsg() );
			return false;
		}

		return true;
		
	}


	function delete()
	{
		$cids = JRequest::getVar( 'cid', array(0), 'post', 'array' );

		$row =& $this->getTable();

		if (count( $cids ))
		{
			foreach($cids as $cid) {
				if (!$row->delete( $cid )) {
					$this->setError( $row->getErrorMsg() );
					return false;
				}
			}//foreach
		}
		
		return true;		
	}
	
	

	function _buildQuery()
	{

		$query = "SELECT s.id AS secid, s.name as title, s.menutype as menutype, m.* FROM #__menu as s 
				  LEFT JOIN #__sjmeta_menu as m 
				  ON s.id = m.menu_id ORDER BY s.id";	
		
		return $query;
	}


	function getData()
	{
		// Lets load the data if it doesn't already exist
		if (empty( $this->_data ))
		{
			$query = $this->_buildQuery();
			$this->_data = $this->_getList( $query, $this->getState('limitstart'), $this->getState('limit') );
		}
		
		return $this->_data;
		
	}
	
	function getTotal()
	{
		// Load the content if it doesn't already exist
		if (empty($this->_total))
		{
			$query = $this->_buildQuery();
			$this->_total = $this->_getListCount($query);	
		}
		
		return $this->_total;
		
	}

  
	function getPagination()
    {
 		// Load the content if it doesn't already exist
	 	if (empty($this->_pagination))
	 	{
	 	    jimport('joomla.html.pagination');
	 	    $this->_pagination = new JPagination($this->getTotal(), $this->getState('limitstart'), $this->getState('limit') );
	 	}
		
 		return $this->_pagination;
		
    }	


}


