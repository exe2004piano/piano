<?php
/*
# ------------------------------------------------------------------------
# Templates for Joomla 2.5 - Joomla 3.5
# ------------------------------------------------------------------------
# Copyright (C) 2011-2013 Jtemplate.ru. All Rights Reserved.
# @license - PHP files are GNU/GPL V2.
# Author: Makeev Vladimir
# Websites:  http://www.jtemplate.ru 
# ---------  http://code.google.com/p/jtemplate/   
# ------------------------------------------------------------------------
*/
// No direct access.
defined('_JEXEC') or die;
class JFormFieldLabels extends JFormField {

  public $type ='Labels';
  
  protected function getInput(){
        require_once (JPATH_SITE.'/modules/mod_jt_jshopping_label_products/helper.php'); 
        $tmp = new stdClass();
        $tmp->id = "";
        $tmp->name = JText::_('JALL');
        $element_1  = array($tmp);
        $productLabel = &JTable::getInstance('productLabel', 'jshop');
        $listLabels = $productLabel->getListLabels();
        $elementes_select =array_merge($element_1 , $listLabels); 
        $ctrl  =  $this->name ;  
        $value        = empty($this->value) ? '' : $this->value; 
        
        return JHTML::_('select.genericlist', $elementes_select, $ctrl,'class="inputbox"','id', 'name', $value );
  }
}

?>
