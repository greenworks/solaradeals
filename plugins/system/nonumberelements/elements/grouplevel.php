<?php
/**
 * Element: Group Level
 * Displays a select box of backend group levels
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
 * Group Level Element
 *
 * Available extra parameters:
 * root				The user group to use as root (default = USERS)
 * showroot			Show the root in the list
 * multiple			Multiple options can be selected
 * notregistered	Add an option for 'Not Registered' users
 */
class nnElementGroupLevel
{
	function getInput( $name, $id, $value, $params, $children )
	{
		$this->params = $params;

		$root = $this->def( 'root', 'USERS' );
		$showroot = $this->def( 'showroot' );
		if ( strtoupper( $root ) == 'USERS' && $showroot == '' ) { $showroot = 0; }
		$multiple =	 $this->def( 'multiple' );
		$show_all =	$this->def( 'show_all' );
		$notregistered = $this->def( 'notregistered' );

		$control = $name.'';
		$attribs = 'class="inputbox"';

		$groups = $this->getUserGroups();
		$options = array();
		if ( $show_all ) {
			$option = new stdClass();
			$option->value = -1;
			$option->text = '- '.JText::_( 'NN_ALL' ).' -';
			$option->disable = '';
			$options[] = $option;
		}
		if ( $notregistered ) {
			$option = new stdClass();
			$option->value = 0;
			$option->text = JText::_( 'NN_NOT_LOGGED_IN' );
			$option->disable = '';
			$options[] = $option;
		}

		foreach ( $groups as $group ) {
			$option = new stdClass();
			$option->value = $group->id;
			$level = '';
			for( $i = 0; $i < $group->level; $i++ ) {
				$level .= '- ';
			}
			$option->text = trim( $level.$group->title );
			$option->disable = '';
			$options[] = $option;
		}

		if ( $multiple ) {
			if( !is_array( $value ) ) {
				$value = explode( ',', $value );
			}

			$attribs .= ' multiple="multiple"';
			$control .= '[]';
		}

		return JHTML::_( 'select.genericlist', $options, $control, $attribs, 'value', 'text', $value, $id );
	}

	protected function getUserGroups()
	{
		// Get a database object.
		$db = JFactory::getDBO();

		// Get the user groups from the database.
		$db->setQuery(
			'SELECT a.id, a.title, a.parent_id AS parent, COUNT(DISTINCT b.id) AS level' .
			' FROM #__usergroups AS a' .
			' LEFT JOIN `#__usergroups` AS b ON a.lft > b.lft AND a.rgt < b.rgt' .
			' GROUP BY a.id' .
			' ORDER BY a.lft ASC'
		);
		$options = $db->loadObjectList();

		return $options;
	}

	function getInput15( $name, $id, $value, $params, $children )
	{
		$this->params = $params;

		$root =				$this->def( 'root', 'USERS' );
		$showroot =			$this->def( 'showroot' );
		if ( strtoupper( $root ) == 'USERS' && $showroot == '' ) { $showroot = 0; }
		$multiple =			$this->def( 'multiple' );
		$notregistered =	$this->def( 'notregistered' );

		$control = $name.'';
		$attribs = 'class="inputbox"';

		$acl		=& JFactory::getACL();
		$options =	$acl->get_group_children_tree( null, $root, $showroot );
		if ( $notregistered ) {
			$option = new stdClass();
			$option->value = 0;
			$option->text = JText::_( 'NN_NOT_LOGGED_IN' );
			$option->disable = '';
			array_unshift( $options, $option );
		}

		if ( $multiple ) {
			if( !is_array( $value ) ) {
				$value = explode( ',', $value );
			}

			$attribs .= ' multiple="multiple"';
			$control .= '[]';

			if ( in_array( 29, $value ) ) {
				$value[] = 18;
				$value[] = 19;
				$value[] = 20;
				$value[] = 21;
			}
			if ( in_array( 30, $value ) ) {
				$value[] = 23;
				$value[] = 24;
				$value[] = 25;
			}
		}

		return JHTML::_( 'select.genericlist', $options, $control, $attribs, 'value', 'text', $value, $id );
	}

	private function def( $val, $default = '' )
	{
		return ( isset( $this->params[$val] ) && (string) $this->params[$val] != '' ) ? (string) $this->params[$val] : $default;
	}
}

if ( version_compare( JVERSION, '1.6.0', 'l' ) ) {
	// For Joomla 1.5
	class JElementGroupLevel extends JElement
	{
		/**
		 * Element name
		 *
		 * @access	protected
		 * @var		string
		 */
		var	$_name = 'GroupLevel';

		function fetchElement( $name, $value, &$node, $control_name )
		{
			$this->_nnelement = new nnElementGroupLevel();
			return $this->_nnelement->getInput15( $control_name.'['.$name.']', $control_name.$name, $value, $node->attributes(), $node->children() );
		}
	}
} else {
	// For Joomla 1.6
	class JFormFieldGroupLevel extends JFormField
	{
		/**
		 * The form field type
		 *
		 * @var		string
		 */
		public $type = 'GroupLevel';

		protected function getInput()
		{
			$this->_nnelement = new nnElementGroupLevel();
			return $this->_nnelement->getInput( $this->name, $this->id, $this->value, $this->element->attributes(), $this->element->children() );
		}
	}
}