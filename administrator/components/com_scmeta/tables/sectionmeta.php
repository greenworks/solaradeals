<?php
// no direct access
defined( '_JEXEC' ) or die( ';)' );

class Tablesectionmeta extends JTable {
	
	var $id = null;
	var $section_id = null;
	var $section_meta_keywords = null;
	var $section__meta_desc = null;
	
	
	
	function Tablesectionmeta(& $db) {
		parent::__construct('#__scmeta_section', 'id', $db);
		
	}
	
}

?>