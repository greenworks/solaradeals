<?php
// no direct access
defined( '_JEXEC' ) or die( ';)' );

class Tablemenumeta extends JTable {
	
	var $id = null;
	var $menu_id = null;
	var $menu_meta_keywords = null;
	var $menu_meta_desc = null;
	
	
	
	function Tablemenumeta(& $db) {
		parent::__construct('#__sjmeta_menu', 'id', $db);
		
	}
	
}

?>