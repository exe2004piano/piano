<?php

// --- костыль со стилями.... спасибо верстальщикам
$price_template =
    '

    <div class="b-filter__block js_filterWrap">
        <p class="b-filter__blockTitle js_filterLink">Цена </p>
                       
        <div class="b-filter__blockContent js_filterBlock">

            <div class="b-sliderRange">
                <div class="b-sliderRange__wrap">
                    <div class="b-sliderRange__wrapLine">
                        <div class="b-sliderRange__block-inp b-sliderRange__block-inp--left">
                            <div class="b-sliderRange__costGroup">
                                <label class="b-sliderRange__label" for="minCost"> от:</label>
                                <!--add min value in data-->
                                <input class="b-sliderRange__input" type="text" id="minCost" name="price_min" data-min="<!--min-->" value="<!--min_val-->">
                            </div>
                        </div>
                        <div class="b-sliderRange__block-inp b-sliderRange__block-inp--right">
                            <div class="b-sliderRange__costGroup">
                                <label class="b-sliderRange__label" for="maxCost">до:</label>
                                <!--add max value in data-->
                                <input class="b-sliderRange__input" type="text" id="maxCost" name="price_max" data-max="<!--max-->" value="<!--max_val-->">
                            </div>
                        </div>
                    </div>

                    <div class="b-sliderRange__block-range">
                        <div id="sliderRange"></div>
                    </div>

                </div>
            </div>
        </div>
    </div>
    ';




$is_slide_template =
    '
    <div class="b-filter__block js_filterWrap">
        <p class="b-filter__blockTitle js_filterLink" id="extra_slide_title_<!--id-->"><!--title--> <!--description--></p>
        <input type="hidden" class="extra_slide_class" rel="<!--id-->" />
        <input type="hidden" id="extra_slide_min_<!--id-->" value="<!--min-->" />
        <input type="hidden" id="extra_slide_max_<!--id-->" value="<!--max-->" />
        <input type="hidden" id="extraslide_<!--id-->" name="extraslide[<!--id-->]" value="<!--min_val-->_<!--max_val-->" />

        <div class="b-filter__blockContent js_filterBlock">

            <div class="b-sliderRange">
                <div class="b-sliderRange__wrap">
                    <div class="b-sliderRange__wrapLine">
                        <div class="b-sliderRange__block-inp b-sliderRange__block-inp--left">
                            <div class="b-sliderRange__costGroup">
                                <label class="b-sliderRange__label" for="slide_min_<!--id-->"> от:</label>
                                <!--add min value in data-->
                                <input class="b-sliderRange__input" type="text" id="slide_min_<!--id-->" name="slide_min_<!--id-->" data-min="<!--min-->" value="<!--min_val-->" disabled>
                            </div>
                        </div>
                        <div class="b-sliderRange__block-inp b-sliderRange__block-inp--right">
                            <div class="b-sliderRange__costGroup">
                                <label class="b-sliderRange__label" for="slide_max_<!--id-->">до:</label>
                                <!--add max value in data-->
                                <input class="b-sliderRange__input" type="text" id="slide_max_<!--id-->" name="slide_max_<!--id-->" data-max="<!--max-->" value="<!--max_val-->" disabled>
                            </div>
                        </div>
                    </div>

                    <div class="b-sliderRange__block-range">
                        <div id="sliderRange_<!--id-->"></div>
                    </div>

                </div>
            </div>
        </div>
    </div>
    ';







/*------------------------------------------------------------------------
# mod_jshopping_extended_filter - Extended Filter for Joomshopping
# ------------------------------------------------------------------------
# author    Andrey Miasoedov
# copyright Copyright (C) 2012 Joomcar.net. All Rights Reserved.
# @license - http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
# Websites: http://joomcar.net
# Technical Support: http://joomcar.net
-------------------------------------------------------------------------*/
defined('_JEXEC') or die('Restricted access');
$db = JFactory::getDBO();


// --- это заполнено уже в компоненте, т.к. он всегда раньше вызывается чем модуль
global $cat_id;
global $sub_cats;
global $all_extra;
global $all_extra_is_slide;
global $all_extra_description;
global $sub_cats_2;
global $all_colors;
global $valute;

// --- цены учитываем с учетом выбранной валюты:
$v_id = 1*$_COOKIE['currency'];
// --- если не задана валюта либо задана неверно, то установим по-умолчанию:
if( ($v_id<1) || ($v_id>3) )
    $v_id = 1;
$kurs = $valute[$v_id]['value'];


$products = "";
if($sub_cats_2!=null)
{
    // --- есть подкатегории, значит выведем их, т.к. мы находимся не в конечном листинге
    // --- но сами подкатегории могут содержать либо еще категории либо уже товары

    ?>
    <div class="b-catList">
        <ul class="b-catList__list">
            <?php
            foreach($sub_cats AS $c) : ?>
                <?php
                $link = SEFLink('index.php?option=com_jshopping&controller=category&task=view&category_id='.$c->category_id);
                ?>
                <li class="b-catList__item">
                    <a href="<?php echo $link; ?>" class="b-catList__link"><?php echo $c->title; ?><i></i></a>
                    <nav class="b-catList__sub b-catList__sub--showMore">
                        <ul class="b-catList__subList">
                            <?php
                            // --- проверим, есть ли у данной категории дочерние
                            // --- если есть, то выведем их в раскрывающемся списке
                            // --- иначе будем выводить товары
                            $q = "SELECT category_id, `name_ru-RU` title FROM #__jshopping_categories WHERE category_parent_id={$c->category_id} AND category_publish=1 ORDER BY ordering";
                            $db->setQuery($q);

                            $rows_num = 0;
                            if($cats = $db->loadObjectList())
                            {
                                $rows_num = sizeof($cats);
                                // --- есть дочерние - выведем их
                                foreach($cats AS $c1)
                                {
                                    $link = SEFLink('index.php?option=com_jshopping&controller=category&task=view&category_id='.$c1->category_id);
                                    ?>
                                    <li class="b-catList__subItem">
                                        <a href="<?php echo $link; ?>" class="b-catList__subLink"><?php echo $c1->title; ?></a>
                                    </li>
                                <?php
                                }
                            }
                            else
                            {
                                // --- дочерних нет, выведем товары
                                $q = "
                             SELECT p.product_ean title, p.product_id
                             FROM #__jshopping_products AS p
                             LEFT JOIN #__jshopping_products_to_categories AS c ON c.product_id=p.product_id
                             WHERE p.product_publish=1 AND c.category_id={$c->category_id}
                             ORDER BY
                             CASE
                                WHEN p.sklad=3 THEN 100
                                WHEN p.sklad=2 THEN 90
                                WHEN p.sklad=5 THEN 85
                                WHEN p.sklad=4 THEN 80
                                WHEN p.sklad=1 THEN 70
                                ELSE 0
                             END ,
                             p.product_price
                            ";
                                $db->setQuery($q);
                                if($products = $db->loadObjectList())
                                {
                                    $rows_num = sizeof($products);
                                    foreach($products AS $p)
                                    {
                                        $link = SEFLink('index.php?option=com_jshopping&controller=product&task=view&category_id='.$c->category_id.'&product_id='.$p->product_id, 1);
                                        ?>
                                        <li class="b-catList__subItem">
                                            <a href="<?php echo $link; ?>" class="b-catList__subLink"><?php echo $p->title; ?></a>
                                        </li>
                                    <?php
                                    }
                                }

                            }
                            ?>
                        </ul>
                        <?php if($rows_num>5) { ?>
                            <a href="#" class="b-catList__subShow">+показать все</a>
                        <?php } ?>
                    </nav>
                </li>
            <?php endforeach; ?>
        </ul>
    </div>
<?php
}
else
{
    // echo "товары и фильтр";


    $filter_module = "";
    $name_ru = 'name_ru-RU';
// проверим есть ли для данного раздела и его родителя свободные атрибуты:





    // для этого найдем родителя
    $q = "SELECT category_parent_id FROM #__jshopping_categories WHERE category_id={$cat_id}";
    $db->setQuery($q);
    $parent_cat = $db->loadObject();
    if($parent_cat->category_parent_id>0)
        $dop_q = " OR catid_1={$parent_cat->category_parent_id} OR catid_2={$parent_cat->category_parent_id} OR catid_3={$parent_cat->category_parent_id} ";
    else
        $dop_q = "";

	$q_limit = "";
    // --- ограничение на синты и цифровые пианино:
	//    $q_limit = " AND (id IN (1, 2, 12, 13, 8, 3, 5, 6, 14, 15) ) ";

    $q = "SELECT * FROM #__jshopping_free_attr WHERE ( catid_1={$cat_id} OR catid_2={$cat_id} OR catid_3={$cat_id}   {$dop_q} ) {$q_limit} ";
    $db->setQuery($q);
    if($attr = $db->loadObjectList())
    {
        $filter_module .=
            '<div class="b-filter__block js_filterWrap">
                <p class="b-filter__blockTitle js_filterLink active">Назначение</p>
                <div class="b-filter__blockContent js_filterBlock" style="display: block;">
                    <ul class="b-filter__list">';
        $counter = 1;
        foreach($attr AS $a)
        {
            if( (isset($_GET['attr'][$a->id])) && (trim($_GET['attr'][$a->id])=='on') )
                $on = ' checked ';
            else
                $on = '';
            $show = ($counter > 4) ? 'data-show="false"' : '';
            $filter_module .=
                '<li class="b-filter__item filter_item" '. $show .'>
                    <input '.$on.' hidden type="checkbox" id="attr_'.$a->id.'" name="attr['.$a->id.']"  tag="'.$a->$name_ru.'" />
                                    <label for="attr_'.$a->id.'">'.$a->$name_ru.'</label>
                                </li>';
        $counter++;
        }
        $filter_module .= '</ul>';
            if ($counter > 4) {
                $filter_module .= '<a href="#" class="b-filter-toggle">Показать еще</a>';
            }
            $filter_module .='
        </div>
    </div>';
    }




// параметры (экстрафилдс)
$pars = explode("\n", $params->get('filters'));
$scripts = "";
if(sizeof($pars)>1)
{
    foreach($pars AS $p)
    {
        $temp = explode(":", $p);
        /*
        * варианты эксплода:
        price:Цена
        characteristic:Тип товара:1
        manufacturer:Производитель
        */
        switch ($temp[0])
        {
            // --- PRICE:
            case "price" :

                $slider_fields = trim($params->get('slider_fields'));
                $min = 0;
                $max = 5000/$kurs;

                if(strpos($slider_fields, '=>')>0)
                {
                    $slider_fields = explode('-', substr($slider_fields, strpos($slider_fields, '=>')+strlen('=>')));
                    $min = floor(1.0*trim($slider_fields[0])/$kurs);
                    $max = floor(1.0*trim($slider_fields[1])/$kurs);
                }

                $price_min = (isset($_GET['price_min']))?(1.0*$_GET['price_min']):$min;
                $price_min = floor($price_min / $kurs);
                $price_max = (isset($_GET['price_max']))?(1.0*$_GET['price_max']):$max;
                $price_max = floor($price_max / $kurs);

                $filter_module .= str_replace(
                    array("<!--min-->", "<!--max-->", "<!--min_val-->", "<!--max_val-->"),
                    array($min, $max, $price_min, $price_max),
                    $price_template
                );
                break;
            // --- END PRICE



            // --- VENDOR:
            case "manufacturer" :
                // нужно выбрать всех производителей в текущем разделе, если их нет - значит мы в конечном разделе (самом производителе)
                if( (sizeof($sub_cats)>0) )
                {
                    $c_url = trim($_SERVER['REQUEST_URI']).'?';
                    $c_url = substr($c_url, 0, strpos($c_url, '?')).'&';
					$c_url = substr($c_url, 0, strpos($c_url, '&')).'#';
					$c_url = substr($c_url, 0, strpos($c_url, '#')).'=';
					$c_url = substr($c_url, 0, strpos($c_url, '='));
					$c_url = substr($c_url, 1);

					$all_menus = $db->setQuery("SELECT * FROM #__menu WHERE path LIKE ".$db->quote("%{$c_url}%"))->loadObjectList();
					$all_menu = Array();
					foreach ($all_menus AS $m)
                    {
                        parse_str ($m->link, $get);
                        $all_menu[$get['category_id']] = $m->path;
                    }
                    unset($all_menus);

                    $filter_module .=
                        '<div class="b-filter__block js_filterWrap">
                            <p class="b-filter__blockTitle js_filterLink">Производитель</p>
                            <div class="b-filter__blockContent js_filterBlock">
                                <ul class="b-filter__list">';


                    // --- нужно найти для товаров в $sub_cats ID производителей с количеством товаров по каждому из них

                    $sub_cats_id = "-1";
                    foreach($sub_cats AS $sc)
                        $sub_cats_id .= ", ".$sc->category_id;


                    // --- теперь найдем все бренды, которые встречаются в этих категориях с количеством товаров внутри
                    $q =
                        "
                        SELECT count(p.product_id) count_prod, b.`name_ru-RU` brand_name, 
                        p.product_manufacturer_id brand_id, c.category_id
                        FROM #__jshopping_products AS p
                        LEFT JOIN #__jshopping_products_to_categories AS c ON c.product_id=p.product_id
                        LEFT JOIN #__jshopping_categories AS cat ON cat.category_id=c.category_id
                        LEFT JOIN #__jshopping_manufacturers AS b ON b.manufacturer_id=p.product_manufacturer_id
                        WHERE p.product_publish=1 AND c.category_id IN ( {$sub_cats_id} )
                        GROUP BY p.product_manufacturer_id
                        ORDER BY b.ordering
                        ";
                    $db->setQuery($q);
                    $vendors = $db->loadObjectList();
                    $counter = 1;


                    foreach($vendors AS $sub)
                    {
                        if($sub->brand_id==0)   continue;
                        if( (isset($_GET['vendor'][$sub->brand_id])) && (trim($_GET['vendor'][$sub->brand_id])=='on') )
                            $on = ' checked ';
                        else
                            $on = '';
                        $show = ($counter > 4) ? 'data-show="false"' : '';

                        $c_link = $all_menu[$sub->category_id];

                        $filter_module .=
                            '<li class="b-filter__item filter_item" '. $show .'>
                                <input '.$on.' hidden type="checkbox" id="vendor_'.$sub->brand_id.'" name="vendor['.$sub->brand_id.']"  tag="'.$sub->brand_name.'" />
                                    <label class="b-filter__label" for="vendor_'.$sub->brand_id.'">'.$sub->brand_name.' (' . $sub->count_prod .')</label>
                                    <a href="'.$c_link.'" class="b-filter__link">
                                        <svg class="redo-icon">
                                            <use class="redo-icon__part" xlink:href="/templates/pianino_new/i/sprite.svg#redo"></use>
                                        </svg>
                                    </a>
								</li>';
                        $counter++;
                    }

                    $filter_module .= '</ul>';
            if ($counter > 4) {
                $filter_module .= '<a href="#" class="b-filter-toggle">Показать еще</a>';
            }
            $filter_module .='
                    </div>
                </div>';
                }
                break;
            // --- END VENDOR


            case "characteristic" :

                // тут используем созданный в компоненте массив $all_extra
                // если с указанным ID существует запись - то выведем ее возможные значения
                $extra_id = trim($temp[2]);
				$desc = trim($all_extra_description[$extra_id]);
				if($desc!='')
				{
					$desc = '
                            <span class="data-title__block">
                            &#63;
                            <span class="data-title__modal">
                                '.$desc.'
                            </span>
                        </span>
                       ';
				}


                if(isset($all_extra[$extra_id]))
                {
                    if($all_extra_is_slide[$extra_id]['is_slide']==0)
                    {



                        // --- если это обычный вывод характеристики в виде чекбоксов
                        $filter_module .=
                        '<div class="b-filter__block js_filterWrap">
                            <p class="b-filter__blockTitle js_filterLink '.(($desc!='')?'data-title':'').'">'.trim($temp[1]). $desc.'</p>
                            
			                <div class="b-filter__blockContent js_filterBlock">
				                <ul class="b-filter__list">';
                                $counter = 1;
                                foreach($all_extra[$extra_id] AS $key=>$value)
                                {
                                    $f_name = 'extra['.$extra_id.']['.$key.']';
                                    $f_id = 'extra_'.$extra_id.'_'.$key;

                                    if( (isset($_GET['extra'][$extra_id][$key])) && (trim($_GET['extra'][$extra_id][$key])=='on') )
                                        $on = ' checked ';
                                    else
                                        $on = '';
                                    $show = ($counter > 4) ? 'data-show="false"' : '';
                                    // $temp[2] - это ID extra_fields
                                    // если он =8, то это цвет, значит выведем еще и квадратик с цветом
                                    $color = "";
                                    $publish = true;
                                    if(1*$extra_id==8)
                                    {
                                        if($all_colors!=null)
                                            if(!isset($all_colors[$key]))
                                                $publish = false;

                                        $color = "<div class='color_item_small color_{$key}'> </div>";
                                    }

                                    if($publish)
                                    {
                                        $filter_module .=
											'<li class="b-filter__item filter_item " '. $show .'>
												<input '.$on.' type="checkbox" id="'.$f_id.'" name="'.$f_name.'" hidden tag="'. trim($temp[1]) . ': ' . $value .'" >
												<label for="'.$f_id.'">'.$color.$value.'</label>
											</li>';
                                    }

                                    $counter++;
                                }
        
                        $filter_module .= '</ul>';
            if ($counter > 4) {
                $filter_module .= '<a href="#" class="b-filter-toggle">Показать еще</a>';
            }
            $filter_module .='
                            </div>
                        </div>';
                    }
                    else
                    {
                        // --- если это вывод характеристики в виде слайдера, то мы должны выводить не ID характеристик, а значения
                        // --- но потом уже при работе самого фильтра находить значения в данном диапазоне, и выборку делать уже по их ID
                        // --- другими словами если передано extra[5][17]=on & extra[5][21]=on
                        // --- то выбирать будем те ID, value которых находятся между 17 и 21
                        // --- но это уже потом в отборе, сейчас нам нужно вывести слайдер
                        // --- в диапазоне min_slide, max_slide
                        $min = $all_extra_is_slide[$extra_id]['min_slide'];
                        $max = $all_extra_is_slide[$extra_id]['max_slide'];

                        // если есть в GET экстрафилды с нашим ID - то нам передали какое-то его значение
                        $min_slide = $min;
                        $max_slide = $max;

                        if(isset($_GET['extraslide'][$extra_id]))
                        {
                            $extra_temp = explode("_", $_GET['extraslide'][$extra_id]);
                            $min_slide = 1*$extra_temp[0];
                            $max_slide = 1*$extra_temp[1];
                        }

                        if($desc!='')
							$js_filterLink = 'js_filterLink data-title';
                        else
                            $js_filterLink = 'js_filterLink';

                        $filter_module .= str_replace(
                            array("js_filterLink", "<!--description-->", "<!--min-->", "<!--max-->", "<!--min_val-->", "<!--max_val-->", "<!--title-->", "<!--id-->"),
                            array($js_filterLink, $desc, $min, $max, $min_slide, $max_slide, trim($temp[1]), $extra_id),
                            $is_slide_template
                        );

                        $scripts .= "make_slider( {$extra_id}, {$min}, {$max}, {$min_slide}, {$max_slide} ); \n";
                    }

                }
                break;
        }
    }

    $filter_module =
        '
                <form class="b-filter" method="get" target="">
                <input type="hidden" id="page_start" name="start" value="0"/>
                    <nav class="b-filter__blockWrap">
                    '.
        $filter_module .
        '
    		<span class="js_filterWrap b-filter__submit">
	    		<input type="submit" class="b-filter__totalLink" id="submit_filter" value="Применить" />
    		</span>
        </nav>
    </form>';

    echo $filter_module;
    if($scripts!="")
    {
        ?>
        <script>
            jQuery(document).ready(function($)
            {
                <?php echo $scripts; ?>
            });
        </script>
        <?php
    }

}
}





return;




















// no direct access


// Define the DS constant under Joomla! 3.0
if (!defined('DS')) define('DS', DIRECTORY_SEPARATOR);

error_reporting(E_ALL & ~E_STRICT & ~E_NOTICE & ~E_WARNING);

require_once (dirname(__FILE__).DS.'helper.php');
require_once(JPATH_ROOT."/components/com_jshopping/lib/factory.php");

JSFactory::loadLanguageFile();

$document = JFactory::getDocument();
$document->addStyleSheet(JURI::root(true).'/modules/mod_jshopping_extended_filter/assets/css/filter.css');

// Main params
$moduleclass_sfx = $params->get('moduleclass_sfx', '');
$getTemplate = $params->get('getTemplate', 'Default');

// Category restriction
$restrict = $params->get('restrict', 0);
$restmode = $params->get('restmode', 0);
if($restmode == 0) {
    $restcat = $params->get('restcat', '');
}
else {
    $restcat = JRequest::getVar("category_id", "");
    if($restcat == "") {
        $restcat = JRequest::getVar("restcata", "");
    }
}
$restsub = $params->get('restsub', 1);
$button = $params->get('button', 1);
$button_text = $params->get('button_text', 'Search');

$clear_btn = $params->get('clear_btn', 0);

$auto_submit = $params->get('auto_submit', 0);

$cols = $params->get('cols', '1');

$filters = $params->get('filters', 0);

$slider_fields = $params->get('slider_fields', 0);

if($filters) {
    $filters = explode("\r\n", $filters);
    $list = Array();

    foreach($filters as $filter) {
        $tmp = new JObject;
        $filter = explode(":", $filter);
        $tmp->type = $filter[0];
        $tmp->title = $filter[1];
        $id = @$filter[2];
        if($id) {
            $tmp->id = $id;
        }
        $list[] = $tmp;
    }
}

if($filters && $slider_fields) {
    $sliders = explode("\r\n", $slider_fields);
    $sliders_list = Array();

    foreach($sliders as $slider) {
        $tmp = new JObject;
        list($name, $range) = explode("=>", $slider);
        $tmp->title = $name;
        $tmp->range = $range;
        $sliders_list[] = $tmp;
    }

    foreach($list as $filter) {
        foreach($sliders_list as $slider) {
            if($filter->title == $slider->title) {
                $filter->slider = 1;
                list($slider_from, $slider_to) = explode("-", $slider->range);

                if($filter->type == "price" && $slider_from == '') {
                    $slider_from = floor(modJShopExtendedFilterHelper::getMinPrice());
                }

                if($filter->type == "price" && !$slider_to) {
                    $slider_to = ceil(modJShopExtendedFilterHelper::getMaxPrice());
                }

                $filter->slider_from = $slider_from;
                $filter->slider_to = $slider_to;

            }
        }
    }
}

if($filters) {
    require (JModuleHelper::getLayoutPath('mod_jshopping_extended_filter', $getTemplate.DS.'template'));
}
else {
    echo "Please, adjust the module params.<br />";
}

if(!JPluginHelper::isEnabled('system', 'jsfilter')) {
    echo "<p>JC Jshopping Extended Filter plugin is not published.</p>";
}

?>