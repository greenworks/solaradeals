<?php
/*------------------------------------------------------------------------
# En Masse - Social Buying Extension 2010
# ------------------------------------------------------------------------
# By Matamko.com
# Copyright (C) 2010 Matamko.com. All Rights Reserved.
# @license - http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
# Websites: http://www.matamko.com
# Technical Support:  Visit our forum at www.matamko.com
-------------------------------------------------------------------------*/


// no direct access
defined('_JEXEC') or die('Restricted access');
/*
 * Function to convert a system URL to a SEF URL
 */

function EnmasseBuildRoute(&$query)
{
	$segments = array();
	
	if(isset($query['controller'])) // NON-Menu-item
	{
		$segments[] = $query['controller'];
		
		// After you remove the Itemid, it will display the component variable e.g. index.php/component/enmasse/....
		unset( $query['Itemid']);
		// To put back the current Itemid of the selected menu, so that it will not display the component variable in the Link
		// but instead, it will display the menu name that the user is in now
		$activeMenu = JSite::getMenu()->getActive();
		if(isset($activeMenu))
			$query['Itemid'] = $activeMenu-> id ;
		
		if(isset($query['task']))
		{
			$segments[] = $query['task'];
			if($query['controller']=="deal" && $query['task']=="view" && !empty($query['id']))
			{
				$deal = getById($query['id']);
				$segments[] = $deal->id;
				$segments[] = $deal->slug_name;
				unset( $query['id'] );
			}
			elseif($query['controller']=="shopping" && $query['task']=="addToCart" && !empty($query['dealId']))
			{
				$deal = getById($query['dealId']);
				$segments[] = $deal->id;
				$segments[] = $deal->slug_name;
				unset( $query['dealId'] );
			}
			unset( $query['task'] );
		}
		unset( $query['controller'] );
	}
	
	// Menu item links will be handled by the "include/router.php" instead
	
	return $segments;
}

/*
 * Function to convert a SEF URL back to a system URL
 */

function EnmasseParseRoute($segments)
{
	$vars = array();
	if(isset($segments[0]))
		$vars['controller'] = $segments[0];
	if(isset($segments[1]))
	{
		$vars['task'] = $segments[1];
		
		if($vars['controller']=="deal" && $vars['task']=="view")
			$vars['id'] = $segments[2];
		elseif($vars['controller']=="shopping" && $vars['task']=="addToCart")
			$vars['dealId'] = $segments[2];
	}
	return $vars;
}

function getById($id)
{
		$db =& JFactory::getDBO();
		$query = "	SELECT
						* 
					FROM 
						#__enmasse_deal 
					WHERE
						id = $id";

		$db->setQuery( $query );
		$deal = $db->loadObject();
		if ($db->getErrorNum())
		{
			echo $db->stderr();
			return false;
		}
		return $deal;
}

?>