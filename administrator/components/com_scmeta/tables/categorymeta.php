<?php
// no direct access
defined( '_JEXEC' ) or die( ';)' );

class Tablecategorymeta extends JTable {
	
	var $id = null;
	var $category_id = null;
	var $category_meta_keywords = null;
	var $category__meta_desc = null;
	
	
	
	function Tablecategorymeta(& $db) {
		parent::__construct('#__scmeta_category', 'id', $db);
		
	}
	
}

?>