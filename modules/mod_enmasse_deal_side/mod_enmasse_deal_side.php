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

defined('_JEXEC') or die('Restricted access');
require_once( JPATH_ADMINISTRATOR . DS ."components". DS ."com_enmasse". DS ."helpers". DS ."EnmasseHelper.class.php"); 
require_once(dirname(__FILE__).DS.'helper.php');

$id = $params->get('dealId');
$height = $params->get('dealHeight');
$width = $params->get('dealWidth');
$item= ModEnmasseDealSideHelper::getItem($id);
$css_suffix = $params->get('css_class_suffix');
if(empty($item))
{
	$item->name = 'DEAL_LIST_NO_DEAL_MESSAGE';
}
$theme = EnmasseHelper::getThemeFromSetting(); 
require( JPATH_BASE . DS ."components". DS ."com_enmasse". DS ."theme". DS. $theme . DS ."tmpl". DS ."mod_enmasse_deal_side.php");
?>