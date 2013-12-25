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
require_once( JPATH_ADMINISTRATOR . DS ."components". DS ."com_enmasse". DS ."helpers". DS ."EnmasseHelper.class.php");
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
class EnmasseControllerMerchant extends JController
{	
	function display()
	{
		$this->checkAccess();
		
		JRequest::setVar('view', 'merchant');
		JRequest::setVar('task', 'dealCouponMgmt');
		parent::display();
	}
	
	function dealCouponMgmt()
	{
		$this->checkAccess();
		
		JRequest::setVar('view', 'merchant');
		JRequest::setVar('task', 'dealCouponMgmt');
		parent::display();
	}
	
	function update()
	{
		$this->checkAccess();
		
		$action = JRequest::getVar('newStatus');
		$coupon = JRequest::getVar('coupon');

		$invty = JModel::getInstance('invty','enmasseModel')->getByName($coupon);
		if($invty == null)
		{
			$msg = JText::_( 'MERCHANT_INVALID_COUPON_SERIAL');
			$this->setRedirect("index.php?option=com_enmasse&controller=merchant&task=dealCouponMgmt", $msg, "error");
		}
		else
		{
			$orderItem = JModel::getInstance('orderItem','enmasseModel')->getById($invty->order_item_id);
			
			if($orderItem->status != "Delivered")
			{
				$msg = JText::_('COUPON_STATUS_ERROR'). "(". $invty->name ." - ". $orderItem->status .")";
				$this->setRedirect("index.php?option=com_enmasse&controller=merchant&task=dealCouponMgmt&filter[deal_id]=$orderItem->pdt_id", $msg, 'error');
			
			}
			elseif($action=="Used")
			{
				$invty = JModel::getInstance('invty','enmasseModel')->getByName($coupon);
				if($invty->status=="Used")
				{
					$msg = JText::_( 'COUPON_ALREADY_IN_USE'). '('.$invty->name.")";
					$this->setRedirect("index.php?option=com_enmasse&controller=merchant&task=dealCouponMgmt&filter[deal_id]=$orderItem->pdt_id", $msg, 'error');
				}		
				else
				{
					JModel::getInstance('invty','enmasseModel')->updateStatusByName($coupon,"Used");
					$msg = JText::_( 'COUPON_STATUS_UPDATE'). '('.$invty->name.")";
					$this->setRedirect("index.php?option=com_enmasse&controller=merchant&task=dealCouponMgmt&filter[deal_id]=$orderItem->pdt_id", $msg);
				}
			}
			else
			{
				JModel::getInstance('invty','enmasseModel')->updateStatusByName($coupon,"Taken");
				$msg = JText::_( 'COUPON_STATUS_UPDATE').'('.$invty->name.")";
				$this->setRedirect("index.php?option=com_enmasse&controller=merchant&task=dealCouponMgmt&filter[deal_id]=$orderItem->pdt_id", $msg);
					
			}
		}
	}
	
//---------------------------------------------
	
	private function checkAccess()
	{
		if (JFactory::getUser()->get('guest'))
		{			
			$msg = JText::_( "MERCHANT_PLEASE_LOGIN_BEFORE");
			$redirectUrl = base64_encode("index.php?option=com_enmasse&controller=merchant&task=dealCouponMgmt");  
			$version = new JVersion;
            $joomla = $version->getShortVersion();
            if(substr($joomla,0,3) == '1.6'){
                $link = JRoute::_("index.php/login-page", false);
            }else{
                $link = JRoute::_("index.php?option=com_user&view=login&return=".$redirectUrl, false);    
            }
			JFactory::getApplication()->redirect($link, $msg);
		}
		
		$merchantId = JFactory::getSession()->get('merchantId');
		if($merchantId == null)
		{
			$merchant = JModel::getInstance('merchant','enmasseModel')->getByUserName(JFactory::getUser()->get('username'));
			if ($merchant != null)
				JFactory::getSession()->set('merchantId', $merchant->id);
			else
			{
	         	$msg = JText::_('MERCHANT_HAS_NO_ACCESS');
				JFactory::getApplication()->redirect("/", $msg);	
			}
		}
		return $merchantId;
	}
}
?>
