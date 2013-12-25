<?php
/*------------------------------------------------------------------------
# En Masse - Social Buying Extension 2010
# ------------------------------------------------------------------------
# By Matamko.com
# Copyright (C) 2010 Matamko.com. All Rights Reserved.
# @license - http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
# Websites: http://www.matamko.com
# Technical Support:  Visit our forum at www.matamko.com
-------------------------------------------------------------------------*/

defined( '_JEXEC' ) or die( 'Restricted access' );
require_once( JPATH_ADMINISTRATOR . DS ."components". DS ."com_enmasse". DS ."helpers". DS ."DatetimeWrapper.class.php");
jimport( 'joomla.application.component.model' );

class EnmasseModelDeal extends JModel
{
	function todayDeal()
	{
		// deal
		$db =& JFactory::getDBO();
		$query = "	SELECT
						* 
					FROM 
						#__enmasse_deal 
					WHERE
	              		published = '1' AND 
	              		end_at > '". DatetimeWrapper::getDatetimeOfNow() . "'
	              	ORDER BY
	              		position Asc
	              	LIMIT
	              		1
	              ";
		$db->setQuery( $query );
		$deal = $db->loadObject();
		
		if ($this->_db->getErrorNum()) {
			JError::raiseError( 500, $this->_db->stderr() );
			return false;
		}
		return $deal;
	}

	function viewDeal($id)
	{

		$deal =& JTable::getInstance('deal', 'Table');
		$deal->load($id);

		if ($this->_db->getErrorNum()) {
			JError::raiseError( 500, $this->_db->stderr() );
			return false;
		}
		
		return $deal;
	}

	function searchStartedPublishedDeal($keyword=null, $categoryId=null, $locationId=null, $sortBy=null)
	{
		$db =& JFactory::getDBO();
		// to create string of deal id base on category and location
	   	 $ids = '';
			if(count($locationId)!=0 && count($categoryId)!=0)
			{
				for($i=0; $i < count($locationId); $i++)
				{
					for($x=0; $x< count($categoryId); $x++)
					{
						if($locationId[$i] == $categoryId[$x])
						 $idArr[] = $locationId[$i];
					}
				}
				
				if(count($idArr)!=0)
				 $ids .=implode(",",$idArr);
				else
				  $ids.='0';
			}
			else if ( count($locationId)!=0)
				$ids .=implode(",",$locationId);
			else if( count($categoryId)!=0)
				$ids .=implode(",",$categoryId);
				
	    // generate the query
		$query = "	SELECT 
						* 
					FROM 
						#__enmasse_deal 
					WHERE
					    status NOT LIKE 'Pending' AND
		          		published = '1' AND
						start_at <='".DatetimeWrapper::getDatetimeOfNow()."' 
		          		AND end_at >= '".DatetimeWrapper::getDatetimeOfNow()."' ";
		if($keyword != null)
	 		$query .= "	AND name like '%$keyword%'";
		if($ids!='')
		  $query .=" AND id IN (".$ids.") ";
	 	if($sortBy != null)
			$query .= "	ORDER BY $sortBy";
			
		$db->setQuery( $query );
		$rows = $db->loadObjectList();
		
		if ($this->_db->getErrorNum()) {
			JError::raiseError( 500, $this->_db->stderr() );
			return false;
		}
		
		return $rows;
	}
	function getDealMaxSoldQtyFromLocation($ids)
	{
	  $db =& JFactory::getDBO();
	  $query .= "SELECT 
						id, Max(cur_sold_qty)
					FROM 
						#__enmasse_deal 
					WHERE
		          		published = '1' AND
						start_at <='".DatetimeWrapper::getDatetimeOfNow()."' 
		          		AND end_at >= '".DatetimeWrapper::getDatetimeOfNow()."' 
		          		AND id IN (".implode(',',$ids).")";
	  
	  $db->setQuery( $query );
	  return  $db->loadObject();
	}
	
    function searchExpiredPublishedDeal($keyword=null, $categoryId=null, $locationId=null, $sortBy=null)
	{
		$db =& JFactory::getDBO();
		$query = "	SELECT 
						* 
					FROM 
						#__enmasse_deal 
					WHERE
		          		published = '1' AND
						end_at < '".DatetimeWrapper::getDatetimeOfNow()."' 
		          		";
		if($keyword != null)
	 		$query .= "	AND name like '%$keyword%'";
		if($locationId != null)
			$query .= " AND location_id = $locationId";
		if($categoryId != null)
			$query .= " AND pdt_cat_id = $categoryId";
	 	if($sortBy != null)
			$query .= "	ORDER BY $sortBy";
			
		$db->setQuery( $query );
		$rows = $db->loadObjectList();
		
		if ($this->_db->getErrorNum()) {
			JError::raiseError( 500, $this->_db->stderr() );
			return false;
		}
		
		return $rows;
	}

	function upcomingDeal()
	{
		$db =& JFactory::getDBO();
		$query = "SELECT * FROM #__enmasse_deal WHERE
		          published = '1'";
		$query.= " AND start_at > '".DatetimeWrapper::getDatetimeOfNow()."'";


		$db->setQuery( $query );
		$rows = $db->loadObjectList();
		
		if ($this->_db->getErrorNum()) {
			JError::raiseError( 500, $this->_db->stderr() );
			return false;
		}
		return $rows;
	}

	function getById($id)
	{
		$db =& JFactory::getDBO();
		$query = "	SELECT
						* 
					FROM 
						#__enmasse_deal 
					WHERE
						id = $id";

		$db->setQuery( $query );
		$deal = $db->loadObject();
		if ($db->getErrorNum())
		{
			echo $db->stderr();
			return false;
		}
		return $deal;
	}

	function listConfirmedByMerchantId($merchantId)
	{
		$db =& JFactory::getDBO();
		$query = "	SELECT
						*
					FROM 
						#__enmasse_deal 
					WHERE
						status = 'Confirmed' AND 
						merchant_id = $merchantId";

		$db->setQuery( $query );
		$dealList = $db->loadObjectList();
		if ($this->_db->getErrorNum()) {
			JError::raiseError( 500, $this->_db->stderr() );
			return false;
		}
		return $dealList;
	}

	function searchBySalesPerson($salesPersonId, $keyword, $published, $status)
	{
		$db =& JFactory::getDBO();
		$query = "	SELECT
						*
					FROM 
						#__enmasse_deal 
					WHERE
						sales_person_id = $salesPersonId";
		if($keyword != null)
	 		$query .= "	AND name like '%$keyword%'";
		if($published != null)
	 		$query .= "	AND published = $published";
		if($status != null)
	 		$query .= "	AND status like '$status'";
	 		

		$db->setQuery( $query );
		$dealList = $db->loadObjectList();
		if ($this->_db->getErrorNum()) {
			JError::raiseError( 500, $this->_db->stderr() );
			return false;
		}
		
		return $dealList;
	}
	
	function addQtySold($id, $qty)
	{
		$db =& JFactory::getDBO();
		
		$query = "	UPDATE 
						#__enmasse_deal 
					SET 
						cur_sold_qty = cur_sold_qty + $qty,
						updated_at = '".DatetimeWrapper::getDatetimeOfNow()."'
	                WHERE 
	                	id = '$id'";
		$db->setQuery( $query );
		$db->query();
		if ($this->_db->getErrorNum()) {
			JError::raiseError( 500, $this->_db->stderr() );
			return false;
		}
		return true;
	}
	
	function store($data)
	{
		$row =& $this->getTable();

		if (!$row->bind($data)) {
			$this->setError($this->_db->getErrorMsg());
			return false;
		}

		if ( ! $row->position )
		{
			$db =& JFactory::getDBO();
			$query = "SELECT * FROM #__enmasse_deal";
			$db->setQuery( $query );
			$rows = $db->loadObjectList();
			if ($db->getErrorNum()) {
				echo $db->stderr();
				return false;
			}
			$row->position = count($rows) + 1;
		}

		if($row->id <= 0)
			$row->created_at = DatetimeWrapper::getDatetimeOfNow();
		$row->updated_at = DatetimeWrapper::getDatetimeOfNow();
				

		if (!$row->check()) {
			$row->success = false;
			$this->setError($this->_db->getErrorMsg());
			return $row;
		}

		if (!$row->store()) {
			$row->success = false;
			$this->setError($this->_db->getErrorMsg());
			return $row;
		}
		$row->success = true;
		return $row;
	}
	
}
