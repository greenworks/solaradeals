<?php
/**
 * @version $Id: header.php 248 2008-05-23 10:40:56Z elkuku $
 * @package		scmeta
 * @subpackage	
 * @author		EasyJoomla {@link http://www.easy-joomla.org Easy-Joomla.org}
 * @author		Deepak Patil {@link http://www.tekdi.net}
 * @author		Created on 12-Jan-2009
 */

// no direct access
defined( '_JEXEC' ) or die( ';)' );

// Require the base controller
require_once( JPATH_COMPONENT.DS.'controller.php' );

// Require specific controller if requested
if( $controller = JRequest::getWord('controller'))
{
   $path = JPATH_COMPONENT.DS.'controllers'.DS.$controller.'.php';
   if( file_exists($path))
	{
       require_once $path;
   } else
   {
       $controller = '';
   }
}

// Create the controller
$classname    = 'scmetaController'.$controller;
$controller   = new $classname( );

// Perform the Request task
$controller->execute( JRequest::getVar( 'task' ) );

// Redirect if set by the controller
$controller->redirect();