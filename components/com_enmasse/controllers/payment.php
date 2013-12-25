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

jimport('joomla.application.component.controller');
require_once( JPATH_ADMINISTRATOR . DS ."components". DS ."com_enmasse". DS ."helpers". DS ."DatetimeWrapper.class.php");
require_once( JPATH_ADMINISTRATOR . DS ."components". DS ."com_enmasse". DS ."helpers". DS ."EnmasseHelper.class.php");
require_once( JPATH_SITE . DS ."components". DS ."com_enmasse". DS ."helpers". DS ."Cart.class.php");
require_once( JPATH_SITE . DS ."components". DS ."com_enmasse". DS ."helpers". DS ."CartHelper.class.php");
// load language pack
$language =& JFactory::getLanguage();
$base_dir = JPATH_SITE.DS.'components'.DS.'com_enmasse';
$version = new JVersion;
$joomla = $version->getShortVersion();
if(substr($joomla,0,3) == '1.6'){
    $extension = 'com_enmasse16';
}else{
    $extension = 'com_enmasse';
}
if($language->load($extension, $base_dir, $language->getTag(), true) == false)
{
	 $language->load($extension, $base_dir, 'en-GB', true);
}
class EnmasseControllerPayment extends JController
{
	function gateway()
	{
		JRequest::setVar('view', 'paygty');
		parent::display();
	}

	function returnUrl()
	{
		sleep(2);
		$msg = JText::_("PAYMENT_BEING_PROCESS_MSG");
		$link = JRoute::_("index.php?option=com_enmasse&controller=order&view=orderList", false);
		JFactory::getApplication()->redirect($link, $msg);
	}

	function notifyUrl()
	{	
		$orderId 	= JRequest::getVar('orderId');
		$payClass 	= JRequest::getVar('payClass', '');
		
		$className = 'PayGty'.ucfirst($payClass);

		require_once JPATH_SITE . DS ."components". DS ."com_enmasse". DS ."helpers". DS ."payGty". DS .$payClass. DS .$className. ".class.php";

		if ( ! call_user_func_array(array($className, "validateTxn"), array()) )
		{
			echo JTEXT::_("PAYMENT_VALIDATION_FAILED");
			exit(0);
		}
		else
		{
			$payDta = call_user_func_array(array($className, "generatePaymentDetail"), array());	
			$payDetail = json_encode($payDta);
			JModel::getInstance('order','enmasseModel')->updatePayDetail($orderId, $payDetail);
			
			$link = JRoute::_("index.php?option=com_enmasse&controller=payment&task=doNotify&$postData&orderId=$orderId", false);
			JFactory::getApplication()->redirect($link);
		}
	}

	function doNotify()
	{		
		$orderId = JRequest::getVar("orderId", null);
		
		$order = JModel::getInstance('order','enmasseModel')->getById($orderId);
		if($order == null)
		{
			echo JTEXT::_("PAYMENT_ERROR_MSG") . $orderId;
			exit(0);
		}
		else if($order->status=="Unpaid") // Pass checking
		{
			EnmasseHelper::doNotify($orderId);
		}

		$msg = JTEXT::_("PAYMENT_SUCCESS");
//		echo JTEXT::_("PAYMENT_SUCCESS");
//		exit(0);
		$link = JRoute::_("index.php?option=com_enmasse&controller=deal", false);
		JFactory::getApplication()->redirect($link, $msg);
	}

	function cancelUrl()
	{
		$msg = JText::_( "CANCEL_TRANSACTION");
		$link = JRoute::_("index.php?option=com_enmasse&controller=deal", false);
		JFactory::getApplication()->redirect($link, $msg);
	}

}
?>