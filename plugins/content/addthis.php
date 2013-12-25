<?php
/* 
 * +--------------------------------------------------------------------------+
 * | Copyright (c) 2010 Add This, LLC                                         |
 * +--------------------------------------------------------------------------+
 * | This program is free software; you can redistribute it and/or modify     |
 * | it under the terms of the GNU General Public License as published by     |
 * | the Free Software Foundation; either version 3 of the License, or        |
 * | (at your option) any later version.                                      |
 * |                                                                          |
 * | This program is distributed in the hope that it will be useful,          |
 * | but WITHOUT ANY WARRANTY; without even the implied warranty of           |
 * | MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the            |
 * | GNU General Public License for more details.                             |
 * |                                                                          |
 * | You should have received a copy of the GNU General Public License        |
 * | along with this program.  If not, see <http://www.gnu.org/licenses/>.    |
 * +--------------------------------------------------------------------------+
 */
  
// no direct access
defined( '_JEXEC' ) or die( 'Restricted access' );

jimport('joomla.plugin.plugin');

/**
 * plgContentAddThis
 * 
 * Creates AddThis sharing button with each and every posts.
 * Reads the user settings and creates the button accordingly.
 */  
class plgContentAddThis extends JPlugin {
  
   /**
    * Constructor
    * Loads the plugin settings and assigns them to class variables
    * 
    * @param object $subject
    */
    public function __construct(&$subject)
    {
        parent::__construct($subject);
  
        // Loading plugin parameters
        $this->_plugin = JPluginHelper::getPlugin('content', 'addthis');
        $this->_params = new JParameter($this->_plugin->params);
        
        //Properties holding plugin settings
        $this->_profile_id                  = $this->_params->get('profile_id');
        $this->_button_style                = $this->_params->get('button_style');
        $this->_custom_url                  = $this->_params->get('custom_url');
        $this->_toolbox_services            = $this->_params->get('toolbox_services');
        $this->_icon_dimension				= $this->_params->get('icon_dimension');
        $this->_addthis_brand               = $this->_params->get('addthis_brand');
        $this->_addthis_header_color        = $this->_params->get('addthis_header_color');
        $this->_addthis_header_background   = $this->_params->get('addthis_header_background');
        $this->_addthis_services_compact    = $this->_params->get('addthis_services_compact');
        $this->_addthis_services_exclude    = $this->_params->get('addthis_services_exclude');
        $this->_addthis_services_expanded   = $this->_params->get('addthis_services_expanded');
        $this->_addthis_services_custom     = $this->_params->get('addthis_services_custom');
        $this->_addthis_offset_top          = $this->_params->get('addthis_offset_top');
        $this->_addthis_offset_left         = $this->_params->get('addthis_offset_left');
        $this->_addthis_hover_delay         = $this->_params->get('addthis_hover_delay');
        $this->_addthis_click               = $this->_params->get('addthis_click');
        $this->_addthis_hover_direction     = $this->_params->get('addthis_hover_direction');
        $this->_addthis_use_addressbook     = $this->_params->get('addthis_use_addressbook');
        $this->_addthis_508_compliant       = $this->_params->get('addthis_508_compliant');
        $this->_addthis_data_track_clickback= $this->_params->get('addthis_data_track_clickback');
        $this->_addthis_hide_embed          = $this->_params->get('addthis_hide_embed');
        $this->_addthis_language            = $this->_params->get('addthis_language');
		$this->_position                    = $this->_params->get('position');
		$this->_show_frontpage              = $this->_params->get('show_frontpage');
		$this->_toolbox_more_services_mode  = $this->_params->get('toolbox_more_services_mode');
		$this->_addthis_use_css             = $this->_params->get('addthis_use_css');
		$this->_addthis_ga_tracker          = $this->_params->get('addthis_ga_tracker');

    }
      
    /**
     * onPrepareContent
     *
     * Creates configuration script and addthis button code while content is being prepared
     * 
     * @param reference $article
     * @param reference $params
     * @param integer $limitstart
     * @return void
     * @see http://docs.joomla.org/Reference:Content_Events_for_Plugin_System#5.5.2_onPrepareContent
     */
    public function onPrepareContent(&$article, &$params, $limitstart)
    {
    	//Creating div elements for AddThis
		$outputValue = " <div class='joomla_add_this'>";
		$outputValue .= "<!-- AddThis Button BEGIN -->" . PHP_EOL;
		
		//Creates addthis configuration script
	    $outputValue .= "<script type='text/javascript'>\r\n";
	    $outputValue .= "var addthis_product = 'jlp-1.2';\r\n";
		$outputValue .="var addthis_config =\r\n{";
		$configValue = "";
		$configValue .= $this->getProfileId();
		$configValue .= $this->getAddThisBrand();
		$configValue .= $this->getAddThisHeaderColor();
		$configValue .= $this->getAddThisHeaderBackground();
		$configValue .= $this->getAddThisServicesCompact();
		$configValue .= $this->getAddThisOffsetTop();
		$configValue .= $this->getAddThisOffsetLeft();
		$configValue .= $this->getAddThisHoverDelay();
		$configValue .= $this->getAddThisLanguage();
		$configValue .= $this->getAddThisHideEmbed(); 
		$configValue .= $this->getAddThisServiceExclude();
		$configValue .= $this->getAddThisServicesExpanded(); 
		$configValue .= $this->getAddThisServicesCustom();
		$configValue .= $this->getAddThisClick();
		$configValue .= $this->getAddThisHoverDirection();
		$configValue .= $this->getAddThisUseAddressBook();
		$configValue .= $this->getAddThis508Compliant();
		$configValue .= $this->getAddThisDataTrackClickback();
        $configValue .= $this->getAddThisUseCss();
        $configValue .= $this->getAddThisGATracker();
            
    	//Removing the last comma and end of line characters
    	if("" != trim($configValue))
		{
		  	$outputValue .= implode( ',', explode( ',', $configValue, -1 ));
		}
		$outputValue .= "}</script>". PHP_EOL;
        
        //Creates the button code depending on the button style chosen
        $buttonValue = "";
        
        //Generates the button code for toolbox
        if("toolbox"  === $this->_button_style)
        {
        	 $buttonValue .= $this->getToolboxScript($this->_toolbox_services, $article);       	
        }
        //Generates button code for rest of the button styles
        else
		{
			$buttonValue .= "<a  href='http://www.addthis.com/bookmark.php' ".
				" onmouseover=\"return addthis_open(this,'', '". urldecode($this->getArticleUrl($article))."', '".$this->escapeText($article->title)."' )\" ".
			" onmouseout='addthis_close();' onclick='return addthis_sendto();'>";
		    $buttonValue .= "<img src='";
		    //Custom image for button
			if ("custom" === trim($this->_button_style))
	    	{
		        if ('' == trim($this->_custom_url))
		        {
		            $buttonValue .= "http://s7.addthis.com/static/btn/v2/" .  $this->getButtonImage('lg-share',$this->_addthis_language);
		        }
	        	else $buttonValue .= $this->_custom_url;
	    	}
	    	//Pointing to addthis button images
	    	else
		    {
				$buttonValue .= "http://s7.addthis.com/static/btn/v2/" . $this->getButtonImage($this->_button_style,$this->_addthis_language);
			}
			$buttonValue .= "' border='0' alt='AddThis Social Bookmark Button' />";
			$buttonValue .= "</a>". PHP_EOL;
		}
		$outputValue .= $buttonValue;
		
		//Adding AddThis script to the page
		$outputValue .= "<script type='text/javascript' src='http://s7.addthis.com/js/250/addthis_widget.js'></script>\r\n";
		$outputValue .= "<!-- AddThis Button END -->". PHP_EOL;
		$outputValue .= "</div>";
        
        //Regular expression for finding the custom tag which disables the addthisbutton in the article.
        $switchregex = "#{addthis (on|off)}#s";
        
        //Gets frontpage
        $menu =& JSite::getMenu();
        //Sets the visibility of AddThis button in frontpage depending on user's settings
        if(($menu->getActive() == $menu->getDefault()) && ($this->_show_frontpage == 0)) {
          $hide_frontpage = true;
          $outputValue = "";
        }

        //Ensuring the custom tag is not present in the article text.
        if (strpos($article->text, '{addthis off}') === false ) {
           //Positioning button according to the position chosen
           if ($this->_position == 'top')
              $article->text = $outputValue . $article->text;
           else
              $article->text = $article->text.$outputValue;
           }
        else {
           //Removing the custom tag from the final output.
           $article->text = preg_replace($switchregex, '', $article->text);
        }
    }
	
	/**
     * getToolboxScript
     * 
     * Preparing the toolbox script
     * 
     * @param string $services - comma seperated list of services
     * @param object $article
     * @return string - Returns the script for rendering the selected services in toolbox
    */
    private function getToolboxScript($services, $article)
    {
    	//Deciding the toobox icon dimensions
    	$dimensionStyle = $this->_icon_dimension == "16" ? '' : ' addthis_32x32_style';
    	//Toolbox main div element, holds the url and title for sharing
    	$toolboxScript  = "<div class='addthis_toolbox" . $dimensionStyle . " addthis_default_style' addthis:url='". urldecode($this->getArticleUrl($article)) . "' addthis:title='" . htmlspecialchars($article->title, ENT_QUOTES) . "'>";
    	$serviceList = explode(",", $services);
    	//Adding the services one by one
    	for ( $i = 0, $max_count = sizeof( $serviceList ); $i < $max_count; $i++ )
    	{
			$toolboxScript .= "<a class='addthis_button_" . $serviceList[$i] . "'></a>";	
		}
		//Adding more services button in user selected mode - (Expanded | Compact)
		$toolboxScript .= "<a class='addthis_button_" . $this->_toolbox_more_services_mode ."'>Share</a>";
		$toolboxScript .= "</div>";
		return $toolboxScript;
    }
    
    /**
    * getArticleUrl
    *
    * Gets the static url for the article
    * 
    * @param object $article - Joomla article object
    **/
    private function getArticleUrl(&$article)
    {
        if (!is_null($article)) 
        {
            require_once( JPATH_SITE . DS . 'components' . DS . 'com_content' . DS . 'helpers' . DS . 'route.php');
            
            $uri = &JURI::getInstance();
            $base = $uri->toString(array('scheme', 'host', 'port'));
            $url = (isset($article->slug) && isset($article->catslug) && isset($article->sectionid)) ? JRoute::_(ContentHelperRoute::getArticleRoute($article->slug, $article->catslug, $article->sectionid)) : '';
            return JRoute::_($base . $url, true, 0);
        }
    }


	/**
     * escapeText
     * 
     * Escapes single quotes 
     * 
     * @param string $text - string to be escaped
     */
    private function escapeText($text)
    {
    	$cleanedText = htmlspecialchars($text);
    	return str_replace("'", "\'", $cleanedText);
    }

    /**
     * getButtonImage
     *
     * This is used for preparing the image button name.
     * 
     * @param string $name - Button style of addthis button selected
     * @param string $language - The language selected for addthis button
     * @return string returns the button image file name
     */
    private function getButtonImage($name, $language)
    {
       if ($name == "sm-plus") {
            $buttonImage = $name . '.gif';
        } elseif ($language != 'en') {
            if ($name == 'lg-share' || $name == 'lg-bookmark' || $name == 'lg-addthis')
            {
                $buttonImage = 'lg-share-' . $language . '.gif';
            }
            elseif($name == 'sm-share' || $name == 'sm-bookmark')
            {
                $buttonImage = 'sm-share-' . $language . '.gif';
            }
        } else {
            $buttonImage = $name . '-' . $language . '.gif';
        }
       return $buttonImage;
    }
    
    /**
     * Gets the AddThis publisher id
     * @return string 
     */
    private function getProfileId()
    {
    	return ("Your Profile ID" != trim($this->_profile_id) && trim($this->_profile_id) != "") ? "pubid : '" . trim($this->_profile_id) . "'," . PHP_EOL : "";
    }
    
    /**
     * Gets the AddThis brand
     * @return string
     */
    private function getAddThisBrand()
    {
    	return ("" != trim($this->_addthis_brand)) ? "ui_cobrand : '" . trim($this->_addthis_brand) . "'," . PHP_EOL : "";
    }
    
    /**
     * Gets the AddThis header color
     * @return string
     */
    private function getAddThisHeaderColor()
    {
    	return ("" != trim($this->_addthis_header_color)) ? "ui_header_color : '" . trim($this->_addthis_header_color) . "'," . PHP_EOL : "";
    }
    
    /**
     * Gets the AddThis header background
     * @return string
     */
    private function getAddThisHeaderBackground()
    {
    	return ("" != trim($this->_addthis_header_background)) ? "ui_header_background : '" . trim($this->_addthis_header_background) . "'," . PHP_EOL : "";
    }
    
    /**
     * Gets the services to show in the AddThis compact menu
     * @return string
     */
    private function getAddThisServicesCompact()
    {
    	return ("" != trim($this->_addthis_services_compact)) ? "services_compact : '" . trim($this->_addthis_services_compact) . "'," . PHP_EOL : "";
    }
    
    /**
     * Gets the top offset value for AddThis compact menu
     * @return string
     */
    private function getAddThisOffsetTop()
    {
    	return (0 != intval(trim($this->_addthis_offset_top))) ? "ui_offset_top : '" . $this->_addthis_offset_top . "'," . PHP_EOL : "";
    }
    
    /**
     * Gets the left offset value for AddThis compact menu
     * @return string
     */
    private function getAddThisOffsetLeft()
    {
    	return (0 != intval(trim($this->_addthis_offset_left))) ? "ui_offset_left : '" . $this->_addthis_offset_left . "'," . PHP_EOL : "";
    }
    
    /**
     * Gets the hover delay for AddThis compact menu
     * @return string
     */
    private function getAddThisHoverDelay()
    {
    	return (intval(trim($this->_addthis_hover_delay)) > 0) ? "ui_delay : '" . $this->_addthis_hover_delay . "'," . PHP_EOL : "";
    }
    
    /**
     * Gets the AddThis menu language
     * @return string
     */
    private function getAddThisLanguage()
    {
    	return ("" != trim($this->_addthis_language)) ? "ui_language : '" . $this->_addthis_language . "'," . PHP_EOL : "";
    }
    
    /**
     * Gets the AddThis hide embed config value
     * @return string
     */
    private function getAddThisHideEmbed()
    {
    	return ('0' == trim($this->_addthis_hide_embed)) ? "ui_hide_embed : false,". PHP_EOL : ""; 
    }
    
    /**
     * Gets the services to be excluded from the AddThis menu
     * @return string
     */
    private function getAddThisServiceExclude()
    {
    	return ("" != trim($this->_addthis_services_exclude)) ? "services_exclude : '" . $this->_addthis_services_exclude . "'," . PHP_EOL : "";
    }
    
    /**
     * Gets the services to be shown in expanded menu
     * @return string
     */
    private function getAddThisServicesExpanded()
    {
    	return ("" != trim($this->_addthis_services_expanded)) ? "services_expanded : '" . $this->_addthis_services_expanded ."'," . PHP_EOL : "";
    }
    
    /**
     * Gets the custom service to show in the menu
     * @return string
     */
    private function getAddThisServicesCustom()
    {
    	return ("" != trim($this->_addthis_services_custom)) ? "services_custom : " . $this->_addthis_services_custom . "," . PHP_EOL : "";
    }
    
    /**
     * Gets the UI click settings of AddThis button
     * @return string
     */
    private function getAddThisClick()
    {
    	return ('1' == trim($this->_addthis_click)) ? "ui_click : true,". PHP_EOL : "";
    }
    
    /**
     * Gets the hover direction of AddThis compact menu
     * @return string 
     */
    private function getAddThisHoverDirection()
    {
    	return ('0' != trim($this->_addthis_hover_direction) && ('' != (trim($this->_addthis_hover_direction)))) ? "ui_hover_direction : " . $this->_addthis_hover_direction ."," . PHP_EOL : "";
    }
    
    /**
     * Gets the address book visibility settings of AddThis menu
     * @return string
     */
    private function getAddThisUseAddressBook()
    {
    	return ('1' == trim($this->_addthis_use_addressbook)) ? "ui_use_addressbook : true,". PHP_EOL : "";
    }
    
    /**
     * Gets the 508 compliat settings for AddThis menu
     * @return string
     */
    private function getAddThis508Compliant()
    {
    	return ('1' == trim($this->_addthis_508_compliant)) ? "ui_508_compliant : true,". PHP_EOL : "";
    }
    
    /**
     * Gets the data track linkback settings of AddThis menu
     * @return string
     */
    private function getAddThisDataTrackClickback()
    {
    	return ('1' == trim($this->_addthis_data_track_clickback)) ? "data_track_clickback : true,". PHP_EOL : "";
    }
    
    /**
     * Gets the use css settings of AddThis menu
     * @return string
     */
    private function getAddThisUseCss()
    {
    	return ('0' == trim($this->_addthis_use_css)) ? "ui_use_css : false,". PHP_EOL : "";
    }
    
    /**
     * Gets the ga tracker object specified by the user
     * @return string
     */
    private function getAddThisGATracker()
    {
    	return ("" != trim($this->_addthis_ga_tracker)) ? "data_ga_tracker : " . $this->_addthis_ga_tracker . "," . PHP_EOL : "";
    }
}
  