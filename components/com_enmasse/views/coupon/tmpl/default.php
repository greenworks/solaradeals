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
$varList 		= $this->varList;
$elementList 	= $this->elementList;
if(!EnmasseHelper::is_urlEncoded($this->bgImageUrl))
 {
 	$bgImageUrl = $this->bgImageUrl;
 }
 else
 {
	$imageUrlArr= unserialize(urldecode($this->bgImageUrl));
	$bgImageUrl = str_replace("\\","/",$imageUrlArr[0]);
 }
//$bgArr          = unserialize(urldecode($this->bgImageUrl));
//$bgImageUrl 	= str_replace("\\","/",$bgArr[0]);//urldecode($this->bgImageUrl);

//---------------------------
// to re define server link
$temp_link_arr  = explode('/',$_SERVER['PHP_SELF']) ;
$server = $_SERVER['SERVER_NAME'];
for ($count=0; $count < count ($temp_link_arr)-1; $count++)
{
	if ($temp_link_arr[$count]!= '')
	{
		$server.='/';
		$server.=$temp_link_arr[$count];
	}
}

/// load language
$language =& JFactory::getLanguage();
$base_dir = JPATH_BASE.DS.'components'.DS.'com_enmasse';

$extension = 'com_enmasse';
if($language->load($extension, $base_dir, $language->getTag(), true) == false)
{
	 $language->load($extension, $base_dir, 'en-GB', true);
}
?>
<style type="text/css">
div#couponImage 
{
	z-index:-100px;
}
div#couponImage div#couponInfo 
{
	position:absolute;
	left:0px;
	top:0px;
	width: 100%;
	height: 100%;
	z-index:100px;
}
</style>
<div id="couponImage" style="width:680px ; height:424px; position: relative;">
<?php
if($bgImageUrl!= "")
	echo '<img src="'.$this->baseurl.'/'.$bgImageUrl.'" width="680" height="424">';
?>

	<div id="couponInfo">
<?php
$body = "";
for($i=0 ; $i < count($elementList); $i++)
{
	if(!isset($varList[$elementList[$i]->name]))
	{
		if($elementList[$i]->name== 'serial')
		{
			if($varList[$elementList[$i]->name] == '' || $varList[$elementList[$i]->name] == null)
			{
				$num = 'SERIAL';
			}
			else
			{
				$num = 	$varList[$elementList[$i]->name];
			}
			$body.='<div id="'.$elementList[$i]->name.'" style="position: absolute; left:' .$elementList[$i]->x.'px; top:'.$elementList[$i]->y.'px; font-size:'.$elementList[$i]->font_size.'px; width:'.$elementList[$i]->width.'px; height:'.$elementList[$i]->height.'px">';
				$body .= '<img src="http://'.$server .'/components/com_enmasse/helpers/barcodegenerator/barcode_img.php?num='.$num.'"/>';
			$body.='</div>';	
		}
		else{
		
		$body.='<div id="'.$elementList[$i]->name.'" style="border: red 2px dashed; position: absolute; left:' .$elementList[$i]->x.'px; top:'.$elementList[$i]->y.'px; font-size:'.$elementList[$i]->font_size.'px; width:'.$elementList[$i]->width.'px; height:'.$elementList[$i]->height.'px">';
			$body .= "[".strtoupper($elementList[$i]->name)."]";
		$body.='</div>';
		}
	}
	elseif($elementList[$i]->name== 'serial')
	{
		if($varList[$elementList[$i]->name] == '' || $varList[$elementList[$i]->name] == null)
		{
			$num = 'SERIAL';
		}
		else
		{
		$num = 	$varList[$elementList[$i]->name];
		}
		$body.='<div id="'.$elementList[$i]->name.'" style="position: absolute; left:' .$elementList[$i]->x.'px; top:'.$elementList[$i]->y.'px; font-size:'.$elementList[$i]->font_size.'px; width:'.$elementList[$i]->width.'px; height:'.$elementList[$i]->height.'px">';
			$body .= '<img src="'.$this->baseurl .'/components/com_enmasse/helpers/barcodegen/test.php?num='.$num.'"/>';
		$body.='</div>';	
	}
	else
	{
	$body.='<div id="'.$elementList[$i]->name.'" style="position: absolute; left:' .$elementList[$i]->x.'px; top:'.$elementList[$i]->y.'px; font-size:'.$elementList[$i]->font_size.'px; width:'.$elementList[$i]->width.'px; height:'.$elementList[$i]->height.'px">';
		$body .= $varList[$elementList[$i]->name];
	$body.='</div>';
	}
}
echo $body;
?>
	</div>
</div>

	<script language="JavaScript">
	function printContent(id){
	    document.getElementById('button').style.display = 'none';
	    window.print();
	    document.getElementById('button').style.display = 'inline';
	}
	</script>
	<div id="button">
	<form>
		<input type="button" value="<?php echo JText::_('COUPON_PRINT_BUTTON');?>" onClick="printContent('content')">
	</form>
	</div>
<?php 
//print_r($varList);
?>