<?php
// no direct access
defined( '_JEXEC' ) or die( ';)' );

/**
 * The main uninstaller function
 */
function com_uninstall()
{
	$errors = FALSE;
	
	$db = & JFactory::getDBO();

	$query = "DROP TABLE IF EXISTS `#__sjmeta_menu`;";
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
