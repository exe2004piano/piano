<?php

    defined('_JEXEC') or die;
	
	
    error_reporting(error_reporting() & ~E_NOTICE);    
    if (!file_exists(JPATH_SITE.'/components/com_jshopping/jshopping.php'))
	{
        JError::raiseError(500,"Please install component \"joomshopping\"");
    }
    require_once (dirname(__FILE__).'/helper.php');
    require_once (JPATH_SITE.'/components/com_jshopping/lib/factory.php'); 
    require_once (JPATH_SITE.'/components/com_jshopping/lib/jtableauto.php');
    require_once (JPATH_SITE.'/components/com_jshopping/tables/config.php'); 
    require_once (JPATH_SITE.'/components/com_jshopping/lib/functions.php');
    require_once (JPATH_SITE.'/components/com_jshopping/lib/multilangfield.php');
    JSFactory::loadCssFiles();
    $lang = JFactory::getLanguage();
    if	(file_exists(JPATH_SITE.'/components/com_jshopping/lang/'.$lang->getTag().'.php'))
	{
        require_once (JPATH_SITE.'/components/com_jshopping/lang/'.$lang->getTag().'.php');
	}
    else
	{
        require_once (JPATH_SITE.'/components/com_jshopping/lang/en-GB.php');
	}
    JTable::addIncludePath(JPATH_SITE.'/components/com_jshopping/tables');
    $field_sort = $params->get('sort', 'id');
    $ordering = $params->get('ordering', 'asc');
	$noimage = "noimage.gif";
	$show_image = $params->get('show_image',0);
	$show_product_image = $params->get('show_product_image',0);
    $categories = VitalikjShopCategoriesHelper::getCatsArray($field_sort, $ordering, $params);
	
	
	
    $jshopConfig = JSFactory::getConfig();
    require(JModuleHelper::getLayoutPath('mod_vitalik_jsh_categories'));