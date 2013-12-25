<?php
// no direct access
defined( '_JEXEC' ) or die( ';)' );

jimport( 'joomla.application.component.model' );

class scmetaModelcategorymeta extends JModel
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

        $cat_filter_order     = $mainframe->getUserStateFromRequest($option.'cat_filter_order', 'cat_filter_order', 'cat.id', 'cmd' );
        $cat_filter_order_Dir = $mainframe->getUserStateFromRequest($option.'cat_filter_order_Dir', 'cat_filter_order_Dir', 'asc', 'word' );
 
        $this->setState('cat_filter_order', $cat_filter_order);
        $this->setState('cat_filter_order_Dir', $cat_filter_order_Dir);		
	}

	
	// Method to set the scmeta identifier 
	function setId($id)
	{ 
		// Set id and wipe data
		$this->_id		= $id;
		$this->_data	= null;
		
	}


	function &getCategoryMetaData()
	{
		// Load the data
		if (empty( $categorymeta )) {
			
			$query = ' SELECT cm.*, c.title FROM #__scmeta_category AS cm, #__categories AS c '.
						'  WHERE c.id = cm.category_id AND cm.category_id = '.$this->_id;
					
			$this->_db->setQuery( $query );
			$categorymeta = $this->_db->loadObject();			

		}
		if (!$categorymeta) {
			$categorymeta = new stdClass();
			$categorymeta->id = 0;
			$categorymeta->category_id = null;
			$categorymeta->category_meta_keywords = null;
			$categorymeta->category_meta_desc = null;					
		}
		
		return $categorymeta;
		
	}
	
	function getCategoryDetails() {
		$db = JFactory::getDBO();
		
		$query = "SELECT title FROM #__categories WHERE id = $this->_id";
		$db->setQuery($query);
		$categoryname = $db->loadResult();
		
		return $categoryname;
	}


	function store()
	{
		$row =& $this->getTable();

		$data = JRequest::get( 'post' );

		// prepare to data saving
		$row->id = $data['id'];
		$row->category_id = $data['category_id'];
		$row->category_meta_keywords = $data['category_meta_keywords'];
		$row->category_meta_desc = $data['category_meta_desc'];		
		
		
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
		$query = "SELECT cat.id AS catid, cat.title, m.*,s.title AS section_title FROM #__sections AS s, #__categories as cat 
				  LEFT JOIN #__scmeta_category as m 
				  ON cat.id = m.category_id 
				  WHERE cat.section > 0 AND cat.section = s.id $orderby ";	
		
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
        $cat_filter_order     = $this->getState('cat_filter_order');
        $cat_filter_order_Dir = $this->getState('cat_filter_order_Dir');

        /* Error handling is never a bad thing*/
        if(!empty($cat_filter_order) && !empty($cat_filter_order_Dir) ){
                $orderby = ' ORDER BY '.$cat_filter_order.' '.$cat_filter_order_Dir;
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


