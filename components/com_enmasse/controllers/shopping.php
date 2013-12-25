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

class EnmasseControllerShopping extends JController
{
	function __construct()
	{
		parent::__construct();
	}

	function display() {
	}

	// Shopping Cart
	function viewCart()
	{
		JRequest::setVar('view', 'shopcart');
		parent::display();
	}

	function addToCart()
	{
		$dealId = JRequest::getVar('dealId');
		$deal = JModel::getInstance('deal','enmasseModel')->getById($dealId);
        

		//*************************************************************************************
		// check Deal
		$now = DatetimeWrapper::getDatetimeOfNow();
		if ($now < $deal->start_at)
		{
			$msg = JText::_( "DEAL_NOT_READY");
			$link = JRoute::_("index.php?option=com_enmasse&controller=deal&task=view&id=".$deal->id, false);
			JFactory::getApplication()->redirect($link, $msg);
		}
		elseif ($now > $deal->end_at)
		{
			$msg = JText::_( "DEAL_END_MSG");
			$link = JRoute::_("index.php?option=com_enmasse&controller=deal&task=view&id=".$deal->id, false);
			JFactory::getApplication()->redirect($link, $msg);
		}
		elseif($deal->published == false)
		{
			$msg = JText::_( "DEAL_NOLONGER_PUBLISH");
			$link = JRoute::_("index.php?option=com_enmasse&controller=deal&task=view&id=".$deal->id, false);
			JFactory::getApplication()->redirect($link, $msg);
		}
		elseif($deal->status == "Voided")
		{
			$msg = JText::_( "DEAL_HAVE_BEEN_VOID");
			$link = JRoute::_("index.php?option=com_enmasse&controller=deal&task=view&id=".$deal->id, false);
			JFactory::getApplication()->redirect($link, $msg);
		}
		else
		{
			// add to cart
			/*
			 $cart = unserialize(JFactory::getSession()->get('cart'));
			 if (empty($cart))
				$cart = new Cart();
				*/
			// We only allow 1 item per cart from now one...
			$cart = new Cart();
			$cart->addItem($deal);
			JFactory::getSession()->set('cart', serialize($cart));

			$dealName = $deal->name;
			$cartItemCount = $cart->getItem($dealId)->getCount();

			$msg = $dealName . " ". JText::_( "DEAL_ADD_TO_CART");
			$link = JRoute::_("index.php?option=com_enmasse&controller=shopping&task=viewCart", false);
			JFactory::getApplication()->redirect($link, $msg);
		}
	}

	function emptyCart()
	{
		$cart = unserialize(JFactory::getSession()->get('cart'));

		if ( !empty($cart) || $cart->getTotalItem() != 0 )
		{
			$cart->deleteAll();
			JFactory::getSession()->set('cart', serialize($cart));
		}

		$msg = JText::_( "EMPTY_CART");
		$link = JRoute::_("index.php?option=com_enmasse&controller=deal&task=listing", false);
		JFactory::getApplication()->redirect($link, $msg);
	}

	function removeItem()
	{
		$itemId = JRequest::getVar('itemId');

		$cart = unserialize(JFactory::getSession()->get('cart'));
		CartHelper::checkCart($cart);
		$cart->deleteItem($itemId);
		JFactory::getSession()->set('cart', serialize($cart));

		$msg = JText::_( "ITEM_REMOVE_MSG");
		$link = JRoute::_("index.php?option=com_enmasse&controller=shopping&task=viewCart", false);
		JFactory::getApplication()->redirect($link, $msg);
	}

	function changeItem()
	{
		$itemId 	= JRequest::getVar('itemId');
		$value 		= JRequest::getVar('value');

		//-----------------------------
		// get max purchase per user allowed and check if the update qty is > or not
		$maxBuyQtyOfDeal = EnmasseHelper::getMaxBuyQtyOfDeal($itemId);
		if($maxBuyQtyOfDeal >= 0 && $value > $maxBuyQtyOfDeal)
		{
			
			$msg = JText::_("ITEM_UPDATE_GREATER_THAN_MAX");
			JFactory::getApplication()->redirect("index.php?option=com_enmasse&controller=shopping&task=viewCart",$msg);
		}

		$cart = unserialize(JFactory::getSession()->get('cart'));
		CartHelper::checkCart($cart);
		$cart->changeItem($itemId, $value);
		JFactory::getSession()->set('cart', serialize($cart));

		$msg = JText::_( "ITEM_UPDATE_MSG");
		$link = JRoute::_("index.php?option=com_enmasse&controller=shopping&task=viewCart", false);
		JFactory::getApplication()->redirect($link, $msg);
	}

	//--------------------------------------------------------------------

	function checkout()
	{
		JRequest::setVar('view', 'shopcheckout');
		if (JFactory::getUser()->get('guest'))
		{
			$msg = JText::_( "<b>Please Login to the system before proceed.</b>");
			$redirectUrl = base64_encode("index.php?option=com_enmasse&controller=shopping&task=checkout");
			$version = new JVersion;
            $joomla = $version->getShortVersion();
            if(substr($joomla,0,3) == '1.6'){
                $link = JRoute::_("index.php/login-page", false);
            }else{
                $link = JRoute::_("index.php?option=com_user&view=login&return=".$redirectUrl, false);    
            }
			JFactory::getApplication()->redirect($link, $msg);
		}
		else
		{
			$cart = unserialize(JFactory::getSession()->get('cart'));
			$orderListObject = JModel::getInstance('order','enmasseModel')->listByBuyerId(JFactory::getUser()->get('id'));
			$boughtQty = EnmasseHelper::getTotalBoughtQtyOfUser($orderListObject,$cart); // to get the bought quantity of this customer
			
		      foreach($cart->getAll() as $cartItem): 
				 $item = $cartItem->getItem();
				 $max_buy_qty = $item->max_buy_qty;
				 $currentBuyQty = $cartItem->getCount();
				 
				 // to check total bought if it is over allowed qty.
				 if($max_buy_qty >=0 && ($boughtQty+$currentBuyQty) > $max_buy_qty)
				 {
					$msg = JText::_("QUANTITY_GREATER_THAN_MAX");
				 	JFactory::getApplication()->redirect("index.php?option=com_enmasse&view=deallisting&Itemid=7",$msg);
				 }
				 //----------------------------
				 // to check if this deal is set with limited coupon to solve out
				 if($item->max_coupon_qty > 0) 
				 {
				 	// get the coupon which is free from inventory
				 	$freeCouponArr = JModel::getInstance('invty','enmasseModel')->getCouponFreeByPdtID($item->id);
				 	
				 	//check if the free coupon enough for this order
				 	if($currentBuyQty > count($freeCouponArr)) 
				 	{
				 	 $msg = JText::_("LIMIT_COUPON_QTY").' '.count($freeCouponArr);
				 	 JFactory::getApplication()->redirect("index.php?option=com_enmasse&controller=shopping&task=viewCart",$msg);
				 	}
				 	
				 }
			  endforeach;	
		    
		    
		}
		parent::display();
	}

	function submitOrderDetail()
	{
		if (JFactory::getUser()->get('guest'))
		{
			$msg = JText::_( "MERCHANT_PLEASE_LOGIN_BEFORE");
			$redirectUrl = base64_encode("index.php?option=com_enmasse&controller=shopping&task=checkout");
			$version = new JVersion;
            $joomla = $version->getShortVersion();
            if(substr($joomla,0,3) == '1.6'){
                $link = JRoute::_("index.php/login-page", false);
            }else{
                $link = JRoute::_("index.php?option=com_user&view=login&return=".$redirectUrl, false);    
            }
			JFactory::getApplication()->redirect($link, $msg);
		}

		// Validating the input
		$setting = JModel::getInstance('setting','enmasseModel')->getSetting();
		
		//------------------------------------------------------
		// to check it this deal is free for customer
       if(JRequest::getVar('isFree') == null || JRequest::getVar('isFree') !='true' || !JRequest::getVar('isFree') )
       {
			if(JRequest::getVar('payGtyId') == null )
			{
				$msg = JText::_( "SELECT_PAYMENT_MSG");
				$link = JRoute::_("index.php?option=com_enmasse&controller=shopping&task=checkout", false);
				JFactory::getApplication()->redirect($link, $msg);
			}
	
	
			if($setting->article_id != 0 && JRequest::getVar('terms')==false)
			{
				$msg = JText::_( "AGREE_TERM_CONDITION_MSG");
				$link = JRoute::_("index.php?option=com_enmasse&controller=shopping&task=checkout", false);
				JFactory::getApplication()->redirect($link, $msg);
			}
	
			$payGtyId 	= JRequest::getVar('payGtyId');
			$name 	= JRequest::getVar('name');
			$email 	= JRequest::getVar('email');
			$msg 	= JRequest::getVar('msg');
	
			$payGty = JModel::getInstance('payGty','enmasseModel')->getById($payGtyId);
	        
			// checking gateway configuration
			if(CartHelper::checkGty($payGty)==false)
			{
				$msg = JText::_( "PAYMENT_INCOMPLETE_MSG");
				$link = JRoute::_("index.php?option=com_enmasse&controller=shopping&task=checkout", false);
				JFactory::getApplication()->redirect($link, $msg);
			}
			// save gty info into session
	
			JFactory::getSession()->set('payGty', serialize($payGty));
			JFactory::getSession()->set('attribute_config', json_encode($payGty->attribute_config));
			$link = JRoute::_("index.php?option=com_enmasse&controller=shopping&task=submitCheckOut&name=$name&email=$email&msg=$msg", false);
       }
       else
       {
       	
	       	$name 	= JRequest::getVar('name');
			$email 	= JRequest::getVar('email');
			$msg 	= JRequest::getVar('msg');
			JFactory::getSession()->set('payGty', 'Free');
			$link = JRoute::_("index.php?option=com_enmasse&controller=shopping&task=submitCheckOut&name=$name&email=$email&msg=$msg&isFree=free", false);
       }
       
       JFactory::getApplication()->redirect($link);
		
		
	}


	function submitCheckOut()
	{
		$cart = unserialize(JFactory::getSession()->get('cart'));
		CartHelper::checkCart($cart);
        foreach($cart->getAll() as $cartItem)
        {
          $item = $cartItem -> getItem();
          
        }
			
			  if(JRequest::getVar('isFree') == null)
               {
               	    $payGty = unserialize(JFactory::getSession()->get('payGty'));
					if ( empty($payGty))
					{
						$msg = JText::_( "CHOOSE_PAYMENT_METHOD_MSG");
						$link = JRoute::_("index.php?option=com_enmasse&controller=shopping&task=checkout", false);
						JFactory::getApplication()->redirect($link, $msg);
					}
					
					//------------------------------------
					// generate name of payment gateway file and class
					$payGtyFile = 'PayGty'.ucfirst($payGty->class_name).'.class.php';
					$className = 'PayGty'.ucfirst($payGty->class_name);
					//---------------------------------------------------
					// get payment gateway object
					require_once (JPATH_SITE . DS ."components". DS ."com_enmasse". DS ."helpers". DS ."payGty". DS .$payGty->class_name. DS.$payGtyFile);
                    $paymentClassObj = new $className();
                    $paymentReturnStatusObj = $paymentClassObj->returnStatus();
                    
                    $status = $paymentReturnStatusObj->order;
                    $couponStatus = $paymentReturnStatusObj->coupon;
               }
              else
              {
              	$payGty = JFactory::getSession()->get('payGty');
              	$status = 'Unpaid';
              	$couponStatus = 'Pending';
              }
	
			$name 	= JRequest::getVar('name', JFactory::getUser()->name);
			$email 	= JRequest::getVar('email', JFactory::getUser()->email);
			$msg 	= JRequest::getVar('msg', '');
	
			if(trim($name)=="")
				$name = JFactory::getUser()->name;
			if(trim($email)=="")
				$email = JFactory::getUser()->email;
	
			$deliveryDetail = array ('name' => $name, 'email' => $email, 'msg' => $msg);
			$deliveryGty 	= JModel::getInstance('deliveryGty','enmasseModel')->getById(1);
			
			//-----------------------------
			// if this deal is set limited the coupon to sold out, go to invty and allocate coupons for this order
			// if not create coupons for that order
			if($item->max_coupon_qty > 0)
			{
				 $now = DatetimeWrapper::getDatetimeOfNow();
				 $nunOfSecondtoAdd = (EnmasseHelper::getMinuteReleaseInvtyFromSetting())*60;
				 $order 			= CartHelper::saveOrder($cart, JFactory::getUser(), $payGty, null, $deliveryGty, $deliveryDetail,$status);
			     $orderItemList 	= CartHelper::saveOrderItem($cart, $order,$status);
			     $intvy             = CartHelper::allocatedInvty($orderItemList,DatetimeWrapper::mkFutureDatetimeSecFromNow($now,$nunOfSecondtoAdd),$couponStatus);
			}
	        else
	        {
				$order 			= CartHelper::saveOrder($cart, JFactory::getUser(), $payGty, null, $deliveryGty, $deliveryDetail,$status);
				$orderItemList 	= CartHelper::saveOrderItem($cart, $order,$status);
				JModel::getInstance('invty','enmasseModel')->generateForOrderItem($orderItemList[0]->pdt_id, $orderItemList[0]->id, $orderItemList[0]->qty, $couponStatus);
	        }
			
	        // --------------------------------
	        // if deal is free then directly do the notify
			 if(JRequest::getVar('isFree') == null)
             {
				$link = JRoute::_("index.php?option=com_enmasse&controller=payment&task=gateway&orderId=" . $order->id, false);
             	JFactory::getApplication()->redirect($link);
             }
             else
             {
             	$link = JRoute::_("index.php?option=com_enmasse&controller=payment&task=doNotify&orderId=$order->id", false);
			    JFactory::getApplication()->redirect($link);
             }
			
	}
}
?>