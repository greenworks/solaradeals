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
JTable::addIncludePath(JPATH_ADMINISTRATOR.DS.'components'.DS.'com_enmasse'.DS.'tables');
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
class EnmasseControllerSalesPerson extends JController
{		
	function dealShow()
	{
		$this->checkAccess();
		
		JRequest::setVar('view', 'salesperson');
		JRequest::setVar('task', 'dealShow');
		parent::display();
	}
	
	function dealEdit()
	{
		$this->checkAccess();
		
		JRequest::setVar('view', 'salesperson');
		JRequest::setVar('task', 'dealEdit');
		parent::display();
	}
	
	function dealAdd()
	{
		$this->checkAccess();
		
		JRequest::setVar('view', 'salesperson');
		JRequest::setVar('task', 'dealEdit');
		parent::display();
	}

	function dealSave()
	{
		$this->checkAccess();
		
		$data = JRequest::get( 'post' );
		
		$data['slug_name'] 		= EnmasseHelper::seoUrl($data['name']);
		$data['description'] 	= JRequest::getVar( 'description', '', 'post', 'string', JREQUEST_ALLOWRAW ); 
		$data['highlight'] 		= JRequest::getVar( 'highlight', '', 'post', 'string', JREQUEST_ALLOWRAW ); 
		$data['terms'] 			= JRequest::getVar( 'terms', '', 'post', 'string', JREQUEST_ALLOWRAW ); 
	    // if is edit
		if($data['id']!=0)
		{
			$deal = JModel::getInstance('deal','enmasseModel')->getById($data['id']);
			if($data['max_coupon_qty'] > 0 && $data['max_coupon_qty'] < $deal->max_coupon_qty )
			{
				if( $data['max_coupon_qty'] <= $deal->cur_sold_qty  )
				{
					$msg = JText::_('MSG_CURRENT_SOLD_GRATER_THAN_MODIFIED_COUPON');
					JFactory::getApplication()->redirect('index.php?option=com_enmasse&controller=deal&task=edit&cid='.$data['id'],$msg);
				}
				else
				{
					$removeCoupons = $deal->max_coupon_qty -  $data['max_coupon_qty'];
				}
			}
			else if($data['max_coupon_qty'] > 0 && $data['max_coupon_qty'] > $deal->max_coupon_qty)
			{
				if($deal->max_coupon_qty < 0)
					$addCoupons = $data['max_coupon_qty'];
				else
					$addCoupons = $data['max_coupon_qty'] - $deal->max_coupon_qty;
			}
			else if($data['max_coupon_qty'] < 0 && $deal->max_coupon_qty > 0)
			{
				$unlimit = true;
			}
		}
		
		if($data['id']=="")
			$data['status'] 			= "Pending";
		$data['sales_person_id'] 	= JFactory::getSession()->get('salesPersonId');
        $row = JModel::getInstance('deal','enmasseModel')->store($data);
		if ($row->success) 
		{
			
			// add location and category
			JModel::getInstance('dealCategory','enmasseModel')->store($row->id,$data['pdt_cat_id']);
			JModel::getInstance('dealLocation','enmasseModel')->store($row->id,$data['location_id']);
		// if is not edit and limited the coupon
			if($data['id']==0 && $row->max_coupon_qty > 0)
			{
				for($i=0 ; $i< $row->max_coupon_qty;$i++)
				{
					$name = $i+1;
					JModel::getInstance('invty','enmasseModel')->generateCouponFreeStatus($row->id,$name,'Free');
				}
			}
			else if($data['id']!=0)
			{
				if(!empty($removeCoupons))
				{
					$freeCoupon = JModel::getInstance('invty','enmasseModel')->getCouponFreeByPdtID($data['id']);
					for($i=0; $i < $removeCoupons ; $i++)
					{
						JModel::getInstance('invty','enmasseModel')->removeById($freeCoupon[$i]->id);
					}
				}
				else if(!empty($addCoupons))
				{
					for($i=0; $i < $addCoupons ; $i++)
					{
						$name = $i+1;
						JModel::getInstance('invty','enmasseModel')->generateCouponFreeStatus($data['id'],$name,'Free');
					}
				}
				else if($unlimit)
				{
					JModel::getInstance('invty','enmasseModel')->removeCouponByPdtIdAndStatus($data['id'],'Free');
				}
				
			}
			$msg = JText::_( 'DEAL_PENDING_MSG' );
		}
		else
			$msg = JText::_( 'DEAL_SAVE_ERROR' );

		// Check the table in so it can be edited.... we are done with it anyway
		$link = 'index.php?option=com_enmasse&controller=salesPerson&task=dealShow';
		$this->setRedirect($link, $msg);
	}
	
//---------------------------------------------
	
	private function checkAccess()
	{
	    if (JFactory::getUser()->get('guest'))
		{			
			$msg = JText::_( "SALE_PLEASE_LOGIN_BEFORE");
			$redirectUrl = base64_encode("index.php?option=com_enmasse&controller=salesPerson&task=dealShow");  
		    $version = new JVersion;
            $joomla = $version->getShortVersion();
            if(substr($joomla,0,3) == '1.6'){
                $link = JRoute::_("index.php/login-page", false);
            }else{
                $link = JRoute::_("index.php?option=com_user&view=login&return=".$redirectUrl, false);    
            }
			JFactory::getApplication()->redirect($link, $msg);
		}
		
		$salesPersonId = JFactory::getSession()->get('salesPersonId');

		if($salesPersonId == null)
		{
			$salesPerson = JModel::getInstance('salesPerson','enmasseModel')->getByUserName(JFactory::getUser()->get('username'));
			if ($salesPerson != null)
				JFactory::getSession()->set('salesPersonId', $salesPerson->id);
			else
			{
	         	$msg = JText::_('SALE_NO_ACCESS');
				$link = JRoute::_("/", false);
				JFactory::getApplication()->redirect($link, $msg);			
			}
		}
		return $salesPersonId;
	}
	
	
}
?>
