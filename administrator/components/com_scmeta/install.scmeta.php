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
 * Main installer
 */
function com_install()
{
	$errors = FALSE;

	//--install...
	$db = & JFactory::getDBO();

	$query = "DROP TABLE IF EXISTS `#__scmeta_section`;";
	$db->setQuery($query);
	if( ! $db->query() )
	{
		echo $img_ERROR.JText::_('Unable to create table').$BR;
		echo $db->getErrorMsg();
		return FALSE;
	}

	$query = "CREATE TABLE IF NOT EXISTS `#__scmeta_section` (
		  `id` int(11) NOT NULL auto_increment,
		  `section_id` int(11) NOT NULL,
		  `section_meta_keywords` mediumtext NOT NULL,
		  `section_meta_desc` mediumtext NOT NULL,
		  PRIMARY KEY  (`id`)
		) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=5 ;";
	$db->setQuery($query);
	if( ! $db->query() )
	{
		echo $img_ERROR.JText::_('Unable to create table').$BR;
		echo $db->getErrorMsg();
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

	$query = "CREATE TABLE IF NOT EXISTS `#__scmeta_category` (
		  `id` int(11) NOT NULL auto_increment,
		  `category_id` int(11) NOT NULL,
		  `category_meta_keywords` mediumtext NOT NULL,
		  `category_meta_desc` mediumtext NOT NULL,
		  PRIMARY KEY  (`id`)
		) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=3 ;";
	$db->setQuery($query);
	if( ! $db->query() )
	{
		echo $img_ERROR.JText::_('Unable to create table').$BR;
		echo $db->getErrorMsg();
		return FALSE;
	}

	
	return TRUE;
	
}// function
