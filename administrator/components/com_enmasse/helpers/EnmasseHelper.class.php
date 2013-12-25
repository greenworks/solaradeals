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

require_once(dirname(__FILE__).DS.'DatetimeWrapper.class.php');

class EnmasseHelper
{
	//-------------------------
	// CONSTANT Values
	public static $DEAL_STATUS_LIST = array(
							"Pending"=>"Pending",
							"On Sales"=>"On Sales",
							"Confirmed"=>"Confirmed", 
							"Voided"=>"Voided"
							);

							public static $ORDER_STATUS_LIST = array(
							"Pending"=>"Pending",
							"Unpaid"=>"Unpaid", 
							"Paid"=>"Paid", 
							"Delivered"=>"Delivered", 
							"Refunded"=>"Refunded"
							);

							public static $ORDER_ITEM_STATUS_LIST = array(
							"Unpaid"=>"Unpaid", 
							"Paid"=>"Paid", 
							"Delivered"=>"Delivered", 
							"Refunded"=>"Refunded"
							);

							public static $INVTY_STATUS_LIST = array(
							"Hold"=>"Hold", 
							"Taken"=>"Taken", 
							"Used"=>"Used"
							);

			//-------------------------
			// Theme
			function themeList()
			{
				$list = scandir(JPATH_SITE.DS.'components'.DS.'com_enmasse'.DS.'theme',0);
				//		return array ('default', 'extended');
				$returnList = array();
				$count = 0;
				for($i=0; $i<count($list);$i++)
				{
					if ($list[$i]==".."||$list[$i]==".")
					{

					}
					else
					{
						$returnList[$count] = $list[$i];
						$count+=1;
					}
				}
				return $returnList;
			}
			function getThemeFromSetting()
			{
				$db =& JFactory::getDBO();
				$query = "SELECT theme FROM #__enmasse_setting limit 1";
				$db->setQuery( $query );
				$setting = $db->loadObject();
				return $setting->theme;
			}
			function getLocationPopUpActiveFromSetting()
			{
				$db =& JFactory::getDBO();
				$query = "SELECT active_popup_location FROM #__enmasse_setting limit 1";
				$db->setQuery( $query );
				$setting = $db->loadObject();
				return $setting->active_popup_location;
			}
			function getMinuteReleaseInvtyFromSetting()
			{
				$db =& JFactory::getDBO();
				$query = "SELECT minute_release_invty FROM #__enmasse_setting limit 1";
				$db->setQuery( $query );
				$setting = $db->loadObject();
				return $setting->minute_release_invty;
			}
			//-------------------------
			// Token

			function generateOrderItemToken($orderItemId, $orderItemCreatedAt)
			{
				return md5($orderItemId. $orderItemCreatedAt . $orderItemId);
			}

			function generateCouponToken($name)
			{
				return md5($name);
			}

			//-------------------------
			// Display Control

			function displayOrderDisplayId($id)
			{
				return str_pad($id, 10, '0', STR_PAD_LEFT);
			}

			function displayCurrency($num)
			{
				$var = floatval($num);
				$db =& JFactory::getDBO();
				$query = "SELECT * FROM #__enmasse_setting limit 1";
				$db->setQuery( $query );
				$setting = $db->loadObject();

				$dec_point = '.';

				$thousand_sep = ',';
				if ($setting->currency_separator!="")
					$thousand_sep = $setting->currency_separator;
					
				if ($setting->currency_decimal_separator!="")
					$dec_point = $setting->currency_decimal_separator;

				$dec_num = 2;
				if ($setting->currency_decimal!="")
					$dec_num = intval($setting->currency_decimal);

				$var = number_format($var, $dec_num, $dec_point, $thousand_sep);

				$prex = '$';
				$post = '';
				if ($setting->currency_prefix )
					$prex = $setting->currency_prefix;
				if ($setting->currency_postfix )
					$post = $setting->currency_postfix ;
				return $prex . '' . $var.' '.$post;

			}

			function displayJson($string)
			{
				$arr = json_decode($string);
				if ($arr)
				{
					$result = '<table>';
					foreach ($arr as $key=>$value)
					{
						$result .= '<tr><td>';
						$result .= '<div align="left"><b>' . ucWords($key) . '</b> : ' . $value . '</div>';
						$result .= '</td></tr>';
					}
					$result .= '</table>';
					return $result;
				}
				return '';
			}

			function displayBuyer($buyer)
			{
				if ( $buyer )
				{
					$result = $buyer->name . '<br/>(' . $buyer->email . ')';
					return $result;
				}
				return '';
			}


			//-------------------------
			// System

			function sendMailByTemplate($mailto, $templateName, $params=array())
			{
				$app= &JFactory::getApplication();

				// get Template
				$db =& JFactory::getDBO();
				$query = "SELECT * FROM #__enmasse_email_template WHERE ";
				$query .= "slug_name='$templateName'";
				$db->setQuery( $query );

				$templateObj = $db->loadObject();
				if ( empty($templateObj) )
				{
					$app->enqueueMessage("Template $templateName not found !!",'error');
					return;
				}
				// replace params
				foreach($params as $key=>$value)
				{
					$templateObj->subject = str_replace($key, $value, $templateObj->subject);
					$templateObj->content = str_replace($key, $value, $templateObj->content);
				}

				EnmasseHelper::sendMail($mailto, $templateObj->subject, $templateObj->content);
			}

			function sendMail($mailto, $subject, $body)
			{
				$app	= &JFactory::getApplication();
				$mailer = &JFactory::getMailer();
				$config = &JFactory::getConfig();

				$sender = array(
				$config->getValue( 'config.mailfrom' ),
				$config->getValue( 'config.fromname' )
				);

				$recipient 	= array ($mailto);//$user->email;

				$mailer->setSubject($subject);
				$mailer->isHTML(true);
				$mailer->setBody($body);

				$mailer->addRecipient($recipient);
				$mailer->setSender($sender);
				$send = &$mailer->Send();

				if ( $send !== true ) {
					$app->enqueueMessage('Error Sending email:<br/>'.$send->message,'error');
				}
			}


			function changePublishState( $state=0,$table,$R_action,$jtable )
			{
				global $mainframe;
                
                $version = new JVersion;
                $joomla = $version->getShortVersion();
                if(substr($joomla,0,3) == '1.6'){
            	   $mainframe = JFactory::getApplication();
                }

				// Initialize variables
				$db 	=& JFactory::getDBO();

				// define variable $cid from GET
				$cid = JRequest::getVar( 'cid' , array() , '' , 'array' );
				JArrayHelper::toInteger($cid);

				// Check there is/are item that will be changed.
				//If not, show the error.
				if (count( $cid ) < 1) {
					$action = $state ? 'publish' : 'unpublish';
					JError::raiseError(500, JText::_('NO_ITEM_SELECTED', true ) );
				}

				// Prepare sql statement, if cid more than one,
				// it will be "cid1, cid2, cid3, ..."
				$cids = implode( ',', $cid );

				$query = 	'UPDATE #__'.$table .
				' 	SET published = ' . (int) $state. 
				' 	WHERE id IN ( '. $cids .' )'
				;
				// Execute query
				$db->setQuery( $query );
				if (!$db->query()) {
					JError::raiseError(500, $db->getErrorMsg() );
				}

				if (count( $cid ) == 1) {
					$row =& JTable::getInstance($jtable, 'Table');
					//		    $row->checkin( intval( $cid[0] ) );
				}

				// After all, redirect to front page
				$mainframe->redirect( 'index.php?option=com_enmasse&controller='.$R_action );
		     }


			function seoUrl($string) {
				//Unwanted:  {UPPERCASE} ; / ? : @ & = + $ , . ! ~ * ' ( )
				$string = strtolower($string);
				//Strip any unwanted characters
				$string = preg_replace("/[^a-z0-9_\s-]/", "", $string);
				//Clean multiple dashes or whitespaces
				$string = preg_replace("/[\s-]+/", " ", $string);
				//Convert whitespaces and underscore to dash
				$string = preg_replace("/[\s_]/", "_", $string);
				return $string;
			}

			//-------------------------------
			// Deal
			function orderItemDelivered($orderItemId)
			{
				$orderItem = JModel::getInstance('orderItem','enmasseModel')->getById($orderItemId);
                $deal      = JModel::getInstance('deal','enmasseModel')->getById($orderItem->pdt_id);
                                
                 //-------------------------------------------------------------------------
			    // update sold coupon status to taken
				JModel::getInstance('invty','enmasseModel')->updateStatusByPdtIdAndStatus($orderItem->pdt_id, "Taken", "Sold");
                                 
                //----------------------------------------------------------------------
				// Update Order Item status to delivered
				JModel::getInstance('orderItem','enmasseModel')->updateStatus($orderItem->id, "Delivered");

				//--------------------------------------------------------------------
				// Update Order, check if all order item are in the status first
				$order = JModel::getInstance('order','enmasseModel')->getById($orderItem->order_id);

				$tempOrderItemList = JModel::getInstance('orderItem','enmasseModel')->listByOrderId($orderItem->order_id);
				$checkFlag = true;
				foreach($tempOrderItemList as $tempOrderItem)
				{
					if($tempOrderItem->status != "Delivered")
					{
						$checkFlag = false;
						break;
					}
				}
                                //---------------------------------------------------------
                                // update order status to delivered
					if($checkFlag)
						JModel::getInstance('order','enmasseModel')->updateStatus($order->id, "Delivered");

					//--------------------
					// Email the Buyer
					$buyerDetail 	= json_decode($order->buyer_detail);
					$deliveryDetail	= json_decode($order->delivery_detail);

					$params = array();
					$params['$orderId'] 		= EnmasseHelper::displayOrderDisplayId($order->id);
					$params['$dealName'] 		= $orderItem->description;
					$params['$buyerName'] 		= $buyerDetail->name;
					$params['$deliveryName'] 	= $deliveryDetail->name;
					$params['$deliveryEmail'] 	= $deliveryDetail->email;

					EnmasseHelper::sendMailByTemplate($buyerDetail->email, 'confirm_deal_buyer', $params);

					//---------------------------
					// Email the Receiver

					$token = EnmasseHelper::generateOrderItemToken($orderItem->id, $orderItem->created_at);
					$link = JURI::root().'/index.php?option=com_enmasse&controller=coupon&task=listing&orderItemId='.$orderItem->id.'&token='.$token.'&buffer=';
					$params = array();
					$params['$orderId'] 		= EnmasseHelper::displayOrderDisplayId($order->id);
					$params['$dealName'] 		= $orderItem->description;
					$params['$buyerName'] 		= $buyerDetail->name;
					$params['$deliveryName'] 	= $deliveryDetail->name;
					$params['$deliveryEmail'] 	= $deliveryDetail->email;
					$params['$deliveryMsg'] 	= $deliveryDetail->msg;
					$params['$linkToCoupon'] 	= $link;
					EnmasseHelper::sendMailByTemplate($deliveryDetail->email,'confirm_deal_receiver', $params);
				}

			function orderItemRefunded($orderItemId)
			{
				$orderItem = JModel::getInstance('orderItem','enmasseModel')->getById($orderItemId);

				//--------------------
				// Update Order Item
				JModel::getInstance('orderItem','enmasseModel')->updateStatus($orderItem->id, "Refunded");
				JModel::getInstance('deal','enmasseModel')->reduceQtySold($orderItem->pdt_id, $orderItem->qty);
				//--------------------
				// Update Order, check if all order item are in the status first
				$order = JModel::getInstance('order','enmasseModel')->getById($orderItem->order_id);

				$tempOrderItemList = JModel::getInstance('orderItem','enmasseModel')->listByOrderId($orderItem->order_id);
				$checkFlag = true;
				foreach($tempOrderItemList as $tempOrderItem)
				{
					if($tempOrderItem->status != "Refunded")
					{
						$checkFlag = false;
						break;
					}
				}
				if($checkFlag)
				JModel::getInstance('order','enmasseModel')->updateStatus($order->id, "Refunded");

				$buyer 		= json_decode($order->buyer_detail);
				$delivery 	= json_decode($order->delivery_detail);

				$params = array();
				$params['$dealName'] 		= $deal->name;
				$params['$buyerName'] 		= $buyer->name;
				$params['$buyerEmail'] 		= $buyer->email;
				$params['$deliveryName'] 	= $delivery->name;
				$params['$deliveryEmail'] 	= $delivery->email;
				$params['$orderId'] 		= EnmasseHelper::displayOrderDisplayId($order->id);
				$params['$refundAmt'] 		= EnmasseHelper::displayCurrency($orderItem->total_price);

				//--------------------
				// Email the Buyer
				EnmasseHelper::sendMailByTemplate($buyer->email, 'void_deal', $params);

				//--------------------
				// Email the Admin
				$db 	=& JFactory::getDBO();
				$query = "SELECT * FROM #__enmasse_setting ";
				$db->setQuery( $query );
				$setting = $db->loadObject();
				EnmasseHelper::sendMailByTemplate($setting->customer_support_email, 'void_deal', $params);

			}

			//--------------------
			// CURL
			function post($postUrl, $postDataStr)
			{
				$ch = curl_init();
				curl_setopt( $ch, CURLOPT_URL, $postUrl); // URL to post
				curl_setopt ($ch, CURLOPT_POST, 1);
				curl_setopt ($ch, CURLOPT_POSTFIELDS,$postDataStr);
				curl_setopt ($ch, CURLOPT_FOLLOWLOCATION, 1);
				$result = curl_exec( $ch ); // runs the post
				if(curl_errno($ch))
				{
					curl_close($ch);
					return false;
				}
				else
				{
					curl_close($ch);
					return true;
				}

			}

			//--------------------
			// json decode from string
			function jsonDecoder($postdata)
			{
				$first_arr = explode("&",$postdata);
				$attribute_array = array();
				for ($i=0 ; $i <  count($first_arr); $i++)
				{
					$second_arr = explode('=',$first_arr[$i]);
					$key = $second_arr[0];
					$attribute_array[$key] = $second_arr[1];
				}
				return $attribute_array;
			}

			//--------------------
			// to generate html table of report for exporting
			function reportGenerator($itemList)
			{
				echo '<table border="1">';
				echo "<thead>
				         <tr><td colspan='9' style='font-size:16px; color:#0000FF; text-align:center;'> Deal Coupon Report </td> </tr>
						<tr>
							<th width=\"5%\">".JTEXT::_('REPORT_SERIAL')."</th>
							<th width=\"15%\">".JTEXT::_('REPORT_BUYER_NAME')."</th>
							<th width=\"15%\">". JTEXT::_('REPORT_BUYER_MAIL')."</th>
							<th width=\"15%\">". JTEXT::_('REPORT_DELIVERY_NAME')."</th>
							<th width=\"15%\">". JTEXT::_('REPORT_DELIVERY_MAIL')."</th>
							<th width=\"15%\">". JTEXT::_('REPORT_ORDER_COMMENT')."</th>
							<th width=\"10%\">". JTEXT::_('REPORT_PURCHASE_DATE')."</th>
							<th width=\"5%\">". JTEXT::_('REPORT_COUPON_SERIAL')."</th>
							<th width=\"5%\">". JTEXT::_('REPORT_COUPON_STATUS')."</th>
						</tr>
					</thead>";
								for($i=0; $i < count($itemList); $i++)
								{ $itemOrder = $itemList[$i];
								echo '<tr>';
								echo '<td>'.$itemOrder['Serial No.'].'</td>';
								echo '<td>'.$itemOrder['Buyer Name'].'</td>';
								echo '<td>'.$itemOrder['Buyer Email'].'</td>';
								echo '<td>'.$itemOrder['Delivery Name'].'</td>';
								echo '<td>'.$itemOrder['Delivery Email'].'</td>';
								echo '<td>'.$itemOrder['Order Comment'].'</td>';
								echo '<td>'.$itemOrder['Purchase Date'].'</td>';
								echo '<td style="text-align:center;"># '.$itemOrder['Coupon Serial'].'</td>';
								echo '<td style="text-align:center;">'.$itemOrder['Coupon Status'].'</td>';
								echo '</tr>';
								}
								echo '</table>';
			}
			//--------------------
			// to get article title for setting
			function getArticleTitleById($id)
			{
				$db =& JFactory::getDBO();
				$query = "SELECT title FROM #__content Where id=".$id;
				$db->setQuery( $query );
				$content = $db->loadObject();
				return $content->title;
			}
			//-----------------------
			function setLastAction($option,$view)
			{
				$session = JFactory::getSession();
				if($option=="com_user" && $view == 'register')
				$session->set('lastAction','registation');
				else
				$session->set('lastAction','none');
			}
			//--------------------
			// to do order notify

			function doNotify($orderId)
			{
				$order = JModel::getInstance('order','enmasseModel')->getById($orderId);
				JModel::getInstance('order','enmasseModel')->updateStatus($order->id, 'Paid');

				$orderItemList = JModel::getInstance('orderItem','enmasseModel')->listByOrderId($orderId);
				$totalQty = 0;

				for($count = 0; $count < count($orderItemList); $count++)
				{
					$orderItem = $orderItemList[0];
					JModel::getInstance('deal','enmasseModel')->addQtySold($orderItem->pdt_id, $orderItem->qty);
					JModel::getInstance('orderItem','enmasseModel')->updateStatus($orderItem->id, 'Paid');
                    JModel::getInstance('invty','enmasseModel')->updateStatusByOrderItemId($orderItem->id,'Sold');
                                    
					if($count == 0)
					$dealName = $orderItem->description;
					elseif($count == (count($orderItemList)-1))
					$dealName .= " & " . $orderItem->description;
					else
					$dealName .= " , " . $orderItem->description;

					$totalQty += $orderItem->qty;
				}

				//--------------------------
				// Sending email
				$payment 			= json_decode($order->pay_detail);
				$buyer 				= json_decode($order->buyer_detail);
				$delivery 			= json_decode($order->delivery_detail);
					
				$params = array();
				$params['$buyerName'] 		= $buyer->name;
				$params['$buyerEmail'] 		= $buyer->email;
				$params['$deliveryName'] 	= $delivery->name;
				$params['$deliveryEmail'] 	= $delivery->email;
				$params['$orderId'] 		= EnmasseHelper::displayOrderDisplayId($order->id);
				$params['$dealName'] 		= $dealName;
				$params['$totalPrice'] 		= EnmasseHelper::displayCurrency($order->total_buyer_paid);
				$params['$totalQty'] 		= $totalQty;
				$params['$createdAt'] 		= DatetimeWrapper::getDisplayDatetime($order->created_at);
					
				EnmasseHelper::sendMailByTemplate($buyer->email,'receipt', $params);
					
				// For Confirmed deals, we will proceed with the delivery immediately
				foreach($orderItemList as $orderItem)
				{
					$deal = JModel::getInstance('deal','enmasseModel')->getById($orderItem->pdt_id);
					if($deal->status=="Confirmed")
						EnmasseHelper::orderItemDelivered($orderItem->id);
				}
				
				
			}
			//------------------------------------
			//-- to get deal image height & width
			function getDealImageSize()
			{
				$db =& JFactory::getDBO();
				$query = "SELECT image_height,image_width FROM #__enmasse_setting limit 1";
				$db->setQuery( $query );
				$setting = $db->loadObject();
				return $setting;
			}

			/*Get max value*/
			function getMaxBuyQtyOfDeal($id){
				$db =&JFactory::getDBO();
				$query = "SELECT max_buy_qty FROM #__enmasse_deal where id=$id";
				$db->setQuery($query);
				return  $db->loadResult();
				
			}
			
		   //------------------------------------------------
		   // to get the total bought quantity on a deal of one user
		   
			function getTotalBoughtQtyOfUser($orderListObject,$cart)
			{
				$boughtQty = 0;
				for($x=0; $x < count($orderListObject); $x++)
				{
					$orderItem = JModel::getInstance('orderItem','enmasseModel')->listByOrderId($orderListObject[$x]->id);
					foreach($cart->getAll() as $cartItem): 
					  $item = $cartItem->getItem();
					  
					  if($item->id == $orderItem[0]->pdt_id)
					  {
					  	$boughtQty += $orderItem[0]->qty;
					  }
					endforeach;
				}
				return $boughtQty;
			}
			
			function is_urlEncoded($string){
			 $test_string = $string;
			 while(urldecode($test_string) != $test_string){
			  $test_string = urldecode($test_string);
			 }
			 return (urlencode($test_string) == $string)?True:False; 
			}						
							
}

?>