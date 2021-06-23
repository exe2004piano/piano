<?php
defined( '_JEXEC' ) or die();
global $roicode_products;
$roicode_products[$product->product_id] = $product->product_id;



$product->title = trim(no_tags(str_replace(Array("'", '"'), "`", $product->title)));
$product->link = SEFLink('index.php?option=com_jshopping&controller=product&task=view&category_id='.$product->category_id.'&product_id='.$product->product_id, 1);
$product->img = get_cache_photo(JIMG.$product->image, 278, 215);

$product->rating = $product->average_rating;
// 0 - есть на складе, 1 - под заказ (дата), 2 - нет, 3 - снято, 4 - под заказ (без даты), 5 - анонсированная модель
$sklad_title = 'в наличии';
$sklad_status = 'inStock';

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

$product->sklad_title = $sklad_title;
$product->sklad_status = $sklad_status;

$float_right = "";
$compare_close = '';
if(isset($product->compare))
{
    $compare_close = '<a href="#" class="b-compare__titleItem-close" onclick="delete_from_compare('.$product->product_id.');"></a>';
    $float_right = "float_right";
}

if(isset($product->like_product))
{
    $compare_close = '<a href="#" class="b-compare__titleItem-close" onclick="delete_from_like('.$product->product_id.');"></a>';
    $float_right = "float_right";
}


$old_price = "";
if((float)$product->product_old_price)
{
	$old_price = (float)$product->product_old_price;
	$skidka = ceil(($old_price-(float)$product->product_price)/$old_price*100);
	$old_price = '
	                  <span> '. $old_price .'</span>
    	              <p>-'.$skidka.'%</p>
                 ';
}



$ratings = "";
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


return
'<div class="list__item">
            '.$compare_close.'
          
                    <div class="list__item-head">
                        <a href="'.$product->link.'" class="list__item-img">
                             <img  alt="" data-lazy="'.$product->img.'" />
                        </a>
                        <span class="list__item-label is--type1' . $product->label_id . '">' . $product->label_name . '</span>

                         <div class="list__item-btns">
                            <div class="list__item-btn">
                                <a href="#" onclick="add_to_like(\''. $product->product_id.'\'); return false;" class="bv-btn bv-btn--like add_to_like">
                                    <svg class="heart-icon">
                                        <use class="heart-icon__part" xlink:href="/templates/pianino_new/i/sprite.svg#heart"></use>
                                    </svg>
                                </a>
                            </div>

                            <div class="list__item-btn">
                                <a href="#" onclick="add_to_compare(\''. $product->product_id.'\'); return false;" class="bv-btn bv-btn--libra add_to_compare">
                                    <svg class="libra-icon">
                                        <use class="libra-icon__part" xlink:href="/templates/pianino_new/i/sprite.svg#libra"></use>
                                    </svg>
                                </a>
                            </div>
                        </div>
                    </div>
                   
                    <div class="list__item-body">
                        <div class="list__item-title">
                            <a href="'.$product->link.'">' . $product->title . '</a>
                        </div>

                        <div class="list__item-blocks">
                            <div class="list__item-block">
                                <div class="list__item-status is--'.$product->sklad_status.'">
                                    <span>'.$product->sklad_title.'</span>
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
                        	<div class="price-old">&nbsp;'.$old_price.'&nbsp;</div>
                            <span>' . echo_price($product->product_price, -1 ,-1 , $product) . '</span>
                        </div>

                    </div>
                  <div class="list__item-footer">
                    <div class="list__item-btns">
                        <div class="list__item-btn list__item-popup">
                            <button data-get-popup="fastOrder"
                                    class="bv-btn bv-btn--third one-click-new"
                                    onclick="event_send(\'Kupit_v_1klik1\', \'Kupitv1klik1\');"
                                    data-id="'.$product->product_id.'"
                                    data-title="'.str_replace(Array("'", '"'), "", $product->title).'"
                                    data-price="'.$product->product_price.'"
                            >
                                <span class="bv-btn__text">Купить в 1 клик</span>
                            </button>
                            
                            
                        </div>

                        <div class="list__item-btn">
                            <a href="#" data-get-popup="toCart" onclick="add_to_basket(\''.trim($product->product_id).'\',1); return false;" class="bv-btn bv-btn--cart">
                                <span class="bv-btn__icon">
                                    <svg class="cart-icon">
                                        <use class="cart-icon__part" xlink:href="/templates/pianino_new/i/sprite.svg#cart"></use>
                                    </svg>
                                </span>
                            </a>
                        </div>
                    </div>
                  </div>
        </div>
';