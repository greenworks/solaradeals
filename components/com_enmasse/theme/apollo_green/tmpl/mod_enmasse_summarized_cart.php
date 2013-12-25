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

<link rel="stylesheet" type="text/css" href="./script/fancybox/jquery.fancybox-1.3.1.css" media="screen" />
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link href="components/com_enmasse/theme/<?php echo $theme ?>/css/style.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" ></script>
<div id="SummarizedCartModule" class="fl">
  <div class="left_cl">
    <div class="SummarizedCartModule_title"><?php echo JTEXT::_('CART_TOTAL_ITEM');?></div>
    <div class="SummarizedCartModule_information"><?php echo $cart->getTotalItem();?></div>
    <div class="green_button">
      <div class="leftbutton"></div>
      <a class="centerbutton" href="index.php?option=com_enmasse&controller=shopping&task=viewCart"><?php echo JText::_('VIEW_CART_BUTTON');?></a>
      <div class="rightbutton"></div>
    </div>
  </div>
  <div class="right_cl">
    <div class="SummarizedCartModule_title"><?php echo JTEXT::_('CART_TOTAL_PRICE');?></div>
    <div class="SummarizedCartModule_information"><?php echo EnmasseHelper::displayCurrency($cart->getTotalPrice());?></div>
    <div class="green_button">
      <div class="leftbutton"></div>
      <a class="centerbutton" href="index.php?option=com_enmasse&controller=shopping&task=checkout"><?php echo JText::_('CHECK_OUT_BUTTON');?></a>
      <div class="rightbutton"></div>
    </div>
  </div>
</div>