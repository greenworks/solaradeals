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

class EnmasseViewDealListing extends JView
{
	function display($tpl = null)
	{
		$sortBy		= JRequest::getVar('sortBy', null);
		$keyword 	= JRequest::getVar('keyword', null);
		$categoryId 	= JRequest::getVar('categoryId', null);
		$locationId 	= JRequest::getVar('locationId', null);
        $dealFromLocation = null;
        $dealFromCategory = null;
		$this->assignRef( 'sortBy', $sortBy );
		$this->assignRef( 'keyword', $keyword );
		$this->assignRef( 'categoryId', $categoryId );
		$this->assignRef( 'locationId', $locationId );
		
		if(!empty($locationId) && trim($locationId)!= '')
			 	$dealFromLocation = JModel::getInstance('dealLocation','enmasseModel')->getDealByLocationId($locationId);
		if(!empty($categoryId) && trim($categoryId)!= '')
			 	$dealFromCategory= JModel::getInstance('dealCategory','enmasseModel')->getDealByCategoryId($categoryId);
	    
		$dealList = JModel::getInstance('deal','enmasseModel')->searchStartedPublishedDeal($keyword, $dealFromCategory, $dealFromLocation, $sortBy);
		$this->assignRef( 'dealList', $dealList );
		$this->assignRef( 'locationList', JModel::getInstance('location','enmasseModel')->listAllPublished());
		$this->assignRef( 'categoryList', JModel::getInstance('category','enmasseModel')->listAllPublished());
			
		$this->_setPath('template',JPATH_SITE . DS ."components". DS ."com_enmasse". DS ."theme". DS .EnmasseHelper::getThemeFromSetting(). DS ."tmpl". DS);
		$this->_layout="deal_listing";
		
		parent::display($tpl);
	}

}
?>