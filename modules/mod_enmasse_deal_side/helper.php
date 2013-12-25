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

defined('_JEXEC') or die('Direct Access to this location is not allowed.');
 
class ModEnmasseDealSideHelper
{
    /**
     * Returns a list of post items
    */
    public function getItem($dealId)
    {
        // get a reference to the database
        $db = &JFactory::getDBO();
 
        // if dealId is not empty and is available then get the item
        // else get a random item in deal list
        if(!empty($dealId) && ModEnmasseDealSideHelper::checkAvailableItem($db,$dealId)== true) 
          $query = 'SELECT id,name,end_at,pic_dir,cur_sold_qty  FROM `#__enmasse_deal`  WHERE id = ' . $dealId  . '';
        else  
          $query = "SELECT id,name,end_at,pic_dir,cur_sold_qty FROM `#__enmasse_deal` WHERE UNIX_TIMESTAMP(end_at) > UNIX_TIMESTAMP(NOW()) ORDER BY RAND() LIMIT 1";
 
        $db->setQuery($query);
        $item = $db->loadObject();
 
        return $item;
    } //end getItems
    
    //======================
    // to check if the dealId is available
    // return true if available
    public function checkAvailableItem($db,$dealId)
    {
    	$query = 'SELECT
    	                *
    	          FROM 
    	               `#__enmasse_deal`
    	          WHERE 
    	               id = '.$dealId;
    	$db->setQuery($query);
    	$item = $db->loadObject();
    	
    	if(empty($item)) 
    		return false;
        else 
            return true;
    }
 
} //end ModHelloWorld2Helper

?>