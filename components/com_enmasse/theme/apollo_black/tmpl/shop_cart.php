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
?>
<br/>
<?php include dirname(__FILE__)."/cart_manage.php";?>
<br/><br/>
<div>
		<?php 
		$cart = $this->cart;
		if ( count($cart->getAll()) > 0 ):?>
		<form action="index.php" id="emptyCart" name="emptyCart"><input type="hidden" name="option"
			value="com_enmasse" /> <input type="hidden" name="task"
			value="emptyCart" /> <input type="hidden" name="controller"
			value="shopping" /> </form>
			
		<form action="index.php" name="checkout" id="checkout">
		<input type="hidden" name="option"
			value="com_enmasse" /> <input type="hidden" name="controller"
			value="shopping" /> <input type="hidden" name="task"
			value="checkout" /> 
		</form>
		 <div class="h13"></div>
		 <div class="h4"></div>
	  <div class="green_button fl">
	    <div class="leftbutton"></div>
	    <a class="centerbutton" onclick="javascript:document.emptyCart.submit();"><?php echo JText::_('EMPTY_CART_BUTTON');?></a>
	    <div class="rightbutton"></div>
	  </div>
	  <div class="h4"></div>
	  <div class="green_button fl">
	    <div class="leftbutton"></div>
	    <a class="centerbutton" onclick="javascript:document.checkout.submit();"><?php echo JText::_('CHECK_OUT_BUTTON');?></a>
	    <div class="rightbutton"></div>
	  </div>
		<?php endif;?>
</div>
