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

// to re define server link

?>

<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link href="components/com_enmasse/theme/apollo_black/css/style.css" rel="stylesheet" type="text/css" />
<br />
<?php 
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
include "cart_view_only.php";
?>
<div class="h13"></div>
<form action='index.php' id="orderDetail" name="orderDetail"  class="form-validate" method="post" onSubmit="return myValidate(this);">
<br /><br /><br /><br /><br /><br /><br /><br /><br /><b><?php echo JText::_('SHOP_CARD_CHECK_OUT_MESSAGE_LINE1');?></b><br />
<i>
<?php echo JText::_('SHOP_CARD_CHECK_OUT_MESSAGE_LINE2');?><br /><br />
</i> 
<table>
<tr>
	<th align="left"><?php echo JText::_('SHOP_CARD_CHECK_OUT_PERSON_NAME');?></th>
	<td><input type="text" name="name" id="name" value="<?php echo $this->user->name?>" class="required" /></td>
</tr>
<tr>
	<th align="left"><?php echo JText::_('SHOP_CARD_CHECK_OUT_PERSON_EMAIL');?></th>
	<td><input type="text" name="email" id="email" 
			value="<?php echo $this->user->email?>"
			class="required validate-email" /></td>
</tr>
<tr>
	<th align="left"><?php echo JText::_('SHOP_CARD_CHECK_OUT_PERSON_MESSAGE');?></th>
	<td><input type="text" name="msg" id="msg" value=""  /></td>
</tr>
<tr>
	<td>
	</td>
</tr>
</table>
<br/><br/>
<?php if($item_price !=0) 
{?>
<div id="Order_Information">
	<div class="top">
    	<div class="line"><span> <?php echo JText::_('SHOP_CARD_CHECK_OUT_CHOOSE_PAYMENT');?> </span></div>
        <div class="line">
	        <select name="payGtyId" id="payGtyId" class="required">
				<option value=""><?php echo JText::_('SHOP_CARD_CHECK_OUT_CHOOSE_PAYMENT_OPTION');?></option>
				<?php
				foreach($this->payGtyList as $row):?>
				<option value="<?php echo $row->id;?>"><?php echo $row->name;?></option>
				<?php endforeach;?>
			</select>
        </div>
        <div class="line">
        <?php if (isset($this->termArticleId) && $this->termArticleId!=0):?>
        <input type="checkbox" name="terms" id="terms" class="required">
       <?php echo JText::_('SHOP_CARD_CHECK_OUT_TERM_CONDITION');?>
        <a style="float:none" href="<?php echo JURI::base();?>components/com_enmasse/theme/<?php echo $this->theme; ?>/tmpl/term.php?artId=<?php echo $this->termArticleId ?>"
			onclick="window.open('<?php echo JURI::base();?>components/com_enmasse/theme/<?php echo $this->theme; ?>/tmpl/term.php?artId=<?php echo $this->termArticleId ?>','name','height=600,width=400,toolbar=no,directories=no,status=no,menubar=no,scrollbars=no,resizable=no'); return false;">
        	<?php echo JText::_('SHOP_CARD_CHECK_OUT_TERM_CONDITION_LINK');?>
       </a>
        <?php else:?>
        <input type="hidden" name="terms" id="terms" class="required" value="checked">
         <?php endif;?> 
         <input type="hidden" name="check" value="post" /> 
         <input type="hidden" name="option" value="com_enmasse" /> 
         <input type="hidden" name="controller" value="shopping" /> 
         <input type="hidden" name="task" value="submitOrderDetail" />
        </div>
    </div>
    <div class="bottom">
     <div class="green_button">
      <div class="leftbutton"></div>
      <a class="centerbutton"  onclick="document.orderDetail.submit();"><?php echo JText::_('PROCESS_CHECK_OUT_BUTTON');?></a>
      <div class="rightbutton"></div>
    </div>
    </div>
</div>
<?php }
 else
 {
?>
   <input type="hidden" name ="isFree" value="true" />
   <input type="hidden" name="check" value="post" /> 
   <input type="hidden" name="option" value="com_enmasse" /> 
   <input type="hidden" name="controller" value="shopping" /> 
   <input type="hidden" name="task" value="submitOrderDetail" />
   <div class="bottom">
     <div class="green_button">
      <div class="leftbutton"></div>
      <a class="centerbutton"  onclick="document.orderDetail.submit();"><?php echo JText::_('PROCESS_CHECK_OUT_BUTTON');?></a>
      <div class="rightbutton"></div>
    </div>
    </div>

<?php }?>
</form>

