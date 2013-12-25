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

//load language pack
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

$emptyJOpt = JHTML::_('select.option', '', JText::_('') );

// create list location for combobox
$locationJOptList = array();
array_push($locationJOptList, $emptyJOpt);
foreach ($this->locationList as $item)
{
	$var = JHTML::_('select.option', $item->id, JText::_($item->name) );
	array_push($locationJOptList, $var);
}

// create list category for combobox
$categoryJOptList = array();
array_push($categoryJOptList, $emptyJOpt);
foreach ($this->categoryList as $item)
{
	$var = JHTML::_('select.option', $item->id, JText::_($item->name) );
	array_push($categoryJOptList, $var);
}

?>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link href="components/com_enmasse/theme/apollo_black/css/style.css" rel="stylesheet" type="text/css" />

<form action="index.php">

	<input type="hidden" name="option" id="option" value="com_enmasse"/>
	<input type="hidden" name="controller" id="controller" value="deal"/>
	<input type="hidden" name="task" id="task" value="expiredlisting"/>
	
	<b><?php echo JText::_('DEAL_SEARCH_NAME');?>: </b>
	<input type="text" name="keyword" size="20" value="<?php echo $this->keyword;?>"/>
	<b><?php echo JText::_('DEAL_SEARCH_LOCATION');?>: </b>
	<?php echo JHTML::_('select.genericList', $locationJOptList, 'locationId', null , 'value', 'text', $this->locationId);?>
	<b><?php echo JText::_('DEAL_SEARCH_CATEGORY');?>: </b>
	<?php echo JHTML::_('select.genericList', $categoryJOptList, 'categoryId', null , 'value', 'text', $this->categoryId);?>
	<select name="sortBy" >
        <option value=""><?php echo JText::_('DEAL_LIST_OPTION_SORT_BY');?></option>
        <option value="name" <?php if($this->sortBy=="name"){ echo "selected"; }?>><?php echo JText::_('DEAL_LIST_OPTION_NAME');?></option>
        <option value="end_at" <?php if($this->sortBy=="end_at"){ echo "selected"; }?>><?php echo JText::_('DEAL_LIST_OPTION_END_DATE');?></option>
        <option value="price" <?php if($this->sortBy=="price"){ echo "selected"; }?>><?php echo JText::_('DEAL_LIST_OPTION_PRICE');?></option>
	</select>
	<input type="submit" value="<?php echo JText::_('DEAL_LIST_SEARCH');?>"/>
	<br/><br/>
	
<div id="DealListingComponent">
<?php
    $dealList = $this->dealList;
    if(count($dealList)==0) echo '<h3>'.JText::_('DEAL_LIST_NO_DEAL_MESSAGE').'</h3>';
	$count = 1; 
	foreach ( $dealList as $row): 
		$link = 'index.php?option=com_enmasse&controller=deal&task=view&id=' . $row->id;
		if(!EnmasseHelper::is_urlEncoded($row->pic_dir))
		 {
		 	$imageUrl = $row->pic_dir;
		 }
		 else
		 {
			$imageUrlArr= unserialize(urldecode($row->pic_dir));
			$imageUrl = str_replace("\\","/",$imageUrlArr[0]);
		 }
?>
    <?php ?>
	<div class="box">
    	<div class="apollo_title"><a href="<?php echo $link;?>"><?php echo $row->name;?></a></div>
        <div class="information">
        	<div class="value">
            	<div class="top">
            		<span><?php echo $row->min_needed_qty;?></span> <br />
            		<?php echo $row->cur_sold_qty;?> <?php echo JText::_('DEAL_BOUGHT');?>
            	</div>
                <div class="h1"></div>
                <div class="bottom">
                	<div class="apollo_info"><?php echo JText::_('DEAL_VALUE');?>: <b><?php echo EnmasseHelper::displayCurrency($row->origin_price)?></b></div>
                    <div class="apollo_info"><?php echo JText::_('DEAL_DISCOUNT');?>: <b><?php echo (100 - intval($row->price/$row->origin_price*100))?>%</b></div>
                    <div class="apollo_info"><?php echo JText::_('DEAL_PRICE');?>: <b><?php echo EnmasseHelper::displayCurrency($row->price)?> </b></div>
                </div>
            </div>
        	<div class="avatar"><img src="<?php echo $imageUrl;?>" alt="" height="115" width="150"/></div>
        </div>
    </div>
    <?php if ($count%2==1){?>
     <div class="h2"></div>
    <?php }
    else if($count%2==0){?>
    <div class="h3"></div>
   <?php  }$count++ ; endforeach;?>
</div>
</form>	



