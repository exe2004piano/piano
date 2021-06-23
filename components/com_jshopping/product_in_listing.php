<?php

defined( '_JEXEC' ) or die();
global $all_extra;
global $all_extra_names;
global $sub_cats;

/*
 * product - объект продукта
 * label - содержит как минимум id и name для метки
 * кроме стандартных данных берем первые 4 экстрафилдс значения которых ненулевые (непустые)
*/

//    $img = get_cache_photo_200($product->image);
    $img = get_cache_photo(JIMG.$product->image, 170, 170);

    $link = SEFLink('index.php?option=com_jshopping&controller=product&task=view&category_id='.$product->category_id.'&product_id='.$product->product_id, 1);
    $rating = $product->average_rating*10;

// 0 - есть на складе, 1 - под заказ (дата), 2 - нет, 3 - снято, 4 - под заказ (без даты), 5 - анонсированная модель

$sklad_title = 'в наличии';
$sklad_status = 'inStock';
if(isset($product->label_name))
{
    unset($label);
    $label->id = $product->label_id;
    $label->name = $product->label_name;
}

switch($product->sklad)
{
    case '0' :
        $sklad_title = 'в наличии';
        $sklad_status = 'inStock';
        break;
    case '1' :
        $sklad_title = 'на складе';
        $sklad_status = 'inStock';
        break;
    case '2' :
        $sklad_title = 'нет в наличии';
        $sklad_status = 'none';
        break;
    case '3' :
        $sklad_title = 'снят с производства';
        $sklad_status = 'none';
        break;
    case '4' :
        $sklad_title = 'под заказ';
        $sklad_status = 'order';
        break;
    case '5' :
        $sklad_title = 'анонсируемая модель';
        $sklad_status = 'notify';
        break;
}

// заполним экстрафилды
$extra = '';
$extra_num = 0;
for($i=1; $i<500;$i++)
{
    $name = 'extra_field_'.$i;
    if( (isset($product->$name)) && (1*trim($product->$name)>0) )
    {
        $extra .= '<li class="b-slider__aboutItem">• ' . $all_extra_names[$i] . ': ' . $all_extra[$i][1*trim($product->$name)] . '</li>';
        $extra_num++;
        if($extra_num>3)
            break;
    }
}

    $extra = '<ul class="b-slider__aboutList">' . $extra . '</ul>';

?>

<div class="b-slider__content">

    <div class="b-slider__left">
        <a href="<?php echo $link;?>">
            <div class="b-slider__img">
                <span class="product_in_listing b-slider__imgMark b-slider__imgMark--type<?php echo $label->id; ?>"><?php echo $label->name; ?></span>
                <img rel='<?php echo $img; ?>' alt="" src="https://piano.by/images/temp.png" class="postloader_src" />
            </div>
        </a>
        <div class="b-slider__leftContent">
            <a href="<?php echo $link;?>" class="b-slider__title"><?php echo $product->title; ?></a>
            <div class="b-slider__productRate">
                <span style="width: <?php echo $rating; ?>%"></span>
                <!--<a href="#" class="b-slider__productRate-num">(10 отзывов)</a>-->
            </div>
            <?php echo $extra; ?>
        </div>
    </div>
    <div class="b-slider__right">
        <div class="b-slider__price b-slider__price--credit">
            <!--
            <span class="b-slider__priceTime">12 x</span> 23 639 р.
            <span class="b-slider__priceMaret">В кредит</span>
            -->

            <?php echo ((($product->sklad!=3)&&($product->product_price>0))?echo_price($product->product_price,-1,-1, $product):''); ?>

        </div>
        <div class="b-slider__option product_in_listing">
            <span class="b-slider__optionStatus b-slider__optionStatus--<?php echo $sklad_status;?>"><?php echo $sklad_title;?></span>
            <div class="b-slider__optionList">
                <a href="#" data-get-popup="toCart" class="b-slider__optionOrder" onclick="add_to_basket('<?php echo trim($product->product_id); ?>',1); return false;">Заказать</a>
                <a href="#" class="b-slider__optionNone">Аналоги</a>
                <a href="#" class="b-slider__optionNotify">Оповестить</a>
                <a href="#" data-get-popup="toCart" class="b-slider__optionBasket" onclick="event_send('Korzina_Dobavit', 'DobavitVkorzina'); add_to_basket('<?php echo trim($product->product_id); ?>',1);">в корзину</a>
                <a href="#" class="b-slider__optionBasket" style="display: none;" onclick="location.href='/basket'; return false;">Оформить</a>
                <a href="#" class="b-slider__optionLike" onclick="add_to_like('<?php echo $product->product_id;?>'); return false;"></a>
                <a href="#" class="b-slider__optionCompare" onclick="add_to_compare('<?php echo $product->product_id;?>'); return false;"></a>
                <button data-get-popup="fastOrder" class="b-slider__oneClick" style="display: none;" onclick="event_send('Kupit_v_1klik1', 'Kupitv1klik1'); $('#product_one_click_id').val('<?php echo $product->product_id; ?>'); $('#product_one_click_price').val('<?php echo $product->product_price; ?>');">Купить в 1 клик</button>
            </div>
        </div>
        <div class="b-slider__delivery">
            <div class="b-slider__deliveryItem">
                <div class="b-slider__deliveryTitle">Доставка</div>
                <span class="b-slider__deliveryDetal">бесплатно, завтра</span>
            </div>
            <div class="b-slider__deliveryItem">
                <div class="b-slider__deliveryTitle">Забрать в магазине</div>
                <span class="b-slider__deliveryDetal">сегодня</span>
            </div>
        </div>
    </div>
</div>
