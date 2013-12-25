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
$classname    = 'sjmetaController'.$controller;
$controller   = new $classname( );

if($controller) {
	JSubMenuHelper::addEntry(JText::_('MENU_META_HEADER'), 'index.php?option=com_sjmeta&view=menumeta');
}
// set default view
if(!JRequest::getVar('view')){
	JRequest::setVar('view','menumeta');
}

// Perform the Request task
$controller->execute( JRequest::getVar( 'task' ) );

// Redirect if set by the controller
$controller->redirect();

?>