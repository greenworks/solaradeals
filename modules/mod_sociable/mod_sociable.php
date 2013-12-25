<?php
/**
* @version		v 1.0.1
* @author		Andrew Sharman
* @filesource	http://www.udjamaflip.com
* @copyright	Copyright (C) 2009 - 2009 Open Source Matters. All rights reserved.
* @license		GNU/GPL, see Joomla's LICENSE.php
* Udjamaflip's Sociable Module is openSource and as such is free for all to use, modify, and redistribute.
* Please keep the above information intact, as without a user base development cannot continue. Thanks.
*/

// no direct access
defined( '_JEXEC' ) or die( 'Restricted access' );

// Include the syndicate functions only once
require_once (dirname(__FILE__).DS.'helper.php');

//concatonaes requested url with the base url for the site
$uri = JURI::base() . substr($_SERVER['REQUEST_URI'],1);

//fetchs the title of the current page (some sociable need this)
global $mainframe;
$title = $mainframe->getPageTitle();

//Sets up the helper class, sending over basic needed info.
$sociable = new sociable($uri,$params->get('icon_size'),$params->get('link_action'), $title, $params);

//includes the html module incase needed
$doc =& JFactory::getDocument();

//if the user wants link to open in greybox, it uses this.
if ($params->get('link_action') == 1)
{
	$gb .= '<script type="text/javascript">';
	$gb .= '	var GB_ROOT_DIR = "'.JURI::base().'modules/mod_sociable/greybox/";';
	$gb .= '</script>';

	$gb .= '<script type="text/javascript" src="'.JURI::base().'modules/mod_sociable/greybox/AJS.js"></script>';

	$gb .= '<script type="text/javascript" src="'.JURI::base().'modules/mod_sociable/greybox/AJS_fx.js"></script>';
	$gb .= '<script type="text/javascript" src="'.JURI::base().'modules/mod_sociable/greybox/gb_scripts.js"></script>';
	$gb .= '<link href="'.JURI::base().'modules/mod_sociable/greybox/gb_styles.css" rel="stylesheet" type="text/css" />';
	
	$doc->addCustomTag($gb);
}

//if the users wants to use the modules CSS this displays it.
if ($params->get('use_css'))
{
	$doc->addCustomTag('<link href="'.JURI::base().'modules/mod_sociable/sociable.css" rel="stylesheet" type="text/css" />');
}

//bit fudgy, converts the supplied value for link_action into legible HTML
$sociable->convert_target();

//outputs the list.
echo '<div class="sociable_'.$params->get('moduleclass_sfx').'">';
echo $sociable->fetch_list();
echo '</div>';

?>