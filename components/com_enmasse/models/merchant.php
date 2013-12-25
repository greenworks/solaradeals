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
require_once( JPATH_ADMINISTRATOR . DS ."components". DS ."com_enmasse". DS ."helpers". DS ."DatetimeWrapper.class.php");
jimport( 'joomla.application.component.model' );

class EnmasseModelMerchant extends JModel
{
	function getById($merchantId)
	{
		$db =& JFactory::getDBO();
		$query = "	SELECT 
						* 
					FROM 
						#__enmasse_merchant 
					WHERE
						id = $merchantId";
		$db->setQuery( $query );
		$merchant = $db->loadObject();

		if ($this->_db->getErrorNum()) {
			JError::raiseError( 500, $this->_db->stderr() );
			return false;
		}
		
		return $merchant;
	}
	
	function getByUserName($username)
	{
		$db =& JFactory::getDBO();
		$query = "	SELECT 
						* 
					FROM 
						#__enmasse_merchant 
					WHERE 
						user_name = '$username'";
		$db->setQuery( $query );
		$merchant = $db->loadObject();

		if ($this->_db->getErrorNum()) {
			JError::raiseError( 500, $this->_db->stderr() );
			return false;
		}
		
		return $merchant;
	}
	
	function listAllPublished()
	{
		$db 	=& JFactory::getDBO();
		$query = ' SELECT id, name FROM #__enmasse_merchant WHERE published = 1';
		$db->setQuery($query);
		$category = $db->loadObjectList();
		
		if ($this->_db->getErrorNum()) {
			JError::raiseError( 500, $this->_db->stderr() );
			return false;
		}
		
		return $category;
	}
	
}
?>