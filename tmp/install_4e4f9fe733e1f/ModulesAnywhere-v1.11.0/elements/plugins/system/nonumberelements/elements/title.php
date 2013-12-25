<?php
/**
 * Element: Title
 * Displays a title with a bunch of extras, like: description, image, versioncheck
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
 * Title Element
 *
 * Available extra parameters:
 * title			The title
 * description		The description
 * message_type		none, message, notice, error?
 * image			Image (and path) to show on the right
 * show_apply		Show an apply tick image on the right (only if the image is not set)
 * url				The main url
 * download_url		The url of the download location
 * help_url			The url of the help page
 * version_url		The url to the new version folder (default = [url]/versions/)
 * version_path		The path to version folder
 * version_file		The filename of the current version file
 */
class nnElementTitle
{
	function getLabel( $name, $id, $label, $description, $params, $j15 = 0 )
	{
		$this->params = $params;

		$nostyle =		$this->def( 'nostyle' );

		if ( $nostyle && $label && $description ) {
			$output = '<label id="'.$id.'-lbl" for="'.$id.'"';
			if ($description) {
				$output .= ' class="hasTip" title="'.JText::_($label).'::'.JText::_($description).'">';
			} else {
				$output .= '>';
			}
			$output .= JText::_( $label ).'</label>';
			return $output;
		}
		return;
	}

	function getInput( $name, $id, $value, $params, $children, $j15 = 0 )
	{
		$this->params = $params;

		$start =		$this->def( 'start' );
		$end =			$this->def( 'end' );
		$blocktype =	$this->def( 'blocktype' );

		if ( $blocktype == 'spacer' ) {
			return;
		}

		$document =& JFactory::getDocument();
		$document->addStyleSheet( JURI::root(true).'/plugins/system/nonumberelements/css/style.css' );

		if ( $end ) {
			$html = '';
			if ( $j15 ) {
				$random = rand( 100000, 999999 );
				$html .= '<div id="end-'.$random.'"></div><script type="text/javascript">NoNumberElementsHideTD( "end-'.$random.'" );</script>';
				$html .= '</td></tr></table>';
			} else {
				$html .= '<div style="clear: both;"></div></li></ul>';
			}
			if ( $j15 && $blocktype == 'fieldset' ) {
				$html .= '</fieldset>';

			} else {
				$html .= '</div></div>';
			}
			return $html;
		}
		$description =	$this->def( 'description' );
		$nostyle =		$this->def( 'nostyle' );

		$title =		$this->def( 'label' );
		$lang_folder =	$this->def( 'language_folder' );
		$message_type =	$this->def( 'message_type' );
		$image =		$this->def( 'image' );
		$image_w =		$this->def( 'image_w' );
		$image_h =		$this->def( 'image_h' );
		$show_apply =	$this->def( 'show_apply' );
		$toggle =		$this->def( 'toggle' );
		$tooltip =		$this->def( 'tooltip' );

		// The main url
		$url =				$this->def( 'url' );
		$help =				$this->def( 'help_url' );
		$extension =		$this->def( 'extension' );
		$xml =				$this->def( 'xml' );
		$version =			$this->def( 'version' );
		$version_file =		$this->def( 'version_file' );

		if ( !$extension ) {
			$extension = str_replace( 'version_', '', $version_file );
		}

		$msg = '';

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
			$lang = str_replace( '_', '-', $language->getTag() );

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
		}

		if ( $nostyle && $description ) {
			return $description;
		}

		if ( $title ) {
			$title = JText::_( $title );
		}

		$user = JFactory::getUser();
		if( strlen( $version ) && strlen( $version_file ) && ( $user->usertype == 'Super Administrator' || $user->usertype == 'Administrator' ) ) {
			// Import library dependencies
			require_once JPATH_PLUGINS.DS.'system'.DS.'nonumberelements'.DS.'helpers'.DS.'versions.php';
			$versions = NNVersions::instance();

			$msg = $versions->getMessage( $extension, $xml, $version, 1 );
			if ( $version ) {
				if ( $title ) {
					$title .= ' v'.$version;
				} else {
					$title = JText::_( 'Version' ).' '.$version;
				}
			}
		} else if ( $xml ) {
			$xml = JApplicationHelper::parseXMLInstallFile( JPATH_SITE.DS.( str_replace( '/', DS, $xml ) ) );
			if ( $xml && isset( $xml['version'] ) ) {
				$version = $xml['version'];
			}
			if ( $version ) {
				if ( $title ) {
					$title .= ' v'.$version;
				} else {
					$title = JText::_( 'Version' ).' '.$version;
				}
			}
		}

		if ( $url ) {
			$url = '<a href="'.$url.'" target="_blank" title="'.$title.'">';
		}

		if ( $image ) {
			$image = str_replace( '/', "\n", str_replace( '\\', '/', $image ) );
			$image = explode( "\n", trim( $image ) );
			if ( $image['0'] == 'administrator' ) {
				$image['0'] = JURI::base(true);
			} else {
				$image['0'] = JURI::root(true).'/'.$image['0'];
			}
			$image = $url.'<img src="'.implode( '/', $image ).'" border="0" style="float:right;margin-left:10px" alt=""';
			if ( $image_w ) {
				$image .= ' width="'.$image_w.'"';
			}
			if ( $image_h ) {
				$image .= ' height="'.$image_h.'"';
			}
			$image .= ' />';
			if ( $url ) { $image .= '</a>'; }
		}

		if ( $url ) { $title = $url.$title.'</a>'; }

		if ( $help ) { $help = '<a href="'.$help.'" target="_blank" title="'.JText::_( 'NN_MORE_INFO' ).'">'.JText::_( 'NN_MORE_INFO' ).'...</a>'; }

		if ( $title ) { $title = html_entity_decoder( $title ); }

		$html = '';
		if ( $image ) { $html .= $image; }
		if ( $show_apply ) {
			$onclick = '';
			if ( $j15 ) {
				$onclick = 'submitbutton( \'apply\' );';
			} else {
				$bar = JToolBar::getInstance('toolbar');
				foreach( $bar->getItems() as $b ) {
					if ( isset( $b['3'] ) && $b['1'] == 'apply' ) {
						$onclick = 'Joomla.submitbutton(\''.$b['3'].'\')';
					}
				}

			}
			if ( $onclick ) {
				$html .= '<a href="#" onclick="'.$onclick.'" title="'.JText::_( 'Apply' ).'"><img style="float:right;" border="0" alt="'.JText::_( 'Apply' ).'" src="'.JURI::root().'plugins/system/nonumberelements/images/tick.png"/></a>';
			}
		}

		if ( $toggle && $description ) {
			$el = 'document.getElementById( \''.$id.'description\' )';
			$onclick =
				'if( this.innerHTML == \''.JText::_( JText::_( 'Show' ).' '.$title ).'\' ){'
					.$el.'.style.display = \'block\';'
					.'this.innerHTML = \''.JText::_( JText::_( 'Hide' ).' '.$title ).'\';'
				.'}else{'
					.$el.'.style.display = \'none\';'
					.'this.innerHTML = \''.JText::_( JText::_( 'Show' ).' '.$title ).'\';'
				.'}'
				.'this.blur();return false;'
				;
			$html .= '<div class="button2-left" style="margin:0px 0px 5px 0px;"><div class="blank"><a href="javascript://;" onclick="'.$onclick.'">'.JText::_( JText::_( 'Show' ).' '.$title ).'</a></div></div>'."\n";
			$html .= '<br clear="all" />';
			$html .= '<div id="'.$id.'description" style="display:none;">';
		} else if ( $title ) {
			if ( !$j15 || $blocktype != 'fieldset' ) {
				$html .= '<h4 style="margin: 0px;">'.$title.'</h4>';
			}
		}
		if ( $description && !$tooltip ) { $html .= $description; }
		if ( $help ) { $html .= '<p>'.$help.'</p>'; }
		if ( $description ) {
			$html = '<div class="nn_title">'.$html.'</div>';
		}

		if ( $toggle && $description ) {
			$html .= '</div>';
		}
		if ( $message_type ) {
			$html = '<dl id="system-message"><dd class="'.$message_type.'"><ul><li>'.html_entity_decoder( $html ).'</li></ul></dd></dl>';
		} else {
			if ( ( !$j15 || $blocktype != 'fieldset' ) && !$nostyle ) {
				$html = '<div class="panel" style="clear:both;'.( $j15 ? '' : 'margin-bottom:10px;' ).'"><div style="padding: 2px 5px;">'.$html.'<div style="clear: both;"></div>';
			}
			if ( $start ) {
				if ( $j15 && $blocktype == 'fieldset' ) {
					if ( $description && $tooltip ) {
						$title = '<span class="hasTip" title="'.htmlentities( $title.'::'.$description ).'">'.$title.'</span>';
					}
					$html = '<fieldset class="adminform"><legend>'.$title.'</legend>'.$html;
				}

				if ( $j15 ) {
					$html .= '<table width="100%" class="paramlist admintable" cellspacing="1">';
					$html .= '<tr><td colspan="2" class="paramlist_value">';
					$random = rand( 100000, 999999 );
					$html .= '<div id="end-'.$random.'"></div><script type="text/javascript">NoNumberElementsHideTD( "end-'.$random.'" );</script>';
				} else {
					$html .= '<ul class="adminformlist"><li>';
				}
			} else {
				$html .= '</div></div>';
			}
		}

		if ( $msg ) { $html = $msg.$html; }

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
	class JElementTitle extends JElement
	{
		/**
		 * Element name
		 *
		 * @access	protected
		 * @var		string
		 */
		var	$_name = 'Title';

		function fetchTooltip( $label, $description, &$node, $control_name, $name )
		{
			$this->_nnelement = new nnElementTitle();
			return $this->_nnelement->getLabel( $control_name.'['.$name.']', $control_name.$name, $label, $description, $node->attributes(), 1 );
		}

		function fetchElement( $name, $value, &$node, $control_name )
		{
			return $this->_nnelement->getInput( $control_name.'['.$name.']', $control_name.$name, $value, $node->attributes(), $node->children(), 1 );
		}
	}
} else {
	// For Joomla 1.6
	class JFormFieldTitle extends JFormField
	{
		/**
		 * The form field type
		 *
		 * @var		string
		 */
		public $type = 'Title';

		protected function getLabel()
		{
			$this->_nnelement = new nnElementTitle();
			return $this->_nnelement->getLabel( $this->name, $this->id, $this->label, $this->description, $this->element->attributes() );
		}

		protected function getInput()
		{
			return $this->_nnelement->getInput( $this->name, $this->id, $this->value, $this->element->attributes(), $this->element->children() );
		}
	}
}