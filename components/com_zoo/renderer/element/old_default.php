<?php


/**
* @package   com_zoo
* @author    YOOtheme http://www.yootheme.com
* @copyright Copyright (C) YOOtheme GmbH
* @license   http://www.gnu.org/licenses/gpl.html GNU/GPL
*/


// no direct access
defined('_JEXEC') or die('Restricted access');
$db = JFactory::getDbo();

// create label
$label = '';
if (isset($params['showlabel']) && $params['showlabel']) {
	$label = ($params['altlabel']) ? $params['altlabel'] : $element->config->get('name');
}

// render element
echo $label.' ';


$text = $element->render($params).' ';
$start = 0;
$page = 1*$_GET['page'];
$cur_page = 0;

//--- курс:
$db->setQuery("SELECT * FROM #__jshopping_currencies WHERE currency_id=2");
$res = $db->loadObject();
$curr = $res->currency_value*1;
$currency->currency_value = $res->currency_value*1;

while(strpos($text, '[prokat', $start)>0)
{
    $start = strpos($text, '[prokat', $start);
    $end = strpos($text, ']', $start) + strlen(']');
    $prokat = substr($text, $start, $end-$start);

    $temp = explode ("=", $prokat);
    $ids = explode("," , substr($temp[1], 0, strpos($temp[1], "]")));

    $new_text = "<hr />";
    if($cur_page==$page)
    {
        // --- ids - список ID товаров, которые по сути являются комплектами в прокат
        // --- нужно перебрать их все, для каждого найти сопутку, найти её общую сумму, прибавить цену нашего товара и от этого всего взять %, который является старой ценой:

        foreach($ids AS $i)
        {
            $i = 1*trim($i);
            if($i>0)
            {
                $q =
                    "
                     SELECT
                     p.product_id prokat_id, p.product_price dobavka, p.product_old_price procent, p.image, p.`name_ru-RU` title, p.`description_ru-RU` info, p.`short_description_ru-RU` dop_info,
                     s.product_id, s.product_price
                     FROM #__jshopping_products_relations AS soput
                     LEFT JOIN #__jshopping_products AS p ON p.product_id = soput.product_id
                     LEFT JOIN #__jshopping_products AS s ON soput.product_related_id = s.product_id
                     WHERE soput.product_id={$i}
                    ";

                $db->setQuery($q);
                $all = $db->loadObjectList();

                $summ = 0;
                $dobavka = 0;
                $procent = 0;
                $title = "";
                $info = "";
                $dop_info = "";
                $img = "";

                foreach($all AS $a)
                {
                    $summ += $a->product_price;
                    $dobavka = $a->dobavka;
                    $procent = $a->procent;
                    $this->prokat->product_id = $a->prokat_id;
                    $this->prokat->title = $a->title;
                    $this->prokat->info = $a->info;
                    $this->prokat->dop_info = $a->dop_info;
                    $this->prokat->image = $a->image;
                }


				echo "<!---$summ--->";
				echo "<!---$dobavka--->";
				echo "<!---$procent--->";
                $summ = ($summ+$dobavka)*$procent/100.0;

            }

            $this->prokat->price = round($summ, 2);
			echo "<!---".$this->prokat->price."--->";

            $item = include(JPATH_BASE."/components/com_jshopping/exe_prokat.php");


            $new_text .= "<div class='procat_items' >" . $item . "</div>";

        }

    }



    $text = str_replace($prokat, $new_text, $text);
    // $start = $end;
    $cur_page++;


}


// [sr_products=31%3141, 21%3779, 12%3054, 2%2593]
if(strpos($text, '[sr_products')>0)
    $text = sr_products_replace($text);

// --- по аналогии делаем для товаров:
while(strpos($text, '[products', $start)>0)
{

    global $all_extra_names;
    global $all_extra;

    $db->setQuery("SELECT id, field_id, `name_ru-RU` title FROM #__jshopping_products_extra_field_values ORDER BY field_id, ordering");
    $res = $db->loadObjectList();
    foreach($res AS $e)
        $all_extra[$e->field_id][$e->id]=$e->title;

    $db->setQuery("SELECT id, `name_ru-RU` title FROM #__jshopping_products_extra_fields ORDER BY id");
    $res = $db->loadObjectList();
    foreach($res AS $e)
        $all_extra_names[$e->id]=$e->title;
    unset($res);


    error_reporting(error_reporting() & ~E_NOTICE);
    if (!file_exists(JPATH_SITE.'/components/com_jshopping/jshopping.php'))
    {
        JError::raiseError(500,"Please install component \"joomshopping\"");
    }

    require_once (JPATH_SITE.'/components/com_jshopping/lib/factory.php');
    require_once (JPATH_SITE.'/components/com_jshopping/lib/jtableauto.php');
    require_once (JPATH_SITE.'/components/com_jshopping/tables/config.php');
    require_once (JPATH_SITE.'/components/com_jshopping/lib/functions.php');
    require_once (JPATH_SITE.'/components/com_jshopping/lib/multilangfield.php');
    require_once (JPATH_SITE.'/components/com_jshopping/models/cart.php');

    JSFactory::loadCssFiles();
    $lang = JFactory::getLanguage();
    if	(file_exists(JPATH_SITE.'/components/com_jshopping/lang/'.$lang->getTag().'.php'))
    {
        require_once (JPATH_SITE.'/components/com_jshopping/lang/'.$lang->getTag().'.php');
    }
    else
    {
        require_once (JPATH_SITE.'/components/com_jshopping/lang/en-GB.php');
    }
    JTable::addIncludePath(JPATH_SITE.'/components/com_jshopping/tables');



    $credit = 0;
    if(strpos($text, '[products_credit')>0)
        $credit = 1;

    $start = strpos($text, '[products', $start);
    $end = strpos($text, ']', $start) + strlen(']');
    $prod = substr($text, $start, $end-$start);

    $temp = explode ("=", $prod);
    $ids = explode("," , substr($temp[1], 0, strpos($temp[1], "]")));

    $new_text = "<br />";

    /*
    $new_text .=
        '<div class="b-compare__content">
            <div class="b-compare__titleBlock">
                <div class="b-compare__titleList b-item__contentList" data-screen="screenItems">
                ';
    */

//    if($cur_page==$page)
    {
        // --- ids - список ID товаров, которые по сути являются товарами
        // --- нужно перебрать их все

        $count = 0;
        foreach($ids AS $i)
        {
            $i = 1*trim($i);
            if($i>0)
            {
                $q =
                    "
                    SELECT p.*, p.`name_ru-RU` title, c.category_id, l.name label_name
                    FROM #__jshopping_products AS p
                    LEFT JOIN #__jshopping_products_to_categories AS c ON c.product_id=p.product_id
                    LEFT JOIN #__jshopping_product_labels AS l ON p.label_id=l.id
                     WHERE p.product_id={$i}
                    ";

                $db->setQuery($q);
                $product = $db->loadObject();
				unset($label);
                $new_text .= include(JPATH_BASE."/components/com_jshopping/exe_product.php");
            }
        }
    }

    /*
    $new_text .= "
</div>
</div>
</div>
";
*/

    $new_text =
        '<nav class="b-item__content">
            <ul class="b-item__contentList hide_extra products_in_news" data-screen="screenItem" id="products_ul">'.
        $new_text .
        '</ul>
     </nav>';


    $text = str_replace($prod, $new_text, $text);
    // $start = $end;
    $cur_page++;

}





while(strpos($text, '[komplekt')>0)
{
    $com = parse_after($text, '[komplekt', ']');
    $id = 1*trim(str_replace('=', '', $com));

    $komplekt = '';

    $db->setQuery("SELECT *, `name_ru-RU` name FROM #__jshopping_products WHERE product_id={$id}");
    $prod = $db->loadObject();
    if($prod->product_id*1>0)
    {
        $this->product = $prod;
        ob_start();
        include (JPATH_ROOT.'/components/com_jshopping/templates/custom/product/komplekts.php');
        $komplekt = ob_get_contents();
        ob_end_clean();
        $komplekt = '</div></div></div></div></div></div><section>'.$komplekt.'</section><div class="container"><div><div><div><div><div>';
    }
    $text = str_replace('[komplekt'.$com.']', $komplekt, $text);
}



















$paginator = "";
/*
if($cur_page>1)
{
    // --- у нас более 1 страницы
    $link = $_SERVER['REQUEST_URI'];
    if(strpos($link, "?")>0)
        $link = substr($link, 0, strpos($link, "?"));

    $paginator =
    "<table class=\"jshop_pagination\">
        <tr>
            <td>
                <div class=\"pagination\">
                    <ul>";

    for($i=0; $i<$cur_page;$i++)
    {
        if($i==$page)
        $paginator .=
                        "<li><span class=\"pagenav\">" . ($i+1) . "</span></li>";
        else
            $paginator .=
                        "<li><a href=\"{$link}?page=" . $i . "\" class=\"pagenav\">" . ($i+1) . "</a></li>";
    }

    $paginator .=
    "               </ul>
                </div>
            </td>
        </tr>
    </table>";

}
  */



/*
{my_tabber}
{my_tab Заголовок1}
текст1
{my_tab Заголово2}
текст2
{my_tab Заголово2}
текст2
{/my_tabber}


<ul id="my_tabs">
    <li><a href="#" title="my_tab1">заголовок 1</a></li>
    <li><a href="#" title="my_tab2">заголовок 2</a></li>
    <li><a href="#" title="my_tab3">заголовок 3</a></li>
</ul>

<div id="my_content_tab">
    <loc id="my_tab1">текст1</loc>
    <loc id="my_tab2">текст2</loc>
    <loc id="my_tab3">текст3</loc>
</div>

<div class="clr"></div>
*/

$tab_index=1;
while(strpos(" ".$text, "{my_tabber")>0)
{
    $tabber = substr($text, strpos($text, "{my_tabber"));
    $tabber = substr($tabber, 0, strpos($tabber, "{/my_tabber}") + strlen("{/my_tabber}"));
    $text = str_replace($tabber, "<!--my_tabs-->", $text);

    $tabber = str_replace(Array("{my_tabber}", "{/my_tabber}"), "", $tabber);
    $temp = explode("{my_tab", $tabber);
    unset($temp[0]);
    $all_tabs = "";
    $all_text = "";
    $i = 1;
    foreach($temp AS $t)
    {
        $title = substr($t, 0, strrpos($t, "}"));
        $tab_text = trim(str_replace($title . "}", "", $t));
        $title = trim($title);

        $all_tabs .= "<li><a href=\"#\" title=\"my_tab_{$tab_index}_{$i}\">{$title}</a></li>\n";
        $all_text .= "<loc id=\"my_tab_{$tab_index}_{$i}\" >{$tab_text}</loc>\n";
        $i++;
    }

    $tab_text =
        "
        <div class='clr'></div>
        <ul id=\"my_tabs\" class='my_tabs_{$tab_index}'>
            {$all_tabs}
        </ul>

        <div id=\"my_content_tab\" class='my_content_tab_{$tab_index}'>
            {$all_text}
        </div>
        <div class='clr'></div>
        ";

    $tab_text .=
    "

    <script>
    jQ(document).ready(function() {
        jQ(\".my_content_tab_{$tab_index} loc\").hide(); // Скрываем содержание
        jQ(\".my_tabs_{$tab_index} li:first\").attr(\"id\",\"current\"); // Активируем первую закладку
        jQ(\".my_content_tab_{$tab_index} loc:first\").fadeIn(); // Выводим содержание

        jQ('.my_tabs_{$tab_index} a').click(function(e)
    {
        e.preventDefault();
        jQ(\".my_content_tab_{$tab_index} loc\").hide(); //Скрыть все сожержание
        jQ(\".my_tabs_{$tab_index} li\").attr(\"id\",\"\"); //Сброс ID
        jQ(this).parent().attr(\"id\",\"current\"); // Активируем закладку
        jQ('#' + jQ(this).attr('title')).fadeIn(); // Выводим содержание текущей закладки
    });
    })();
    </script>

    ";



    $text = str_replace("<!--my_tabs-->", $tab_text, $text);
    $tab_index++;
}



echo $text;
echo $paginator;

