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

$row 	= $this->payGty;
$option = 'com_enmasse';
$version = new JVersion;
$joomla = $version->getShortVersion();
if(substr($joomla,0,3) == '1.6'){
?>
    <script language="javascript" type="text/javascript">
        <!--
        Joomla.submitbutton = function(pressbutton)
<?php
}else{
?>
    <script language="javascript" type="text/javascript">
        <!--
        submitbutton = function(pressbutton)
<?php
}
?>
        {
            var form = document.adminForm;
            if (pressbutton == 'cancel')
            {
                submitform( pressbutton );
                return;
            }
            // do field validation
            if (form.name.value == "")
            {
                alert( "<?php echo JText::_( 'FILL_IN_MERCHANT_PAYMENT_GATEWAY_NAME', true ); ?>" );
            }
            else
            {
                submitform( pressbutton );
            }
        }
        //-->
        </script>

<form action="index.php" method="post" name="adminForm" id="adminForm">
<fieldset class="adminform">
<legend><?php echo JText::_('PAY_DETAIL')?></legend>
<table class="admintable">
	<tr>
		<td width="100" align="right" class="key"><?php echo Jtext::_('PAY_NAME');?></td>
		<td><input class="text_area" type="text" name="name" id="name"
			size="50" maxlength="250" value="<?php echo $row->name;?>" /></td>
	</tr>
	<tr>
		<td width="100" align="right" class="key"><?php echo Jtext::_('PUBLISHED');?></td>
		<td><?php
		if ($row->published == null)
		{
			echo JHTML::_('select.booleanlist', 'published',
                          'class="inputbox"', 1);
		}
		else
		{
			echo JHTML::_('select.booleanlist', 'published',
                          'class="inputbox"', $row->published);
		}
		?></td>
	</tr>
	<tr>
		<td width="100" align="right" class="key"><?php echo Jtext::_('CREATED_AT');?></td>
		<td><?php echo DatetimeWrapper::getDisplayDatetime($row->created_at); ?></td>
	</tr>
	<tr>
		<td width="100" align="right" class="key"><?php echo Jtext::_('UPDATED_AT');?></td>
		<td><?php echo DatetimeWrapper::getDisplayDatetime($row->updated_at); ?></td>
	</tr>
</table>
</fieldset>
<fieldset class="adminform">
<legend><?php echo JText::_('GATEWAY_SETTING');?></legend>
<table class="admintable">
<?php
		$attribute_list 	= explode(",",$row->attributes);
		$attribute_obj 		= json_decode($row->attribute_config);
		
		for ($i=0; $i < count($attribute_list); $i++)
		{
			$title = $attribute_list[$i];
			$value = $attribute_obj->$title;
?>
							<tr>
								<td width="100" align="right" class="key"><?php echo $title?></td>
								<td>
								    <?php 
								    if($row->class_name == 'cash')
								    {
									    $editor =& JFactory::getEditor();
										echo $editor->display('attribute_config['.$title.']', $value, '800', '300', '40', '3');
								    }
								    else{
									?>
									<input class="text_area" type="text" name="attribute_config[<?php echo $title?>]" 
							           id="attribute_config_<?php echo $title?>" size="50" maxlength="250" 
							           value="<?php echo $value?>" />
							      </td>
						    </tr>
<?php
								    }
		}
?></table>
</fieldset>
<input type="hidden" name="id" value="<?php echo $row->id; ?>" />
<input type="hidden" name="option" value="<?php echo $option;?>" />
<input type="hidden" name="controller" value="payGty" />
<input type="hidden" name="task" value="" />
	 
</form>
