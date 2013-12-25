<?php
JHTML::_('behavior.mootools');
//-------------------
// to re-define the link of server root

$temp_uri_arr =explode ('/',$_SERVER['REQUEST_URI'])  ;
$link_server = "";
 for($count = 0; $count < count($temp_uri_arr); $count++)
 {
   if($temp_uri_arr[$count] == 'administrator' )
 	{
 		break ;
 	}
 	if($temp_uri_arr[$count]!= '')
 	{
 	$link_server.= '/';
 	$link_server.=$temp_uri_arr[$count];	
 	}
 }

?>
<head>
	<style>
	#minwidth-body {width:100%}
	#border-top{ display:none;}
	#header-box{ display:none;}
	#content-box{ border-left:none ; border-right:none;}
	#border-bottom{display:none;}
	</style>
	<?php 
        $version = new JVersion;
        $joomla = $version->getShortVersion();
        //echo substr($joomla,0,3);
        if(substr($joomla,0,3) == '1.6'){
    ?>
        <script type="text/javascript">
    	    var limit=0;
    		function insertURL(val)
    		{
    			this.parent.document.getElementById(val).value = document.getElementById('image-dir').value;
                window.parent.SqueezeBox.close();
    		}
    		function addInput(divName){
    
    			      if(limit < 4)
    			      {
    				      limit+=1;
    			          var newdiv = document.createElement('div');
    			          newdiv.innerHTML = " <br><input type='file' name='Filedata[]'>";
    			          document.getElementById(divName).appendChild(newdiv);
    			      }
    			      else if(limit == 4)
    			      {
    			    	  limit+=1;
    			    	  var newdiv = document.createElement('div');
    			          newdiv.innerHTML = "<b>Limmited Images Upload !</b>";
    			          document.getElementById(divName).appendChild(newdiv);
    				  }
    		     
    		}
    	</script>
    <?php
    	}else{
    ?>
        <script type="text/javascript">
    	    var limit=0;
    		function insertURL(val)
    		{
    			this.parent.document.getElementById(val).value = document.getElementById('image-dir').value;
    			window.parent.document.getElementById( 'sbox-window' ).close();
    		}
    		function addInput(divName){
    
    			      if(limit < 4)
    			      {
    				      limit+=1;
    			          var newdiv = document.createElement('div');
    			          newdiv.innerHTML = " <br><input type='file' name='Filedata[]'>";
    			          document.getElementById(divName).appendChild(newdiv);
    			      }
    			      else if(limit == 4)
    			      {
    			    	  limit+=1;
    			    	  var newdiv = document.createElement('div');
    			          newdiv.innerHTML = "<b>Limmited Images Upload !</b>";
    			          document.getElementById(divName).appendChild(newdiv);
    				  }
    		     
    		}
    	</script>
    <?php
    	}
    ?>
</head>
<body>
	<form action="<?php echo JURI::base(); ?>index.php?option=com_enmasse&controller=uploader&task=upload" name ="uploadForm" id="uploadForm" method="post" enctype="multipart/form-data">
						<fieldset>
							<legend><?php echo JText::_( 'UPLOAD_FILE_TITLE' ); ?> </legend>
							
							<fieldset class="actions">
							    <div id="dvFile">
									<input type="file" name="Filedata[]" />
								</div>
								<?php if($this->parent == 'deal'){?>
								<input type="button" value="<?php echo JTEXT::_('ADD_MORE_IMG');?>" onclick="addInput('dvFile');" />
								<?php }?>
								<input type="submit" id="file-upload-submit" value="<?php echo JText::_('UPLOAD_FILE_BUTTON'); ?>"/>
								<span id="upload-clear"></span>
							</fieldset>
							
							<ul class="upload-queue" id="upload-queue">
								<li style="display: none" />
							</ul>
							
						</fieldset>
						
						<input type="hidden" name="return-url" value="<?php echo base64_encode('index.php?option=com_enmasse&controller=uploader&task=display'); ?>" />
						<input type="hidden" name="parentId" value="<?php echo $this->parentId;?>" />
						<input type="hidden" name="parent" value="<?php echo $this->parent;?>" />
						<input type="hidden" name="couponbg" value="<?php echo $this->couponbg;?>" />
						<fieldset>
							<legend><?php echo JText::_( 'Review' ); ?> </legend>
							<?php 
							 if(!empty($this->imageUrl))
							 {
							 	$imagePathArr = unserialize($this->imageUrl);
							 	for($i=0 ; $i< count($imagePathArr); $i++)
							 	{
							 	    
								 	$link='http://';
								 	$link.= $_SERVER["SERVER_NAME"].$link_server.DS;
								 	$link.=$imagePathArr[$i];
								 	$link =str_replace("\\","/",$link);
								 	$imageLinkArr[$i] = $link;
							 	}
								?>
								
							 	<input type="text"  size ="80" name="image-dir" id='image-dir' value="<?php echo urlencode($this->imageUrl);?>"/>
							 	<input type="button" onclick="insertURL('<?php echo $this->parentId; ?>');" value="<?php echo JTEXT::_('UPLOADER_INSERT_BUTTON');?>" />
							 	<br><br>
								<?php 
							 	for($z=0 ; $z < count($imageLinkArr); $z++)
							 	{
							 		echo '<img width="200" hieght="100" src="'.$imageLinkArr[$z].'"/>&nbsp;';
							 		if( $z%2 == 1)
							 		{echo '<br><br>';}
							 	}
							 	
							 	
							 }
							?>
						</fieldset>
	
	</form>
</body>
