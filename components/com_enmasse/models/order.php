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

jimport( 'joomla.application.component.model' );
require_once( JPATH_ADMINISTRATOR . DS ."components". DS ."com_enmasse". DS ."helpers". DS ."EnmasseHelper.class.php");

class EnmasseModelOrder extends JModel
{
	//Order id
	function getById($orderId)
	{
		$db =& JFactory::getDBO();
	    $query = "	SELECT 
	    				* 
	    			FROM 
	    				#__enmasse_order 
	    			WHERE
	              		id = $orderId";
	    $db->setQuery( $query );
	    $order = $db->loadObject();

		if ($this->_db->getErrorNum()) {
			JError::raiseError( 500, $this->_db->stderr() );
			return false;
		}
	    
		return $order;
	}
	//user id
	
	function listForBuyer($buyerId)
	{
		$db =& JFactory::getDBO();
	    $query = "	SELECT 
	    				* 
	    			FROM 
	    				#__enmasse_order 
	    			WHERE
	    				status !='Unpaid' AND
	              		buyer_id = $buyerId
	              	ORDER BY
	              		created_at DESC";
	    $db->setQuery( $query );
	    $orderList = $db->loadObjectList();
		if ($this->_db->getErrorNum()) {
			JError::raiseError( 500, $this->_db->stderr() );
			return false;
		}
		
		return $orderList;
	}
	
	function updateStatus($id, $value)
	{
		$db =& JFactory::getDBO();
		$query = 'UPDATE #__enmasse_order SET status ="'.$value.'", updated_at = "'. DatetimeWrapper::getDatetimeOfNow() .'" where id ='.$id;
		$db->setQuery( $query );
		$db-> query();
		
		if ($this->_db->getErrorNum()) {
			JError::raiseError( 500, $this->_db->stderr() );
			return false;
		}
		
		return true;
	}
	
	function updatePayDetail($id, $payDetail)
	{
		$db =& JFactory::getDBO();
		$query = "UPDATE #__enmasse_order SET pay_detail ='".$payDetail."', updated_at = '". DatetimeWrapper::getDatetimeOfNow() ."' where id =".$id;
		$db->setQuery( $query );
		$db->query();
		
		if ($this->_db->getErrorNum()) {
			JError::raiseError( 500, $this->_db->stderr() );
			return false;
		}
		return true;
	}
    function listByBuyerId($buyerId)
	{
		$db =& JFactory::getDBO();
	    $query = "	SELECT 
	    				* 
	    			FROM 
	    				#__enmasse_order 
	    			WHERE
	              		buyer_id = $buyerId";
	    $db->setQuery( $query );
	    $orderList = $db->loadObjectList();
		if ($this->_db->getErrorNum()) {
			JError::raiseError( 500, $this->_db->stderr() );
			return false;
		}
		
		return $orderList;
	}
}
?>