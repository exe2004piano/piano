<?php

defined('_JEXEC') or die;
$db = JFactory::getDBO();


// --- выберем все характеристики
// --- это накладно для памяти, но иначе мы будем делать 100500 обращений к базе
// --- в общем так быстрее
// --- даже если значений характеристик будет 5000 - это 500кб памяти максимум
// --- зато прирост в скорости норм
$all_extra = Array();

$db->setQuery("SELECT id, field_id, `name_ru-RU` title FROM #__jshopping_products_extra_field_values ORDER BY field_id, ordering");
$res = $db->loadObjectList();
foreach($res AS $e)
    $all_extra[$e->field_id][$e->id]=$e->title;


$db->setQuery("SELECT id, `name_ru-RU` title FROM #__jshopping_products_extra_fields ORDER BY id");
$res = $db->loadObjectList();
foreach($res AS $e)
    $all_extra_names[$e->id]=$e->title;
unset($res);



$compare = explode("~", trim($_COOKIE['new_like']));
if(!$compare)
    return "У Вас нет товаров в избранных";

$ids = "";
foreach($compare AS $c)
    if(1*$c>0)
        $ids .= (1*$c) . ", ";

if($ids=="")
    return "У Вас нет товаров в избранных";


$text = "";

$text .=
    '<div class="b-section__title">
		<span>Избранные товары</span>
	</div>';

$ids .= "-1";


$q =
    "
SELECT p.*, p.`name_ru-RU` title, c.category_id, l.name label_name
FROM #__jshopping_products AS p
LEFT JOIN #__jshopping_products_to_categories AS c ON c.product_id=p.product_id
LEFT JOIN #__jshopping_product_labels AS l ON p.label_id=l.id
WHERE p.product_id IN ({$ids}) AND p.product_publish=1
";

$db->setQuery($q);
if(!$products = $db->loadObjectList())
    return "Данные товары устарели или никогда не были представлены на сайте";

$text .=
    '<div class="b-compare__content">
        <div class="b-compare__titleBlock">
            <div class="b-compare__titleList b-item__contentList" data-screen="screenItems">
            ';


foreach($products AS $product)
{
    $product->like_product=1;
    // $text .= include(JPATH_ROOT.'/exe/product_in_slider.php');
    $text .= include('exe_product.php');
    $temp_prod = $product;
}





$text .= "
</div>
</div>
</div>
";


return $text;