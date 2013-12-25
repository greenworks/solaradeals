<?php
// no direct access
defined( '_JEXEC' ) or die( ';)' );

jimport( 'joomla.application.component.view');



class sjmetaViewmenumeta extends JView
{
   
   
	function display($tpl = null)
	{
		
		if (JRequest::getVar('layout')== 'form'){
		JToolBarHelper::title(   JText::_( 'MENU_MANAGER' ), 'generic.png' );
		JToolBarHelper::save();
		JToolBarHelper::cancel( 'cancel', 'Close' );
	 }else 
	  {
		JToolBarHelper::title(   JText::_( 'MENU_MANAGER' ), 'generic.png' );
		JToolBarHelper::deleteList("","remove", "Remove MetaData");		
		JToolBarHelper::editListX();		
		}

		//get the sjmeta
		$menumeta		=& $this->get('MenuMetaData');
		$this->assignRef('menumeta', $menumeta);
		
		// Get data from the model
	 	$items =& $this->get('Data');	
	 	$pagination =& $this->get('Pagination');
		
		//$lists['menu'] = JHTML::_('list.menu', 'menu', '', 'class="inputbox" onchange="getCats(this.value)"');
		$this->assignRef('lists',	$lists); 
	
		// push data into the template
		$this->assignRef('items',		$items);
		$this->assignRef('pagination', $pagination);
		
		$menuname	=& $this->get('MenuDetails');
		$this->assignRef('menuname', $menuname);
		

		parent::display($tpl);
		
    }
	
}// class

