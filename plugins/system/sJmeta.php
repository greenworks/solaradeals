<?php
// no direct access
defined( '_JEXEC' ) or die( 'Restricted access' );

jimport( 'joomla.plugin.plugin');

class plgSystemsJmeta extends JPlugin
{
	
	function plgSystemScmeta(&$subject, $config)  {
		parent::__construct($subject, $config);
	}



	function onAfterDispatch()
	{
		global $mainframe;
		
		$adminarea = $mainframe->isAdmin();
		if( $adminarea ) return 0;
		
		$user =& JFactory::getUser();
		if($user->usertype=='Super Administrator') return false;

		$db = JFactory::getDBO();
		$document =& JFactory::getDocument();
		//$id = JRequest::getVar('id');
		$jmenu = &JSite::getMenu();
		$menu  = $jmenu->getActive();
		$id=$menu->id;
		// get plugin params
		$plugin		=& JPluginHelper::getPlugin('system', 'sJmeta');
		$pluginParams	= new JParameter( $plugin->params );
		
		$menu_append_keywords = $pluginParams->get('menu_append_keywords');
		$menu_append_desc = $pluginParams->get('menu_append_desc');
			
		// get the menu meta keyword and description
		if(JRequest::getVar('view') != 'article') {
			$query = "SELECT * FROM #__sjmeta_menu WHERE menu_id = $id";
			$db->setQuery($query);
			$menumeta = $db->loadObjectList();
			$menumeta = $menumeta[0];
			
			if($menu_append_keywords == '1') {
				if($document->getMetaData("keywords"))
					$document->setMetaData("keywords", $document->getMetaData("keywords").', '.$menumeta->menu_meta_keywords);
			} else {
				$mainframe->addMetaTag( "keywords", $menumeta->menu_meta_keywords );
			}
			
			if($menu_append_desc == '1') {				
				$document->setDescription($document->getDescription().' '.$menumeta->menu_meta_desc);
			} else {
				$mainframe->appendMetaTag( "description", $menumeta->menu_meta_desc );
			}
			
		}
	}

}



