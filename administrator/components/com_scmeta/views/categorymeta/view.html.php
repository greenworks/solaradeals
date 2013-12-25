<?php
// no direct access
defined( '_JEXEC' ) or die( ';)' );

jimport( 'joomla.application.component.view');



class scmetaViewcategorymeta extends JView
{
   
   
	function display($tpl = null)
	{

		if (JRequest::getVar('layout')== 'form') {
			JToolBarHelper::title(   JText::_( 'CATEGORY_MANAGER' ), 'generic.png' );
			JToolBarHelper::save();
			JToolBarHelper::cancel( 'cancel', 'Close' );
		} else {
			JToolBarHelper::title(   JText::_( 'CATEGORY_MANAGER' ), 'generic.png' );
			JToolBarHelper::deleteList("","remove", "Remove MetaData");		
			JToolBarHelper::editListX();		
		}

		//get the scmeta
		$categorymeta		=& $this->get('CategoryMetaData');
		$this->assignRef('categorymeta', $categorymeta);
		
		// Get data from the model
	 	$items =& $this->get('Data');	
	 	$pagination =& $this->get('Pagination');
	
		// push data into the template
		$this->assignRef('items',		$items);
		$this->assignRef('pagination', $pagination);
		
		$categoryname	=& $this->get('CategoryDetails');
		$this->assignRef('categoryname', $categoryname);

        /* Call the state object */
        $state =& $this->get( 'state' );

        /* Get the values from the state object that were inserted in the model's construct function */
        $lists['order_Dir'] = $state->get( 'cat_filter_order_Dir' );
        $lists['order']     = $state->get( 'cat_filter_order' );

        $this->assignRef( 'lists', $lists );
		

		parent::display($tpl);
		
    }
	
}// class

