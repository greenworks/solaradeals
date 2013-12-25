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

defined('_JEXEC') or die('Direct Access to this location is not allowed.');

require_once( JPATH_ROOT. DS ."components". DS ."com_enmasse". DS ."helpers". DS ."Cart.class.php");
require_once( JPATH_ADMINISTRATOR . DS ."components". DS ."com_enmasse". DS ."helpers". DS ."EnmasseHelper.class.php");
//------------------------------------------------
// geting url requests
$option = JRequest::getVar('option');
$view = JRequest::getVar('view');
$task = JRequest::getVar('task'); 

//----------------------------------
// Get the Cart
$session = JFactory::getSession();
$cart = unserialize($session->get('cart'));

//--------------------------------------
// check if last aciont is new user register and shop cart is not empty then redirect to shop cart
if(JFactory::getSession()->get('lastAction') == 'registation' && !empty($cart))
{
	JFactory::getSession()->set('lastAction','none');
    $app = JFactory::getApplication();
	$app->redirect('index.php?option=com_enmasse&controller=shopping&task=viewCart','your account is successful active ! please contunue your check out !');
}
enmasseHelper::setLastAction($option,$view); // to set lastaction to session
//------------------------------------------------------------

//if($option=="com_user" && $task == 'activate')
//{
//	$app = JFactory::getApplication();
//	$app->redirect('index.php?option=com_enmasse&controller=shopping&task=viewCart','your account is successful active ! please contunue your check out !');
//}

//----------------------------------
// check if cart is empty
if ( empty($cart) )
    $cart = new Cart();

//----------------------------------
// Get the Theme Setting
$theme = EnmasseHelper::getThemeFromSetting();    
require_once( JPATH_BASE . DS ."components". DS ."com_enmasse". DS ."theme". DS. $theme . DS ."tmpl". DS ."mod_enmasse_summarized_cart.php");
?>