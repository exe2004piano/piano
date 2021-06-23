<?php

defined( '_JEXEC' ) or die();
$db = JFactory::getDbo();

$brand_name = "";
if( (isset($_GET['atrib'])) && (trim($_GET['atrib'])!='') )
    $brand_name = trim($_GET['atrib']);

// --- если brand не указан, то видимо мы на странице со всеми брендами, нужно их выдать
if($brand_name=='')
{
    return  "страница всех брендов";
}



// --- если указан, то найдем товары и соответственно разделы, в которых данный бренд имеется
$db->setQuery("SELECT manufacturer_id id, `description_ru-RU` info FROM #__jshopping_manufacturers WHERE `name_ru-RU`=".$db->quote($brand_name));
if(!$brand = $db->loadObject())
    return "Такой производитель на сайте не представлен";

// --- бренд нашелся, нужно найти товары которые к нему относятся и категории
$q =
"
SELECT count(p.product_id) count_prod, cat.`name_ru-RU` cat_name, cat.category_id cat_id, cat_parent.`name_ru-RU` cat_parent_name, cat_parent.category_id cat_parent_id, cat_parent_2.`name_ru-RU` cat_parent_2_name
FROM #__jshopping_products AS p
LEFT JOIN #__jshopping_products_to_categories AS c ON c.product_id=p.product_id
LEFT JOIN #__jshopping_categories AS cat ON cat.category_id=c.category_id
LEFT JOIN #__jshopping_categories AS cat_parent ON cat.category_parent_id=cat_parent.category_id
LEFT JOIN #__jshopping_categories AS cat_parent_2 ON cat_parent.category_parent_id=cat_parent_2.category_id
WHERE p.product_publish=1 AND p.product_manufacturer_id={$brand->id}
GROUP BY c.category_id
ORDER BY cat_parent_2.ordering, cat_parent.ordering
";
$db->setQuery($q);
if(!$cats = $db->loadObjectList())
    return "Такой производитель на сайте не представлен";

// cat_parent - это категории, родительские для категорий с товарами нашего бренда, их и нужно выводить, но ссылки на них должны быть фильтрами

$this->document->setTitle("Весь ассортимент продукции " . $brand_name . ' в лучшем музыкальном магазине Беларуси - Piano.by!');




$text =
'
<div class="b-breadcrumbs">
    <ul class="b-breadcrumbs__list">
        <li class="b-breadcrumbs__item">
            <a href="/" class="b-breadcrumbs__link">Главная</a>
        </li>
        <li class="b-breadcrumbs__item">
            <a href="/brand" class="b-breadcrumbs__link">Производители</a>
        </li>
        <li class="b-breadcrumbs__item">
            <a href="/brand/'.$brand_name.'" class="b-breadcrumbs__link">'.$brand_name.'</a>
        </li>
    </ul>
</div>

<div class="b-section__title b-section__title--big">
    <h1>Продукция '.$brand_name.'</h1>
</div>

<div class="row">
    <div class="col-sm-3">
        <form class="b-filter">
            <div class="b-filter__categoryList-wrap">

                <a href="#" class="b-filter__categoryList-button"></a>
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
foreach($cats AS $c)
{
    // --- переделаем линк на раздел + бренд
    $link = SEFLink('index.php?option=com_jshopping&controller=category&task=view&category_id='.$c->cat_parent_id, 1);
    // http://pianino.bu/cifrovye-pianino?start=0&cat_id=1&price_min=0&price_max=10000&vendor[7]=on
    $link .= "?start=0&vendor[".$brand->id."]=on";

    $title = '';
    $title .= $c->cat_parent_name;

    if(trim(strtolower($c->cat_name))!=trim(strtolower($brand_name)))
    {
        $title = $c->cat_name;
        $link = SEFLink('index.php?option=com_jshopping&controller=category&task=view&category_id='.$c->cat_id, 1);
        $link .= "?start=0&vendor[".$brand->id."]=on";
    }


    $cat_list .=
    '
                    <li class="b-filter__categoryIyem">
                        <a href="'.$link.'" class="b-filter__categoryLink">'.$title.' <span>('.$c->count_prod.')</span></a>
                    </li>
    ';
}

$text = str_replace('<!--category_list-->', $cat_list, $text);


$all_items = '<nav class="b-item__content">';

// --- товары по найденным разделам:


foreach($cats AS $c)
{
	if($c->cat_id==401)
		continue;


    $q = "
    SELECT p.*, p.`name_ru-RU` title, c.category_id, l.name label_name
    FROM #__jshopping_products AS p
    LEFT JOIN #__jshopping_products_to_categories AS c ON c.product_id=p.product_id
    LEFT JOIN #__jshopping_product_labels AS l ON p.label_id=l.id
    WHERE c.category_id={$c->cat_id} AND p.product_publish=1 AND p.product_manufacturer_id={$brand->id}
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
	
	$c->cat_id = 1*$c->cat_id;

	$c->cat_parent_id = 1*$c->cat_parent_id;
	$c->cat_parent_name = $c->cat_parent_name?$c->cat_parent_name:"";

    // --- переделаем линк на раздел + бренд
    $link = SEFLink('index.php?option=com_jshopping&controller=category&task=view&category_id='.$c->cat_parent_id, 1);
    // http://pianino.bu/cifrovye-pianino?start=0&cat_id=1&price_min=0&price_max=10000&vendor[7]=on
    $link .= "?start=0&vendor[".$c->cat_id."]=on";

    $title = '';
    $title .= $c->cat_parent_name;

    if(trim(strtolower($c->cat_name))!=trim(strtolower($brand_name)))
    {
        $title = $c->cat_name;
        $link = SEFLink('index.php?option=com_jshopping&controller=category&task=view&category_id='.$c->cat_id, 1);
        $link .= "?start=0&vendor[".$brand->id."]=on";
    }

    $all_items .=
								'
								<div class="b-item__contentBlock">
								<div class="b-section__title">
									<span>'.$title.'</span>
								</div>
								<ul class="list list-brands" data-screen="screenItems">
								';

    $cat_link = $link;
	

    foreach($products AS $product)
    {
        //$all_items .= include(JPATH_ROOT.'/exe/product_in_slider.php');
        $all_items .= include("exe_product.php");
    }

	
    $all_items .= '</ul>';
    if(sizeof($products)>3)
        $all_items .= '<a href="'.$cat_link.'" class="b-item__more" >Показать еще</a>';

    $all_items .= '</div>';
	
}

$all_items .= '</nav>';




$text = str_replace('<!--products-->', $all_items, $text);
$text = str_replace('<!--info-->', $brand->info, $text);


return $text;