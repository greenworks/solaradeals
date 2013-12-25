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

$emptyJOpt = JHTML::_('select.option', '', JText::_('') );

$row 			= $this->deal;
$merchantList 	= $this->merchantList;
$locationList 	= $this->locationList;
$categoryList 	= $this->categoryList;
$statusList 	= $this->statusList;

global $mainframe;
JHTML::_('behavior.calendar');
JHTML::_('behavior.tooltip');
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

<script language="javascript" type="text/javascript">
        <!--
        function moveOptions(from,to) {
			  // Move them over
			  for (var i=0; i<from.options.length; i++) {
				var o = from.options[i];
				if (o.selected) {
				  to.options[to.options.length] = new Option( o.text, o.value, false, false);
				}
			  }
			  // Delete them from original
			  for (var i=(from.options.length-1); i>=0; i--) {
				var o = from.options[i];
				if (o.selected) {
				  from.options[i] = null;
				}
			  }
			  from.selectedIndex = -1;
			  to.selectedIndex = -1;
			}
        function dateGen(str)
        {
        	var year  = parseInt(str.substring(0,4),10);
        	var month  = parseInt(str.substring(5,7),10);
        	var day  = parseInt(str.substring(8,10),10);
        	var date = new Date(year, month, day);
        	return date.getTime();
        }
        function currDate()
        {
        	 var currentTime = new Date();
             var year = currentTime.getFullYear();
             var month = currentTime.getMonth() + 1;
             var day = currentTime.getDate();
             var today = "";
             if(month > 9 && day > 9 )
             {today = year+"-"+month+"-"+day;}
             else if(month > 9)
             {today = year+"-"+month+"-0"+day;}
             else if(day > 9)
             {today = year+"-0"+month+"-"+day;}
             else
             {today = year+"-0"+month+"-0"+day;}
             return today;
        }
        function submitForm()
        {
            var form = document.adminForm;
            // do field validation
            if (form.name.value == "")
            {
                alert( "<?php echo JText::_( 'FILL_DEAL_NAME', true ); ?>" );
            }
            else if (form.min_needed_qty.value == "")
            {
                alert( "<?php echo JText::_( 'FILL_DEAL_MIN_QTY', true ); ?>" );
            }
            else if (isNaN(form.min_needed_qty.value))
            {
                alert( "<?php echo JText::_( ' MIN_QTY_SHOULD_BE_NUM', true ); ?>" );
            }
            else if (form.origin_price.value == "")
            {
                alert( "<?php echo JText::_( 'FILL_DEAL_O_PRICE', true ); ?>" );
            }
            else if (form.price.value == "")
            {
                alert( "<?php echo JText::_( 'FILL_DEAL_DEAL_PRICE', true ); ?>" );
            }
            else if (isNaN(form.price.value))
            {
                alert( "<?php echo JText::_( ' DEAL_PRICE_SHOULD_BE_NUM', true ); ?>" );
            }
            else if (form.start_at.value == "")
            {
                alert( "<?php echo JText::_( 'CHOOSE_S_DATE', true ); ?>" );
            }
            else if (form.end_at.value == "")
            {
                alert( "<?php echo JText::_( 'CHOOSE_E_DATE', true ); ?>" );
            }
            else if(dateGen(form.start_at.value) > dateGen(form.end_at.value))
            {
            	alert( "<?php echo JText::_( 'DEAL_END_GREATER_START', true ); ?>" ); 
            }
            else if(dateGen(form.start_at.value) < dateGen(currDate()))
            {
            	alert( "<?php echo JText::_( 'DEAL_START_LESS_TODAY', true ); ?>" ); 
            }
            else if(form.max_buy_qty.value < -1){
                alert("<?php echo JText::_('MAX_BUY_QTY_LESS_THAN_ZERO', true)?>");
            }
            else if(document.adminForm['location_id[]'].options.length ==0)
            {
                  alert ("<?php echo JText::_('PLEASE_CHOOSE_LOCATION_FOR_DEAL', true);?>");
            }
            else if(document.adminForm['pdt_cat_id[]'].options.length ==0)
            {
                  alert ("<?php echo JText::_('PLEASE_CHOOSE_CATEGORY_FOR_DEAL', true);?>");
            }
            else
            {
            	allSelected(document.adminForm['location_id[]']);
            	allSelected(document.adminForm['pdt_cat_id[]']);
            	 document.adminForm.submit();
            }
        }
        function allSelected(element) {
			   for (var i=0; i<element.options.length; i++) {
					var o = element.options[i];
					o.selected = true;

				}
			 }
        //-->
        </script>
        
<table>
	<tr>
		<td><a href="javascript:window.location.href='index.php?option=com_enmasse&controller=salesPerson&task=dealShow';">List Your Deal</a></td>
		<td width="10" align="center"> | </td>	
		<td><a href="javascript:window.location.href='index.php?option=com_enmasse&controller=salesPerson&task=dealAdd';">Add New Deal</a></td>
	</tr>
</table>
<br/>

<form action="index.php" method="post" name="adminForm" id="adminForm" class="form-validate" >
<div class="col100">
<fieldset class="adminform">
	<legend><?php echo JText::_( 'DEAL_DETAILS' ); ?></legend>
<table class="admintable">
	<tr>
		<td width="100" align="right" class="key">
			<label for="name"> 
				<?php echo JHTML::tooltip(JTEXT::_('TOOLTIP_DEAL_NAME'), JTEXT::_('TOOLTIP_DEAL_NAME_TITLE'), '', 'DEAL_NAME'); ?>*:
			</label>
		</td>
		<td>
			<input class="required" type="text" name="name" id="name" size="45" maxlength="250" value="<?php echo $row->name;?>" /></td>
	</tr>
	<tr>
		<td width="100" align="right" class="key">
			<label for="description">
				<?php echo JHTML::tooltip(JTEXT::_('TOOLTIP_DEAL_DESCRIPTION'), JTEXT::_('TOOLTIP_DEAL_DESCRIPTION_TITLE'), '', 'DEAL_DESC'); ?>:
			</label>
		</td>
		<td>
			<?php 
				$editor =& JFactory::getEditor();
				echo $editor->display('description', $row->description, '600', '300', '50', '3');
			?>
		</td>
	</tr>
	<tr>
		<td width="100" align="right" class="key">
			<label for="short_desc">
				<?php echo JHTML::tooltip(JTEXT::_('TOOLTIP_DEAL_S_DESCRIPTION'), JTEXT::_('TOOLTIP_DEAL_S_DESCRIPTION_TITLE'), '', 'DEAL_SHORT_DESC'); ?>:
			</label>
		</td>
		<td>
			<textarea class="text_area" name="short_desc" id="short_desc" rows="3" cols="50"><?php echo $row->short_desc;?></textarea>
		</td>
	</tr>
	<tr>
		<td width="100" align="right" class="key">
			<label for="price">
				<?php echo JHTML::tooltip(JTEXT::_('TOOLTIP_DEAL_O_PRICE'), JTEXT::_('TOOLTIP_DEAL_O_PRICE_TITLE'), '', 'DEAL_ORIGINAL_PRICE'); ?>*:
			</label>
		</td>
		<td>
			<?php echo $this->currencyPrefix;?>
			<input class="text_area" type="text" name="origin_price" id="origin_price" size="20" maxlength="250"
			value="<?php echo $row->origin_price;?>" />		<?php echo $this->currencyPostfix;?>	
		</td>
	</tr>
	<tr>
		<td width="100" align="right" class="key">
			<label for="price">
				<?php echo JHTML::tooltip(JTEXT::_('TOOLTIP_DEAL_C_PRICE'), JTEXT::_('TOOLTIP_DEAL_C_PRICE_TITLE'), '', 'DEAL_PRICE'); ?>*:
			</label>
		</td>
		<td>
			<?php echo $this->currencyPrefix;?>
			<input class="text_area" type="text" name="price" id="price" size="20" maxlength="250" value="<?php echo $row->price;?>" />
			<?php echo $this->currencyPostfix;?>
		</td>
	</tr>
	<tr>
		<td width="100" align="right" class="key">
			<label for="pic_dir">
				<?php echo JHTML::tooltip(JTEXT::_('TOOLTIP_DEAL_URL'), JTEXT::_('TOOLTIP_DEAL_URL_TITLE'), '', 'DEAL_PIC_URL'); ?>:
			</label>
		</td>
		<td>

			<input class="text_area" type="text" name="pic_dir" id="pic_dir"
			size="50" maxlength="250" value="<?php echo $row->pic_dir;?>" />
			<?php $link= 'http://'.$_SERVER['SERVER_NAME'];?>
			
			<a rel="{handler: 'iframe', size: {x: 500, y: 400}}"
			href="<?php echo 'index.php?option=com_enmasse&controller=uploader&task=display&parentId=pic_dir'; ?>"
			class="modal"><?php echo JTEXT::_('DEAL_PIC_URL_LINK');?></a>
		</td>
	</tr>
	<tr>
		<td width="100" align="right" class="key">
			<label for="start_at">
				<?php echo JHTML::tooltip(JTEXT::_('TOOLTIP_DEAL_START_DATE'), JTEXT::_('TOOLTIP_DEAL_START_DATE_TITLE'), '', 'DEAL_START_AT'); ?>*:
			</label>
		</td>
		<td>
			<input type="text" name="start_at" id="start_at" value="<?php echo $row->start_at ?>" size="30">
			<input type="reset" class="button" value="..." onclick="return showCalendar('start_at','%Y-%m-%d');" />
		</td>
	</tr>
	<tr>
		<td width="100" align="right" class="key">
			<label for="end_at">
				<?php echo JHTML::tooltip(JTEXT::_('TOOLTIP_DEAL_END_DATE'), JTEXT::_('TOOLTIP_DEAL_END_DATE_TITLE'), '', 'DEAL_END_AT'); ?>*:
			</label>
		</td>
		<td>
			<input type="text" name="end_at" id="end_at"
			value="<?php echo $row->end_at ?>" size = "30">
			
			<input type="reset" class="button" value="..."
			onclick="return showCalendar('end_at','%Y-%m-%d');" />
		</td>
	</tr>
	<tr>
		<td width="100" align="right" class="key">
			<label for="min_need_qty">
				<?php echo JHTML::tooltip(JTEXT::_('TOOLTIP_DEAL_MIN_QTY'), JTEXT::_('TOOLTIP_DEAL_MIN_QTY_TITLE'), '', 'DEAL_MIN_QUANTITY'); ?>*:
			</label>
		</td>
		<td>
			<input type="text" name="min_needed_qty" id="min_needed_qty"
			value="<?php echo $row->min_needed_qty ?>">
		</td>
	</tr>
	<tr>
		<td width="100" align="right" class="key"><label for="max_buy_qty"> <?php echo JHTML::tooltip(JTEXT::_('TOOLTIP_DEAL_MAX_BUY_QTY'), JTEXT::_('TOOLTIP_DEAL_MAX_BUY_QTY_TITLE'), '', 'DEAL_MAX_BUY_QUANTITY'); ?>
		</label></td>
		<td><input type="text" name="max_buy_qty" id="max_buy_qty"
			value="<?php if(!empty($row->max_buy_qty) && $row->max_buy_qty!=0) echo $row->max_buy_qty ; else echo '-1'; ?>">
			<i><?php  echo JText::_('MSG_MAX_BUY_QTY_DES');?></i>
			</td>
	</tr>
	<tr>
		<td width="100" align="right" class="key"><label for="max_coupon_qty"> <?php echo JHTML::tooltip(JTEXT::_('TOOLTIP_DEAL_MAX_COUPON_QTY'), JTEXT::_('TOOLTIP_DEAL_COUPON_BUY_QTY_TITLE'), '', 'DEAL_MAX_COUPON_QUANTITY'); ?>
		</label></td>
		<td><input type="text" name="max_coupon_qty" id="max_coupon_qty"
			value="<?php if(!empty($row->max_coupon_qty) && $row->max_coupon_qty!=0) echo $row->max_coupon_qty ; else echo '-1'; ?>">
			<i><?php  echo JText::_('MSG_MAX_COUPON_QTY_DES');?></i>
			</td>
	         
	</tr>
	<tr>
		<td width="100" align="right" class="key">
			<label for="merchant_id">
				<?php echo JHTML::tooltip(JTEXT::_('TOOLTIP_DEAL_MERCHANT'), JTEXT::_('TOOLTIP_DEAL_MERCHANT_TITLE'), '', 'DEAL_MERCHANT'); ?>:
			</label>
		</td>
		<td>
			<?php
				$merchantJOptList = array();
				array_push($merchantJOptList, $emptyJOpt);
				foreach ($this->merchantList as $item)
				{
					$var = JHTML::_('select.option', $item->id, JText::_($item->name) );
					array_push($merchantJOptList, $var);
				}
				 
				echo JHTML::_('select.genericList', $merchantJOptList, 'merchant_id', null , 'value','text',$row->merchant_id );
       		?>
       	</td>
	</tr>
	<tr>
		<td width="100" align="right" class="key"><label for="pdt_cat_id"> <?php echo JHTML::tooltip(JTEXT::_('TOOLTIP_DEAL_CATEGORY'),JTEXT::_('TOOLTIP_DEAL_CATEGORY_TITLE'), '', 'DEAL_CATEGORY'); ?>
		</label></td>
		<td>
		     <div>
			       <div style="float: left;">
						<?php
						// create list location for combobox
						$categoryJOptList = array();
						
							if(count($this->dealCategoryList)!=0)
							{
								$categoryList = $this->categoryList;
								$dealCategoryList = $this->dealCategoryList;
								for ( $i=0; $i < count($categoryList); $i++)
								{
									$available = false;
									for( $x=0 ; $x < count($dealCategoryList); $x++)
									{
										if($categoryList[$i]->id == $dealCategoryList[$x]->id )
										{
											$available = true;
										}
									}
									if(!$available)
									{
									$var = JHTML::_('select.option', $categoryList[$i]->id, JText::_($categoryList[$i]->name) );
									array_push($categoryJOptList, $var);
									}
								}
							}
							else
							{
								foreach ($this->categoryList as $item)
								{
									$var = JHTML::_('select.option', $item->id, JText::_($item->name) );
									array_push($categoryJOptList, $var);
								}
							}
						
							
						echo JHTML::_('select.genericList',$categoryJOptList, 'pdt_cat_list', 'class="inputbox" size="10" onDblClick="moveOptions(document.adminForm.pdt_cat_list, document.adminForm[\'pdt_cat_id[]\'])" multiple="multiple"', 'value','text',null );
						?>
					</div>
					<div style="float: left; padding: 40px 10px 0px 10px">
							<input style="width: 50px" type="button" name="Button" value="&gt;"  onClick="moveOptions(document.adminForm.pdt_cat_list, document.adminForm['pdt_cat_id[]'])"/>
	                            <br /><br />
	                        <input style="width: 50px" type="button" name="Button" value="&lt;" onClick="moveOptions(document.adminForm['pdt_cat_id[]'],document.adminForm.pdt_cat_list)" />
	                            <br /><br />
					</div>
					<div style="float: left;">
					        <?php 
					            $dealCategoryJOptList = array();
					            if(!empty($this->dealCategoryList))
					            {
					            	foreach($this->dealCategoryList as $item)
					            	{
					            		$var = JHTML::_('select.option', $item->id, JText::_($item->name) );
					            		array_push($dealCategoryJOptList, $var);
					            	}
					            }
					        	echo JHTML::_('select.genericList',$dealCategoryJOptList, 'pdt_cat_id[]', 'class="inputbox" size="10" onDblClick="moveOptions(document.adminForm[\'pdt_cat_id[]\'],document.adminForm.pdt_cat_list)" multiple="multiple"', 'value','text',null );
					        ?>
					</div>
			 </div>
		</td>
	</tr>
	<tr>
		<td width="100" align="right" class="key"><label for="location_id"> <?php echo JHTML::tooltip(JTEXT::_('TOOLTIP_DEAL_LOCATION'), JTEXT::_('TOOLTIP_DEAL_LOCATION_TITLE'), '', 'DEAL_LOCATION'); ?>
		</label></td>
		<td>
			<div>
					<div style="float: left; ">
						<?php
						// create list location for combobox
						$locationJOptList = array();
						
							if(!empty($this->dealLocationList))
							{
								$locationList = $this->locationList;
								$dealLocationList = $this->dealLocationList;
								for ($i=0 ; $i < count($locationList); $i++)
								{
									$locationAvailable = false;
									for($x = 0 ; $x < count($dealLocationList); $x++)
									{
										if($locationList[$i]->id == $dealLocationList[$x]->id)
										{
											$locationAvailable = true;
										}
									}
									if(!$locationAvailable)
									{
										$var = JHTML::_('select.option', $locationList[$i]->id, JText::_($locationList[$i]->name) );
										array_push($locationJOptList, $var);
									}
								}
							}
							else
							{
								foreach ($this->locationList as $item)
								{
									$var = JHTML::_('select.option', $item->id, JText::_($item->name) );
									array_push($locationJOptList, $var);
								}
							}
							
						echo JHTML::_('select.genericList',$locationJOptList, 'location_list', 'class="inputbox" size="10" onDblClick="moveOptions(document.adminForm.location_list, document.adminForm[\'location_id[]\'])" multiple="multiple"' , 'value','text',null);
						?>
					</div>
					<div style="float: left; padding: 40px 10px 0px 10px">
					
							<input style="width: 50px" type="button" name="Button" value="&gt;" onClick="moveOptions(document.adminForm.location_list, document.adminForm['location_id[]'])" />
	                            <br /><br />
	                        <input style="width: 50px" type="button" name="Button" value="&lt;" onClick="moveOptions(document.adminForm['location_id[]'],document.adminForm.location_list)" />
	                            <br /><br />
	                            
					</div>
					<div style="float: left; ">
						<?php 
						$dealLocationJOptList = array();
						foreach($this->dealLocationList as $item)
						{
							$var = JHTML::_('select.option', $item->id, JText::_($item->name) );
							array_push($dealLocationJOptList, $var);
						}
						echo JHTML::_('select.genericList',$dealLocationJOptList, 'location_id[]', 'class="inputbox" size="10" onDblClick="moveOptions(document.adminForm[\'location_id[]\'],document.adminForm.location_list)" multiple="multiple"' , 'value','text',null );?>
				    </div>
			</div>
		</td>
	</tr>
	<tr>
		<td width="100" align="right" class="key">
			<label for="highlight">
				<?php echo JHTML::tooltip(JTEXT::_('TOOLTIP_DEAL_HIGHTLIGHT'), JTEXT::_('TOOLTIP_DEAL_HIGHTLIGHT_TITLE'), '', 'DEAL_HIGHLIHGTS'); ?>:
			</label>
		</td>
		<td>
			<?php 
				$editor =& JFactory::getEditor();
				echo $editor->display('highlight', $row->highlight, '400', '200', '50', '3');
			?>
		</td>
	</tr>
	<tr>
		<td width="100" align="right" class="key">
			<label for="terms">
				<?php echo JHTML::tooltip(JTEXT::_('TOOLTIP_DEAL_CONDITION'),JTEXT::_('TOOLTIP_DEAL_CONDITION_TITLE'), '', 'DEAL_TERMS_CONDITIONS'); ?>:
			</label>
		</td>
		<td>
			<?php 
				$editor =& JFactory::getEditor();
				echo $editor->display('terms', $row->terms, '400', '200', '50', '3');
			?>
		</td>
	</tr>
	<tr>
		<td width="100" align="right" class="key"><?php echo JTEXT::_('CREATED_AT'); ?>:</td>
		<td><?php echo DatetimeWrapper::getDisplayDatetime($row->created_at); ?></td>
	</tr>
	<tr>
		<td width="100" align="right" class="key"><?php echo JTEXT::_('UPDATED_AT'); ?>:</td>
		<td><?php echo DatetimeWrapper::getDisplayDatetime($row->updated_at); ?></td>
	</tr>
</table>
</fieldset>
</div>

<input type="hidden" name="position" value="<?php echo $row->position; ?>" />
<input type="hidden" name="id" value="<?php echo $row->id; ?>" />
<input type="hidden" name="option" value="com_enmasse" />
<input type="hidden" name="controller" value="salesPerson" />
<input type="hidden" name="task" value="dealSave" />

<input type="button" value="<?php echo JTEXT::_('DEAL_SAVE_BUTTON');?>" onclick="submitForm();"/>
</form>
