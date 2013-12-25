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
 
class EnmasseViewDealDetail extends JView
{
    function display($tpl = null)
    { 
    	
    	$id = JRequest::getVar('id', 0);
    	$upcoming = JRequest::getVar('upcoming');
        $modelDeal = JModel::getInstance('deal','enmasseModel');
        $modelMerchant = JModel::getInstance('merchant','enmasseModel');

        $deal 			= JModel::getInstance('deal','enmasseModel')->viewDeal($id);        
        $deal->merchant = JModel::getInstance('merchant','enmasseModel')->getById($deal->merchant_id);
        
        $this->assignRef( 'deal', $deal );
        $this->assignRef( 'sideDealFlag', JRequest::getVar('sideDealFlag', false));
        
        if(!empty($upcoming))
        	 $this->assignRef('upcoming',$upcoming);
        else
        {
        	$upcoming = false;
        	$this->assignRef('upcoming',$upcoming);
        }
        	 
        $this->_setPath('template',JPATH_SITE . DS ."components". DS ."com_enmasse". DS ."theme". DS .EnmasseHelper::getThemeFromSetting(). DS ."tmpl". DS);
    	$this->_layout="deal_detail";
        parent::display($tpl);
    }

}
?>