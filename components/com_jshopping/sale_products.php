<?php

defined( '_JEXEC' ) or die();
$db = JFactory::getDbo();

// --- если указан, найдем товары и соответственно разделы, в которых есть распродажи
$q =
    "
SELECT
count(p.product_id) count_prod, cat.`name_ru-RU` cat_name,
cat.category_id cat_id, cat_parent.`name_ru-RU` cat_parent_name, cat_parent.category_id cat_parent_id,
cat_parent_2.`name_ru-RU` cat_parent_2_name
FROM #__jshopping_products AS p
LEFT JOIN #__jshopping_products_to_categories AS c ON c.product_id=p.product_id
LEFT JOIN #__jshopping_categories AS cat ON cat.category_id=c.category_id
LEFT JOIN #__jshopping_categories AS cat_parent ON cat.category_parent_id=cat_parent.category_id
LEFT JOIN #__jshopping_categories AS cat_parent_2 ON cat_parent.category_parent_id=cat_parent_2.category_id
WHERE p.product_publish=1 AND p.sale>0
GROUP BY cat_parent.category_id
ORDER BY cat_parent_2.ordering, cat_parent.ordering
";
$db->setQuery($q);
if(!$cats = $db->loadObjectList())
    return "Распродажи временно отсутствуют";


// cat_parent - это категории, родительские для категорий с товарами нашего бренда, их и нужно выводить, но ссылки на них должны быть фильтрами
// $this->document->setTitle('Распродажа товаров в лучшем музыкальном магазине Беларуси - Piano.by!');


$text =
    '
    <div class="b-section__title b-section__title--big">
        <span>Товары на распродаже</span>
    </div>

<div class="row">
    <div class="col-sm-3">
        <form class="b-filter">
            <div class="b-filter__categoryList-wrap">

                <a href="#" class="b-filter__categoryList-button">Категории товаров</a>
                <ul class="b-filter__categoryList">
                    <!--category_list-->
                </ul>
            </div>
        </form>
    </div>


    <div class="col-sm-9">

        <!--products-->

        <div class="b-text__contentWrap">
    		<div class="b-text__content">
                <!--info-->
    		</div>
		</div>

        <!--video-->

    </div>
</div>
';



// --- меню слева:
$cat_list = "";
$all_cats = "";
foreach($cats AS $c)
{
    // --- переделаем линк на раздел + бренд
    $link = SEFLink('index.php?option=com_jshopping&controller=category&task=view&category_id='.$c->cat_parent_id."&sale=1", 1);
    // http://pianino.bu/cifrovye-pianino?start=0&cat_id=1&price_min=0&price_max=10000&vendor[7]=on
    // $link .= "?start=0&vendor[".$brand->id."]=on";

    $title = '';
    $title .= $c->cat_parent_name;
    if($title=='')
        continue;


    $cat_list .=
        '
                        <li class="b-filter__categoryIyem">
                            <a href="'.$link.'" class="b-filter__categoryLink">'.$title.' <span>('.$c->count_prod.')</span></a>
                    </li>
    ';

    $all_cats[$c->cat_parent_id] = $title;
}

$text = str_replace('<!--category_list-->', $cat_list, $text);


$all_items = '<nav class="b-item__content">';

// --- товары по найденным разделам:
foreach($all_cats AS $c_id => $title)
{
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
    LIMIT 4
    ";

    $db->setQuery($q);
    if(!$products = $db->loadObjectList())
        continue;

    $all_items .=
        '
        <div class="b-item__contentBlock">
        <div class="b-section__title">
            <span>'.$title.'</span>
								</div>
								<ul class="b-item__contentList" data-screen="screenItems" id="more_products_'.$c_id.'" >
								';

    foreach($products AS $product)
    {
        $all_items .= include("exe_product.php");
    }
    $all_items .= '</ul>';

    if(sizeof($products)>3)
        $all_items .= " <a href=\"#\" onclick=\"$(this).hide(); show_more_products('{$c_id}'); return false;\" class=\"b-item__more\" >Показать еще</a>";

    $all_items .= '</div>';
}

$all_items .= '</nav>';



$text = str_replace('<!--products-->', $all_items, $text);
$text = str_replace('<!--info-->', $brand->info, $text);




return $text;