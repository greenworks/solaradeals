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

defined( '_JEXEC' ) or die( 'Restricted access' );
 
jimport( 'joomla.application.component.view');
require_once( JPATH_ADMINISTRATOR . DS ."components". DS ."com_enmasse". DS ."helpers". DS ."EnmasseHelper.class.php"); 
 
class EnmasseViewDealToday extends JView
{
    function display($tpl = null)
    {       
     	
    	// test the cookie of location  
        if(isset($_COOKIE["location"]) && $_COOKIE["location"]!=null)
		{
			$dealIdArrFromLocation = JModel::getInstance('dealLocation','enmasseModel')->getDealByLocationId($_COOKIE["location"]);
			if(count($dealIdArrFromLocation)!=0)
			{
				JFactory::getApplication()->redirect('index.php?option=com_enmasse&controller=deal&task=dealSetLocationCookie&locationId='.$_COOKIE["location"]);
			}
		}
		
		
        $deal = JModel::getInstance('deal','enmasseModel')->todayDeal();
    	if(empty($deal))
		{
			$link = JRoute::_("index.php?option=com_enmasse&view=deallisting", false);
			$msg = "Sorry, there is no special Today Deal.";

			JFactory::getApplication()->redirect($link, $msg);
		}
		
        $deal->merchant = JModel::getInstance('merchant','enmasseModel')->getById($deal->merchant_id);
        $this->assignRef( 'deal', $deal );
        $this->assignRef('locationPopup',EnmasseHelper::getLocationPopUpActiveFromSetting());
        $this->assignRef( 'locationList', JModel::getInstance('location','enmasseModel')->listAllPublished());
        $this->_setPath('template',JPATH_SITE . DS ."components". DS ."com_enmasse". DS ."theme". DS .EnmasseHelper::getThemeFromSetting(). DS ."tmpl". DS);
    	$this->_layout="deal_today";
        parent::display($tpl);
    }

}
?>