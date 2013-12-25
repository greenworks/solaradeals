<?php
/**
 * Element: Slide
 * Element to create a new slide pane
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
 * Slide Element
 */
class nnElementSlide
{
	function getInput( $name, $id, $value, $params, $children, $j15 = 0 )
	{
		$this->params = $params;

		$label =		$this->def( 'label' );
		$description =	$this->def( 'description' );
		$lang_folder =	$this->def( 'language_folder' );
		$show_apply =	$this->def( 'show_apply' );

		$html = '</td></tr></table></div></div>';
		$html .= '<div class="panel"><h3 class="jpane-toggler title" id="advanced-page"><span>';
		$html .= html_entity_decoder( JText::_( $label ) );
		$html .= '</span></h3>';
		$html .= '<div class="jpane-slider content"><table width="100%" class="paramlist admintable" cellspacing="1"><tr><td colspan="2" class="paramlist_value">';


		if ( $description ) {
			// variables
			$v1 = $this->def( 'var1' );
			$v2 = $this->def( 'var2' );
			$v3 = $this->def( 'var3' );
			$v4 = $this->def( 'var4' );
			$v5 = $this->def( 'var5' );

			$description = html_entity_decoder( trim( JText::sprintf( $description, $v1, $v2, $v3, $v4, $v5 ) ) );
		}

		if ( $lang_folder ) {
			$lang_file = $this->def( 'language_file' );
			jimport( 'joomla.filesystem.file' );

			// Include extra language file
			$language =& JFactory::getLanguage();
			$lang = str_replace( '_', '-', $language->_lang );

			if ( strpos( $lang_folder, '/administrator' ) === 0 ) {
				$lang_folder = str_replace( '/', DS, str_replace( '/administrator', JPATH_ADMINISTRATOR, $lang_folder ) );
			} else {
				$lang_folder = JPATH_SITE.str_replace( '/', DS, $lang_folder );
			}

			$inc = '';
			$lang_file = ( $lang_file ? '.'.$lang_file : '' ).'.inc.php';
			if ( JFile::exists( $lang_folder.DS.$lang.DS.$lang.$lang_file ) ) {
				$inc = $lang_folder.DS.$lang.DS.$lang.$lang_file;
			} else if ( JFile::exists( $lang_folder.DS.$lang.$lang_file ) ) {
				$inc = $lang_folder.DS.$lang.$lang_file;
			}
			if ( !$inc && $lang != 'en-GB' ) {
				$lang = 'en-GB';
				if ( JFile::exists( $lang_folder.DS.$lang.DS.$lang.$lang_file ) ) {
					$inc = $lang_folder.DS.$lang.DS.$lang.$lang_file;
				} else if ( JFile::exists( $lang_folder.DS.$lang.$lang_file ) ) {
					$inc = $lang_folder.DS.$lang.$lang_file;
				}
			}
			if ( $inc ) {
				include $inc;
			}
		}

		if ( $description ) {
			$description = str_replace( 'span style="font-family:monospace;"', 'span class="nn_code"', $description );
			if ( $description['0'] != '<' ) {
				$description = '<p>'.$description.'</p>';
			}
			$html .= '<div class="panel"><div style="padding: 2px 5px;">';
			if ( $show_apply ) {
				$apply_button = '<a href="#" onclick="submitbutton( \'apply\' );" title="'.JText::_( 'Apply' ).'"><img align="right" border="0" alt="'.JText::_( 'Apply' ).'" src="images/tick.png"/></a>';
				$html .= $apply_button;
			}
			$html .= $description;
			$html .= '<div style="clear: both;"></div></div></div>';
		}

		return $html;
	}

	private function def( $val, $default = '' )
	{
		return ( isset( $this->params[$val] ) && (string) $this->params[$val] != '' ) ? (string) $this->params[$val] : $default;
	}
}

if ( !function_exists( 'html_entity_decoder' ) ) {
	function html_entity_decoder( $given_html, $quote_style = ENT_QUOTES, $charset = 'UTF-8' )
	{
		if ( is_array( $given_html ) ) {
			foreach( $given_html as $i => $html ) {
				$given_html[$i] = html_entity_decoder( $html );
			}
			return $given_html;
		}
		return html_entity_decode( $given_html, $quote_style, $charset );
	}
}

if ( version_compare( JVERSION, '1.6.0', 'l' ) ) {
	// For Joomla 1.5
	class JElementSlide extends JElement
	{
		/**
		 * Element name
		 *
		 * @access	protected
		 * @var		string
		 */
		var	$_name = 'Slide';

		function fetchTooltip( $label, $description, &$node, $control_name, $name )
		{
			return;
		}

		function fetchElement( $name, $value, &$node, $control_name )
		{
			$this->_nnelement = new nnElementSlide();
			return $this->_nnelement->getInput( $control_name.'['.$name.']', $control_name.$name, $value, $node->attributes(), $node->children(), 1 );
		}
	}
} else {
	// For Joomla 1.6
	class JFormFieldSlide extends JFormField
	{
		/**
		 * The form field type
		 *
		 * @var		string
		 */
		public $type = 'Slide';

		protected function getLabel()
		{
			return;
		}

		protected function getInput()
		{
			$this->_nnelement = new nnElementSlide();
			return $this->_nnelement->getInput( $this->name, $this->id, $this->value, $this->element->attributes(), $this->element->children() );
		}
	}
}