<?php
/*
# ------------------------------------------------------------------------
# Templates for Joomla 2.5
# ------------------------------------------------------------------------
# Copyright (C) 2011-2012 Jtemplate.ru. All Rights Reserved.
# @license - PHP files are GNU/GPL V2.
# Author: JTemplate.ru
# Websites:  http://www.jtemplate.ru 
# ---------  http://code.google.com/p/jtemplate/   
# 
*/
// No direct access.
defined('_JEXEC') or die;
class JFormFieldCategories extends JFormField {

  public $type = 'categories';
  
  protected function getInput(){
        require_once (JPATH_SITE.'/modules/mod_jt_jshopping_bestseller/helper.php'); 
        $tmp = new stdClass();  
        $tmp->category_id = "";
        $tmp->name = JText::_('JALL');
        $categories_1  = array($tmp);
        $categories_select =array_merge($categories_1 , buildTreeCategory(0)); 
        $ctrl  =  $this->name ;   
        //$ctrl  = $this->control_name .'['. $this->name .']';   
        //$ctrl  = 'jform[params][catids]'; 
        $ctrl .= '[]'; 
        
        $value        = empty($this->value) ? '' : $this->value;    

        return JHTML::_('select.genericlist', $categories_select,$ctrl,'class="inputbox" id = "category_ordering" multiple="multiple"','category_id','name', $value );
  }
}
?>