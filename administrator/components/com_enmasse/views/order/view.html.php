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
jimport( 'joomla.application.component.view');
require_once( JPATH_ADMINISTRATOR . DS ."components". DS ."com_enmasse". DS ."toolbar.enmasse.html.php");

class EnmasseViewOrder extends JView
{
	function display($tpl = null)
	{
			
		$task = JRequest::getWord('task');

		if($task == 'edit') // display order with its item
		{
			TOOLBAR_enmasse::_ORDERNEW();
			$orderId = JRequest::getVar('orderId');

			$modelOrder 	= JModel::getInstance('order','enmasseModel');
			$modelOrderItem = JModel::getInstance('orderItem','enmasseModel');
            $modelInvty = JModel::getInstance('invty','enmasseModel');
			
			$order 				= JModel::getInstance('order','enmasseModel')->getById($orderId);
			$orderItemList		= JModel::getInstance('orderItem','enmasseModel')->listByOrderId($orderId);
			
			$order->orderItem 	= $orderItemList[0];
			$order->orderItem->invtyList = $modelInvty->listByOrderItemId($order->orderItem->id);
			
			$this->assignRef( 'statusList', EnmasseHelper::$ORDER_STATUS_LIST);
			$this->assignRef( 'order', $order );
		}
		else // display list of orders
		{
			TOOLBAR_enmasse::_SMENU();
			TOOLBAR_enmasse::_ORDER();
			
			$filter 	= JRequest::getVar('filter');
            
			if($filter['status']=="")
				$filter['status'] = "Paid";
			
			// Weird that only this will caused warning...
			if(!isset($filter['deal_name']))
				$filter['deal_name'] = "";
				
			$orderList 	= JModel::getInstance('order','enmasseModel')->search($filter['status'], $filter['deal_name'], "created_at", "DESC");
		    $pagination =JModel::getInstance('order','enmasseModel')->getPagination();
			$this->assignRef('statusList', EnmasseHelper::$ORDER_STATUS_LIST);
			$this->assignRef('filter', $filter);
			$this->assignRef('orderList', $orderList );
			$this->assignRef('pagination', $pagination);
		}
		parent::display($tpl);
	}

}
?>