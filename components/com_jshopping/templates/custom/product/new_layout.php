<?php
    global $db;

	$cat_id = $this->product->product_categories[0]->category_id;
    $cat = $db->setQuery("SELECT * FROM #__jshopping_categories WHERE category_id={$cat_id}")->loadObject();
	$cat_list = "";
	$cat_list .= str_replace(Array('"', "'"), "`", $cat->{'name_ru-RU'});

	if($cat->category_parent_id!=0)
	{
		$db->setQuery("SELECT `name_ru-RU` title, category_parent_id FROM #__jshopping_categories WHERE category_id={$cat->category_parent_id}");
		$temp = $db->loadObject();
		$cat_list = str_replace(Array('"', "'"), "`",$temp->title) . "/" . $cat_list;
		if($temp->category_parent_id>0)
		{
			$db->setQuery("SELECT `name_ru-RU` title, category_parent_id FROM #__jshopping_categories WHERE category_id={$temp->category_parent_id}");
			$temp = $db->loadObject();
			$cat_list = str_replace(Array('"', "'"), "`",$temp->title) . "/" . $cat_list;
		}
	}

	global $valute;
	$kurs_ye = $valute[2]['value'];
	$vendor = $db->setQuery("SELECT `name_ru-RU` title FROM #__jshopping_manufacturers WHERE manufacturer_id=".((int)$product->product_manufacturer_id))->loadObject()->title;
?>

<!-- New layout -->
  <div class="section__title">
    <h1><span itemprop="name"><span itemprop="description"><?php echo $product->name;?></span></span></h1>
</div>
<div class="section__subtitle">
	<h6># <?php echo $product->product_ean; ?><span id="roicode"></span></h6>

	<ul class="icon-list">
		<? for($i=0;$i<$product->average_rating;$i++) { ?>
		<li class="icon-list__item">
			<svg class="star-icon">
				<use class="star-icon__part" xlink:href="/templates/pianino_new/i/sprite.svg#star"></use>
			</svg>
		</li>
		<? } ?>
	</ul>
</div>

<div class="section__row section__row--second">
	<div class="section__row-item  section__row-card">
		<div class="card-nav">
			<div class="card-nav__head">
				<div class="card-nav__items">

					<? if($img_3d) { ?>
					<div class="card-nav__item" id="init-cloudimage">
						<a href="#" class="card-nav__img">
							<svg class="around-icon">
								<use class="around-icon__part" xlink:href="/templates/pianino_new/i/sprite.svg#around"></use>
							</svg>
						</a>
					</div>
					<? } ?>

					<? if($video_img) { ?>
					<div class="card-nav__item" id="init-video">
						<a href="#" class="card-nav__img">
							<svg class="play-icon">
								<use class="play-icon__part" xlink:href="/templates/pianino_new/i/sprite.svg#play"></use>
							</svg>
						</a>
					</div>
					<? } ?>

				</div>
			</div>

			<div class="card-nav__items slick-carousel">

                <?
                if($this->images)
                {
                    // --- выведем все картинки кроме главной
                    foreach($this->images AS $i=>$img)
                    {
                        $im = get_cache_photo(JIMG.$img->image_full, 85,85);
                        //if($img->image_thumb!='thumb_'.$product->image) {
                            ?>
                            <div class="card-nav__item">
                                <div class="card-nav__img">
                                    <img src="<?=$im;?>" alt="<?=$product->name;?>-<?=$i;?>" loading="lazy"/>
                                </div>
                            </div>
                        <? //}
                    }
                }
                ?>
			</div>
		</div>

		<div class="card-list-row" id="row-init">

            <? // label_id1, label_id2 ... ?>
            <?  $temp = $db->setQuery("SELECT * FROM #__jshopping_product_labels")->loadObjectList();
                $labels = Array();
                foreach ($temp AS $l)
                    $labels[$l->id] = $l;
                $style = "";
            ?>

            <div class="card__labels">


                <? if($product->label_id>0) { ?>
                    <span class="card__label cart_label_0"><?=$product->_label_name;?></span>
					<?
					$l_id = "label_id";
					$class .=  ".cart_label_0 { \n";
					if($labels[$product->{$l_id}]->color_text!='')
						$class .= " color: ".$labels[$product->{$l_id}]->color_text."!important; ";
					if($labels[$product->{$l_id}]->color_border!='')
						$class .= " border: 1px solid ".$labels[$product->{$l_id}]->color_border."!important; ";
					if($labels[$product->{$l_id}]->color_back!='')
						$class .= " background-color: ".$labels[$product->{$l_id}]->color_back."!important; ";
					if($labels[$product->{$l_id}]->color_shaddow!='')
						$class .= " box-shadow: 2px 2px 4px ".$labels[$product->{$l_id}]->color_shaddow."!important; ";
					$class .=  " } \n";
					?>
				<? } ?>

                <? for ($i=1;$i<=5;$i++) { ?>
                    <?
                        $l_id = "label_id".$i;
                        if((int)($product->{$l_id}) == 0) continue;
                    ?>

                    <span class="card__label cart_label_<?=$product->{$l_id};?>"><?=$labels[$product->{$l_id}]->name;?></span>

                    <?
                        $class .=  ".cart_label_{$product->{$l_id}} { \n";
                        if($labels[$product->{$l_id}]->color_text!='')
                            $class .= " color: ".$labels[$product->{$l_id}]->color_text."!important; ";
					    if($labels[$product->{$l_id}]->color_border!='')
						    $class .= " border: 1px solid ".$labels[$product->{$l_id}]->color_border."!important; ";
		    			if($labels[$product->{$l_id}]->color_back!='')
			    			$class .= " background-color: ".$labels[$product->{$l_id}]->color_back."!important; ";
	    				if($labels[$product->{$l_id}]->color_shaddow!='')
    						$class .= " box-shadow: 2px 2px 4px ".$labels[$product->{$l_id}]->color_shaddow."!important; ";
					    $class .=  " } \n";
                    ?>

                <? } ?>




                <? if($class!='') { ?>
                    <style>
                        <?=$class;?>
                    </style>
                <? } ?>


                <? if($warr!='') { ?>
                    <span class="card__label"><strong><?=$warr;?></strong></span>
                <? } ?>
            </div>

            <div class="card__icons">
                <a 	href="#" 
					class="card__icon icon add_to_compare"
					data-product='<?=$product->product_id;?>'
				>
                    <svg class="libra-icon">
                        <use class="libra-icon__part" xlink:href="/templates/pianino_new/i/sprite.svg#libra"></use>
                    </svg>
                </a>
				
                <a 	href="#" 
					class="card__icon icon add_to_like"
					data-product='<?=$product->product_id;?>'
				>
                    <svg class="heart-icon">
                        <use class="heart-icon__part" xlink:href="/templates/pianino_new/i/sprite.svg#heart"></use>
                    </svg>
                </a>
            </div>

			<div class="card-list slick-carousel" id="gallery" itemscope itemtype="http://schema.org/ImageGallery">
				<?
					if($this->images)
					{
						// --- выведем все картинки кроме главной
						foreach($this->images AS $i=>$img)
						{
							$im = get_cache_photo(JIMG.$img->image_full, 640,592, 95, 1);
							?>

                            <div class="card-list__item">
                                <div class="card">

                                    <figure itemprop="associatedMedia" itemscope itemtype="http://schema.org/ImageObject">
                                        <a class="card__img" href="<?=JIMG.$img->image_full;?>" data-width="1200" data-height="900" itemprop="contentUrl">
                                            <img src="<?=$im;?>" alt="<?=$product->name;?>" itemprop="thumbnail">
                                        </a>
                                    </figure>

                                </div>
                            </div>
							<?
						}
					}
				?>
			</div>


            <? if($video_img) { ?>
			<div class="video-wrap">
				<iframe data-src="https://www.youtube.com/embed/<?=$video;?>"
						allow="accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture"
                        allowfullscreen=""
						style="width:100%;height:350px;" ></iframe>
				<div class="video-preview" style="background-image: url('/components/com_jshopping/files/img_products/<?=$product->image;?>');"></div>
				<button class="video-play"></button>
			</div>
            <? } ?>

            <? if($img_3d) { ?>
			<div class="cloudimage">
				<div class="cloudimage-360"
					 data-folder="/images/3d/<?=$product->product_id;?>/"
					 data-filename="{index}.jpg"
					 data-amount="16"
					 data-magnifier="3"
					 data-spin-reverse>
                </div>
			</div>
            <? } ?>

		</div>
	</div>




	<div class="section__row-item section__row-inf">
		<div class="inf">
			<div class="inf__top">
				<div class="inf__top-btns">
					<a href="javascript:void(0);" data-value="byn" data-price1="<?=$price_byn_old;?>" data-price2="<?=$price_byn;?>" class="inf__top-btn bv-btn is--active"><span
							class="bv-btn__text">byn</span></a>

					<a href="javascript:void(0);" data-value="usd" data-price1="<?=$price_usd_old;?>" data-price2="<?=$price_usd;?>" class="inf__top-btn bv-btn"><span
							class="bv-btn__text">usd</span></a>

					<a href="javascript:void(0);" data-value="rur" data-price1="<?=$price_rur_old;?>" data-price2="<?=$price_rur;?>" class="inf__top-btn bv-btn"><span
							class="bv-btn__text">rur</span></a>
				</div>
			</div>

			<div class="inf__body">
				<div class="inf__body-form">
					<div class="inf__body-row">
						<ul class="inf__body-items">
							<li class="inf__body-item">
                                <? if($sk!='') { ?>
                                    <div class="inf__body-block">
                                        <p class="inf__body-text is--second" data-row-price1><?=$price_byn_old;?></p>
                                        <?=$sk;?>
                                    </div>
                                <? } ?>



								<div class="inf__body-block">
									<p class="inf__body-text <? if( ($product->sklad==2) || ($product->sklad==3)) { // --- товара нет или снят с производства ?> not-in-stok <? } ?>"
                                       data-row-price2
                                    >
                                        <?=$price_byn;?>
                                    </p>
								</div>


							</li>

							<li class="inf__body-item">
								<span class="inf__body-label small-p"><?=$sklad_title;?></span>

                                <? if($product->sklad>1) { ?>
								    <p class="mt-10">*Уточняйте цену </p>
								    <p>у консультантов</p>
                                <? } ?>

							</li>
						</ul>
					</div>

                    <? // 0, 1 - есть, 2 - нет, 3 - снято, 4 - под заказ, 5 - анонс ?>
                    <? if( ($product->sklad!=2) && ($product->sklad!=3)) {  ?>
                        <div class="inf__body-row inf__body-btn">
                            <a
                                    href="#"
                                    class="bv-btn bv-btn--third one-click-new"
                                    data-get-popup="fastOrder"
                                    onclick="fbq('track','Lead'); event_send('Kupit_v_1klik1', 'Kupitv1klik1'); "
                                    data-id="<?=$cur_id; ?>"
                                    data-title="<?php echo str_replace(Array("'", '"'), " ", $product->name); ?>"
                                    data-price="<?=$this->product->product_price; ?>"
                            >
                                <span class="bv-btn__text">Купить в 1 клик</span>
                            </a>
                        </div>
                        <? include_once("rassrochka.php"); ?>




                        <div class="inf__body-row">
                            <a
                                    href="#"
                                    class="bv-btn bv-btn--cart"
                                    data-get-popup="toCart"
                                    onclick="event_send('Korzina_Dobavit', 'DobavitVkorzina');
                                    add_to_basket('<?php echo $product->product_id;?>', 1);
                                    gp_productCart('<?=str_replace('/', ' ', $cat_list);?>',  '<?=str_replace(Array('"', "'"), "`", $product->name);?>',   '<?=$product->product_id;?>',   '<?=round($product->product_price/$kurs_ye, 2);?>',   '<?=$vendor;?>',   '<?=$cat_list;?>',  '1' );
                                    "
                            >
                                <span class="bv-btn__icon">
                                  <svg class="cart-icon">
                                    <use class="cart-icon__part" xlink:href="/templates/pianino_new/i/sprite.svg#cart"></use>
                                  </svg>
                                </span>
                                <span class="bv-btn__text">Добавить в корзину</span>
                            </a>
                        </div>
					<? } else { // --- нет в наличии или снят с производства?>

						<? if((int)$product->analog_id==0) { // --- аналога нет ?>
                            <div class="inf__body-row width280px">
                                <a href="#"
                                   data-get-popup="analog"
                                   data-name="<?=str_replace(Array("'", '"'), ' ', $product->name);?>"
                                   data-id="<?=$product->product_id;?>"
                                   class="bv-btn bv-btn--third analog-btn">
                                    <span class="list-btn__text">Подберем оптимальный аналог</span>
                                </a>
                            </div>
                        <? } else { // --- аналог есть ?>
							<? $p_analog = $db->setQuery("SELECT real_link FROM #__jshopping_products WHERE product_id={$product->analog_id}")->loadObject();  ?>
							<? $a_text = $db->setQuery("SELECT * FROM #__z_config WHERE `name`='analog_text'")->loadObject()->value; ?>
                            <div class="inf__body-row width280px">
                                <a href="<?=$p_analog->real_link;?>"
                                   class="bv-btn bv-btn--third analog-btn">
                                    <span class="list-btn__text">Смотреть аналог</span>
                                </a>
                            </div>

                        <? } ?>

					<? } ?>


                    <? include_once __DIR__.'/colors.php' ;?>

					<?=str_replace("\n", "<br />", $a_text);?>

					<? if( ($product->sklad!=2) && ($product->sklad!=3)) {  ?>
					<div class="inf__body-row">
						<a href="#"
                           onclick="event_send('Nashli_deshevle1', 'NashliDeshevle1'); $('#product_cheap').val('<?php echo str_replace(Array('`', "'", '"'), ' ', $product->name); ?>'); $('#product_cheap_id').val('<?php echo $product->product_id; ?>');"
                           class="inf__body-preview"
                           data-get-popup="cheepOrder"><span>нашли дешевле?</span> <span>Снизим цену!</span>
							<span><strong>Хочу дешевле!</strong></span></a>
					</div>
                    <? } else { // --- ??? ?>
                        <div class="inf__body-row">

                            <a
                               href="#"
                               data-get-popup="analog"
                               data-name="<?=str_replace(Array("'", '"'), ' ', $product->name);?>"
                               data-id="<?=$product->product_id;?>"
                               class="inf__body-preview analog-btn"
                               onclick="$('#product_analog_comment').val('В мессенджер!'); return true;"
                            >
                                <span>Отправить аналоги</span> <span>в мессенджер</span>
                                <span><strong>Присылайте</strong></span>
                            </a>
                        </div>
                    <? } ?>

					<? include_once __DIR__.'/dostavka.php'; ?>
				</div>
			</div>


		</div>
	</div>
</div>

<!--End new layout -->
