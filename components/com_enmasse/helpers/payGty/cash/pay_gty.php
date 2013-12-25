<?php
require_once( JPATH_ADMINISTRATOR . DS ."components". DS ."com_enmasse". DS ."helpers". DS ."EnmasseHelper.class.php");

echo JTEXT::_('CASH_ORDER_SUCCESS');
echo '<br>';echo '<br>';
echo JTEXT::_('DEAL_NAME').'&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;:&nbsp;&nbsp;';
$cart = $this -> cart;
	 $count = 0;
	 foreach($cart->getAll() as $cartItem): 
		 $item = $cartItem->getItem();
		 echo $item->name;
	 endforeach;
echo '<br>';
echo JTEXT::_('SHOP_CARD_TOTAL_ITEM').' &nbsp;:&nbsp;&nbsp;'.$this->cart->getTotalItem();
echo '<br>';
echo JTEXT::_('SHOP_CARD_TOTAL_PRICE').' :&nbsp;&nbsp;'.EnmasseHelper::displayCurrency($this->cart->getTotalPrice());
echo '<br>';echo '<br>';
?>

<link href="/components/com_enmasse/theme/<?php echo EnmasseHelper::getThemeFromSetting();?>/css/style.css" rel="stylesheet" type="text/css" />
<table border='1' id='instruction_table'>
  <thead>
     <tr id='instruction_table_header'> 
     	<td colspan="2" align="center"><?php echo JTEXT::_('CASH_PAY_INFO'); ?> </td>
     </tr>
  </thead>
  	<tr>
  	 	
  	    <td><?php echo $this->attributeConfig->instruction;?></td>
  	</tr>
</table>

<?php 
 $this->cart->deleteAll();
 JFactory::getSession()->set('cart', serialize($cart));
?>