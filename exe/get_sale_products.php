<?php

define('_JEXEC', 1);
define('DS', DIRECTORY_SEPARATOR);
if (file_exists(dirname(__FILE__) . '/defines.php')) {
    include_once dirname(__FILE__) . '/defines.php';
}
if (!defined('_JDEFINES')) {
    define('JPATH_BASE', $_SERVER['DOCUMENT_ROOT']);
    require_once JPATH_BASE.'/includes/defines.php';
}
require_once JPATH_BASE.'/includes/framework.php';
$app = JFactory::getApplication('site');
$app->initialise();
$db = JFactory::getDbo();
$app = JFactory::getApplication('site');
$app->initialise();
if (!file_exists(JPATH_SITE.'/components/com_jshopping/jshopping.php')){
    JError::raiseError(500,"Please install component \"joomshopping\"");
}
require_once (JPATH_SITE.'/components/com_jshopping/lib/factory.php');
require_once (JPATH_SITE.'/components/com_jshopping/lib/functions.php');

$jshopConfig = JSFactory::getConfig();
$jshopConfig->cur_lang = $jshopConfig->frontend_lang;

global $all_extra_names;
global $all_extra;

$db->setQuery("SELECT id, field_id, `name_ru-RU` title FROM #__jshopping_products_extra_field_values ORDER BY field_id, ordering");
$res = $db->loadObjectList();
foreach($res AS $e)
    $all_extra[$e->field_id][$e->id]=$e->title;

$db->setQuery("SELECT id, `name_ru-RU` title FROM #__jshopping_products_extra_fields ORDER BY id");
$res = $db->loadObjectList();
foreach($res AS $e)
    $all_extra_names[$e->id]=$e->title;
unset($res);




$c_id = 1*$_GET['cat_id'];

$q = "
SELECT cat.`name_ru-RU` cat_name, cat.category_id cat_id, cat_parent.`name_ru-RU` cat_parent_name, cat_parent.category_id cat_parent_id, cat_parent_2.`name_ru-RU` cat_parent_2_name
, p.*, p.`name_ru-RU` title, c.category_id
FROM #__jshopping_products AS p
LEFT JOIN #__jshopping_products_to_categories AS c ON c.product_id=p.product_id
LEFT JOIN #__jshopping_categories AS cat ON cat.category_id=c.category_id
LEFT JOIN #__jshopping_categories AS cat_parent ON cat.category_parent_id=cat_parent.category_id
LEFT JOIN #__jshopping_categories AS cat_parent_2 ON cat_parent.category_parent_id=cat_parent_2.category_id
WHERE p.product_publish=1 AND p.sale>0 AND cat_parent.category_id={$c_id}
    ORDER BY
    CASE
            WHEN p.sklad=3 THEN 100
            WHEN p.sklad=2 THEN 90
            WHEN p.sklad=5 THEN 85
            WHEN p.sklad=4 THEN 80
            WHEN p.sklad=1 THEN 70
            ELSE 0
    END,
    c.product_ordering
    ";

$db->setQuery($q);
$products = $db->loadObjectList();
$all = '';
foreach($products AS $product)
{
    $all .= include($_SERVER['DOCUMENT_ROOT']."/components/com_jshopping/exe_product.php");
}

echo $all;