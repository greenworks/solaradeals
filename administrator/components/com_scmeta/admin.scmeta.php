<?php
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

if($controller) {
	
	JSubMenuHelper::addEntry(JText::_('SECTION_META_HEADER'), 'index.php?option=com_scmeta&view=sectionmeta');
	JSubMenuHelper::addEntry(JText::_('CATEGORY_META_HEADER'), 'index.php?option=com_scmeta&view=categorymeta');
}
// set default view
if(!JRequest::getVar('view')){
	JRequest::setVar('view','sectionmeta');
}

// Perform the Request task
$controller->execute( JRequest::getVar( 'task' ) );

// Redirect if set by the controller
$controller->redirect();
