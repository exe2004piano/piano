<?php

defined('_JEXEC') or die;
$db = JFactory::getDBO();



$attrib_alias =  trim($_GET['atrib']);      // алиас атрибута (обязательно)
$max_price = 1.0*($_GET['max_price']);
$start = 1*$_GET['start'];
$vendor_id = 1*$_GET['manufacturer'];
$num_on_page = 12;

$max_price_query = "";
if($max_price>0)
{
    $max_price_query = " AND p.product_price<=" . ($max_price) . " ";
}

$vendor_query = "";
if($vendor_id>0)
{
    $vendor_query = " AND p.product_manufacturer_id={$vendor_id} ";
}


unset($max_price_input);
$max_price_input[$max_price] = " SELECTED ";

$sort = " c.product_ordering ASC ";
unset($sort_id);

switch ($_GET['order'])
{
    case "0":
        $sort = " c.product_ordering ASC ";
        $sort_id[0]=" SELECTED ";
        break;

    case "1":
        $sort = " p.product_price ASC ";
        $sort_id[1]=" SELECTED ";
        break;

    case "2":
        $sort = " p.average_rating ASC ";
        $sort_id[2]=" SELECTED ";
        break;

    case "3":
        $sort = " p.hits ASC ";
        $sort_id[3]=" SELECTED ";
        break;
}


$q = "
        SELECT SQL_CALC_FOUND_ROWS p.*, p.`name_ru-RU` title, p.`short_description_en-GB` info, c.category_id, a.`name_ru-RU` attr_title, a.`description_ru-RU` as attr_text
        FROM #__jshopping_products AS p
        LEFT JOIN #__jshopping_products_free_attr AS free_attr USING (product_id)
        LEFT JOIN #__jshopping_products_to_categories AS c ON c.product_id=p.product_id
        LEFT JOIN #__jshopping_free_attr AS a ON a.id=free_attr.attr_id
        WHERE `a`.`name_en-GB` = " . $db->quote($attrib_alias) . " AND p.product_publish=1 {$max_price_query} {$vendor_query}
        ORDER BY

    CASE
            WHEN p.sklad=3 THEN 100
            WHEN p.sklad=2 THEN 90
            WHEN p.sklad=5 THEN 85
            WHEN p.sklad=4 THEN 80
            WHEN p.sklad=1 THEN 70
            ELSE 0
    END,

        {$sort}
        LIMIT {$start}, {$num_on_page}
        ";

$db->setQuery($q);
$res = $db->loadObjectList();

$q = "SELECT FOUND_ROWS() num";
$db->setQuery($q);
$num = $db->loadObject();
$attr_text = '';


$text .= "
<!--attr_bread-->
<!--attr_nav-->
<!--attr_text-->
";

$text .=
'<nav class="b-item__content">
    <ul class="b-item__contentList" data-screen="screenItems" >';

foreach ($res as $product)
{
    if($attr_text=='')
    {
        $attr_text = $product->attr_text;
        $attr_title = $product->attr_title;
    }

    $this->product = $product;
    $item = include(JPATH_BASE."/components/com_jshopping/exe_product.php");
    $text .= $item;
}


if($num->num==0)
{
    $text .= '
<li class="b-slider__item b-slider__title">
    К сожалению ничего не найдено
</li>';
}

$text .= '</ul></nav>';

if($attr_title=='')
    $attr_title = 'Поиск инструмента';
$this->document->setTitle($attr_title . ' в лучшем музыкальном магазине Беларуси - Piano.by!');

$attr_text = '<div class="b-text__content">' . $attr_text . '</div>';
$text = str_replace("<!--attr_text-->", $attr_text, $text);

$attr_bread =
    '<div class="b-breadcrumbs">
        <ul class="b-breadcrumbs__list">
            <li class="b-breadcrumbs__item"><a class="b-breadcrumbs__link" href="/">Главная</a></li>
                        <li class="b-breadcrumbs__item">
                        <a href="#" class="b-breadcrumbs__link">'.$attr_title.'</a>
                </li>
	</ul>
</div>';
$text = str_replace("<!--attr_bread-->", $attr_bread, $text);






// --- сортировка:
$order_by =
    "<select name='order' id='order' onchange='$(\"#attrib_form\").submit();'>
            <option value='0' {$sort_id[0]} >Упорядочить по-умолчанию</option>
            <option value='1' {$sort_id[1]} >Упорядочить по цене</option>
            <option value='2' {$sort_id[2]} >Упорядочить по рейтингу</option>
            <option value='3' {$sort_id[3]} >Упорядочить по популярности</option>
            </select>\n";



// --- максимальная цена:
$max_price_select = "<select name='max_price' onchange='$(\"#attrib_form\").submit();'>";
$max_price_select .= "<option {$max_price_input[0]} value='0'>Любая цена</option>";
for($i=200; $i<=2000;$i+=200)
    $max_price_select .= "<option " . $max_price_input[$i] . " value='" . ($i) . "'>до {$i} р.</option>";
$max_price_select .= "</select>\n";

// --- производитель:

$q = "SELECT manufacturer_id id, `name_ru-RU` title FROM #__jshopping_manufacturers ORDER BY id";
$db->setQuery($q);
$manufacturers = $db->loadObjectList();
unset($vendor_input);
$vendor_input[$vendor_id] = " SELECTED ";


$vendor = "<select name='manufacturer' onchange='$(\"#attrib_form\").submit();'>";
$vendor .= "<option {$vendor_input[0]} value='0' >Любой производитель</option>";
foreach($manufacturers AS $m)
{
    $vendor .= "<option {$vendor_input[$m->id]} value='{$m->id}' >{$m->title}</option>";
}
$vendor .= "</select>\n";



$attr_nav =
'<div class="b-filter__line">
    <form id="attrib_form" method="GET">
        <div class="b-filter__numberOf" id="all_filter_product_number">Найдено товаров: '.$num->num.'</div>
        <div class="b-filter__select">' . $order_by . '</div>
        <div class="b-filter__select">' . $max_price_select . '</div>
        <div class="b-filter__select">' . $vendor . '</div>
    </form>
</div>';


$text = str_replace("<!--attr_nav-->", $attr_nav, $text);


/*
jimport( 'joomla.html.pagination' );
$pag = new JPagination( $num->num, $start, $num_on_page);
$pages = $pag->getPagesLinks();
*/

// --- если несколько страниц, то выведем пагинатор:
if($num->num>$num_on_page)
{
    // GET : Array ( [option] => com_content [view] => article [id] => 4 [atrib] => sintezatory-dlia-obuchenia [order] => 1 [max_price] => 1600 [manufacturer] => 0 )
    $num_page = $num->num/$num_on_page;
    $url = "/items/" . $_GET['atrib'];
    if(isset($_GET['order']))
        $url .= '&order='.$_GET['order'];
    if(isset($_GET['max_price']))
        $url .= '&max_price='.$_GET['max_price'];
    if(isset($_GET['manufacturer']))
        $url .= '&manufacturer='.$_GET['manufacturer'];

    $pages = '<nav class="b-section__pagination"><ul class="pagination" id="pagination">';

    for($p=0;$p<=$num_page;$p++)
    {
        if($p*$num_on_page==$start)
            $pages .= '<li class="active disabled"><a href="#">'.($p+1).'</a></li>';
        else
            $pages .= '<li><a href="'.$url.'&start='.($p*$num_on_page).'">'.($p+1).'</a></li>';
    }
    $pages .= '</ul></nav>';
    $text .= $pages;
}











return $text;