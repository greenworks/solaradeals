<?php
/**
 * Plugin Helper File
 *
 * @package     Modules Anywhere
 * @version     1.11.0
 *
 * @author      Peter van Westen <peter@nonumber.nl>
 * @link        http://www.nonumber.nl
 * @copyright   Copyright © 2011 NoNumber! All Rights Reserved
 * @license     http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
 */

// Ensure this file is being included by a parent file
defined( '_JEXEC' ) or die( 'Restricted access' );

// Import library dependencies
jimport( 'joomla.event.plugin' );

/**
* Plugin that places modules
*/
class plgSystemModulesAnywhereHelper
{
	function __construct( &$params )
	{
		$this->params = $params;
		$this->params->comment_start = '<!-- START: Modules Anywhere -->';
		$this->params->comment_end = '<!-- END: Modules Anywhere -->';
		$this->params->message_start = '<!--  Modules Anywhere Message: ';
		$this->params->message_end = ' -->';

		$tags = array();
		$tags[] = preg_quote( $this->params->module_tag, '#' );
		$tags[] = preg_quote( $this->params->modulepos_tag, '#' );
		if ( $this->params->handle_loadposition ) { $tags[] = 'loadposition'; }
		$tags = '('.implode( '|', $tags ).')';
		$this->params->tags = $tags;

		$bts =	'((?:<p(?: [^>]*)?>\s*)?)(?:<br ?/?>\s*)?';
		$bte =	'(?:\s*<br ?/?>)?((?:\s*</p>)?)';
		$this->params->regex = '#'
			.$bts.'((?:\{div(?: [^\}]*)\})?)'.$bte.'\s*'
			.$bts.'\{'.$tags.'(?: ([^\}\|]*))?((?:\|[^\}]+)?)\}'.$bte.'\s*'
			.$bts.'((?:\{/div\})?)'.$bte.'#s';

		$acl =& JFactory::getACL();
		$this->params->acl = $acl->get_group_data( $this->params->articles_security_level );
		$this->params->acl = $this->params->acl['4'];
		$this->params->acls = array();

		$user =& JFactory::getUser();
		$this->params->aid = $user->get( 'aid', 0 );
		$this->params->aid_jaclplus = $user->get( 'jaclplus', 0 );
	}

////////////////////////////////////////////////////////////////////
// ARTICLES
////////////////////////////////////////////////////////////////////

	function replaceInArticles ( &$article )
	{
		$message = '';

		if ( isset( $article->created_by ) ) {
			// Lookup group level of creator
			if ( !isset( $this->params->acls[$article->created_by] ) ) {
				$acl =& JFactory::getACL();
				$this->params->acls[$article->created_by] = $acl->getAroGroup( $article->created_by );
			}
			$article_group = $this->params->acls[$article->created_by];

			if ( !isset( $article_group->lft ) ) {
				$article_group->lft = 0;
			}

			// Set if security is passed
			// passed = creator is equal or higher than security group level
			if ( $this->params->acl > $article_group->lft ) {
				$message = JText::_( 'MA_OUTPUT_REMOVED_SECURITY' );
			}
		}

		if ( isset( $article->text ) ) {
			$this->processModules( $article->text, 'articles', $message );
		}
		if ( isset( $article->description ) ) {
			$this->processModules( $article->description, 'articles', $message );
		}
		if ( isset( $article->title ) ) {
			$this->processModules( $article->title, 'articles', $message );
		}
		if ( isset( $article->author ) ) {
			if ( isset( $article->author->name ) ) {
				$this->processModules( $article->author->name, 'articles', $message );
			} else if ( is_string( $article->author ) ) {
				$this->processModules( $article->author, 'articles', $message );
			}
		}
	}

////////////////////////////////////////////////////////////////////
// COMPONENTS
////////////////////////////////////////////////////////////////////

	function replaceInComponents()
	{
		$document =& JFactory::getDocument();
		$docType = $document->getType();

		if ( ( $docType == 'feed' || JRequest::getCmd( 'option' ) == 'com_acymailing' ) && isset( $document->items ) ) {
			$itemids = array_keys( $document->items );
			foreach ( $itemids as $i ) {
				$this->replaceInArticles( $document->items[$i] );
			}
		}

		if ( isset( $document->_buffer ) ) {
			$this->tagArea( $document->_buffer, 'component' );
		}

		// PDF
		if ( $docType == 'pdf' ) {
			if ( isset( $document->_header ) ) {
				$this->replaceInTheRest( $document->_header );
				$this->cleanLeftoverJunk( $document->_header );
			}
			if ( isset( $document->title ) ) {
				$this->replaceInTheRest( $document->title );
				$this->cleanLeftoverJunk( $document->title );
			}
			if ( isset( $document->_buffer ) ) {
				$this->replaceInTheRest( $document->_buffer );
				$this->cleanLeftoverJunk( $document->_buffer );
			}
		}
	}

////////////////////////////////////////////////////////////////////
// OTHER AREAS
////////////////////////////////////////////////////////////////////
	function replaceInOtherAreas()
	{
		$document =& JFactory::getDocument();
		$docType = $document->getType();

		// not in pdf's
		if ( $docType == 'pdf' ) { return; }

		$html = JResponse::getBody();
		if ( $html == '' ) { return; }

		if ( $docType != 'html' ) {
			$this->replaceInTheRest( $html );
		} else {
			if ( !( strpos( $html, '<body' ) === false ) && !( strpos( $html, '</body>' ) === false ) ) {
				$html_split = explode( '<body', $html, 2 );
				$body_split = explode( '</body>', $html_split['1'], 2 );

				// only do stuff in body
				$this->protect( $body_split['0'] );
				$this->replaceInTheRest( $body_split['0'] );

				$html_split['1'] = implode( '</body>', $body_split );
				$html = implode( '<body', $html_split );
			} else {
				$this->protect( $html );
				$this->replaceInTheRest( $html );
			}
		}

		$this->cleanLeftoverJunk( $html );
		$this->unprotect( $html );

		JResponse::setBody( $html );
	}

	function replaceInTheRest( &$str )
	{
		if ( $str == '' ) { return; }

		$document =& JFactory::getDocument();
		$docType = $document->getType();

		// COMPONENT
		if ( $docType == 'feed' || JRequest::getCmd( 'option' ) == 'com_acymailing' ) {
			$s = '#(<item[^>]*>)#s';
			$str = preg_replace( $s, '\1<!-- START: MODA_COMPONENT -->', $str );
			$str = str_replace( '</item>', '<!-- END: MODA_COMPONENT --></item>', $str );
		}
		if ( strpos( $str, '<!-- START: MODA_COMPONENT -->' ) === false ) {
			$this->tagArea( $str, 'MODA', 'component' );
		}

		$components = $this->params->components;
		if ( !is_array( $components ) ) {
			$components = explode( '|', $components );
		}

		$message = '';
		if ( in_array( JRequest::getCmd( 'option' ), $components ) ) {
			// For all components that are selected, set the meassage
			$message = JText::_( 'MA_OUTPUT_REMOVED_NOT_ENABLED' );
		}

		$components = $this->getTagArea( $str, 'MODA', 'component' );

		foreach ( $components as $component ) {
			$this->processModules( $component[1], 'components', $message );
			$str = str_replace( $component[0], $component[1], $str );
		}

		// EVERYWHERE
		$this->processModules( $str, 'other' );
	}

	function tagArea( &$str, $ext = 'EXT', $area = '' )
	{
		if ( $area ) {
			if ( is_array( $str ) ) {
				foreach ( $str as $key => $val ) {
					$this->tagArea( $val, $ext, $area );
					$str[ $key ] = $val;
				}
			} else if ( $str ) {
				$str = '<!-- START: '.strtoupper( $ext ).'_'.strtoupper( $area ).' -->'.$str.'<!-- END: '.strtoupper( $ext ).'_'.strtoupper( $area ).' -->';
				if ( $area == 'article_text' ) {
					$str = preg_replace( '#(<hr class="system-pagebreak".*?/>)#si', '<!-- END: '.strtoupper( $ext ).'_'.strtoupper( $area ).' -->\1<!-- START: '.strtoupper( $ext ).'_'.strtoupper( $area ).' -->', $str );
				}
			}
		}
	}

	function getTagArea( &$str, $ext = 'EXT', $area = '' )
	{
		$matches = array();
		if ( $str && $area ) {
			$start = '<!-- START: '.strtoupper( $ext ).'_'.strtoupper( $area ).' -->';
			$end = '<!-- END: '.strtoupper( $ext ).'_'.strtoupper( $area ).' -->';
			$matches = explode( $start, $str );
			array_shift( $matches );
			foreach ( $matches as $i => $match ) {
				list( $text ) = explode( $end, $match, 2 );
				$matches[$i] = array(
					$start.$text.$end,
					$text
				);
			}
		}
		return $matches;
	}

	function processModules( &$string, $area = 'articles', $message = '' )
	{
		jimport('joomla.application.module.helper');
		JPluginHelper::importPlugin( 'content' );

		if (
			$area == 'articles' && !$this->params->articles_enable ||
			$area == 'components' && !$this->params->components_enable ||
			$area == 'other' && !$this->params->other_enable
		) {
			$message = JText::_( 'MA_OUTPUT_REMOVED_NOT_ENABLED' );
		}

		$regex = $this->params->regex;
		if ( @preg_match( $regex.'u', $string ) ) {
			$regex .= 'u';
		}
		$count = 0;
		while ( $count++ < 10 && preg_match_all( $this->params->regex, $string, $matches, PREG_SET_ORDER ) > 0 ) {
			foreach ( $matches as $match ) {
				$module_html = '';
				if ( $message != '' ) {
					if ( $this->params->place_comments ) {
						$module_html = $this->params->message_start.$message.$this->params->message_end;
					}
				} else {
					$type = trim( $match['5'] );
					$module = trim( $match['6'] );
					$vars = trim( $match['7'] );

					$style = $this->params->style;
					$overrides = array();

					if ( $this->params->override_style || $this->params->override_settings ) {
						$vars = str_replace( '\|', '[:MA_BAR:]', $vars );
						$vars = explode( '|', $vars );
						foreach ( $vars as $var ) {
							$var = trim( str_replace( '[:MA_BAR:]', '|', $var ) );
							if ( !$var ) {
								continue;
							}
							if ( strpos( $var, '=' ) === false ) {
								if ( $this->params->override_style ) {
									$style = $var;
								}
							} else {
								if ( $this->params->override_settings && $type == $this->params->module_tag ) {
									list( $key, $val ) = explode( '=', $var, 2 );
									$overrides[$key] = $val;
								}
							}
						}
					}

					if ( $type == $this->params->module_tag ) {
						// module
						$module_html = $this->processModule( $module, $style, $overrides );
					} else {
						// module position
						$module_html = $this->processPosition( $module, $style );
					}

					if ( trim( $match['2'] ) ) {
						$extra = trim( preg_replace( '#\{div(.*)\}#si', '\1', $match['2'] ) );

						$div = '';
						if ( $extra ) {
							$extra = explode( '|', $extra );
							$extras = new stdClass();
							foreach ( $extra as $e ) {
								if ( !( strpos( $e, ':' ) === false ) ) {
									list( $key, $val ) = explode( ':', $e, 2 );
									$extras->$key = $val;
								}
							}
							if ( isset( $extras->class ) ) {
								$div .= 'class="'.$extras->class.'"';
							}

							$style = array();
							if ( isset( $extras->width ) ) {
								if ( is_numeric( $extras->width ) ) {
									$extras->width .= 'px';
								}
								$style[] = 'width:'.$extras->width;
							}
							if ( isset( $extras->height ) ) {
								if ( is_numeric( $extras->height ) ) {
									$extras->height .= 'px';
								}
								$style[] = 'height:'.$extras->height;
							}
							if ( isset( $extras->align ) ) {
								$style[] = 'float:'.$extras->align;
							} else if ( isset( $extras->float ) ) {
								$style[] = 'float:'.$extras->float;
							}

							if ( !empty( $style ) ) {
								$div .= ' style="'.implode( ';', $style ).';"';
							}
						}
						$module_html = trim( '<div '.trim($div) ).'>'.$module_html.'</div>';
					}
				}
				$module_html = $this->params->comment_start.$module_html.$this->params->comment_end;

				if ( !$match['2'] ) {
					$module_html = $match['1'].$match['3'].$module_html.$match['9'].$match['11'];
				}

				$string = str_replace( $match['0'], $module_html, $string );
			}
		}
	}
	function processPosition( $position, $style = 'none' )
	{
		$document	=& JFactory::getDocument();
		$renderer	= $document->loadRenderer( 'module' );

		$html = '';
		foreach ( JModuleHelper::getModules( $position ) as $mod ) {
			$html .= $renderer->render( $mod, array( 'style'=>$style ) );
		}
		return $html;
	}

	function processModule( $module, $style = 'none', $overrides = array() )
	{
		$db =& JFactory::getDBO();

		$where = ' AND ( title='.$db->quote( html_entity_decoder( $module ) ).'';
		if ( is_numeric( $module ) ) {
			$where .= ' OR id='.$module;
		}
		$where .=  ' ) ';
		if ( !$this->params->ignore_state ) {
			$where .= ' AND published = 1';
		}

		$query =
			'SELECT *'
			.' FROM #__modules'
			.' WHERE access '.( defined( '_JACL' ) ? 'IN ('.$this->params->aid_jaclplus.')' : '<= '. (int) $this->params->aid )
			.' AND client_id = 0'
			.$where
			.' ORDER BY ordering'
			.' LIMIT 1';

		$db->setQuery( $query );
		$module = $db->loadObject();

		$html = '';
		if ( $module ) {
			//determine if this is a custom module
			$module->user = ( substr( $module->module, 0, 4 ) == 'mod_' ) ? 0 : 1;

			// set style
			$module->style = $style;

			// override module settings
			$params = '';
			foreach ( $overrides as $key => $val ) {
				$params .= "\n".$key.'='.$val;
			}
			if ( $params != '' ) {
				$module->params = trim( $module->params ).$params."\n\n";
			}

			$document = clone( JFactory::getDocument() );
			$document->_type = 'html';
			$renderer = $document->loadRenderer( 'module' );
			$html = $renderer->render( $module, array( 'style'=>$style ) );
		}
		return $html;
	}

		/*
	 * Protect input and text area's
	 */
	function protect( &$string )
	{
		if (	in_array( JRequest::getCmd( 'task' ), array( 'edit' ) )
			||	in_array( JRequest::getCmd( 'view' ), array( 'edit', 'form' ) )
			||	in_array( JRequest::getCmd( 'layout' ), array( 'edit', 'form', 'write' ) )
			||	in_array( JRequest::getCmd( 'option' ), array( 'com_contentsubmit', 'com_cckjseblod' ) )
		) {
			// Protect complete adminForm (to prevent articles from being created when editing articles and such)
			$unprotected = '{'.$this->params->module_tag;
			$protected = $this->protectStr( $unprotected );
			$string = preg_replace( '#(<'.'form [^>]*(id|name)="adminForm")#si', '<!-- TMP_START_EDITOR -->\1', $string );
			$string = explode( '<!-- TMP_START_EDITOR -->', $string );
			foreach ( $string as $i => $str ) {
				if ( !empty( $str ) != '' && fmod( $i, 2 ) ) {
					if ( !( strpos( $str, $unprotected ) === false ) ) {
						$str = explode( '</form>', $str, 2 );
						$str['0'] = str_replace( $unprotected, $protected, $str['0'] );
						$string[$i] = implode( '</form>', $str );
					}
				}
			}
			$string = implode( '', $string );
		}
	}

	function unprotect( &$string )
	{
		$string = str_replace( $this->protectStr( '{'.$this->params->module_tag ), '{'.$this->params->module_tag, $string );
	}

	function protectStr( $string )
	{
		$string = base64_encode( $string );
		return $string;
	}

	function cleanLeftoverJunk( &$str )
	{
		$regex = '#\{'.$this->params->tags.'(?: [^\}]*)?\}#s';
		if ( @preg_match( $regex.'u', $str ) ) {
			$regex .= 'u';
		}
		$str = preg_replace( $regex, '', $str );
		$str = preg_replace( '#<\!-- (START|END): MODA_[^>]* -->#', '', $str );
		if ( !$this->params->place_comments ) {
			$str = str_replace( array(
					$this->params->comment_start, $this->params->comment_end,
					htmlentities( $this->params->comment_start ), htmlentities( $this->params->comment_end ),
					urlencode( $this->params->comment_start ), urlencode( $this->params->comment_end )
				), '', $str );
			$str = preg_replace( '#'.preg_quote( $this->params->message_start, '#' ).'.*?'.preg_quote( $this->params->message_end, '#' ).'#', '', $str );
		}
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