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
$deal = $this->deal;
$merchant = $deal->merchant;
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

// load the deal image size
$dealImageSize = EnmasseHelper::getDealImageSize();
if(!empty($dealImageSize))
{
	$image_height = $dealImageSize->image_height;
	$image_width = $dealImageSize->image_width;
}
else
{
	$image_height = 252 ;
	$image_width = 400;
}

//------- to set the meta data and page title for SEO
$document =& JFactory::getDocument();
$document->setMetadata('Keywords',  $deal->name);
$version = new JVersion;
$joomla = $version->getShortVersion();
if(substr($joomla,0,3) == '1.6'){
    $document   =& JFactory::getDocument();
    $document->setTitle( $deal->name );
}else{
    $mainframe->setPageTitle($deal->name);    
}
?>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link href="components/com_enmasse/theme/apollo_red/css/style.css" rel="stylesheet" type="text/css" />
<link href="components/com_enmasse/theme/apollo_red/css/blinds.css" rel="stylesheet" type="text/css" />
<link href="components/com_enmasse/theme/apollo_red/css/basic.css" rel="stylesheet" type="text/css" />
<link href="components/com_enmasse/theme/apollo_red/css/basic_ie.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="components/com_enmasse/theme/apollo_red/script/DD_roundies_0.0.2a-min.js" ></script>
<script type="text/javascript" >DD_roundies.addRule ('.descrip', '2px', true );</script>
<script language="Javascript" type="text/javascript" src="components/com_enmasse/theme/apollo_red/script/jquery-1.4.1.js"></script>
<script language="Javascript" type="text/javascript" src="components/com_enmasse/theme/apollo_red/script/jquery.blinds-0.9.js"></script>
<script language="Javascript" type="text/javascript" src="components/com_enmasse/theme/apollo_red/script/jquery.simplemodal.js"></script>
<script language="javascript">
function resizediv (){

	var h=0;
	var h1=document.getElementById("leftcol").clientHeight;
	var h2=document.getElementById("rightcol").clientHeight;
	
	  if (h2>h1) { var h=h2; } else { var h=h1; }
	
	document.getElementById("leftcol").style.height=h+"px";
	document.getElementById("rightcol").style.height=h+"px";
	
}




</script>

<script type="text/javascript" src="http://maps.google.com/maps/api/js?sensor=true">
</script>
<script type="text/javascript">
var map;
function initialize() {
  // get the latitude and long titude value of marchant 
  var lat = "<?php  echo $merchant->google_map_lat;?>"; 
  var long = "<?php  echo $merchant->google_map_long;?>";
  //define above value to googgle map
  var myLatlng = new google.maps.LatLng(parseFloat(lat),parseFloat(long));
  var myOptions = {
    zoom: 14,
    center: myLatlng,
    mapTypeId: google.maps.MapTypeId.ROADMAP
  }
  
  // create a map with above optional values
  map = new google.maps.Map(document.getElementById("map_canvas"), myOptions);
  
  // add zoom_changed listener to map
  google.maps.event.addListener(map, 'zoom_changed', function() {
    setTimeout(moveToDarwin, 3000);
  });

  // define a marker on map
  var marker = new google.maps.Marker({
      position: myLatlng, 
      map: map,
      title:"<?php echo $merchant->name;?>"
  });

  // add licking listner to marker
  google.maps.event.addListener(marker, 'click', function() {
    map.setZoom(15);
  });
}
  
function moveToDarwin() {
  var lat = "<?php  echo $merchant->google_map_lat;?>";
  var long = "<?php  echo $merchant->google_map_long;?>";
  var darwin = new google.maps.LatLng(parseFloat(lat),parseFloat(long));
  map.setCenter(darwin);
}

</script>

<body onLoad="resizediv();initialize()">

<div id="ComponentHomepage">
  <div class="apollo_title"><?php echo $deal->name;?></div>
  <div class="content">
    <div class="leftcontent">
      <div class="buybox">
        <div class="middle">
          <div class="payment"><?php echo EnmasseHelper::displayCurrency($deal->price)?></div>
          <a class="buybutton" href="index.php?option=com_enmasse&controller=shopping&task=addToCart&dealId=<?php echo $deal->id;?>"></a> </div>
      </div>
      <div class="column">
        <div class="valuebox">
          <div class="textbox"><?php echo JText::_('DEAL_VALUE');?><br />
            <span><?php echo EnmasseHelper::displayCurrency($deal->origin_price)?></span></div>
          <div class="textbox"><?php echo JText::_('DEAL_DISCOUNT');?> <br />
            <span><?php echo 100 - intval($deal->price/$deal->origin_price*100)?>%</span></div>
          <div class="textbox"><?php echo JText::_('DEAL_SAVE');?><br />
            <span><?php echo EnmasseHelper::displayCurrency($deal->origin_price - $deal->price)?></span></div>
        </div>
        <div class="h5"></div>
        <a class="giftbox" href="index.php?option=com_enmasse&controller=shopping&task=addToCart&dealId=<?php echo $deal->id;?>"><img src="components/com_enmasse/theme/apollo_red/images/giftbox.png" alt="" /></a>
        <div class="h6"></div>
        <div class="timebox">
        	<div class="text"><?php echo JText::_('DEAL_TIME_LEFT_TO_BUY');?>:</div>
        	<div class="text" id="cday"><b>00</b></div>
            <div class="text" id="chour"><b>00</b></div>
            <div class="text" id="cmin"><b>00</b></div>
          <div class="text" id="csec"><b>00</b></div>
            <img src="components/com_enmasse/theme/apollo_red/images/imgs/avatar4.png" alt="" />
        </div>
        <div class="h6"></div>
        <div class="rankbox">
        	<div class="apollo_title"><?php echo $deal->cur_sold_qty?> <?php echo JText::_('DEAL_BOUGHT');?></div>
<?php
	if(($deal->min_needed_qty - $deal->cur_sold_qty) <= 0)
	{
?>
		<div class="text"><?php echo JText::_('DEAL_IS_ON');?></div>
<?php 
	}
	else
	{
?>
            <div class="point">
            	<div class="leftpoint"></div>
                <div class="centerpoint">
                	<div class="leftvote"></div>
                    <div class="centervote" style="width:<?php echo 159 * ($deal->cur_sold_qty/$deal->min_needed_qty); ?>px;"></div>
                    <div class="rightvote"></div>
                </div>
                <div class="rightpoint"></div>
            </div>
            <div class="point">
            	<div class="leftnumber">0</div>
                <div class="rightnumber"><?php echo $deal->min_needed_qty;?></div>
            </div>
            <div class="text"><?php echo $deal->min_needed_qty - $deal->cur_sold_qty?> <?php echo JText::_('DEAL_NEED_MORE');?></div>
<?php 
	}
?>
		</div>
      </div>
    </div>
    <div class="rightcontent">
         <?php 
         if(!EnmasseHelper::is_urlEncoded($deal->pic_dir))
         {?>
         <img width="400" height="252" src="<?php echo $deal->pic_dir?>" alt="" />
         <?php }
         else{
           $imageUrlArr= unserialize(urldecode($deal->pic_dir));
           if(count($imageUrlArr) == 1)
           {
         ?>
    			 <img width="<?php echo $image_width;?>" height="<?php $image_height;?>" src="<?php echo str_replace("\\","/",$imageUrlArr[0]);?>" alt="" />
    	 <?php 
           }
    	   else 
    	   {
    	 ?>
		          <div class="slideshow" > 
				     <ul> 
				      <?php 
				       	for ($i=0; $i<count($imageUrlArr);$i++)
				       	{
				       		echo '<li><img src="'.str_replace("\\","/",$imageUrlArr[$i]).'" height="'.$image_height.'" width="'.$image_width.'" /></li>';
				       	}
				      ?>
				     </ul> 
				 </div> 
				 <div class="pages">
				     <?php 
				     
				     	for ($x=0 ; $x < count($imageUrlArr); $x++)
				     	{
				     		echo "<a style=\"cursor: pointer;\" class=\"change_link\" onclick=\"$('.slideshow').blinds_change($x)\">".($x+1)."</a>";
				     	}
				     ?>
				  </div>
		  <?php } }?>
        <div class="h7"></div>
        <div class="archives">
          <p>&nbsp;</p>
          <p><?php echo $deal->short_desc?></p>
        </div>
    </div>
  </div>
  <div class="h13"></div>
  <div class="descrip">
    	<div class="leftdescrip" id="leftcol">
        	<p><?php echo $deal->description?></p>
            <p class="titled"><?php echo JText::_('DEAL_HIGHLIGHT');?></p>
            <p><?php echo $deal->highlight?></p>
            <p class="titled"><?php echo JText::_('DEAL_TERM_CONDITION');?></p>
            <p><?php echo $deal->terms?></p>
        </div>

        <div class="rightdescript" id="rightcol">
        <?php if ($merchant != null){?>
            	<div class="titled"><?php echo $merchant->name;?> </div>
                <div class="information"><?php echo $merchant->address;?> (<?php echo $merchant->postal_code;?>)</div>
                <?php 
					if($merchant->telephone!="")
					{                
                ?>
                <div class="information h11"><?php echo JText::_('DEAL_CONTACT');?>: <?php echo $merchant->telephone;?></div>
				<?php 
					}
				?>
				<?php 
					if($merchant->web_url!="")
					{                
                ?>
                <div class="information"><a href="<?php echo $merchant->web_url;?>" target="_blank"><?php echo $merchant->web_url;?></a></div>
				<?php 
					}
				?>
                <br/>
                <?php 
					if(!($merchant->google_map_height==0 || $merchant->google_map_width==0))
					{                
                ?>
	                <div id="map_canvas" align="center" style="width:<?php echo $merchant->google_map_width;?>px; height:<?php echo $merchant->google_map_height;?>px"></div>
				<?php 
					}
        }
				?>
        </div>
    </div>  
</div>
<?php if((!isset($_COOKIE["location"]) || $_COOKIE["location"]== null)&& $this->locationPopup!=0) {?>
<div id="basic-modal-content">
<?php 
include 'location_listing.php';
?>
</div>
<?php } ?>
<script type="text/javascript">
			$(window).load(function () {
				// start the slideshow
				<?php if((!isset($_COOKIE["location"]) || $_COOKIE["location"]== null)&& $this->locationPopup!=0)
					
				    echo "$('#basic-modal-content').modal();"
				    
				 ?>
				$('.slideshow').blinds();
 })
</script>
</body>

<!--Time Count Down Script--> <script language="JavaScript">
TargetDate = "<?php echo date('Y/m/d H:i:s', strtotime($deal->end_at));?>";
CountActive = true;
CountStepper = -1;
LeadingZero = true;

function calcage(secs, num1, num2) {
  s = ((Math.floor(secs/num1))%num2).toString();
  if (LeadingZero && s.length < 2)
    s = "0" + s;
  return "<b>" + s + "</b>";
}

function CountBack(secs) {
  if (secs < 0) {
    window.location.reload();
    return;
  }
  document.getElementById("cday").innerHTML = calcage(secs,86400,100000)+" <b><?php echo JTEXT::_('DAY');?></b>";
  document.getElementById("chour").innerHTML = calcage(secs,3600,24)+" <b><?php echo JTEXT::_('HOUR');?></b>";
  document.getElementById("cmin").innerHTML = calcage(secs,60,60)+" <b><?php echo JTEXT::_('MIN');?></b>";
  document.getElementById("csec").innerHTML = calcage(secs,1,60)+" <b><?php echo JTEXT::_('SECOND');?></b>";

  if (CountActive)
    setTimeout("CountBack(" + (secs+CountStepper) + ")", SetTimeOutPeriod);
}

if (typeof(TargetDate)=="undefined")
  TargetDate = "12/31/2020 5:00 AM";
if (typeof(CountActive)=="undefined")
  CountActive = true;
if (typeof(FinishMessage)=="undefined")
  FinishMessage = "";
if (typeof(CountStepper)!="number")
  CountStepper = -1;
if (typeof(LeadingZero)=="undefined")
  LeadingZero = true;


CountStepper = Math.ceil(CountStepper);
if (CountStepper == 0)
  CountActive = false;
var SetTimeOutPeriod = (Math.abs(CountStepper)-1)*1000 + 990;

var dthen = new Date(TargetDate);
var dnow = new Date();

if(CountStepper>0)
  ddiff = new Date(dnow-dthen);
else
  ddiff = new Date(dthen-dnow);

gsecs = Math.floor(ddiff.valueOf()/1000);

CountBack(gsecs);
</script>