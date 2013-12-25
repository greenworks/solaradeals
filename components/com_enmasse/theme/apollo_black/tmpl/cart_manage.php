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

require_once( JPATH_ADMINISTRATOR . DS ."components". DS ."com_enmasse". DS ."helpers". DS ."EnmasseHelper.class.php");

?>



<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link href="/components/com_enmasse/theme/apollo_black/css/style.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" ></script>
<div id="ShoppingCart">
  <div class="top">
   
    <div class="dealname">
      <div class="apollo_title" style="text-align:left;"> <?php echo JText::_('SHOP_CARD_DEAL_NAME');?></div>
      <div class="apollo_info"  style="text-align:left;"><?php 
	$cart = $this -> cart;
	$count = 0; foreach($cart->getAll() as $cartItem): 
	 $item = $cartItem->getItem();
	 echo $item->name;
	 endforeach;?></div>
    </div>
    <div class="price">
      <div class="apollo_title"> <?php echo JText::_('SHOP_CARD_PRICE');?> </div>
      <div class="apollo_info"><?php 
	$cart = $this -> cart;
	$count = 0; foreach($cart->getAll() as $cartItem): 
	 $item = $cartItem->getItem();
	 echo  EnmasseHelper::displayCurrency($item->price);
	 endforeach;?></div>
    </div>
    <div class="qty">
      <div class="apollo_title"> <?php echo JText::_('SHOP_CARD_QTY');?> </div>
      <div class="apollo_info">
        <form action="index.php" id="changeItem" method="post" name="changeItem"
					class="form-validate" onSubmit="return myValidate(this);"><input
					type="hidden" name="itemId" value="<?php 
					$cart = $this -> cart;
					foreach($cart->getAll() as $cartItem): 
					 $item = $cartItem->getItem();
					 echo $item->id;
					 endforeach;?>" /> <input
					type="hidden" name="option" value="com_enmasse" /> <input
					type="hidden" name="controller" value="shopping" /> <input
					type="hidden" name="task" value="changeItem" /> <input type="input"
					size="1px" id="value" name="value"
					value="<?php echo $cartItem->getCount();?>"
					class="required validate-numeric" /> </form>

      </div>
    </div>
    <div class="total">
      <div class="apollo_title"> <?php echo JText::_('SHOP_CARD_TOTAL');?> </div>
      <div class="apollo_info">
        <?php 
	$cart = $this -> cart;
	$count = 0; foreach($cart->getAll() as $cartItem): 
	 $item = $cartItem->getItem();
	 echo EnmasseHelper::displayCurrency(($item->price)*$cartItem->getCount());
	 endforeach;?></div>
	 <?php $cart = $this -> cart;
					foreach($cart->getAll() as $cartItem): 
					 $item = $cartItem->getItem(); 
					 if($item->id!=null){ ?>
    </div>
    <div class="updateqtiny">
      <div class="apollo_title"> <?php echo JText::_('SHOP_CARD_UPDATE_QTY');?> </div>
      <div class="apollo_info">
        <div class="green_button fl">
          <div class="leftbutton"></div>
          <a class="centerbutton" onclick="javascript:document.changeItem.submit();"><?php echo JText::_('UPDATE_BUTTON');?></a>
          <div class="rightbutton"></div>
        </div>
        <form action="index.php" name="deleteItems" id="deleteItems"><input type="hidden" name="itemId"
					value="<?php 
					$cart = $this -> cart;
					$count = 0; foreach($cart->getAll() as $cartItem): 
					 $item = $cartItem->getItem();
					 echo $item->id;
					 endforeach;?>" /> <input type="hidden"
					name="option" value="com_enmasse" /> 
					<input type="hidden"
					name="controller" value="shopping" />
					<input type="hidden"
					name="task" value="removeItem" /> 
		</form>
        <?php } endforeach;?>
      </div>
    </div>
  </div>
  <div class="bottom">
    <div class="text"><?php echo JText::_('SHOP_CARD_TOTAL_ITEM');?>:  <?php echo $cart->getTotalItem();?></div>
    <div class="text"><?php echo JText::_('SHOP_CARD_TOTAL_PRICE');?>: <?php echo EnmasseHelper::displayCurrency($cart->getTotalPrice());?></div>
  </div>
</div>
