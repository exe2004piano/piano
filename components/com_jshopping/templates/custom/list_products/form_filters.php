<?php
	die;
?>

<?php defined( '_JEXEC' ) or die(); ?>
<form action="<?php print $this->action;?>" method="post" name="sort_count" id="sort_count">
<?php if ($this->config->show_sort_product || $this->config->show_count_select_products){?>


<div class="block_sorting_count_to_page">
    <?php if ($this->config->show_sort_product){?>
        <span class="box_products_sorting">
            <?php print _JSHOP_ORDER_BY.": ".$this->sorting?>
            <img src="<?php print $this->path_image_sorting_dir?>" alt="orderby" onclick="submitListProductFilterSortDirection()" />
        </span>
    <?php }?>

    <?php
    if($_COOKIE['credit']*1==1)
    {
        $css_credit = " credit_selected ";
    }
    ?>
    &nbsp;&nbsp;<a href="#" onclick="set_credit();" class="credit_but <?php echo $css_credit; ?> " >В кредит</a>

    <div class="series_div on_sklad_div">
        <?php
        if(!isset($_COOKIE['show_all_sklad']))
            echo "Наличие: <u>Все</u><a href='#' onclick='setCookie(\"show_all_sklad\",1); location.reload(); return false;' >На складе</a>";
        else
            echo "Наличие: <a href='#' onclick='deleteCookie(\"show_all_sklad\"); location.reload(); return false;'>Все</a><u>На складе</u>";
        ?>
    </div>


    <?php if ($this->config->show_count_select_products){?>
        <span class="box_products_count_to_page"><?php print _JSHOP_DISPLAY_NUMBER.": ".$this->product_count?></span>
    <?php }?>
</div>



<?php }?>

<?php if ($this->config->show_product_list_filters && $this->filter_show){?>
    <?php if ($this->config->show_sort_product || $this->config->show_count_select_products){?>
    <div class="margin_filter"></div>
    <?php }?>

    <div class="jshop filters">
        <?php if ($this->filter_show_category){?>
        <span class="box_category"><?php print _JSHOP_CATEGORY.": ".$this->categorys_sel?></span>
        <?php }?>
        <?php if ($this->filter_show_manufacturer){?>
        <span class="box_manufacrurer"><?php print _JSHOP_MANUFACTURER.": ".$this->manufacuturers_sel?></span>
        <?php }?>
        <?php print $this->_tmp_ext_filter_box;?>

        <?php if (getDisplayPriceShop()){?>
        <span class="filter_price"><?php print _JSHOP_PRICE?>:
            <span class="box_price_from"><?php print _JSHOP_FROM?> <input type="text" class="inputbox" name="fprice_from" id="price_from" size="7" value="<?php if ($this->filters['price_from']>0) print $this->filters['price_from']?>" /></span>
            <span class="box_price_to"><?php print _JSHOP_TO?> <input type="text" class="inputbox" name="fprice_to"  id="price_to" size="7" value="<?php if ($this->filters['price_to']>0) print $this->filters['price_to']?>" /></span>
            <?php print $this->config->currency_code?>
        </span>
        <?php }?>

        <?php print $this->_tmp_ext_filter;?>
        <input type="button" class="button" value="<?php print _JSHOP_GO?>" onclick="submitListProductFilters();" />
        <span class="clear_filter"><a href="#" onclick="clearProductListFilter();return false;"><?php print _JSHOP_CLEAR_FILTERS?></a></span>
    </div>
<?php }?>
<input type="hidden" name="orderby" id="orderby" value="<?php print $this->orderby?>" />
<input type="hidden" name="limitstart" value="0" />
</form>

<?php
    $db = JFactory::getDBO();
    $cat_id = $this->category->category_id;

    $q = "
    SELECT a.`name_ru-RU` title, a.id attr_id, f.id field_id
    FROM      #__jshopping_products_extra_field_values AS a
    LEFT JOIN #__jshopping_products_extra_fields AS f ON f.id=a.field_id
    WHERE (f.cats='a:1:{i:0;s:1:\"{$cat_id}\";}' OR f.cats='a:1:{i:0;s:2:\"{$cat_id}\";}' OR f.cats='a:1:{i:0;s:3:\"{$cat_id}\";}') AND f.`name_en-GB`='attrib'
    ORDER BY a.ordering ASC
    ";
    $db->setQuery($q);
    if($res = $db->loadObjectList())
    {
        /*
        $url = $_SERVER['REQUEST_URI'];
        if(strpos($url, '&attr_id')>0)
            $url = substr($attr, 0, strpos($url, '&attr_id'));
        */

        // --- проверим если есть текущий атрибут на принадлежность к текущей категории
        $cur_attr = 1*$_COOKIE['attr_id'];
        echo "<div class='block_sorting_count_to_page series_div'>Серии: ";
        $itog = "";
        foreach($res AS $attr)
        {
            if($cur_attr==1*$attr->attr_id)
            {
                $itog .= "<u>{$attr->title}</u>";
                $cur_attr=-1;
            }
            else
            {
                $itog .= "<a href='#' onclick='set_attr({$attr->attr_id}, {$attr->field_id}, {$cat_id}); return false;'>{$attr->title}</a>";
            }
        }

        if( ($cur_attr!=-1) || ($itog=="") )
        {
            // --- мы перешли в другую категорию, не в ту, где была установлена кука
            unset($_COOKIE['attr_id']);
            unset($_COOKIE['field_id']);
            unset($_COOKIE['cat_id']);
            echo "<script>deleteCookie('attr_id'); deleteCookie('field_id'); deleteCookie('cat_id');</script>";
        }

       if($cur_attr!=0)
	       $itog = "<a href='#' onclick='set_attr(0, 0, 0); return false;' >Все</a>" . $itog;
	   else
	       $itog = "<u>Все</u>" . $itog;
       echo $itog . "</div>\n";
    }
else
{
    unset($_COOKIE['attr_id']);
    unset($_COOKIE['field_id']);
    unset($_COOKIE['cat_id']);
    echo "<script>deleteCookie('attr_id'); deleteCookie('field_id'); deleteCookie('cat_id');</script>";
}

// --- Проверим есть ли для данной категории доп. атрибуты, если да - то выведем их


$q = "
    SELECT attr_id
    FROM   #__jshopping_attr
    WHERE cats LIKE '%\"{$cat_id}\"%'
    ORDER BY attr_id
    ";

$cur_attr = 0;
$db->setQuery($q);
if($res = $db->loadObject())
    $cur_attr = 1*$res->attr_id;

if($cur_attr>0)
{
    // --- у данной категории есть атрибуты, найдем их:
    $q = "
    SELECT value_id id, `name_ru-RU` title
    FROM #__jshopping_attr_values
    WHERE attr_id=$cur_attr
    ORDER BY value_ordering
    ";
    $db->setQuery($q);
    $itog = "";
    // --- Проверим передал ли нам номер атрибута клиент:
    $my_attr = 1 * $_GET['product_type'];

    if($res = $db->loadObjectList())
    {
        $main_url = $_SERVER['REQUEST_URI'];
        if(strpos($main_url, "?")>0)
            $main_url = substr($main_url, 0, strpos($main_url, "?"));

        $itog .= "<div class='block_sorting_count_to_page series_div'>Вид инструмента: ";
        if($my_attr==0)
            $itog .= "<u>Все</u> | ";
        else
            $itog .= "<a href='{$main_url}'>Все</a> | ";


        foreach($res AS $a)
        {
            if($a->id==$my_attr)
                $itog .= "<u>" . $a->title . "</u> | ";
            else
                $itog .= "<a href='" . $main_url . "?product_type={$a->id}' >" . $a->title . "</a> | ";

        }
        $itog = substr($itog, 0, strrpos($itog, "|"));
        $itog .= "</div>";

        echo $itog;
    }
}

?>