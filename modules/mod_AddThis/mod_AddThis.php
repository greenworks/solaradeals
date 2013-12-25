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
	defined('_JEXEC') or die('Restricted access');
	
	//Properties holding plugin settings
	$profile_id                  = $params->get('profile_id');
    $button_style                = $params->get('button_style');
    $custom_url                  = $params->get('custom_url');
    $toolbox_services            = $params->get('toolbox_services');
    $icon_dimension				 = $params->get('icon_dimension');
    $addthis_language            = $params->get('addthis_language');
    $addthis_brand               = $params->get('addthis_brand');
    $addthis_header_color        = $params->get('addthis_header_color');
    $addthis_header_background   = $params->get('addthis_header_background');
    $addthis_options             = $params->get('addthis_options');
    $addthis_services_exclude    = $params->get('addthis_services_exclude');
    $addthis_services_expanded   = $params->get('addthis_services_expanded');
    $addthis_services_custom     = $params->get('addthis_services_custom');
    $addthis_offset_top          = $params->get('addthis_offset_top');
    $addthis_offset_left         = $params->get('addthis_offset_left');
    $addthis_hover_delay         = $params->get('addthis_hover_delay');
    $addthis_click               = $params->get('addthis_click');
    $addthis_hover_direction     = $params->get('addthis_hover_direction');
    $addthis_use_addressbook     = $params->get('addthis_use_addressbook');
    $addthis_508_compliant       = $params->get('addthis_508_compliant');
    $addthis_data_track_clickback= $params->get('addthis_data_track_clickback');
    $addthis_hide_embed          = $params->get('addthis_hide_embed');
    $toolbox_more_services_mode  = $params->get('toolbox_more_services_mode');
	$addthis_use_css             = $params->get('addthis_use_css');
	$addthis_ga_tracker          = $params->get('addthis_ga_tracker');
		
	//Creating div elements for AddThis
	$outputValue = " <div class='joomla_add_this'>";
	$outputValue .= "<!-- AddThis Button BEGIN -->\r\n";
	
	//AddThis configuration script creation
	$outputValue .= "<script type='text/javascript'>\r\n";
	$outputValue .= "var addthis_product = 'jlp-1.2';\r\n";
	$outputValue .= "var addthis_config =\r\n{";
	
	//Creates addthis configuration script
	$configValue = "";
	$configValue .= getProfileId($profile_id);
	$configValue .= getAddThisBrand($addthis_brand);
	$configValue .= getAddThisHeaderColor($addthis_header_color);
	$configValue .= getAddThisHeaderBackground($addthis_header_background);
	$configValue .= getAddThisServicesCompact($addthis_options);
	$configValue .= getAddThisOffsetTop($addthis_offset_top);
	$configValue .= getAddThisOffsetLeft($addthis_offset_left);
	$configValue .= getAddThisHoverDelay($addthis_hover_delay);
	$configValue .= getAddThisLanguage($addthis_language);
	$configValue .= getAddThisHideEmbed($addthis_hide_embed); 
	$configValue .= getAddThisServiceExclude($addthis_services_exclude);
	$configValue .= getAddThisServicesExpanded($addthis_services_expanded); 
	$configValue .= getAddThisServicesCustom($addthis_services_custom);
	$configValue .= getAddThisClick($addthis_click);
	$configValue .= getAddThisHoverDirection($addthis_hover_direction);
	$configValue .= getAddThisUseAddressBook($addthis_use_addressbook);
	$configValue .= getAddThis508Compliant($addthis_508_compliant);
	$configValue .= getAddThisDataTrackClickback($addthis_data_track_clickback);
	$configValue .= getAddThisUseCss($addthis_use_css);
	$configValue .= getAddThisGATracker($addthis_ga_tracker);
	
	//Removing the last comma and end of line characters
	if(trim($configValue) != "")
	{
	   	$outputValue .= implode( ',', explode( ',', $configValue, -1 ));
	}
	$outputValue .= "}</script>\r\n";
	
	/**
     * getToolboxScript
     * 
     * Used for preparing the toolbox script
     * 
     * @param string $services - comma seperated list of services
     * @param string $dimension - Icon dimensions (16 | 32)
     * @param string $mode - Toolbox mode (expanded | compact)
     * @return string - Returns the script for rendering the selected services
    */
    function getToolboxScript($services, $dimension, $mode)
    {
    	$dimensionStyle = ($dimension == "16") ? '' : ' addthis_32x32_style';
    	$toolboxScript  = "<div class='addthis_toolbox" . $dimensionStyle . " addthis_default_style'>";
    	$serviceList = explode(",", $services);
    	for ( $i = 0, $max_count = sizeof( $serviceList ); $i < $max_count; $i++ )
    	{
			$toolboxScript .= "<a class='addthis_button_" . $serviceList[$i] . "'></a>";	
		}
		$toolboxScript .= "<a class='addthis_button_" . $mode ."'>Share</a>";
		$toolboxScript .= "</div>";
		return $toolboxScript;
    }
    
	//Creates the button code depending on the button style chosen
    $buttonValue = "";
    
    //Generates the button code for toolbox
    if($button_style === "toolbox")
    {
       	 $buttonValue .= getToolboxScript($toolbox_services, $icon_dimension, $toolbox_more_services_mode);       	
    }
    //Generates button code for rest of the button styles
    else
	{
		$buttonValue .= "<a  href='http://www.addthis.com/bookmark.php' onmouseover=\"return addthis_open(this, '', '[URL]', '[TITLE]'); \"   onmouseout='addthis_close();' onclick='return addthis_sendto();'>";
	    $buttonValue .= "<img src='";
	    //Custom image for button
		if (trim($button_style) === "custom")
	   	{
	        if (trim($custom_url) == '')
		    {
		           $buttonValue .= "http://s7.addthis.com/static/btn/v2/" .  getButtonImage('lg-share',$addthis_language);
	        }
	       	else $buttonValue .= $custom_url;
	   }
	   //Pointing to addthis button images
	   else
	   {
			$buttonValue .= "http://s7.addthis.com/static/btn/v2/" . getButtonImage($button_style,$addthis_language);
		}
		$buttonValue .= "' border='0' alt='AddThis Social Bookmark Button' />";
		$buttonValue .= "</a>\r\n";
	}
        
	//Adding AddThis script to the page
	$outputValue .= $buttonValue;
	$outputValue .= "<script type='text/javascript' src='http://s7.addthis.com/js/250/addthis_widget.js'></script>\r\n";
	$outputValue .= "<!-- AddThis Button END -->\r\n";
	$outputValue .= "</div>";
	
    echo $outputValue;
    
    /**
     * getButtonImage
     *
     * This is used for preparing the image button name.
     * 
     * @param string $name - Button style of addthis button selected.
     * @param string $language - The language selected for addthis button.
     * @return string returns the button image file name.
     */
    function getButtonImage($name, $language)
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
    function getProfileId($pub)
    {
    	return ("Your Profile ID" != trim($pub) && trim($pub) != "") ? "pubid : '" . trim($pub) . "'," . PHP_EOL : "";
    }
    
    /**
     * Gets the AddThis brand
     * @return string
     */
    function getAddThisBrand($brand)
    {
    	return ("" != trim($brand)) ? "ui_cobrand : '" . trim($brand) . "'," . PHP_EOL : "";
    }
    
    /**
     * Gets the AddThis header color
     * @return string
     */
    function getAddThisHeaderColor($color)
    {
    	return ("" != trim($color)) ? "ui_header_color : '" . trim($color) . "'," . PHP_EOL : "";
    }
    
    /**
     * Gets the AddThis header background
     * @return string
     */
    function getAddThisHeaderBackground($background)
    {
    	return ("" != trim($background)) ? "ui_header_background : '" . trim($background) . "'," . PHP_EOL : "";
    }
    
    /**
     * Gets the services to show in the AddThis compact menu
     * @return string
     */
    function getAddThisServicesCompact($services)
    {
    	return ("" != trim($services)) ? "services_compact : '" . trim($services) . "'," . PHP_EOL : "";
    }
    
    /**
     * Gets the top offset value for AddThis compact menu
     * @return string
     */
    function getAddThisOffsetTop($offsetValue)
    {
    	return (0 != intval(trim($offsetValue))) ? "ui_offset_top : '" . $offsetValue . "'," . PHP_EOL : "";
    }
    
    /**
     * Gets the left offset value for AddThis compact menu
     * @return string
     */
    function getAddThisOffsetLeft($offsetValue)
    {
    	return (0 != intval(trim($offsetValue))) ? "ui_offset_left : '" . $offsetValue . "'," . PHP_EOL : "";
    }
    
    /**
     * Gets the hover delay for AddThis compact menu
     * @return string
     */
    function getAddThisHoverDelay($delay)
    {
    	return (intval(trim($delay)) > 0) ? "ui_delay : '" . $delay . "'," . PHP_EOL : "";
    }
    
    /**
     * Gets the AddThis menu language
     * @return string
     */
    function getAddThisLanguage($language)
    {
    	return ("" != trim($language)) ? "ui_language : '" . $language . "'," . PHP_EOL : "";
    }
    
    /**
     * Gets the AddThis hide embed config value
     * @return string
     */
    function getAddThisHideEmbed($embedSetting)
    {
    	return ('0' == trim($embedSetting)) ? "ui_hide_embed : false,". PHP_EOL : ""; 
    }
    
    /**
     * Gets the services to be excluded from the AddThis menu
     * @return string
     */
    function getAddThisServiceExclude($services)
    {
    	return ("" != trim($services)) ? "services_exclude : '" . $services . "'," . PHP_EOL : "";
    }
    
    /**
     * Gets the services to be shown in expanded menu
     * @return string
     */
    function getAddThisServicesExpanded($services)
    {
    	return ("" != trim($services)) ? "services_expanded : '" . $services ."'," . PHP_EOL : "";
    }
    
    /**
     * Gets the custom service to show in the menu
     * @return string
     */
    function getAddThisServicesCustom($service)
    {
    	return ("" != trim($service)) ? "services_custom : " . $service . "," . PHP_EOL : "";
    }
    
    /**
     * Gets the UI click settings of AddThis button
     * @return string
     */
    function getAddThisClick($clickSettings)
    {
    	return ('1' == trim($clickSettings)) ? "ui_click : true,". PHP_EOL : "";
    }
    
    /**
     * Gets the hover direction of AddThis compact menu
     * @return string 
     */
    function getAddThisHoverDirection($direction)
    {
    	return ('0' != trim($direction) && ('' != (trim($direction)))) ? "ui_hover_direction : " . $direction ."," . PHP_EOL : "";
    }
    
    /**
     * Gets the address book visibility settings of AddThis menu
     * @return string
     */
    function getAddThisUseAddressBook($addressbookSettings)
    {
    	return ('1' == trim($addressbookSettings)) ? "ui_use_addressbook : true,". PHP_EOL : "";
    }
    
    /**
     * Gets the 508 compliat settings for AddThis menu
     * @return string
     */
    function getAddThis508Compliant($compliantSettings)
    {
    	return ('1' == trim($compliantSettings)) ? "ui_508_compliant : true,". PHP_EOL : "";
    }
    
    /**
     * Gets the data track linkback settings of AddThis menu
     * @return string
     */
    function getAddThisDataTrackClickback($clickbackSettings)
    {
    	return ('1' == trim($clickbackSettings)) ? "data_track_clickback : true,". PHP_EOL : "";
    }
    
    /**
     * Gets the use css settings of AddThis menu
     * @return string
     */
    function getAddThisUseCss($useCssSettings)
    {
    	return ('0' == trim($useCssSettings)) ? "ui_use_css : false,". PHP_EOL : "";
    }
    
    /**
     * Gets the ga tracker object specified by the user
     * @return string
     */
    function getAddThisGATracker($gaTrackerSettings)
    {
    	return ("" != trim($gaTrackerSettings)) ? "data_ga_tracker : " . $gaTrackerSettings . "," . PHP_EOL : "";
    }

