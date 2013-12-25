<?php
/**
* @Copyright Freestyle Joomla (C) 2010
* @license GNU/GPL http://www.gnu.org/copyleft/gpl.html
*     
* This file is part of Freestyle Support Portal
* This program is free software: you can redistribute it and/or modify
* it under the terms of the GNU General Public License as published by
* the Free Software Foundation, either version 3 of the License, or
* (at your option) any later version.
* 
* This program is distributed in the hope that it will be useful,
* but WITHOUT ANY WARRANTY; without even the implied warranty of
* MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
* GNU General Public License for more details.
* 
* You should have received a copy of the GNU General Public License
* along with this program.  If not, see <http://www.gnu.org/licenses/>.
**/
?>
<?php defined('_JEXEC') or die('Restricted access'); ?>
<?php

//SENC

class JElementPagefaq extends JElement
{
   var   $_name = 'Pagefaq';

   function fetchElement($name, $value, &$node, $control_name)
   {
        
        $categories[] = JHTML::_('select.option', '-1', JText::_('Use default params'), 'id', 'title');
        $categories[] = JHTML::_('select.option', '0', JText::_('Do not display'), 'id', 'title');
        
        $query = 'SELECT name as title, id, link FROM #__menu WHERE menutype = "mainmenu" AND type = "component" AND link LIKE "%option=com_fsf%" AND link LIKE "%view=faq%" AND published > -1';
        $db    = & JFactory::getDBO();
        $db->setQuery($query);
        $objects = $db->loadObjectList();
        foreach ($objects as $object)
        { 
        	if (strpos($object->link,'layout=') > 0)
        		continue;
        		
        	$categories[] = $object;
		}

        return JHTML::_('select.genericlist',  $categories, ''.$control_name.'['.$name.']', 'class="inputbox" size="1"', 'id', 'title', $value);
   }
}
//EENC

?>