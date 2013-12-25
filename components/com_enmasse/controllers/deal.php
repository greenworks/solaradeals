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

jimport('joomla.application.component.controller');

require_once( JPATH_ADMINISTRATOR . DS ."components". DS ."com_enmasse". DS ."helpers". DS ."EnmasseHelper.class.php");
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
class EnmasseControllerDeal extends JController
{
	function display(){
		JRequest::setVar('view', 'deallisting');
		parent::display();
	}
    
	function listing(){
		JRequest::setVar('view', 'deallisting');
		parent::display();
	}
	
   function expiredlisting(){
		JRequest::setVar('view', 'expireddeallisting');
		parent::display();
	}
		
	function today() {
		
		JRequest::setVar('view', 'dealtoday');
		parent::display();
	}
	
	function upcoming() {
		JRequest::setVar('view', 'dealupcoming');
		parent::display();
	}
	 
	function view() {
		JRequest::setVar('view', 'dealdetail');
		parent::display();
	}
	
	function dealSetLocationCookie()
	{
		$location = JRequest::getVar('locationId');
		if($location != null && $location!='')
		{
			$dealIdArrFromLocation = JModel::getInstance('dealLocation','enmasseModel')->getDealByLocationId($location);
			setCookie("location", $location);
			if(count($dealIdArrFromLocation)!=0)
			{
				$dealList = JModel::getInstance('deal','enmasseModel')->getDealMaxSoldQtyFromLocation($dealIdArrFromLocation);
				if($dealList->id != '')
		        	JFactory::getApplication()->redirect('index.php?option=com_enmasse&controller=deal&task=view&id='.$dealList->id);
		        else
		        {
		        	$dealToday = JModel::getInstance('deal','enmasseModel')->todayDeal();
		        	if(!empty($dealToday))
		        	{
			        	$msg = JText::_( "NO_DEAL_ON_YOUR_LOCATION");
						$link = JRoute::_('index.php?option=com_enmasse&controller=deal&task=view&id='.$dealToday->id, false);
		        	}
		        	else
		        	{
		        		$msg = JText::_( "NO_DEAL_ON_YOUR_LOCATION");
						$link = JRoute::_('index.php?option=com_enmasse&controller=deal&task=listing', false);
		        	}
					JFactory::getApplication()->redirect($link,$msg);
		        }
		        
			}
			else
			{
				$msg = JText::_( "NO_DEAL_ON_YOUR_LOCATION");
				$link = JRoute::_('index.php?option=com_enmasse&controller=deal&task=today', false);
				JFactory::getApplication()->redirect($link,$msg);
			}
		}
	}
	
	
	
}
?>
