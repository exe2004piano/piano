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
defined('JPATH_BASE') or die;
jimport('joomla.form.formfield');
class JFormFieldAsset extends JFormField {
        protected $type = 'asset';
        protected function getInput() {
                $doc = JFactory::getDocument();
                $doc->addStyleSheet(JURI::root().$this->element['path'].'style.css');
                return null;
        }
}
?>