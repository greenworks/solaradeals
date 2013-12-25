<?php
// no direct access
defined( '_JEXEC' ) or die( ';)' );

jimport( 'joomla.application.component.model' );

class scmetaModelsectionmeta extends JModel
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
		
        $sec_filter_order     = $mainframe->getUserStateFromRequest(  $option.'sec_filter_order', 'sec_filter_order', 's.id', 'cmd' );
        $sec_filter_order_Dir = $mainframe->getUserStateFromRequest( $option.'sec_filter_order_Dir', 'sec_filter_order_Dir', 'asc', 'word' );
 
        $this->setState('sec_filter_order', $sec_filter_order);
        $this->setState('sec_filter_order_Dir', $sec_filter_order_Dir);		
		
	}

	
	// Method to set the scmeta identifier 
	function setId($id)
	{ 
		// Set id and wipe data
		$this->_id		= $id;
		$this->_data	= null;		
	}


	function &getSectionMetaData()
	{
		// Load the data
		if (empty( $sectioncmeta )) {
			$query = ' SELECT s.title, sm.* FROM #__scmeta_section AS sm, #__sections AS s '.
					'  WHERE s.id = sm.section_id AND sm.section_id = '.$this->_id;
			$this->_db->setQuery( $query );
			$sectioncmeta = $this->_db->loadObject();			

		}
		if (!$sectioncmeta) {
			$sectioncmeta = new stdClass();
			$sectioncmeta->id = 0;
			$sectioncmeta->section_id = null;
			$sectioncmeta->section_meta_keywords = null;
			$sectioncmeta->section_meta_desc = null;					
		}
		
		return $sectioncmeta;
		
	}
	
	function getSectionDetails() 
	{
		$db = JFactory::getDBO();
		
		$query = "SELECT title FROM #__sections WHERE id = $this->_id";
		$db->setQuery($query);
		$sectionname = $db->loadResult();
		
		return $sectionname;
	}


	function store()
	{
		$row =& $this->getTable();

		$data = JRequest::get( 'post' );

		// prepare to data saving
		$row->id = $data['id'];
		$row->section_id = $data['section_id'];
		$row->section_meta_keywords = $data['section_meta_keywords'];
		$row->section_meta_desc = $data['section_meta_desc'];		
		
		
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
		$orderby = $this->_buildContentOrderBy();
		$query = "SELECT s.id AS secid, s.title, m.* FROM #__sections as s 
				  LEFT JOIN #__scmeta_section as m 
				  ON s.id = m.section_id WHERE s.scope = 'content' $orderby";	
		
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


	function _buildContentOrderBy()
	{
	    global $mainframe, $option;
 
        $orderby = '';
        $sec_filter_order     = $this->getState('sec_filter_order');
        $sec_filter_order_Dir = $this->getState('sec_filter_order_Dir');

        /* Error handling is never a bad thing*/
        if(!empty($sec_filter_order) && !empty($sec_filter_order_Dir) ){
                $orderby = ' ORDER BY '.$sec_filter_order.' '.$sec_filter_order_Dir;
        }

        return $orderby;
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
