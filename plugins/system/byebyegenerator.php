<?php

/**
* @package plugin ByeByeGenerator
* @copyright (C) 2010-2011 RicheyWeb - www.richeyweb.com
* @license http://www.gnu.org/copyleft/gpl.html GNU/GPL
* ByeByeGenerator Copyright (c) 2010 Michael Richey.
* ByeByeGenerator is licensed under the http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
*
* ByeByeGenerator version 1.4 for Joomla 1.5.x devloped by RicheyWeb
*
*/
// no direct access
defined( '_JEXEC' ) or die( 'Restricted access' );

jimport( 'joomla.plugin.plugin' );

/**
 * ByeByeGenerator system plugin
 */
class plgSystemByeByeGenerator extends JPlugin
{
	/**
	 * Constructor
	 *
	 * @access	protected
	 * @param	object	$subject The object to observe
	 * @param 	array   $config  An array that holds the plugin configuration
	 * @since	1.0
	 */
	function plgSystemByeByeGenerator( &$subject, $config )
	{
		parent::__construct( $subject, $config );
	}
	
	/* The generator tag can be altered at any time before the page is rendered */
	function onAfterDispatch()
	{
		/* do we bother, are we customizing the generator? */
		if ((int)$this->params->get('generator') == 0) {
			/* test that the page is not administrator */
			$mainframe = JFactory::getApplication();
			if ($mainframe->isSite()) {
				/* test that the document is HTML output which will contain a generator*/
				$document =& JFactory::getDocument();
				if($document->getType() == 'html') {
					$document->setGenerator($this->params->get('custom'));
				}
			}
		}
	}

	/* The generator tag isn't added until the document is rendered */
	function onAfterRender()
	{
		/* do we bother, are we removing the generator? */
		if ((int)$this->params->get('generator') == 1 OR (int)$this->params->get('robots') == 1) {
			/* test that the page is not administrator */
			$mainframe = JFactory::getApplication();
			if ($mainframe->isSite()) {
				/* test that the document is HTML output */
				$document =& JFactory::getDocument();
				if($document->getType() == 'html') {
					/* Get the document body - the generator tag has been added */
					$buffer = JResponse::getBody();
					$replace = false;
					if ((int)$this->params->get('generator') == 1) {
						/* find the location of the generator tag and retrieve the full tag to determine its length */
						preg_match('/<meta name="generator".*\/>/',$buffer,$position,PREG_OFFSET_CAPTURE);
						/* replace the existing body with a new body minus the generator tag starting position and length */
						$buffer=substr($buffer,0,$position[0][1]).substr($buffer,$position[0][1]+strlen($position[0][0])+1);
						$replace = true;
					}
					if ((int)$this->params->get('robots') == 1) {
						/* find the location of the generator tag and retrieve the full tag to determine its length */
						preg_match('/<meta name="robots".*\/>/',$buffer,$position,PREG_OFFSET_CAPTURE);
						/* replace the existing body with a new body minus the generator tag starting position and length */
						$buffer=substr($buffer,0,$position[0][1]).substr($buffer,$position[0][1]+strlen($position[0][0])+1);
						$replace = true;
					}
					if($replace) JResponse::setBody($buffer);
				}
			}
		}
	}
}
