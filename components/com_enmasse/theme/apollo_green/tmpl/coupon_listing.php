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
$language->load($extension, $base_dir, $language->getTag(), true);

$invtyList = $this->invtyList;
$deal = $this->deal;
?>

<?php $count = 0;?>
<h3><?php echo JText::_('COUPON_MESSAGE');?> "<?php echo $deal->name ?>":</h3>
<table>
<?php foreach ($invtyList as $invty):?>
	<?php $link = "index.php?option=com_enmasse&controller=coupon&task=generate&invtyName=".$invty->name
	          ."&token=".EnmasseHelper::generateCouponToken($invty->name);
	          ?>
	<tr>
		<td><?php echo JText::_('COUPON');?>: <?php echo $invty->name;?></td>
		<td>
			<a href="<?php echo $link ?>" target="_blank"><?php echo JText::_('COUPON_PRINT_LINK');?></a>
		</td>
	</tr>	
<?php endforeach;?>
</table>