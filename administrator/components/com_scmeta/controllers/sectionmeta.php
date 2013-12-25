<?php
// no direct access
defined( '_JEXEC' ) or die( ';)' );

jimport('joomla.application.component.controller');


class scmetaControllersectionmeta extends scmetaController
{
	
	function __construct()
	{
		parent::__construct();

		// Register Extra tasks
		$this->registerTask( 'add'  , 	'edit' );
		
	}


	function edit()
	{
		JRequest::setVar( 'view', 'sectionmeta' );
		JRequest::setVar( 'layout', 'form'  );
		JRequest::setVar('hidemainmenu', 1);

		parent::display();
		
	}


	function save()
	{
		$model = $this->getModel('sectionmeta');

		if ($model->store($post)) {
			$msg = JText::_( 'SECTION_META_SAVE_MSG' );
		} else {
			$msg = JText::_( 'SECTION_META_ERROR_MSG' );
		}

		// Check the table in so it can be edited.... we are done with it anyway
		$link = 'index.php?option=com_scmeta&view=sectionmeta';
		$this->setRedirect($link, $msg);
		
	}


	function remove()
	{
		$model = $this->getModel('sectionmeta');
		if(!$model->delete()) {
			$msg = JText::_( 'SECTION_META_DEL_ERR_MSG' );
		} else {
			$msg = JText::_( 'SECTION_META_DEL_MSG' );
		}

		$this->setRedirect( 'index.php?option=com_scmeta&view=sectionmeta', $msg );
		
	}

	
	function cancel()
	{
		$msg = JText::_( 'SECTION_CANCEL' );
		$this->setRedirect( 'index.php?option=com_scmeta&view=sectionmeta', $msg );
	}
	

	
}// class
