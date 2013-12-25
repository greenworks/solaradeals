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

jimport( 'joomla.application.component.model' );
require_once( JPATH_SITE . DS ."components". DS ."com_enmasse". DS ."helpers". DS ."CartHelper.class.php");
class EnmasseModelSetting extends JModel
{
	function getCompanyName()
	{
		$db =& JFactory::getDBO();
		$query = "SELECT company_name FROM #__enmasse_setting";
		$query .= " limit 1";
		$db->setQuery( $query );
		$coyName = $db->loadResult();
		
		if ($this->_db->getErrorNum()) {
			JError::raiseError( 500, $this->_db->stderr() );
			return false;
		}
		
		return $coyName;
	}
	
   function getSetting()
	{
		$db =& JFactory::getDBO();
		$query = "SELECT * FROM #__enmasse_setting";
		$db->setQuery( $query );
		$setting = $db->loadObject();
		
		if ($this->_db->getErrorNum()) {
			JError::raiseError( 500, $this->_db->stderr() );
			return false;
		}
		return $setting;
	}
	
	function getCurrencyPrefix()
	{
		$db =& JFactory::getDBO();
		$query = ' SELECT currency_prefix as text FROM #__enmasse_setting WHERE id = 1';
		$db->setQuery($query);
		$prefix = $db->loadResult();
		
		if ($this->_db->getErrorNum()) {
			JError::raiseError( 500, $this->_db->stderr() );
			return false;
		}
		return $prefix ;
	}
	function getCurrencyPostfix()
	{
		$db =& JFactory::getDBO();
		$query = 'SELECT currency_postfix FROM #__enmasse_setting WHERE id = 1';
		$db->setQuery($query);
		$postfix = $db->loadResult();;
		
		if ($this->_db->getErrorNum()) {
			JError::raiseError( 500, $this->_db->stderr() );
			return false;
		}
		return $postfix ;
	}
    function getCurrency()
	{
		$db =& JFactory::getDBO();
		$query = ' SELECT default_currency as text FROM #__enmasse_setting WHERE id = 1';
		$db->setQuery($query);
		$currency = $db->loadResult();
		
		if ($this->_db->getErrorNum()) {
			JError::raiseError( 500, $this->_db->stderr() );
			return false;
		}
		return $currency ;
	}
	function getCountry()
	{
		$db =& JFactory::getDBO();
		$query = ' SELECT country as text FROM #__enmasse_setting WHERE id = 1';
		$db->setQuery($query);
		$country = $db->loadResult();
		
		if ($this->_db->getErrorNum()) {
			JError::raiseError( 500, $this->_db->stderr() );
			return false;
		}
		return $country ;
	}
	function getCouponBg()
	{
		$db =& JFactory::getDBO();
		$query = "SELECT coupon_bg_url from #__enmasse_setting WHERE id = 1";
		$db -> setQuery($query);
		$result = $db -> loadResult();
		
		if ($this->_db->getErrorNum()) {
			JError::raiseError( 500, $this->_db->stderr() );
			return false;
		}
		return $result;
	}
}
?>