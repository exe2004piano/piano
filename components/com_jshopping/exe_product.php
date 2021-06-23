<?php

defined( '_JEXEC' ) or die( 'Restricted access' );
if(!isset($product))
    $product = $this->product;

if(!isset($product->real_link))
{
	$link = SEFLink('index.php?option=com_jshopping&controller=product&task=view&category_id=' . $product->category_id . '&product_id=' . $product->product_id, 1);
	$link = str_replace(Array('/exe/', '/z/'), '/', $link);
}
else
	$link = $product->real_link;

$price = echo_price($product->product_price, -1, -1, $product);

if(!isset($z_cat_alias))
	$z_cat_alias = "";

$sale = "";
if( (isset($product->sale)) && ($product->sale*1>0) )
{
    $sale = "<div class='product_sale'>-{$product->sale}%</div>";
}

$old_price = '';
if($product->product_old_price*1.0 > 0)
	$old_price = echo_price($product->product_old_price, -1, -1, $product);

//$img = get_cache_photo_200($product->image);
if(trim($product->image)!='')
	$img = get_cache_photo(JIMG.$product->image, 170, 170);
else
	$img = '/images/temp.jpg';

$rating = $product->average_rating*10;

global $all_extra_names;
global $all_extra;
global $sr_products;
// 0 - в наличии, 1 - на складе, 2 - нет, 3 - снято, 4 - под заказ (без даты), 5 - анонсированная модель

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
        $extra .= '<div class="list__item-text">' . $all_extra_names[$i] . ': ' . $all_extra[$i][1*trim($product->$name)] . '</div>';
        $extra_num++;
        if($extra_num>3)
            break;
    }
}
$extra = '<div class="list__item-contains">' . $extra . '</div>';



$float_right = "";
$compare_close = '';
if(isset($product->compare))
{
    if( (!isset($sr_products)) || ($sr_products=='') )
        $compare_close = '<a href="#" class="b-compare__titleItem-close" onclick="delete_from_compare('.$product->product_id.');"></a>';
    $float_right = "float_right";
}

if(isset($product->like_product))
{
    $compare_close = '<a href="#" class="b-compare__titleItem-close" onclick="delete_from_like('.$product->product_id.');"></a>';
    $float_right = "float_right";
}


if(!isset($label))
{
    $label = new stdClass();
    $label->id = 0;
    $label->name = '';
}

	$ratings = '';
	for($i=0;$i<$product->average_rating;$i++)
	{
		$ratings .= '
	<div class="icon-list__item">
		<svg class="star-icon">
			<use class="star-icon__part" xlink:href="/templates/pianino_new/i/sprite.svg#star"></use>
		</svg>
	</div>
	';
	}


$itog =
'
<div class="list__item z_'.$z_cat_alias.' ">
'.$compare_close.'
    <div class="list__item-head">
        <a href="'. $link .'" class="list__item-img">
            <div class="lazyload">
                <!--<img  alt="" src="'.$img.'" />-->
            </div>
            
        </a>

        
        <!--<span class="list__item-label is--type'.$label->id.'">'.$label->name.'</span>-->
        
        <div class="list__item-btns">
            <div class="list__item-btn">
                <a href="#" class="bv-btn bv-btn--like add_to_like" data-product="'.$product->product_id.'" ><svg class="heart-icon">
                    <use class="heart-icon__part" xlink:href="/templates/pianino_new/i/sprite.svg#heart"></use>
                    </svg>
                </a>
            </div>

            <div class="list__item-btn">
                <a href="#" class="bv-btn bv-btn--libra add_to_compare" data-product="'.$product->product_id.'"><svg class="libra-icon">
                    <use class="libra-icon__part" xlink:href="/templates/pianino_new/i/sprite.svg#libra"></use>
                    </svg>
                </a>
            </div>
        </div>

    </div>
    <div class="list__item-body">
        <div class="list__item-title">
            <a href="'.$link.'">'.$product->title.'</a>
        </div>

        <div class="list__item-blocks">
            <div class="list__item-block">
                <div class="list__item-status is--'.$sklad_status.'">
                    <span>'.$sklad_title.'</span>
                </div>
            </div>

            <div class="list__item-block list__item-ratings">
                <div class="list__item-rating">
                    <div class="icon-list">
                    '.$ratings.'
                    </div>
                </div>
            </div>
        </div>

        <div class="list__item-price">
            <div class="price-old">
                <span>'.$old_price.'</span>
                <p> '.$sale.'</p>
            </div>

            <span>'.((($product->sklad!=3)&&($product->product_price>0))?echo_price($product->product_price,-1,-1, $product):'').'</span>
        </div>
    </div>
    <div class="list__item-footer">
        <div class="list__item-btns">
            <div class="list__item-btn list__item-popup">
                <button data-get-popup="fastOrder" class="bv-btn bv-btn--third" onclick="event_send(\'Kupit_v_1klik1\', \'Kupitv1klik1\'); $(\'#product_one_click\').val(\''.$product->title.'\'); $(\'#product_one_click_id\').val(\''.$product->product_id.'\'); $(\'#product_one_click_price\').val(\''.$product->product_price.'\'); ">Купить в 1 клик</button>
            </div>

            <div class="list__item-btn">
                <a href="#" data-get-popup="toCart" class="bv-btn bv-btn--cart" onclick="event_send(\'Korzina_Dobavit\', \'DobavitVkorzina\'); add_to_basket(\''.trim($product->product_id).'\',1);"><span class="bv-btn__icon"><svg class="cart-icon">
            <use class="cart-icon__part" xlink:href="/templates/pianino_new/i/sprite.svg#cart"></use>
          </svg></span></a>
            </div>
        </div>


        <!-- подставлять вместо купить 1 в клик и корзины-->
        <div class="list__item-btns" style="display: none;">
            <div class="list__item-btn is--second">
                <a href="#" class="list-btn" data-get-popup="analog" onclick="$(\'#product_analog\').val(\''.$product->title.'\'); $(\'#product_analog_id\').val(\''.$product->product_id.'\');">Аналоги</a>
            </div>

            <div class="list__item-btn is--second">
            <a href="#" class="list-btn list-btn--second" data-get-popup="notify" onclick="$(\'#product_anons\').val(\''.$product->title.'\'); $(\'#product_anons_id\').val(\''.$product->product_id.'\');"><span
                class="list-btn__text">Оповестить</span>
                <div class="list-btn__icon"><svg class="alarm-icon">
                    <use class="alarm-icon__part" xlink:href="/templates/pianino_new/i/sprite.svg#alarm"></use>
                </svg></div></a>
            </div>
        </div>


        <!-- подставлять вместо купить 1 в клик и корзины-->
        <div class="list__item-btns" style="display: none;">
            <div class="list__item-btn is--second">
                <a href="#" class="list-btn list-btn--third" data-get-popup="toOrder" onclick="add_to_basket(\''.trim($product->product_id).'\',1); return false;">Заказать</a>
            </div>
        </div>
    </div>
</div>
';

return $itog;