<?php
/**
 * Element: Checkbox
 * Displays options as checkboxes
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
 * Checkbox Element
 */
class nnElementCheckbox
{
	function getInput( $name, $id, $value, $params, $children, $j15 = 0 )
	{
		$this->params = $params;

		$newlines =			$this->def( 'newlines', 0 );
		$showcheckall =		$this->def( 'showcheckall', 0 );

		$checkall = ( $value == '*' );

		if ( !$checkall ) {
			if ( !is_array( $value ) ) {
				$value = explode( ',', $value );
			}
		}

		$options = array();
		foreach ( $children as $option ) {
			$text = $option->data();
			if ( isset( $option->_attributes['value'] ) ) {
				$val		= $option->attributes( 'value' );
				$disabled	= $option->attributes( 'disabled' );
				$option = '<input type="checkbox" class="nn_'.$id.'" id="'.$id.$val.'" name="'.$name.'[]" value="'.$val.'"';
				if ( $checkall || in_array( $val, $value ) ) {
					$option .= ' checked="checked"';
				}
				if ( $disabled ) {
					$option .= ' disabled="disabled"';
				}
				$option .= ' /> '.JText::_( $text );
			} else {
				$option = '<strong>'.JText::_( $text ).'</strong>';
			}
			$options[] = $option;
		}

		if ( $newlines ) {
			$options = implode( '<br />', $options );
		} else {
			$options = implode( '&nbsp;&nbsp;&nbsp;', $options );
		}

		if ( $showcheckall ) {
			$checkers = array();
			if ( $showcheckall ) {
				$checkers[] = '<input id="nn_checkall_'.$id.'" type="checkbox" onclick="NoNumberElementsCheckAll( this, \'nn_'.$id.'\' );" /> '.JText::_( 'All' );

				$document =& JFactory::getDocument();
				$js = "
					window.addEvent('domready', function() {
						$('nn_checkall_".$id."').checked = NoNumberElementsAllChecked( 'nn_".$id."' );
					});
				";
				$document->addScriptDeclaration( $js );
			}
			$options = implode( '&nbsp;&nbsp;&nbsp;', $checkers ).'<br />'.$options;
		}
		$options .= '<input type="hidden" id="'.$id.'x" name="'.$name.''.'[]" value="x" checked="checked" />';

		return $options;
	}

	private function def( $val, $default = '' )
	{
		return ( isset( $this->params[$val] ) && (string) $this->params[$val] != '' ) ? (string) $this->params[$val] : $default;
	}
}

if ( version_compare( JVERSION, '1.6.0', 'l' ) ) {
	// For Joomla 1.5
	class JElementCheckbox extends JElement
	{
		/**
		 * Element name
		 *
		 * @access	protected
		 * @var		string
		 */
		var	$_name = 'Checkbox';

		function fetchElement( $name, $value, &$node, $control_name )
		{
			$this->_nnelement = new nnElementCheckbox();
			return $this->_nnelement->getInput( $control_name.'['.$name.']', $control_name.$name, $value, $node->attributes(), $node->children(), 1 );
		}
	}
} else {
	// For Joomla 1.6
	class JFormFieldCheckbox extends JFormField
	{
		/**
		 * The form field type
		 *
		 * @var		string
		 */
		public $type = 'Checkbox';

		protected function getInput()
		{
			$this->_nnelement = new nnElementCheckbox();
			return $this->_nnelement->getInput( $this->name, $this->id, $this->value, $this->element->attributes(), $this->element->children() );
		}
	}
}