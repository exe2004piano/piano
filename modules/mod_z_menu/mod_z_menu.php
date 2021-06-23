<?php

defined('_JEXEC') or die;


$db = JFactory::getDbo();
$q = "SELECT * FROM #__jshopping_categories WHERE category_parent_id=0 AND category_publish=1 ORDER BY ordering";
$db->setQuery($q);
$cat1 = $db->loadObjectList();
$name_ru = 'name_be-BY';
$alias_ru = 'alias_ru-RU';

if($page = cache_page('mod_z_menu', 'SDFwer234535', 36000))    // --- кэш 10 часов
{
	echo $page;
    return;
}


$menu =  '<ul class="b-menu__list" id="list">';
foreach($cat1 AS $c1)
{
    $link = SEFLink('index.php?option=com_jshopping&controller=category&task=view&category_id='.$c1->category_id);

    $words = explode(' ', $link);
    $showword = trim($words[count($words) - 1], '/');

    $menu .='
        <li class="b-menu__item ">
            <a href="'.$link.'" class="b-menu__link"><svg class="b-menu__icon"><use class="b-menu__part" xlink:href="/templates/pianino_new/i/sprite.svg#'.$showword.'"></use></svg><span class="b-menu__text">'.$c1->$name_ru.'</span></a>';

    $q = "SELECT * FROM #__jshopping_categories WHERE category_parent_id={$c1->category_id} AND category_publish=1 ORDER BY ordering";
    $db->setQuery($q);
    if($cat2 = $db->loadObjectList())
    {
        $menu .='<div class="b-menu__subContent">
                    <div class="b-menu__subMenu">';

        foreach($cat2 AS $c2)
        {
            $link = SEFLink('index.php?option=com_jshopping&controller=category&task=view&category_id='.$c2->category_id);
            $menu .= '<div class="b-menu__subMenu-column"><a href="'.$link.'" class="b-menu__subMenu-title"><span>'.$c2->$name_ru.'</span></a>';

            $q =
                "SELECT p.product_id, p.product_price, p.sklad, p.`product_ean` title
                 FROM #__jshopping_products AS p
                 LEFT JOIN #__jshopping_products_to_categories AS c ON p.product_id=c.product_id
                 WHERE p.product_publish = '1' AND c.category_id={$c2->category_id}
                 ORDER BY
                 CASE
                    WHEN p.sklad = 3 THEN 1 ELSE 0
                 END ,
                 p.product_price
                 LIMIT 0, 10
                ";

            $db->setQuery($q);
            $items = $db->loadObjectList();
            if($items)
            {
                $menu .= '<div class="b-menu__subMenu-nameBlock one_line_div">';
                // --- найдены товары, значит это финальный раздел
                foreach($items AS $a)
                {
                    $link_p = SEFLink('index.php?option=com_jshopping&controller=product&task=view&category_id='.$c2->category_id.'&product_id='.$a->product_id);
                    $menu .= '<a href="'.$link_p.'" class="b-menu__subMenu-name one_line">'.$a->title.'</a>';
                }

                $menu .= '<a href="'.$link.'" class="b-menu__subMenu-name one_line small_line">еще товары</a>';
                $menu .= '</div>';
            }
            else
            {
                // --- товаров нет, значит это не последняя категория
                $q = "SELECT * FROM #__jshopping_categories WHERE category_parent_id={$c2->category_id} AND category_publish=1 ORDER BY ordering";
                $db->setQuery($q);
                if($cat3 = $db->loadObjectList())
                {
                    $menu .= '<div class="b-menu__subMenu-nameBlock one_line_div">';
                    foreach($cat3 AS $c3)
                    {
                        $link_c = SEFLink('index.php?option=com_jshopping&controller=category&task=view&category_id='.$c3->category_id);
                        $menu .= '<a href="'.$link_c.'" class="b-menu__subMenu-name one_line">'.$c3->$name_ru.'</a>';
                    }
                    $menu .= '</div>';
                }

            }

            $menu .= '</div>';
        }

        $menu .= '</div></div>';
    }

    $menu .= '</li>';


    if($c1->category_id==32)    // --- отдельно дополнительно вынести микрофоны.... мляцкие сеошники
    {
        $dop_id = 85;   // --- микрофоны
        include __DIR__."/dop.php";
    }


}

$menu .= '</ul>';

echo $menu;

cache_save('mod_z_menu', 'SDFwer234535', $menu);
