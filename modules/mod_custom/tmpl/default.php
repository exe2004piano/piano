<?php
/**
 * @package     Joomla.Site
 * @subpackage  mod_custom
 *
 * @copyright   Copyright (C) 2005 - 2016 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

$db = JFactory::getDbo();
$text = " " .$module->content;
$cur_page = $start = 0;
$new_text = "";

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
                if($product = $db->loadObject())
                    $new_text .= include(JPATH_BASE."/components/com_jshopping/exe_product.php");
            }
        }


    $new_text =
        '<nav class="b-item__content">
            <ul class="list list--fourth" data-screen="screenItem" id="products_ul">'.
        $new_text .
        '</ul>
</nav>';

    $text = str_replace($prod, $new_text, $text);
    $cur_page++;
}




echo $text;

