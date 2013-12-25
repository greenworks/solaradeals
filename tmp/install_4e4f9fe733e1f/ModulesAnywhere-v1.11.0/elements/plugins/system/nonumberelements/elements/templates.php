<?php
/**
 * Element: Templates
 * Displays a select box of templates
 *
 * @package     NoNumber! Elements
 * @version     2.5.0
 *
 * @author      Peter van Westen <peter@nonumber.nl>
 * @link        http://www.nonumber.nl
 * @copyright   Copyright © 2011 NoNumber! All Rights Reserved
 * @license     http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
 */

// Ensure this file is being included by a parent file
defined( '_JEXEC' ) or die( 'Restricted access' );

/**
 * Templates Element
 */
class nnElementTemplates
{
	function getInput( $name, $id, $value, $params, $children, $j15 = 0 )
	{
		$this->params = $params;

		$size =				$this->def( 'size' );
		$multiple =			$this->def( 'multiple' );
		$subtemplates =		$this->def( 'subtemplates', 1 );

		$control = $name.'';
		$attribs = 'class="inputbox"';
		if ( $multiple ) {
			if( !is_array( $value ) ) { $value = explode( ',', $value ); }
			$attribs .= ' multiple="multiple"';
			$control .= '[]';
		}

		require_once JPATH_ADMINISTRATOR.DS.'components'.DS.'com_templates'.DS.'helpers'.DS.'template.php';
		$rows = TemplatesHelper::parseXMLTemplateFiles( JPATH_ROOT.DS.'templates' );
		$options = $this->createList( $rows, JPATH_ROOT.DS.'templates', $subtemplates );

		if( $size ) {
			$attribs .= ' size="'.$size.'"';
		} else {
			$attribs .= ' size="'.( ( count( $options) > 10 ) ? 10 : count( $options) ).'"';
		}

		$list =	JHTML::_( 'select.genericlist', $options, $control, $attribs, 'value', 'text', $value, $id );

		return $list;
	}
	function createList( $rows, $templateBaseDir, $subtemplates = 1 )
	{
		$options = array();

		$options[] = JHTML::_( 'select.option', 'system:component', JText::_( 'None' ).' (System - component)' );

		foreach ( $rows as $row ) {
			$options[] = JHTML::_( 'select.option', $row->directory, $row->name );

			if ( $subtemplates ) {
				$options_sub = $this->getSubTemplates( $row, $templateBaseDir );
				$options = array_merge( $options, $options_sub );
			}
		}
		return $options;
	}

	function getSubTemplates( $row, $templateBaseDir )
	{
		$options = array();
		$templateDir = dir( $templateBaseDir.DS.$row->directory );
		while ( false !== ( $file = $templateDir->read() ) ) {
		  	if ( is_file( $templateDir->path.DS.$file ) ) {
				if ( !( strpos( $file, '.php' ) === false ) && $file != 'index.php' ) {
					$file_name = str_replace( '.php', '', $file );
					if ( $file_name != 'index' && $file_name != 'editor' && $file_name != 'error' ) {
						$options[] = JHTML::_( 'select.option', $row->directory.':'.$file_name, $row->name.' - '.$file_name );
					}
				}
			}
		}
		$templateDir->close();

		return $options;
	}

	private function def( $val, $default = '' )
	{
		return ( isset( $this->params[$val] ) && (string) $this->params[$val] != '' ) ? (string) $this->params[$val] : $default;
	}
}

if ( version_compare( JVERSION, '1.6.0', 'l' ) ) {
	// For Joomla 1.5
	class JElementTemplates extends JElement
	{
		/**
		 * Element name
		 *
		 * @access	protected
		 * @var		string
		 */
		var	$_name = 'Templates';

		function fetchElement( $name, $value, &$node, $control_name )
		{
			$this->_nnelement = new nnElementTemplates();
			return $this->_nnelement->getInput( $control_name.'['.$name.']', $control_name.$name, $value, $node->attributes(), $node->children(), 1 );
		}
	}
} else {
	// For Joomla 1.6
	class JFormFieldTemplates extends JFormField
	{
		/**
		 * The form field type
		 *
		 * @var		string
		 */
		public $type = 'Templates';

		protected function getInput()
		{
			$this->_nnelement = new nnElementTemplates();
			return $this->_nnelement->getInput( $this->name, $this->id, $this->value, $this->element->attributes(), $this->element->children() );
		}
	}
}