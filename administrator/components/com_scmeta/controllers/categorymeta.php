<?php
// no direct access
defined( '_JEXEC' ) or die( ';)' );

jimport('joomla.application.component.controller');


class scmetaControllercategorymeta extends scmetaController
{
	
	function __construct()
	{
		parent::__construct();

		// Register Extra tasks
		$this->registerTask( 'add'  , 	'edit' );
		
	}


	function edit()
	{
		JRequest::setVar( 'view', 'categorymeta' );
		JRequest::setVar( 'layout', 'form'  );
		JRequest::setVar('hidemainmenu', 1);

		parent::display();
		
	}


	function save()
	{
		$model = $this->getModel('categorymeta');

		if ($model->store($post)) {
			$msg = JText::_( 'CATEGORY_META_SAVE_MSG' );
		} else {
			$msg = JText::_( 'CATEGORY_META_ERROR_MSG' );
		}

		// Check the table in so it can be edited.... we are done with it anyway
		$link = 'index.php?option=com_scmeta&view=categorymeta';
		$this->setRedirect($link, $msg);
		
	}


	function remove()
	{
		$model = $this->getModel('categorymeta');
		if(!$model->delete()) {
			$msg = JText::_( 'CATEGORY_META_DEL_ERR_MSG' );
		} else {
			$msg = JText::_( 'CATEGORY_META_DEL_MSG' );
		}

		$this->setRedirect( 'index.php?option=com_scmeta&view=categorymeta', $msg );
		
	}

	
	function cancel()
	{
		$msg = JText::_( 'OPERATION_CANCEL' );
		$this->setRedirect( 'index.php?option=com_scmeta&view=categorymeta', $msg );
	}
	

	
}// class
