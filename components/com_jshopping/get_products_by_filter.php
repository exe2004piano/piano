<?php

defined( '_JEXEC' ) or die();


// попробуем переделать всё под обработку и аякс запроса
/*
 * POST приходит в виде:
 * cat_id - ID категории из которой кликнули
 * vendor_1, vendor_2, vendor_3... - ID вендора (производителя) который выбрали. если не указан, то выбирать нужно все что в cat_id и дочерних
 * extra_id_val - ID и значение экстрафилда
 */
global $cat_id;
global $sub_cats;
global $sub_cats_2;
global $all_extra;
global $all_extra_names;
global $num_products;
global $all_vendors;


$extra_fields = '';
if(isset($_GET['vendor']))
{
    foreach($_GET['vendor'] AS $key=>$value)
    {
        $key=$key*1;
        if( ($key>0)  ) //&& ($cat_id!=$key)
            $extra_fields .= "vendor_".$key."~";
    }
}


if(isset($_GET['extra']))
{
    foreach($_GET['extra'] AS $key=>$value)
    {
        foreach($value AS $k=>$v)
            $extra_fields .= "extra_" . $key . "_" . $k . "~";
    }
}

if(isset($_GET['extraslide']))
{
    foreach($_GET['extraslide'] AS $key=>$value)
    {
        $temp = explode("_", $value);
        $extra_fields .= "extraslide_" . $key . "_" . $temp[0] . "_" . $temp[1] . "~";
        // extraslide[5]=91_835  --->   extraslide_5_91_835
    }
}


if(isset($_GET['attr']))
{
    foreach($_GET['attr'] AS $key=>$on)
        $extra_fields .= "attr_".$key . "~";
}


$_POST['cat_id'] = $cat_id;
if($extra_fields!='')
    $_POST['filter'] = $extra_fields;
$_POST['price_min'] = $_GET['price_min'];
$_POST['price_max'] = $_GET['price_max'];
$_POST['start'] = 1*$_GET['start'];
$_POST['sale'] = 1*$_GET['sale'];

include_once(JPATH_ROOT.'/exe/get_filter_products.php');

return;









































// print_r($_GET);
// Array ( [price_min] => 0 [price_max] => 5000 [extra] => Array ( [1] => Array ( [1] => on [70] => on ) [11] => Array ( [123] => on [124] => on [125] => on ) [2] => Array ( [6] => on [310] => on ) ) )
// die;


/*
 * отбор продуктов по заданным GET параметрам фильтра
 * extra - массив фильтров, их соединяем через AND, а значения через OR
 * то есть например :  [extra] => Array ( [1] => Array ( [10] => on [70] => on ) ...
 * даст SQL: SELECT .... (1=1) AND ( extra_field_1 IN ('10', '70') ) ...
 *
 */

global $cat_id;
global $sub_cats;
global $sub_cats_2;
global $all_extra;
global $all_extra_names;
global $num_products;

// id категорий, в которых нужно найти товары
// это текущая категория + вложенные + вложенные_2 уровня если нужно
// в случае если не указаны категории (производители) в гет-запросе, то ищем везде:
$vendors = '';
if(isset($_GET['vendor']))
{
    foreach($_GET['vendor'] AS $key=>$value)
    {
        $key=$key*1;
        if($key>0)
            $vendors .= $key . ", ";
    }
}

if($vendors=='')
{
$cats_ids = (1*$cat_id);
if($sub_cats!=null)
    foreach($sub_cats AS $c)
        $cats_ids .= ", " . $c->category_id;

if($sub_cats_2!=null)
    foreach($sub_cats_2 AS $c)
        $cats_ids .= ", " . $c->category_id;
}
else
{
    $cats_ids = $vendors . " -1";
}




$q =
"
SELECT SQL_CALC_FOUND_ROWS p.`name_ru-RU` title, p.*, c.category_id, l.name label_name
FROM #__jshopping_products AS p
LEFT JOIN #__jshopping_products_to_categories AS c ON c.product_id=p.product_id
LEFT JOIN #__jshopping_product_labels AS l ON p.label_id=l.id
WHERE (c.category_id IN ({$cats_ids}) ) ";

$price_min = 1.0*$_GET['price_min'];
$price_max = 1.0*$_GET['price_max'];

if($price_min>$price_max)
    $price_min = 0;

if($price_max<$price_min)
    $price_max=100000;

if($price_max==0)
    $price_max=100000;

$q .= " AND (p.product_price BETWEEN {$price_min} AND {$price_max} ) ";

// доп.поля если переданы:
if( (isset($_GET['extra'])) && (sizeof($_GET['extra'])>0) )
    foreach($_GET['extra'] AS $key=>$value)
    {
        $q .= " AND (extra_field_" . (1*$key) . " IN ('-1' ";
        foreach($value AS $v=>$on)
            $q .= ", " . $db->quote(trim($v));
        $q .= " ) )";
    }

// сортировка обязательная - по складу:
$q .=
"
ORDER BY
CASE
        WHEN p.sklad=3 THEN 100
        WHEN p.sklad=2 THEN 90
        WHEN p.sklad=5 THEN 85
        WHEN p.sklad=4 THEN 80
        WHEN p.sklad=1 THEN 70
        ELSE 0
END
";


$start = $_GET['start']*1;
$q .= " LIMIT ".$start.",". ($start+20);
$db->setQuery($q);
$products = $db->loadObjectList();

$db->setQuery("SELECT FOUND_ROWS() num");
$res = $db->loadObject();
$num_products = $res->num;

return $products;