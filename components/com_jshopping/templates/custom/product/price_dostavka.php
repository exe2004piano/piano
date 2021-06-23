<?php
defined( '_JEXEC' ) or die();
$user = JFactory::getUser();
?>



<form class="b-option__content" action="#">
    <ul class="b-option__tabContent">

        <?php
        $v_id = 1*$_COOKIE['currency'];
        if(isset($_GET['prods_from_search_valute']))
            $v_id = $_GET['prods_from_search_valute']*1;
        // --- если незадана валюта либо задана неверно, то установим по-умолчанию:
        if( ($v_id<1) || ($v_id>3) )
            $v_id = 1;
        $actve[$v_id] = ' active ';

        for ($i=1;$i<=3;$i++)
        {
        ?>
        <li class="b-option__tabItem <?php echo $actve[$i];?>" data-tabContent="<?php echo $i;?>">
            <div class="b-option__tabPrice" itemprop="offers" itemscope itemtype="http://schema.org/Offer" >
                <?php

                if($product->sklad!=3)
                {
                    // --- отдельно фиксируем цену в рублях если она >0
                    $fixed_price = "price_".$i;

                if($product->$fixed_price==0)
                {
                if($product->product_old_price>0)
                    echo '<span class="b-option__tabPrice-old">' . echo_price($product->product_old_price, $i, -1, $product) . '</span>';
                }

                if($product->product_price>0)
                {
                    $cur_ = "";
                    if($i==1)
                        $cur_ = "BYN";
                    elseif($i==2)
                        $cur_ = "USD";
                    else
                        $cur_ = "RUR";

                    echo "<meta itemprop=\"priceCurrency\" content=\"{$cur_}\" />";

                    echo '<span class="b-option__tabPrice-new">' . echo_price($product->product_price, $i, -1, $product);
                    echo " <span style='display:none;' itemprop=\"price\">". (1.0*str_replace(" ", "", echo_price($product->product_price, $i, -1, $product))) ."</span>";

                    if($i==1)
                    {
                        // --- белки отобразим с BYR и BYN:
                        if($product->product_price>0)
                        {
                            $pr = $product->product_price;
                            if($product->price_1>0)
                                $pr = $product->price_1;

                            // echo '<br /><i>(' . number_format($pr*10000, 0, " ", " ") . ' р.)</i>';

                            // --- рассрочка
                            include_once("rassrochka.php");
                            echo "<br />";
                        }
                    }
                    echo '</span>';

                }
                }


                ?>
            </div>


            <div class="b-option__tabOptin">

                <? if ( ($product->ball >= 10) && ($product->sklad==0) ) { ?>
                    <div class="b-slider__optionStatus">
                        <a href="#oneClick" onclick="one_click_info(1); event_send('Kupit_v_1klik1', 'Kupitv1klik1'); $('#product_one_click').val('Экспресс-доставка'); $('#product_one_click_id').val('<?php echo $product->product_id; ?>');">
                            <div class="express-dostavka">Экспресс-доставка</div>
                        </a>
                    </div>
                <? } ?>

                <div class="b-slider__option">
                    <span class="b-slider__optionStatus b-slider__optionStatus--<?php echo $sklad_status;?>"><?php echo $sklad_title; ?></span>
                </div>
                <a href="#cheaper" class="b-option__tabCheaper" onclick="event_send('Nashli_deshevle1', 'NashliDeshevle1'); $('#product_cheap').val('<?php echo $product->name; ?>'); $('#product_cheap_id').val('<?php echo $product->product_id; ?>');"><span>Нашли дешевле?</span> </a>
            </div>
        </li>
        <?php
        }
        ?>
</ul>




<?php
$color_name = 'meta_title_be-BY';
$c_title = trim($product->$color_name);
$c_title = trim(mb_substr($c_title, 0, mb_strlen($c_title, "utf-8")-2, "utf-8"));

// --- иногда товары заканчиваются не маркой цвета - предусмотреть на будущее
$q = "
SELECT p.product_id, p.extra_field_8 color, c.category_id
FROM #__jshopping_products AS p
LEFT JOIN #__jshopping_products_to_categories AS c ON c.product_id = p.product_id
WHERE p.`meta_title_be-BY` LIKE " . $db->quote($c_title.'%') ." AND p.extra_field_8<>'0' AND p.extra_field_8<>''
GROUP BY p.product_id
ORDER BY c.product_ordering
";

// AND p.product_id<>{$product->product_id}
// ($product->extra_field_8);

$db->setQuery($q);
if( ($res = $db->loadObjectList()) && (sizeof($res)>1) )
{
    // --- есть цвета, отличные от нашего = есть смысл загрузить цвета
    $q = "SELECT id, `name_ru-RU` title FROM #__jshopping_products_extra_field_values WHERE field_id=8 ORDER BY id";
    $db->setQuery($q);
    $temp_colors = $db->loadObjectList();
    $colors = null;
    foreach($temp_colors AS $c)
        $colors[$c->id] = $c->title;
    unset($temp_colors);

?>
<div class="b-option__block">
    <h3 class="b-option__blockTitle">Варианты цветов:</h3>
    <ul class="b-filter__list b-filter__list--color">

        <?php
            $i=1;
            foreach($res AS $r)
            {
                $link = SEFLink('index.php?option=com_jshopping&controller=product&task=view&category_id='.$r->category_id.'&product_id='.$r->product_id, 1);
                if($r->product_id!=$product->product_id)
                {
            ?>

                    <li class="b-filter__item color_item">
                        <a href="<?php echo $link;?>" title="<?php echo $colors[$r->color]; ?>"><div class="color_<?php echo $r->color;?>"> </div></a>
                    </li>

            <?php
                }
                else
                {
                    ?>
                    <li class="b-filter__item color_item active_color">
                        <a href="#" onclick="location.reload(); return false;" title="<?php echo $colors[$r->color]; ?>"><div class="color_<?php echo $r->color;?>"> </div></a>
                    </li>
                    <?php
                }
            }
            ?>
    </ul>
</div>
<?php
}
?>
<!--
<div class="b-option__block">
    <h3 class="b-option__blockTitle">Доп. гарантия:</h3>
    <div class="b-option__line">
        <div class="b-option__checkWrap">
            <input type="radio" name="option" id="option_ch1_1" hidden checked>
            <label for="option_ch1_1">нет</label>
        </div>
        <div class="b-option__checkWrap">
            <input type="radio" name="option" id="option_ch1_2" hidden >
            <label for="option_ch1_2">на 2 года <span>(+12 000 р.)</span></label>
        </div>
    </div>
</div>
-->

<?php

$db->setQuery("SELECT * FROM #__z_config WHERE id=20 ");
$c = $db->loadObject();
$dost_mo_text = $c->value;

$db->setQuery("SELECT * FROM #__z_config WHERE name='dostavka' ");
$c = $db->loadObject();
$temp = explode("\n", $c->value);
$config = null;
$i=0;
foreach($temp AS $t)
{
    if($i++>5)
        break;

    $tt = explode("=", $t);
    $tt[1] = 1.0*trim($tt[1]);
    $config[$i] = $tt[1];
}

// config :
// [1] => минимальная сумма бесплатной доставки по минску
// [2] => стоимость доставки если сумма меньше
// [3] [4] => тоже самое для РБ
// [5] [6] => тоже самое для Москвы

if($product->product_price>=$config[1])
    $dost_minsk = "бесплатно";
else
    $dost_minsk = $config[2] . ' руб.';

if($product->product_price>=$config[3])
    $dost_rb = "бесплатно";
else
    $dost_rb = "от " . $config[4] . ' руб.';

if($product->product_price>=$config[5])
    $dost_mo = "бесплатно";
else
    $dost_mo = "от " . $config[6] . ' RUR';

?>



<?php
if($dost_srok_minsk!='')
{
    // --- если указан срок доставки, тогда эти блоки выводим
    ?>
    <div class="b-option__block">
        <div class="b-option__lineEnter">
            <div class="b-option__lineEnter-left">
                <span class="b-option__counterText">Количество</span>
            </div>
            <div class="b-option__lineEnter-center">
                <div class="b-counter">
                    <span class="b-counter__nav b-counter__nav--minus"></span>
                    <input type="text" class="b-counter__area" id="add_to_basket_num" value="1">
                    <span class="b-counter__nav b-counter__nav--plus"></span>
                </div>
            </div>
            <div class="b-option__lineEnter-right">
                <button class="b-option__busket" onclick="event_send('Korzina_Dobavit', 'DobavitVkorzina'); add_to_basket(<?php echo $product->product_id;?>, $('#add_to_basket_num').val()); $(this).hide(); $(this).next().show(); return false;">ДОБАВИТЬ В КОРЗИНУ</button>
                <button class="b-option__busket active" style="display: none;" onclick="location.href='/basket'; return false;">ОФОРМИТЬ ПОКУПКУ</button>
            </div>
        </div>

        <div class="b-option__buttons">
            <a href="#fee" class="b-option__buttonItem" onclick="event_send('Platit_potom1', 'PlatiPotom1'); $('#product_credit').val('<?php echo $product->name;?>'); $('#product_credit_id').val('<?php echo $product->product_id; ?>');">Платить потом</a>
            <a href="#oneClick" class="b-option__buttonItem" onclick="one_click_info(0); event_send('Kupit_v_1klik1', 'Kupitv1klik1'); $('#product_one_click').val('<?php echo $product->name;?>'); $('#product_one_click_id').val('<?php echo $product->product_id; ?>'); $('#product_one_click_price').val('<?php echo $product->product_price; ?>'); ">Купить в 1 клик</a>
        </div>

    </div>

    <div class="b-option__block b-option__block--type1">
        <div class="b-option__blockItem b-option__blockItem--left">
            <h3 class="b-option__itemTitle b-option__itemTitle--type1">Доставка по Минску</h3>
            <span class="b-option__itemLink" onclick="show_dostavka('dostavka_minsk');"><?php echo $dost_minsk . ' ' . $dost_srok_minsk; ?></span>
            <div class="dostavka_info" id="dostavka_minsk">Доставка по Минску осуществляется с 9-00 до 22-00 курьером до подъезда.</div>
        </div>

        <div class="b-option__blockItem b-option__blockItem--right">
            <h3 class="b-option__itemTitle b-option__itemTitle--type2" >Доставка по РБ</h3>
            <span class="b-option__itemLink" onclick="show_dostavka('dostavka_rb');"><?php echo $dost_rb . ' ' . $dost_srok_rb; ?></span>
            <div class="dostavka_info" id="dostavka_rb" >Доставка по РБ осуществляется с 9-00 до 22-00 курьером до подъезда.</div>
        </div>

        <hr />
        <div class="b-option__blockItem b-option__blockItem--all" style="width: 100%;">
            <h3 class="b-option__itemTitle b-option__itemTitle--type3" style="display: inline-block; float: left;">Доставка по Москве и МО</h3>
            <span class="b-option__itemLink" onclick="show_dostavka('dostavka_mo');"><?php echo $dost_mo . ' ' . $dost_srok_rb; ?></span>
            <div class="dostavka_info" id="dostavka_mo" style="margin-top: 10px; max-width: 80%!important; width: 80%!important;"><?php echo $dost_mo_text;?></div>
        </div>


    </div>
<?php
}
elseif($product->sklad==5)
{
    // --- анонс
    ?>
    <div class="b-option__block">
        <div class="b-option__buttons">
            <h3 class="b-option__itemTitle b-option__itemTitle--type2">Узнайте первым о появлении данной модели в продаже!</h3>
            <a href="#anons" onclick="$('#product_anons').val('<?php echo $product->name;?>'); $('#product_anons_id').val('<?php echo $product->product_id; ?>');" class="b-option__buttonItem">Оповестить о наличии</a>
        </div>
    </div>
<?php
}
else
{
    // --- снято с производства либо нет на складе
    $analog_id = 1*$product->analog_id;
    $analog_button = "";
    if($analog_id>0)
    {
        $db->setQuery("SELECT real_link FROM #__jshopping_products WHERE product_id={$analog_id}");
        if($analog = $db->loadObject())
            $analog_button = "<a href=\"{$analog->real_link}\" class=\"b-option__buttonItem\" >Посмотреть аналог</a>";
    }

    ?>
    <div class="b-option__block">
        <div class="b-option__buttons">
            <h3 class="b-option__itemTitle b-option__itemTitle--type2">Данная модель уже не выпускается.</h3>
            <a href="#analog" onclick="$('#product_analog').val('<?php echo $product->name;?>'); $('#product_one_click_id').val('<?php echo $product->product_id; ?>');" class="b-option__buttonItem">Подобрать аналог</a>
            <?php echo $analog_button; ?>
        </div>
    </div>
<?php
}
?>


</form>



