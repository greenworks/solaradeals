<?php
// no direct access
defined( '_JEXEC' ) or die( ';)' );

jimport( 'joomla.application.component.view');

class scmetaViewsectionmeta extends JView
{    
	function display($tpl = null)
	{
		
		if (JRequest::getVar('layout')== 'form') {
			JToolBarHelper::title(   JText::_( 'SECTION_MANAGER' ), 'generic.png' );
			JToolBarHelper::save();
			JToolBarHelper::cancel( 'cancel', 'Close' );
	 	} else {
			JToolBarHelper::title(   JText::_( 'SECTION_MANAGER' ), 'generic.png' );
			JToolBarHelper::deleteList("","remove", "Remove MetaData");		
			JToolBarHelper::editListX();		
		}

		//get the scmeta
		$sectionmeta		=& $this->get('SectionMetaData');
		$this->assignRef('sectionmeta', $sectionmeta);
		
		// Get data from the model
	 	$items =& $this->get('Data');	
	 	$pagination =& $this->get('Pagination');

        /* Call the state object */
        $state =& $this->get( 'state' );

        /* Get the values from the state object that were inserted in the model's construct function */
        $lists['order_Dir'] = $state->get( 'sec_filter_order_Dir' );
        $lists['order']     = $state->get( 'sec_filter_order' );
		$lists['section'] = JHTML::_('list.section', 'section', '', 'class="inputbox" onchange="getCats(this.value)"');
		
		$this->assignRef('lists',	$lists); 
	
		// push data into the template
		$this->assignRef('items',		$items);
		$this->assignRef('pagination', $pagination);
		
		$sectionname	=& $this->get('SectionDetails');
		$this->assignRef('sectionname', $sectionname);
		

		parent::display($tpl);
		
    }
	
}// class
