<?
	global $valute;
	$kurs_ye = $valute[2]['value'];
?>

<div class="list__item">
	<div class="list__item-head">
		<a class="list__item-img" href="<?=$link;?>"
		   onclick="gp_productClick('<?=str_replace('/', ' ', $cat_list);?>',  '<?=str_replace(Array('"', "'"), "`", $product->title);?>',   '<?=$product->product_id;?>',   '<?=round($product->product_price/$kurs_ye, 2);?>',   '<?=$all_vendors_copy[$product->product_manufacturer_id];?>',   '<?=$cat_list;?>',  '<?=$cur_i;?>' );"
        >
			<div class="lazyload">
				<!--<img alt="" src="<?=$img;?>">-->
			</div>
		</a>


		<div class="list__item-btns">
			<div class="list__item-btn">
				<a href="#" onclick="add_to_like('<?=$product->product_id;?>'); return false;"
				   class="bv-btn bv-btn--like add_to_like">
					<svg class="heart-icon">
						<use class="heart-icon__part" xlink:href="/templates/pianino_new/i/sprite.svg#heart"></use>
					</svg>
				</a>
			</div>

			<div class="list__item-btn">
				<a href="#" onclick="add_to_compare('<?=$product->product_id;?>'); return false;"
				   class="bv-btn bv-btn--libra add_to_compare">
					<svg class="libra-icon">
						<use class="libra-icon__part" xlink:href="/templates/pianino_new/i/sprite.svg#libra"></use>
					</svg>
				</a>
			</div>
		</div>

	</div>

	<div class="list__item-body">
		<span class="roiprod_id" id="roiprod_<?=$product->product_id;?>"></span>

		<div class="list__item-title">
			<a href="<?=$link;?>"><?=$product->title;?></a>
		</div>

		<div class="list__item-blocks">
			<div class="list__item-block">
				<div class="list__item-status is--<?=$sklad_status;?>">
					<span><?=$sklad_title;?></span>
				</div>
			</div>

			<div class="list__item-block list__item-ratings">
				<div class="list__item-rating">
					<div class="icon-list">

						<? for($i=0;$i<$product->average_rating;$i++) { ?>
                            <div class="icon-list__item">
                                <svg class="star-icon">
                                    <use class="star-icon__part" xlink:href="/templates/pianino_new/i/sprite.svg#star"></use>
                                </svg>
                            </div>
						<? } ?>

					</div>
				</div>
			</div>
		</div>

		<div class="list__item-price">
			<? if($product->product_old_price>0) { ?>
				<div class="price-old">
					<span><?=echo_price($product->product_old_price);?></span>
					<? $skidka = ceil(($product->product_old_price-(float)$product->product_price)/$product->product_old_price*100); ;?>
					<p>-<?=$skidka;?>%</p>
				</div>
			<? } ?>
			<span><?php echo ((($product->sklad!=3)&&($product->product_price>0))?echo_price($product->product_price,-1,-1, $product):'') . $old_price; ?></span>
		</div>

		<div class="list__item-contains">
			<?=$extra;?>
		</div>

	</div>
	<div class="list__item-footer">



		<div class="list__item-btns">
                <? if($product->sklad<=1) { // --- в наличии или на складе ?>
                        <div class="list__item-btn list__item-popup">
                            <button data-get-popup="fastOrder"
                                    class="bv-btn bv-btn--third one-click-new"
                                    onclick="event_send('Kupit_v_1klik1', 'Kupitv1klik1');"
                                    data-id="<?=$product->product_id;?>"
                                    data-title="<?=str_replace(Array("'", '"'), "", $product->title);?>"
                                    data-price="<?=$product->product_price;?>"
                            >
                                <span class="bv-btn__text">Купить в 1 клик</span>
                            </button>
                        </div>

                        <div class="list__item-btn">
                            <a href="#"
                               data-get-popup="toCart"
                               class="bv-btn bv-btn--cart"
                               onclick="event_send('Korzina_Dobavit', 'DobavitVkorzina');
                               add_to_basket('<?php echo trim($product->product_id); ?>',1);
                               gp_productCart('<?=str_replace('/', ' ', $cat_list);?>',  '<?=str_replace(Array('"', "'"), "`", $product->title);?>',   '<?=$product->product_id;?>',   '<?=round($product->product_price/$kurs_ye, 2);?>',   '<?=$all_vendors_copy[$product->product_manufacturer_id];?>',   '<?=$cat_list;?>',  '<?=$cur_i;?>' );
                               ">
                               <span class="bv-btn__icon">
                                   <svg class="cart-icon"><use class="cart-icon__part" xlink:href="/templates/pianino_new/i/sprite.svg#cart"></use></svg>
                               </span>
                            </a>
                        </div>
                <? } elseif( $product->sklad==2 || $product->sklad==5)  { // ---  нет в наличии или анонс ?>
                        <div class="list__item-btn is--second">
                            <a href="#"
                               class="list-btn list-btn--second anons-click-new"
                               data-get-popup="notify"
                               data-id="<?=$product->product_id;?>"
                               data-title="<?=str_replace(Array("'", '"'), "", $product->title);?>"
                               data-price="<?=$product->product_price;?>"
                            >
                                <span class="list-btn__text">Оповестить</span>
                                <div class="list-btn__icon">
                                    <svg class="alarm-icon">
                                        <use class="alarm-icon__part" xlink:href="/templates/pianino_new/i/sprite.svg#alarm"></use>
                                    </svg>
                                </div>
                            </a>
                        </div>
				<? } elseif($product->sklad==4)  { // --- под заказ ?>
                        <div class="list__item-btn is--second">
                            <a href="#" data-get-popup="toCart" class="list-btn list-btn--third" onclick="event_send('Korzina_Dobavit', 'DobavitVkorzina'); add_to_basket('<?php echo trim($product->product_id); ?>',1);">
                                <span class="list-btn__text">Заказать</span>
                                <div class="list-btn__icon">
                                    <svg class="cart-icon">
                                        <use class="cart-icon__part" xlink:href="/templates/pianino_new/i/sprite.svg#cart"></use>
                                    </svg>
                                </div>
                            </a>
                        </div>
				<? } elseif($product->sklad==3)  { // --- снят с производства ?>
                    <div class="list__item-btn is--second">
                        <a href="#" data-get-popup="analog" data-name="<?=$product->title;?>" data-id="<?=$product->product_id;?>"
                           class="list-btn analog-btn">
                            <span class="list-btn__text">Аналоги</span>
                            <div class="list-btn__icon">
                                <svg class="brief-icon">
                                    <use class="brief-icon__part" xlink:href="/templates/pianino_new/i/sprite.svg#brief"></use>
                                </svg>
                            </div>
                        </a>
                    </div>
            <? } ?>
		</div>


        <? if($dost_srok_minsk!='') { ?>
                <div class="list__item-row">
                    <p><strong>Доставка по Минску:</strong> <br> <?=$dost_minsk;?>, <?=$dost_srok_minsk;?></p>
                </div>
                <div class="list__item-row">
                    <p><strong>Доставка по РБ:</strong> <br> <?=$dost_rb;?>, <?=$dost_srok_rb;?></p>
                </div>
        <? } ?>


	</div>
</div>