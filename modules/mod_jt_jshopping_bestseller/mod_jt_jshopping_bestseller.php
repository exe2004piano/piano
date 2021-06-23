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
    error_reporting(E_ALL & ~E_NOTICE);
    
    if (!file_exists(JPATH_SITE.'/components/com_jshopping/jshopping.php')){
        JError::raiseError(500,"Please install component \"joomshopping\"");
    } 
    
    require_once (JPATH_SITE.'/components/com_jshopping/lib/factory.php'); 
    require_once (JPATH_SITE.'/components/com_jshopping/lib/functions.php');       
    JSFactory::loadCssFiles();
    JSFactory::loadLanguageFile();
    $jshopConfig = &JSFactory::getConfig();
    
    $product = &JTable::getInstance('product', 'jshop');
    $cat_str = $params->get('catids',NULL); 
    if (is_array($cat_str)) {    
        $cat_arr = array();
        foreach($cat_str as $key=>$curr){
           if (intval($curr)) $cat_arr[$key] = intval($curr);
        }  
    } else {
        $cat_arr = array();
        if (intval($cat_str)) $cat_arr[] = intval($cat_str);
    }
	$last_prod = $product->getBestSellers($params->get('count_products', 4), $cat_arr); 
    foreach($last_prod as $key=>$value){
        $last_prod[$key]->product_link = SEFLink('index.php?option=com_jshopping&controller=product&task=view&category_id='.$value->category_id.'&product_id='.$value->product_id, 1);
    }
	
    $noimage = "noimage.gif";
    $show_image = $params->get('show_image',1);
	
	$document 			=& JFactory::getDocument();
	$jquery   			= $params->get('jquery');
	$jt_script_bx   	= $params->get('jt_script_bx');
	//$jt_jquery_ver		= $params->get('jt_jquery_ver');
	$jt_load_jquery		= $params->get('jt_load_jquery');
	$class_sfx			= htmlspecialchars($params->get('class_sfx'));
	$document->addStyleSheet(JURI::base() . 'modules/mod_jt_jshopping_bestseller/css/style.css');
	//$jt_style_mod			= $params->get('jt_style_mod');
	//$id_mod					= $params->get('id_mod');
	$jt_short_desc			= $params->get('jt_short_desc');
	$jt_review_mark			= $params->get('jt_review_mark');
	$jt_count_commentar		= $params->get('jt_count_commentar');
	$jt_item_detal			= $params->get('jt_item_detal');
	$jt_buttom_text			= $params->get('jt_buttom_text');
	$jt_width				= $params->get('jt_width');
	$jt_height				= $params->get('jt_height');
	$jt_label_prod			= $params->get('jt_label_prod');
	$jt_mode				= $params->get('jt_mode');
	$jt_speed				= $params->get('jt_speed');
	$jt_controls			= $params->get('jt_controls');
	$jt_auto				= $params->get('jt_auto');
	$jt_autohover			= $params->get('jt_autohover');
	$jt_pause				= $params->get('jt_pause');
	$jt_auto_delay			= $params->get('jt_auto_delay');
	$jt_pager				= $params->get('jt_pager');
	$jt_pager_type 			= $params->get('jt_pager_type');
	$jt_pager_location		= $params->get('jt_pager_location');
	$jt_pager_saparator		= $params->get('jt_pager_saparator');
	$jt_display_slide_qty	= $params->get('jt_display_slide_qty');
	$jt_move_slide_qty		= $params->get('jt_move_slide_qty');
	$jt_id_sfx				= $params->get('jt_id_sfx', 1);

	if ($jt_load_jquery == 0) {
		//if ($jquery == 1) { $document->addScript('http://ajax.googleapis.com/ajax/libs/jquery/'.$jt_jquery_ver.'/jquery.min.js'); }
		if ($jquery == 1) { $document->addScript(JURI::base() . 'modules/mod_jt_jshopping_bestseller/js/jquery.min.js'); }
		if ($jt_script_bx == 1 ) { $document->addScript(JURI::base() . 'modules/mod_jt_jshopping_bestseller/js/jquery.bxSlider.min.js'); }
		$document->addCustomTag('<script type = "text/javascript">if (jQuery) jQuery.noConflict();</script>');
		}
	if ($jt_load_jquery == 1) { 
		//if ($jquery == 1) { $document->addCustomTag('<script type = "text/javascript" src = "http://ajax.googleapis.com/ajax/libs/jquery/'.$jt_jquery_ver.'/jquery.min.js"></script>'); }
		if ($jquery == 1) { $document->addCustomTag('<script type = "text/javascript" src = "'.JURI::root().'modules/mod_jt_jshopping_bestseller/js/jquery.min.js"></script>'); }
		if ($jt_script_bx == 1 ) { $document->addCustomTag('<script type = "text/javascript" src = "'.JURI::root().'modules/mod_jt_jshopping_bestseller/js/jquery.bxSlider.min.js"></script>');	}
		$document->addCustomTag('<script type = "text/javascript">if (jQuery) jQuery.noConflict();</script>');	
		}
	require JModuleHelper::getLayoutPath('mod_jt_jshopping_bestseller', $params->get('layout', 'default'));
	echo JText::_(MOD_JT);
?>