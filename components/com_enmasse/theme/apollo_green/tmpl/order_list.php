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
require_once( JPATH_ADMINISTRATOR . DS ."components". DS ."com_enmasse".DS."helpers". DS ."EnmasseHelper.class.php");
?>


<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link href="css/style.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" ></script>
<div id="OrderList">
  <div class="top">
		<table width="100%">
			<tr style="text-align:left; font-size:10px;">
				<th><?php echo JText::_('ORDER_ID');?></th>
				<th width="160"><?php echo JText::_('ORDER_DEAL');?></th>
				<th><?php echo JText::_('ORDER_QTY');?></th>
				<th><?php echo JText::_('ORDER_TOTAL');?></th>
				<th><?php echo JText::_('ORDER_DATE');?></th>
				<th><?php echo JText::_('ORDER_DELIVERY');?></th>
				<th><?php echo JText::_('ORDER_STATUS');?></th>
				<th><?php echo JText::_('ORDER_COMMENT');?></th>
				<th></th>
			</tr>
			<?php $count = 0;?>
			<?php foreach($this->orderList as $orderRow):?>
			<tr  style="background-color:#CCCCFF; text-align:left; font-size:10px;">

				<td><?php echo $orderRow->display_id?></td>
				<td><?php echo $orderRow->orderItem->description?></td>
				<td><?php echo $orderRow->orderItem->qty?></td>
				<td><?php echo EnmasseHelper::displayCurrency($orderRow->total_buyer_paid);?></td>
				<td><?php echo DatetimeWrapper::getDisplayDatetime($orderRow->created_at);?></td>
			    <td><?php
			    	$deliveryObj = json_decode($orderRow->delivery_detail);
			    	echo $deliveryObj->name ."<br/>(".$deliveryObj->email.")";?></td>
			    <td><?php echo JTEXT::_('ORDER_'.$orderRow->status);?></td>
			    <td><?php echo $orderRow->description?></td>
			    <td>
			    <?php 
			    	if($orderRow->orderItem->status=="Delivered")
			    	{
			    		$token=EnmasseHelper::generateOrderItemToken($orderRow->orderItem->id, $orderRow->orderItem->created_at);
			    ?>
					<a href='<?php echo JURI::base();?>index.php?option=com_enmasse&controller=coupon&task=listing&orderItemId=<?php echo $orderRow->orderItem->id ?>&token=<?php echo $token?>'>
						<?php echo JText::_('ORDER_LIST_COUPON');?>
					</a>
				<?php 
			    	}
				?>
			    </td>

			</tr>
			<?php endforeach;?>
		</table>
		
    
  </div>
  <div class="bottom">
  </div>
</div>

