<?php
// no direct access
defined( '_JEXEC' ) or die( ';)' );

/**
 * Main installer
 */
function com_install()
{
	$errors = FALSE;

	//--install...
	$db = & JFactory::getDBO();

	$query = "DROP TABLE IF EXISTS `#__sjmeta_menu`;";
	$db->setQuery($query);
	if( ! $db->query() )
	{
		echo $img_ERROR.JText::_('Unable to create table').$BR;
		echo $db->getErrorMsg();
		return FALSE;
	}

	$query = "CREATE TABLE `#__sjmeta_menu` (
				  `id` int(11) NOT NULL auto_increment,
				  `menu_id` int(11) NOT NULL,
				  `menu_meta_keywords` mediumtext NOT NULL,
				  `menu_meta_desc` mediumtext NOT NULL,
				  PRIMARY KEY  (`id`)
				) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=8 ;";
	$db->setQuery($query);
	if( ! $db->query() )
	{
		echo $img_ERROR.JText::_('Unable to create table').$BR;
		echo $db->getErrorMsg();
		return FALSE;
	}

	
}// function
