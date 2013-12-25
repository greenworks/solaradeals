	<?php
/**
 * Element: Languages
 * Displays a select box of languages
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
class nnElementLanguages
{
	function getInput( $name, $id, $value, $params, $children, $j15 = 0 )
	{
		$this->params = $params;

		$size =			$this->def( 'size' );
		$multiple =		$this->def( 'multiple' );
		$client =		$this->def( 'client', 'SITE' );

		$control = $name.'';
		$attribs = 'class="inputbox"';
		if ( $multiple ) {
			if( !is_array( $value ) ) { $value = explode( ',', $value ); }
			$attribs .= ' multiple="multiple"';
			$control .= '[]';
		}

		jimport('joomla.language.helper');
		$options = JLanguageHelper::createLanguageList( $value, constant( 'JPATH_'.strtoupper( $client ) ), true );
		foreach ( $options as $i => $option ) {
			if ( $option['value'] ) {
				$options[$i]['text'] = $option['text'].' ['.$option['value'].']';
			}
		}
		if( $size ) {
			$attribs .= ' size="'.$size.'"';
		} else {
			$attribs .= ' size="'.( ( count( $options) > 10 ) ? 10 : count( $options) ).'"';
		}

		$list 	= JHTML::_( 'select.genericlist', $options, $control, $attribs, 'value', 'text', $value, $id );

		return $list;
	}

	private function def( $val, $default = '' )
	{
		return ( isset( $this->params[$val] ) && (string) $this->params[$val] != '' ) ? (string) $this->params[$val] : $default;
	}
}

if ( version_compare( JVERSION, '1.6.0', 'l' ) ) {
	// For Joomla 1.5
	class JElementLanguages extends JElement
	{
		/**
		 * Element name
		 *
		 * @access	protected
		 * @var		string
		 */
		var	$_name = 'Languages';

		function fetchElement( $name, $value, &$node, $control_name )
		{
			$this->_nnelement = new nnElementLanguages();
			return $this->_nnelement->getInput( $control_name.'['.$name.']', $control_name.$name, $value, $node->attributes(), $node->children(), 1 );
		}
	}
} else {
	// For Joomla 1.6
	class JFormFieldLanguages extends JFormField
	{
		/**
		 * The form field type
		 *
		 * @var		string
		 */
		public $type = 'Languages';

		protected function getInput()
		{
			$this->_nnelement = new nnElementLanguages();
			return $this->_nnelement->getInput( $this->name, $this->id, $this->value, $this->element->attributes(), $this->element->children() );
		}
	}
}