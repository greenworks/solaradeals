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

defined( '_JEXEC' ) or die( 'Restricted access' );
jimport( 'joomla.application.component.view');

require_once( JPATH_ADMINISTRATOR . DS ."components". DS ."com_enmasse". DS ."helpers". DS ."EnmasseHelper.class.php");

class EnmasseViewSalesPerson extends JView
{
	function display($tpl = null)
	{
		$task = JRequest::getWord('task');
		switch ($task) 
		{
    		case 'dealEdit':
    			
				$cid 	= JRequest::getVar( 'cid', array(0), '', 'array' );
				// define deal attributes
				$row->id = null;
				$row->name = null;
				$row->description = null;
				$row->short_desc = null;
				$row->origin_price = null;
				$row->price = null;
				$row->pic_dir = null;
				$row->start_at = null;
				$row->end_at = null;
				$row->min_needed_qty = null;
				$row->highlight = null;
				$row->terms = null;
				$row->created_at =null;
				$row->updated_at = null;
				$row->merchant_id = null;
				
				
				if($cid[0]!=0)
					$row = JModel::getInstance('deal','enmasseModel')->getById($cid[0]);
				
				$this->assignRef( 'deal', $row );
				
				$this->assignRef( 'currencyPrefix', JModel::getInstance('setting','enmasseModel')->getCurrencyPrefix());
				$this->assignRef( 'currencyPostfix', JModel::getInstance('setting','enmasseModel')->getCurrencyPostfix());
				$this->assignRef( 'statusList', EnmasseHelper::$DEAL_STATUS_LIST);
				$this->assignRef( 'locationList', JModel::getInstance('location','enmasseModel')->listAllPublished());
				$this->assignRef( 'categoryList', JModel::getInstance('category','enmasseModel')->listAllPublished());
				$this->assignRef( 'merchantList', JModel::getInstance('merchant','enmasseModel')->listAllPublished());
				
				if($cid[0] != null)
				{
					$dealCategoryIdList = JModel::getInstance('dealcategory','enmasseModel')->getCategoryByDealId($cid[0]);
				    $this->assignRef('dealCategoryList',JModel::getInstance('category',enmasseModel)->getCategoryListInArrId($dealCategoryIdList));
				    $dealLocationIdList = JModel::getInstance('deallocation','enmasseModel')->getLocationByDealId($cid[0]);
				    $this->assignRef('dealLocationList',JModel::getInstance('location','enmasseModel')->getLocationListInArrId($dealLocationIdList));
				}
				else
				{
					$dealCategoryList = array();
					$this->assignRef('dealCategoryList',$dealCategoryList);
					$dealLocationList = array();
					$this->assignRef('dealLocationList',$dealLocationList);
				}		
			
				$this->_setPath('template',JPATH_SITE . DS ."components". DS ."com_enmasse". DS ."theme". DS .EnmasseHelper::getThemeFromSetting(). DS ."tmpl". DS);
				$this->_layout="sales_person_deal_edit";
				parent::display($tpl);
				
				break;
				
			case 'dealShow':
				$salesPersonId = JFactory::getSession()->get('salesPersonId');
				
				$filter = JRequest::getVar('filter');
				$this->assignRef('filter',$filter);
		
				$dealList = JModel::getInstance('deal','enmasseModel')->searchBySalesPerson($salesPersonId, $filter['name'], $filter['published'], $filter['status']);
				$this->assignRef('dealList',$dealList);
				
				$this->assignRef( 'statusList', EnmasseHelper::$DEAL_STATUS_LIST);
				$this->assignRef( 'locationList', JModel::getInstance('location','enmasseModel')->listAllPublished());
				$this->assignRef( 'categoryList', JModel::getInstance('category','enmasseModel')->listAllPublished());
				$this->assignRef( 'dealList', $dealList );
				
				$this->_setPath('template',JPATH_SITE . DS ."components". DS ."com_enmasse". DS ."theme". DS .EnmasseHelper::getThemeFromSetting(). DS ."tmpl". DS);
				$this->_layout="sales_person_deal_show";
				parent::display($tpl);
				
				break;
				
			default:
				$link = JRoute::_("index.php?option=com_enmasse&controller=salesPerson&task=dealShow", false);
				JFactory::getApplication()->redirect($link, $null);
		}
	}

}
?>