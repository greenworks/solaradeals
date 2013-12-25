<?php
// no direct access
defined( '_JEXEC' ) or die( 'Restricted access' );

jimport( 'joomla.plugin.plugin');

class plgSystemScmeta extends JPlugin
{	
	function plgSystemScmeta(&$subject, $config)  {
		parent::__construct($subject, $config);
	}

	function onAfterDispatch()
	{
		global $mainframe;
		$db = JFactory::getDBO();
		$document =& JFactory::getDocument();
		$id = JRequest::getInt('id');
		
		// get plugin params
		$plugin			=& JPluginHelper::getPlugin('system', 'scmeta');
		$pluginParams	= new JParameter( $plugin->params );
		
		$section_append_keywords 	= $pluginParams->get('section_append_keywords'); 
		$section_append_desc 		= $pluginParams->get('section_append_desc'); 
		$category_append_keywords 	= $pluginParams->get('category_append_keywords');
		$category_append_desc 		= $pluginParams->get('category_append_desc');
		
		// Meta data only for content component
		if (JRequest::getCmd('option') != 'com_content') { return; }
		
		// get the section meta keyword and description
		if(JRequest::getVar('view') == 'section') 
		{ 
			$query = "SELECT * FROM #__scmeta_section WHERE section_id = $id";
			$db->setQuery($query);
			$sectionmeta = $db->loadObjectList();
			$sectionmeta = $sectionmeta[0];
			
			if($section_append_keywords == '1') {
				if($document->getMetaData("keywords"))
					$document->setMetaData("keywords", $document->getMetaData("keywords").', '.$sectionmeta->section_meta_keywords);
			} else {
				if($sectionmeta->section_meta_keywords) {
					$mainframe->addMetaTag( "keywords", $sectionmeta->section_meta_keywords );
				}	
			}
			
			if($section_append_desc == '1') {				
				$document->setDescription($document->getDescription().' '.$sectionmeta->section_meta_desc);
			} else {
				if($sectionmeta->section_meta_desc) {
					$mainframe->appendMetaTag( "description", $sectionmeta->section_meta_desc );
				}	
			}				
		}
		
		// get the category meta keyword and description
		if(JRequest::getVar('view') == 'category') 
		{		
			$query = "SELECT * FROM #__scmeta_category WHERE category_id = $id";
			$db->setQuery($query);
			$categorymeta = $db->loadObjectList();
			$categorymeta = $categorymeta[0];

			if($category_append_keywords == '1') {
				if($document->getMetaData("keywords"))
					$document->setMetaData("keywords", $document->getMetaData("keywords").', '.$categorymeta->category_meta_keywords);
			} else {
				if($categorymeta->category_meta_keywords) {
					$mainframe->addMetaTag( "keywords", $categorymeta->category_meta_keywords );
				}	
			}
			
			if($category_append_desc == '1') {				
				$document->setDescription($document->getDescription().' '.$categorymeta->category_meta_desc);
			} else {
				if($categorymeta->category_meta_desc) {
					$mainframe->appendMetaTag( "description", $categorymeta->category_meta_desc );
				}
			}	
			
		}
				
	}// end function

} // end class
