<?php
define('_JEXEC', 1);
global $roicode_products;
global $cat_list;
global $all_vendors;

/*
 * POST приходит в виде:
 * cat_id - ID категории из которой кликнули
 * vendor_1, vendor_2, vendor_3... - ID вендора (производителя) который выбрали. если не указан, то выбирать нужно все что в cat_id и дочерних
 * extra_id_val - ID и значение экстрафилда
 * extraslidemin_ID_VAL, extraslidemax_ID_VAL - значения экстрафилдов полученных от слайдера экстрафилдов
 * ajax=1 если запрос с аякса, иначе - запрос с движка
 */


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

$cat_id = 1*$_POST['cat_id'];

// --- допустимые экстрафилды:
$extra_tab = Array();


$all_vendors_copy = Array();
foreach($all_vendors AS $key=>$val)
    $all_vendors_copy[$key] = $val;


$all_cats = "";
$all_attr = "";
$filter = null;

// --- цены учитываем с учетом выбранной валюты:
$v_id = 1*$_COOKIE['currency'];
if(isset($_GET['prods_from_search_valute']))
	$v_id = $_GET['prods_from_search_valute']*1;

// --- если не задана валюта либо задана неверно, то установим по-умолчанию:
if( ($v_id<1) || ($v_id>3) )
    $v_id = 1;

global $valute;
$kurs = $valute[$v_id]['value'];

$price_min = 1.0*$_POST['price_min']*$kurs;
if($price_min<=0)
    $price_min = 0;

$price_max = 1.0*$_POST['price_max']*$kurs;
if($price_max<$price_min)
    $price_max = $price_min;

if($price_max==0)
    $price_max = 9999999;


// echo $price_min . "===" . $price_max . "----" . $kurs;


$all_extra = null;
$all_extra_names = null;
// --- выберем все характеристики
// --- это накладно для памяти, но иначе мы будем делать 100500 обращений к базе
// --- в общем так быстрее
// --- даже если значений характеристик будет 5000 - это 500кб памяти максимум
// --- зато прирост в скорости норм
$db->setQuery("SELECT id, field_id, `name_ru-RU` title FROM #__jshopping_products_extra_field_values ORDER BY field_id, ordering");
$res = $db->loadObjectList();
foreach($res AS $e)
    $all_extra[$e->field_id][$e->id]=$e->title;

$db->setQuery("SELECT id, `name_ru-RU` title FROM #__jshopping_products_extra_fields ORDER BY id");
$res = $db->loadObjectList();
foreach($res AS $e)
    $all_extra_names[$e->id]=$e->title;

$db->setQuery("SELECT id, `name_ru-RU` title, is_slide, min_slide, max_slide FROM #__jshopping_products_extra_fields ORDER BY id");
$res = $db->loadObjectList();
foreach($res AS $e)
{
    $all_extra_names[$e->id]=$e->title;
    $all_extra_is_slide[$e->id]['is_slide']=$e->is_slide;
    $all_extra_is_slide[$e->id]['min_slide']=$e->min_slide;
    $all_extra_is_slide[$e->id]['max_slide']=$e->max_slide;
}
unset($res);








$filter_url = "";
$all_vendors = "-1";    // здесь будут производители

if(isset($_POST['filter']))
{
    $filt = explode("~",$_POST['filter']);
    foreach($filt AS $f)
    {
        $temp = explode("_", $f);
        // 0 - это что за фильтр
        // 1 - либо id производителя, либо экстрафилда
        // 2 - только для экстрафилда его значение
        // 3 - только для слайдера экстрафилдов, 2 - это его минимальное значение, 3 - максимальное

        switch($temp[0])
        {
            case "attr":
                if(1*$temp[1]>0)
                    $all_attr .=  (1*$temp[1]) . ", ";
                $filter_url .= "&attr[".(1*$temp[1])."]=on";
                break;

            case "vendor":
                if(1*$temp[1]>0)
                    $all_vendors .=  ", ".(1*$temp[1]);
                $filter_url .= "&vendor[".(1*$temp[1])."]=on";
                break;

            case "extra":
                $f_id = 1*$temp[1];
                $f_val = 1*$temp[2];

                if( ($f_id>0) && ($f_val>0) )
                {
                    if(!isset($filter[$f_id]))
                        $filter[$f_id] = " p.extra_field_{$f_id}=-1 ";

                    $filter[$f_id] .= " OR p.extra_field_{$f_id}='{$f_val}' ";
                    $filter_url .= "&extra[".(1*$temp[1])."][".(1*$temp[2])."]=on";
                }
                break;

            case "extraslide":
                // минимальное значение экстрафилда
                $f_id = 1*trim($temp[1]);
                if($f_id==0)
                    break;
                $f_min = 1*trim($temp[2]);
                $f_max = 1*trim($temp[3]);
                // нам нужно отобрать все id в экстрафилд_валуес где значения переведенные в int находятся в данном диапазоне

                $f_ids = '';
                foreach($all_extra[$f_id] AS $key=>$value)
                {
                    if( (trim($value)*1>=$f_min) && (trim($value)*1<=$f_max) )
                        $f_ids .= ", ".trim($key)." ";
                }

                if($f_ids!="")
                {
                    $filter[$f_id] .= " CONVERT(p.extra_field_{$f_id}, UNSIGNED) IN ( -1 {$f_ids} ) ";
                    $filter_url .= "&extraslide[".(1*$temp[1])."]=".(1*$temp[2])."_".(1*$temp[3]);
                }
                break;

        }
    }
}





// --- нужно загрузить товары из текущей категории + из всех дочерних если есть
$all_cats = $cat_id;
$db->setQuery("SELECT category_id FROM #__jshopping_categories WHERE category_parent_id={$cat_id}");
$res = $db->loadObjectList();
foreach($res AS $a)
{
    $all_cats .= ", " . $a->category_id;
}


// --- собираем запрос:
/*
$q = "
SELECT SQL_CALC_FOUND_ROWS p.`name_ru-RU` title, p.`name_be-BY` title_short, p.*, c.category_id, l.name label_name
FROM #__jshopping_products AS p
LEFT JOIN #__jshopping_products_to_categories AS c ON c.product_id=p.product_id
LEFT JOIN #__jshopping_categories AS cat ON cat.category_id=c.category_id
LEFT JOIN #__jshopping_categories AS cat_parent ON cat.category_parent_id=cat_parent.category_id
LEFT JOIN #__jshopping_product_labels AS l ON p.label_id=l.id
LEFT JOIN #__jshopping_products_free_attr AS p_attr ON p_attr.product_id=p.product_id
LEFT JOIN #__jshopping_free_attr AS attr ON attr.id=p_attr.attr_id
WHERE p.product_publish=1 AND cat.category_publish=1 AND c.category_id IN ( {$all_cats} )
";
*/

// --- дополнение от 8.06.19 - оптимизация запроса в связи с увеличением потребляемых ресурсов
$attr_sql = "";
// атрибуты (назначение) :
if($all_attr!='')
	$attr_sql = 
"
LEFT JOIN #__jshopping_products_free_attr AS p_attr ON p_attr.product_id=p.product_id
LEFT JOIN #__jshopping_free_attr AS attr ON attr.id=p_attr.attr_id
";
// --- уберем free_attrs для 10-тикратного прироста скорости!!!
$q = "
SELECT SQL_CALC_FOUND_ROWS p.`name_ru-RU` title, p.`name_be-BY` title_short, p.*, c.category_id, l.name label_name
FROM #__jshopping_products AS p
LEFT JOIN #__jshopping_products_to_categories AS c ON c.product_id=p.product_id
LEFT JOIN #__jshopping_categories AS cat ON cat.category_id=c.category_id
LEFT JOIN #__jshopping_categories AS cat_parent ON cat.category_parent_id=cat_parent.category_id
LEFT JOIN #__jshopping_product_labels AS l ON p.label_id=l.id
{$attr_sql}
WHERE p.product_publish=1 AND cat.category_publish=1 AND c.category_id IN ( {$all_cats} )
";


if($_POST['sale']*1==1)
{
    $q .= " AND p.sale=1 ";
}
if($all_vendors!="-1")
{
    // --- указаны какие-то производители:
    $q .= " AND p.product_manufacturer_id IN ({$all_vendors}) ";
}

// экстрафилды :
if(sizeof($filter)>0)
{
    foreach($filter AS $f)
        $q .= " AND (" . $f . ")";
}

// цена :
$q .= " AND (p.product_price BETWEEN {$price_min} AND {$price_max} ) ";


// атрибуты (назначение) :
if($all_attr!='')
    $q .= " AND (attr.id IN ({$all_attr} -1) ) ";


// сортировка обязательная - по складу:
$q .=
    "
    GROUP BY p.product_id
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

// и пользовательская если задана:
$order = 1*$_COOKIE['filter_order'];
if($order<=0)
	$order = 1;
/*
<option value="0">По-умолчанию</option>
<option value="1">По цене вверх</option>
<option value="11">По цене вниз</option>
<option value="2">По рейтингу</option>
<option value="3">По популярности</option>
*/

switch($order)
{
    case 1:
        $q .= ", p.product_price ";
        break;
    case 11:
        $q .= ", p.product_price DESC";
        break;
    case 2:
        $q .= ", p.average_rating DESC ";
        break;
    case 3:
        $q .= ", p.hits DESC ";
        break;
    default:
        $q .= ", c.product_ordering ";
        break;
}

$start = $_POST['start']*1;
$q .= " LIMIT ".$start.",". (20);

$db->setQuery($q);
try
{
    $products = $db->loadObjectList();
    $db->setQuery("SELECT FOUND_ROWS() num");
    $res = $db->loadObject();
    $num_products = $res->num;

    if($z_user = get_current_user_z())
    {
		foreach ($products AS $key=>$p)
		    $products[$key]->product_price = $products[$key]->price_reg;
    }
}
catch (Exception $e)
{
    $products = Array();
    $num_products = 0;
    return;
}




echo "<input type='hidden' id='all_num_products' value='{$num_products}' />";
echo "<input type='hidden' id='show_num_products' value='" . sizeof($products) . "' />";


// потом добавить панель:
// $extra_fields = include_once(JPATH_ROOT."/components/com_jshopping/get_fields_panel.php");

$db->setQuery("SELECT * FROM #__z_config WHERE name='dostavka' ");
$c = $db->loadObject();
$temp = explode("\n", $c->value);
$config = null;
$i=0;
foreach($temp AS $t)
{
    if($i++>3)
        break;

    $tt = explode("=", $t);
    $tt[1] = 1.0*trim($tt[1]);
    $config[$i] = $tt[1];
}

// config :
// [1] => минимальная сумма бесплатной доставки по минску
// [2] => стоимость доставки если сумма меньше
// [3] [4] => тоже самое для РБ


// вывод листинга товаров
$cur_i=0;
global $google_push;
$google_push = "";

$kurs_ye = $valute[2]['value'];

$prod_number=0;
foreach($products AS $product)
{

    $roicode_products[$product->product_id] = $product->product_id;
	$img = get_cache_photo(JIMG.$product->image, 202, 215);
	$link = $product->real_link;
	$rating = $product->average_rating*10;


	$cur_i++;
	$google_push .= "
        {
          'id': '{$product->product_id}',
          'name': '".str_replace(Array('"', "'"), "`", $product->title)."',
          'list_name': 'Listing',
          'brand': '{$all_vendors_copy[$product->product_manufacturer_id]}',
          'category': '".$cat_list."',
          'list_position': {$cur_i},
          'quantity': 1,
          'price': ".round($product->product_price/$kurs_ye, 2)."
        },
    ";










	// 0 - есть на складе, 1 - под заказ (дата), 2 - нет, 3 - снято, 4 - под заказ (без даты), 5 - анонсированная модель
	$sklad_title = 'в наличии';
	$sklad_status = 'inStock';
	$dost_srok_minsk = $dost_srok_rb = '';

	unset($label);
	$label->id = '0';
	$label->name = '';
	if(isset($product->label_name))
	{
		unset($label);
		$label->id = $product->label_id;
		$label->name = $product->label_name;
	}

	switch($product->sklad)
	{
		case '0' :
			$sklad_title = 'в наличии';
			$sklad_status = 'inStock';
			$dost_srok_minsk = 'сегодня';
			$dost_srok_rb = '1-3 дня';
			break;
		case '1' :
			$sklad_title = 'на складе';
			$sklad_status = 'inStock';
			$dost_srok_minsk = '1-4 дня';
			$dost_srok_rb = '1-4 дня';
			break;
		case '2' :
			$sklad_title = 'нет в наличии';
			$sklad_status = 'none--second';
			break;
		case '3' :
			$sklad_title = 'снят с производства';
			$sklad_status = 'none';
			break;
		case '4' :
			$sklad_title = 'под заказ';
			$sklad_status = 'order';
			$dost_srok_minsk = date("d.m.Y", time() + 14*24*60*60);
			$dost_srok_rb = date("d.m.Y", time() + 16*24*60*60);
			break;
		case '5' :
			$sklad_title = 'анонсируемая модель';
			$sklad_status = 'notify';
			break;
	}

	// заполним экстрафилды
	$extra = '';
	$extra_num = 0;
	for($i=1; $i<500;$i++)
	{
		$name = 'extra_field_'.$i;
		if( (isset($product->$name)) && (1*trim($product->$name)>0) )
		{
			$extra .= '<p class="list__item-text">' . $all_extra_names[$i] . ': ' . $all_extra[$i][1*trim($product->$name)] . '</p>';
			$extra_num++;
			if($extra_num>3)
				break;
		}
	}

	$extra = '<div class="list__item-contains">' . $extra . '</div>';

	$dost_minsk = '';

	if($product->product_price>=$config[1])
		$dost_minsk = "бесплатно";
	else
		$dost_minsk = $config[2] . 'руб.';

	if($product->product_price>=$config[3])
		$dost_rb = "бесплатно";
	else
		$dost_rb = $config[4] . 'руб.';

	$old_price = "";
	if( ($product->label_id==2) && ($product->product_old_price*1.0>0) )   // --- акция
	{
		$old_price = echo_price($product->product_old_price, -1 ,-1 , $product);
	}

	if(trim($product->title_short)!='')
		$product->title = $product->title_short;

    include JPATH_ROOT.'/exe/product_in_list_new.php';
	$prod_number++;
	if($prod_number==4)
	{
		include_position('new-banner');
	}
}









// --------- дополнительно если запрос пришел с аякса
if(isset($_POST['ajax']))
{
    $roi_id = $_COOKIE['roi_new']*1;
    if( (sizeof($roicode_products)>0)  && ($roi_id>0) )
    {
        $ids = "-1";
        foreach($roicode_products AS $pr)
        {
            $ids .= ", ".$pr;
        }

        $db->setQuery("SELECT * FROM #__z_roi_to_product WHERE product_id IN ({$ids}) AND roi_id={$roi_id}");
        $prods = Array();
        if($all = $db->loadObjectList())
        {
            foreach($all AS $a)
                $prods[$a->product_id] = $a->code;
        }

        $scripts = "";
        foreach($roicode_products AS $r)
        {
            $code = substr(strtoupper(md5($roi_id*$r)), 0, 4);
            if(!isset($prods[$r]))
            {
                $db->setQuery("INSERT INTO #__z_roi_to_product (product_id, roi_id, code) VALUES ({$r}, {$roi_id}, '{$code}')");
                $db->execute();
            }
            $scripts .= "document.getElementById('roiprod_{$r}').innerHTML = 'Артикул: {$code}';\n";
        }

        echo "<script>\n{$scripts}</script>";
        // roiprod_
    }














    // --- если запрос с аякса, то вернем через разделители урл страницы
    echo "~~~~~";
    $url = substr($_SERVER['HTTP_REFERER'], 0, strpos($_SERVER['HTTP_REFERER'], '?'));
    $url = "{$url}?start=0&cat_id={$_POST['cat_id']}&price_min={$_POST['price_min']}&price_max={$_POST['price_max']}&price_super_max={$_POST['price_super_max']}" . $filter_url;
    echo $url;
    echo "~~~~~ ";


    if($num_products>20)
    {
        // --- pagination
        for($p=0;$p<=$num_products;$p+=20)
        {
            if($p==((int)$_POST['start']))
                $class = 'pagination-li page_'.$p.' active disabled ';
            else
                $class = 'pagination-li page_'.$p.' ';

            echo "<li class='{$class}'><a href=\"#\" onclick=\"get_page_start({$p}); return false;\">".(($p+20)/20)."</a></li>";
        }
    }


    die;
}

















//  https://pianino.bu/cifrovye-pianino?start=0&cat_id=1&filter=attr_1~attr_2~attr_12~vendor_7~&price_min=9135&price_max=87499

// https://pianino.bu/cifrovye-pianino?start=0&attr%5B1%5D=on&attr%5B12%5D=on&price_min=0&price_max=100000&vendor%5B7%5D=on