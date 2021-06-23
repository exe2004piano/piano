<?php
defined( '_JEXEC' ) or die();
$db = JFactory::getDbo();


$text =
    '

<div class="b-section__title b-section__title--big">
    <span>Товары на акции</span>
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


// --- загрузим ID товаров из конфига
$db->setQuery("SELECT * FROM #__z_config WHERE name='blackfriday'");
$c = $db->loadObject();
$temp = explode(",", $c->value);

$ids = "-1";
foreach($temp AS $t)
{
    $id = trim($t)*1;
    if($id>0)
        $ids .= ", ".$id;
}



$q =
    "
SELECT
cat.`name_ru-RU` cat_name, cat.category_id cat_id, cat_parent.`name_ru-RU` cat_parent_name, cat_parent_2.category_id parent_id,
cat_parent.category_id cat_parent_id, cat_parent_2.`name_ru-RU` cat_parent_2_name, cat_parent_2.category_id cat_parent_2_id
FROM #__jshopping_products AS p
LEFT JOIN #__jshopping_products_to_categories AS c ON c.product_id=p.product_id
LEFT JOIN #__jshopping_categories AS cat ON cat.category_id=c.category_id
LEFT JOIN #__jshopping_categories AS cat_parent ON cat.category_parent_id=cat_parent.category_id
LEFT JOIN #__jshopping_categories AS cat_parent_2 ON cat_parent.category_parent_id=cat_parent_2.category_id
WHERE p.product_publish=1 AND p.product_id IN ($ids)
GROUP BY cat_parent.category_id
ORDER BY cat_parent_2.ordering, cat_parent.ordering
";
$db->setQuery($q);

$cats = $db->loadObjectList();

// --- меню слева:
$cat_list = "";
$last_cat = -1;

unset($all_cats);

foreach($cats AS $id=>$c)
{
    if($c->parent_id*1>0)
    {
        if($c->parent_id==$last_cat)
            continue;
        else
            $last_cat = $c->parent_id;
    }

    if(trim($c->cat_parent_2_name)!='')
    {
        $all_cats[$c->parent_id] = $c->cat_parent_2_name;
    }
    else
        $all_cats[$c->cat_parent_id] = $c->cat_parent_name;
}




$this->document->setTitle('Товары со скидками в лучшем музыкальном магазине Беларуси - Piano.by!');


$all_items = '<nav class="b-item__content">';
$cat_list = "";

foreach($all_cats AS $c=>$title)
{
    $link = md5(time().rand(0,10000));

    $cat_list .=
        '
                        <li class="b-filter__categoryIyem">
                            <a href="#'.$link.'" class="b-filter__categoryLink">'.$title.'</a>
                    </li>
    ';



    $q = "
        SELECT c.`name_ru-RU` title, c.category_id c_id, c1.category_id c1_id, c2.category_id c2_id, c3.category_id c3_id, c4.category_id c4_id
        FROM #__jshopping_categories AS c
        LEFT JOIN #__jshopping_categories AS c1 ON c1.category_parent_id=c.category_id
        LEFT JOIN #__jshopping_categories AS c2 ON c2.category_parent_id=c1.category_id
        LEFT JOIN #__jshopping_categories AS c3 ON c3.category_parent_id=c2.category_id
        LEFT JOIN #__jshopping_categories AS c4 ON c4.category_parent_id=c3.category_id
        WHERE c.category_id={$c}
    ";

    $db->setQuery($q);
    $cc = $db->loadObjectList();
    unset($all_ids);
    foreach($cc AS $cat)
    {
        $all_ids[$cat->c_id] = 1;
        $all_ids[$cat->c1_id] = 1;
        $all_ids[$cat->c2_id] = 1;
        $all_ids[$cat->c3_id] = 1;
        $all_ids[$cat->c4_id] = 1;
    }

    $cat_ids = "-1";
    foreach($all_ids AS $key=>$value)
    {
        if($key*1>0)
            $cat_ids .= ", ".$key;
    }


    $q = "
    SELECT p.*, p.`name_ru-RU` title, c.category_id, l.name label_name
    FROM #__jshopping_products AS p
    LEFT JOIN #__jshopping_products_to_categories AS c ON c.product_id=p.product_id
    LEFT JOIN #__jshopping_categories AS cat ON cat.category_id=c.category_id
    LEFT JOIN #__jshopping_product_labels AS l ON p.label_id=l.id
    WHERE (c.category_id IN ({$cat_ids})) AND (p.product_publish=1 AND p.product_id IN ({$ids}) )
    ORDER BY
    CASE
            WHEN p.sklad=3 THEN 100
            WHEN p.sklad=2 THEN 90
            WHEN p.sklad=5 THEN 85
            WHEN p.sklad=4 THEN 80
            WHEN p.sklad=1 THEN 70
            ELSE 0
    END,
    cat.category_parent_id, cat.category_id, c.product_ordering
    ";

    $db->setQuery($q);
    if(!$products = $db->loadObjectList())
        continue;


    $all_items .=
        '<div class="b-item__contentBlock">
            <div class="b-section__title">
                <span id="'.$link.'">'.$title.'</span>
			</div>';

    $all_items.= '<ul class="b-item__contentList" data-screen="screenItems">';

    foreach($products AS $product)
    {
        $all_items .= include("exe_product.php");
    }

    $all_items .= '</ul>';
    $all_items .= '</div>';
}

$all_items .= '</nav>';

$text = str_replace('<!--category_list-->', $cat_list, $text);
$text = str_replace('<!--products-->', $all_items, $text);

return $text;














// --- товары по найденным разделам:
foreach($cats AS $c)
{
    $q = "
    SELECT p.*, p.`name_ru-RU` title, c.category_id, l.name label_name
    FROM #__jshopping_products AS p
    LEFT JOIN #__jshopping_products_to_categories AS c ON c.product_id=p.product_id
    LEFT JOIN #__jshopping_categories AS cat ON cat.category_id=c.category_id
    LEFT JOIN #__jshopping_categories AS cat1 ON cat1.category_parent_id=cat.category_id
    LEFT JOIN #__jshopping_categories AS cat2 ON cat2.category_parent_id=cat1.category_id
    LEFT JOIN #__jshopping_categories AS cat3 ON cat3.category_id=cat.category_parent_id
    LEFT JOIN #__jshopping_categories AS cat4 ON cat4.category_id=cat3.category_parent_id
    LEFT JOIN #__jshopping_product_labels AS l ON p.label_id=l.id
    WHERE (c.category_id={$c->cat_id} OR cat1.category_id={$c->cat_id} OR cat2.category_id={$c->cat_id} OR cat3.category_id={$c->cat_id} OR cat4.category_id={$c->cat_id}) AND p.product_publish=1 AND p.product_id IN ({$ids})
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
    if(!$products = $db->loadObjectList())
        continue;

    // --- переделаем линк на раздел + бренд
    $link = SEFLink('index.php?option=com_jshopping&controller=category&task=view&category_id='.$c->cat_parent_id, 1);
    // http://pianino.bu/cifrovye-pianino?start=0&cat_id=1&price_min=0&price_max=10000&vendor[7]=on
    $link .= "?start=0&vendor[".$c->cat_id."]=on";

    $title = $c->cat_parent_name;

    if($c->cat_parent_2_name!='')
        $title = $c->cat_parent_2_name;

    $all_items .=
        '<div class="b-item__contentBlock">';

    if( (isset($c->link)) && (trim($c->link)!='') )
    {
        $all_items .= '
            <div class="b-section__title">
                <span id="'.$c->link.'">'.$title.'</span>
			</div>';
    }

	$all_items.= '<ul class="b-item__contentList" data-screen="screenItems">';

    $cat_link = $link;


    foreach($products AS $product)
    {
        //$all_items .= include(JPATH_ROOT.'/exe/product_in_slider.php');
        $all_items .= include("exe_product.php");
    }

        $all_items .= '</ul>';
        $all_items .= '</div>';
}

$all_items .= '</nav>';


$text = str_replace('<!--products-->', $all_items, $text);

return $text;