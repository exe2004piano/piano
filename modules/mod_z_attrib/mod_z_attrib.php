<?php
/**
 * @package     Joomla.Site
 * @subpackage  mod_custom
 *
 * @copyright   Copyright (C) 2005 - 2015 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;


?>

<section class="b-catalog">
    <div class="container">
        <?php
        /*
         * публиковать нужно группами по 3 аттрибута в строке
         * для адаптивности блоки с атрибутами должны занимать 6+3+3, либо 3+6+3, либо 3+3+6 столбцов из 12 возможных
         * соответственно по-умолчанию класс будет 3, а 6 будем подставлять по мере надобности
         */

        $db = JFactory::getDBO();
        $q = "SELECT `name_en-GB` link, `name_ru-RU` title FROM #__jshopping_free_attr WHERE main=1 ORDER BY id DESC";
        $db->setQuery($q);
        $attribs = $db->loadObjectList();


        $cur_num = 0;   // --- количество записей в текущей строке, если 1 - то строку открываем, если 3 - то закрываем и обнуляем счетчик
        $is_6 = 1;      // --- показатель того, какой из элементов будет занимать 6 столбцов


        foreach($attribs AS $a)
        {
            $cur_num++;
            if($cur_num==1)
                echo '<div class="row b-catalog__itemWrap">';

            if($is_6==$cur_num)
                $class = '6';
            else
                $class = '3';

            $title = trim(substr($a->title, 0, strpos($a->title, ' ')));
            $name = trim(substr($a->title, strpos($a->title, ' ')));

?>

            <div class="col-md-<?php echo $class;?>">
                <a href="https://pianino.by/items/<?php echo $a->link;?>" class="b-catalog__item b-catalog__item--type1 lazy" data-r="1" data-src="<?=get_webp("/images/attrib/{$a->link}.jpg");?>" >
                    <div class="b-catalog__text">
                        <h4 class="b-catalog__title"><?php echo $title;?></h4>
                        <span class="b-catalog__name"><?php echo $name;?></span>  
                    </div>
                </a>
            </div>

<?php

            if($cur_num==3)
            {

                echo '</div>';
                $cur_num=0;
                $is_6++;
                if($is_6==4)
                    $is_6 = 1;
            }


            $text .= str_replace(
                Array("<!--link-->", "<!--title-->"),
                Array($a->link, $a->title),
                $temp
            );
        }

        // --- дошли до финала, но не закрыли строку полностью:
        if($cur_num!=0)
            echo '</div>';
        ?>
    </div>
</section>




<?php
/*
 // --- OLD VARIANT : ---

$temp =
"
            <li>
                <div class=\"block_item\">
                    <div class=\"item_name\">
                        <span class=\"h3\"><a href=\"/items/<!--link-->\"><!--title--></a></span>
                    </div>
                    <div class=\"item_image\">
						<span>
							<a href=\"/items/<!--link-->\">
                                <div class=\"img\">
                                    <img src=\"/images/attrib/<!--link-->.jpg\" alt=\"<!--title-->\" />
                                </div>
                            </a>
						</span>
                    </div>
                </div>
            </li>
";

$db = JFactory::getDBO();
$q = "SELECT `name_en-GB` link, `name_ru-RU` title FROM #__jshopping_free_attr WHERE main=1 ORDER BY id DESC";
$db->setQuery($q);
$attribs = $db->loadObjectList();

$text = "";
foreach($attribs AS $a)
{
    $text .= str_replace(
        Array("<!--link-->", "<!--title-->"),
        Array($a->link, $a->title),
        $temp
    );
}

$count = sizeof($attribs);
if($count>3)
    $count = 3;

echo
"
<script type=\"text/javascript\">
    jQuery(document).ready(function(){
        jQuery('#label_slider99').bxSlider({
            prevSelector:'.jt_button_prev_l_99',
            nextSelector:'.jt_button_next_l_99',
            mode: 'horizontal',
            speed: 500,
            controls: true,
            auto: false,
            pause: 3000,
            autoDelay: 0,
            autoHover: false,
            pager: false,
            pagerType: 'full',
            pagerLocation: 'bottom',
            pagerShortSeparator: '/',
            displaySlideQty: {$count},
            moveSlideQty: {$count}	});
    });
</script>


<style type=\"text/css\">
    #label_slider99 li { background: none;  width:170px; height:180px;}
    .jt_button_prev_l_99 a, .jt_button_next_l_99 a {height:180px;}
    #label_slider99 img
    {
    width: 150px; height: 150px;
    }
    #label_slider99 li .block_item
    {
    border: 1px solid #EEE;
    margin: 5px;
    padding: 10px;
    text-align: center;
    }

    #label_slider99 li .block_item .item_image
    {
        margin-top: 10px;
    }

    #label_slider99 .item_name
    {
        height: 48px;
    }

</style>




<div class=\"mod_jt_jshopping_label_products \">
    <div id=\"jt_jshopping_label_slider\">
        <div class=\"jt_button_prev_l_99 jt_prev_l\"></div>
        <ul id=\"label_slider99\">

        {$text}


        </ul>
        <div class=\"jt_button_next_l_99 jt_next_l\"></div>
    </div>
    <div style=\"clear:both\"></div>
</div>

";

*/