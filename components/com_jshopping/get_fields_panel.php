<?php

/*
 * получим и вернем панель выбранных экстрафилдов на основании GET-параметров
 */

defined( '_JEXEC' ) or die();

global $cat_id;
global $sub_cats;
global $sub_cats_2;
global $all_extra;
global $all_extra_names;
global $all_attribs;
global $all_vendors;
global $all_extra_is_slide;

$name_ru = 'name_ru-RU';
$itog = "";

$itog .= '<div class="b-filter__tagLine">
    <ul class="b-filter__tagLine-list" id="filter_panel_ul">';




if ( (isset($_GET['price_min'])) || (isset($_GET['price_max'])) )
    if( (1.0*$_GET['price_min']>0) || (1.0*$_GET['price_max']<100000) )
    {
        $itog .=
            '<li class="b-filter__tagLine-item">
                <p class="b-filter__tagLine-title panel_price_item">Стоимость от '.(1.0*$_GET['price_min']) . ' до ' . (1.0*$_GET['price_max']) . '</p>
                <a href="#" class="b-filter__tagLine-close" id="price_min_max" onclick="del_extra_price(); "></a>
            </li>
            ';
    }





if(isset($_GET['vendor']))
{
    // --- указан "производитель"
    // --- нам нужно по ИД взять название
    foreach($_GET['vendor'] AS $key=>$value)
    {
        $key = 1*$key;
        $name = $all_vendors[$key];

        $itog .=
            '<li class="b-filter__tagLine-item">
                <p class="b-filter__tagLine-title">'. $name . '</p>
                <a href="#" class="b-filter__tagLine-close" rel="vendor_'.$key.'" onclick="del_extra_vendor(this); return false;"></a>
            </li>
            ';


    }
}


if(isset($_GET['attr']))
{
    foreach($_GET['attr'] AS $e_id=>$attr)
    {
            $itog .=
                '<li class="b-filter__tagLine-item">
                    <p class="b-filter__tagLine-title">'. $all_attribs[$e_id]->$name_ru .'</p>
                <a href="#" class="b-filter__tagLine-close" onclick="del_attr('.$e_id.'); return false;"></a>
            </li>
            ';
    }
}


if(isset($_GET['extra']))
{
    foreach($_GET['extra'] AS $e_id=>$extra)
    {
        foreach($extra AS $key=>$value)
        {
            $itog .=
                '<li class="b-filter__tagLine-item">
                    <p class="b-filter__tagLine-title">'.$all_extra_names[$e_id] . ": " . $all_extra[$e_id][$key].'</p>
                <a href="#" class="b-filter__tagLine-close" rel1="'.$e_id.'" rel2="'.$key.'" onclick="del_extra(this); return false;"></a>
            </li>
            ';
        }
    }
}




if(isset($_GET['extraslide']))
{
    foreach($_GET['extraslide'] AS $e_id=>$extra)
    {
        $min_v = $all_extra_is_slide[$e_id]['min_slide'];
        $max_v = $all_extra_is_slide[$e_id]['max_slide'];

        // e_id это ид экстрафилда со слайдером, extra - его мин и макс значения через _
        $temp = explode("_", $extra);

        if ($all_extra_is_slide[$e_id]['is_slide']*1==0)
            continue;

        if ( ($min_v*1==$temp[0]) && ($max_v*1==$temp[1]) )
            continue;

        $itog .=
            '<li class="b-filter__tagLine-item">
                <p class="b-filter__tagLine-title">'.$all_extra_names[$e_id]." от ".$temp[0]." до ".$temp[1].'</p>
                <a href="#" class="b-filter__tagLine-close" id="extra_slide_tag_'.$e_id.'" onclick="del_extra_slide(\''.$e_id.'\'); return false;"></a>
            </li>
            ';
    }
}




$itog .=
    '</ul>
</div>';

echo $itog;
