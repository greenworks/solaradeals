<?php

/**
* @package		SCMeta
* @copyright Copyright (C) 2009 -2010 Techjoomla, Tekdi Web Solutions . All rights reserved.
* @license GNU GPLv2 <http://www.gnu.org/licenses/old-licenses/gpl-2.0.html>
* @link     http://www.techjoomla.com
**/

// no direct access
defined( '_JEXEC' ) or die( ';)' );

/**
 * The main uninstaller function
 */
function com_uninstall()
{
	$errors = FALSE;
	
	$db = & JFactory::getDBO();

	$query = "DROP TABLE IF EXISTS `#__scmeta_section`;";
	$db->setQuery($query);
	if( ! $db->query() )
	{
		echo $img_ERROR.JText::_('Unable to create table').$BR;
		echo $db->getErrorMsg();
		return FALSE;
	}

	if( $errors )
	{
		return FALSE;
	}
	
	$query = "DROP TABLE IF EXISTS `#__scmeta_category`;";
	$db->setQuery($query);
	if( ! $db->query() )
	{
		echo $img_ERROR.JText::_('Unable to create table').$BR;
		echo $db->getErrorMsg();
		return FALSE;
	}

	if( $errors )
	{
		return FALSE;
	}
		
	return TRUE;
	
}// function
