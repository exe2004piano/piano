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

	if( ($sklad_status=='none') || ($sklad_status=='notify') )
		$dost_minsk = $dost_rb = $dost_mo = "";

?>



<?
	if($dost_srok_minsk!='')
	{
?>
	<div class="inf__body-row">
		<ul class="block-inf">
			<li class="block-inf__item">
				<div class="block-inf__icon">
					<svg class="car-icon">
						<use class="car-icon__part" xlink:href="/templates/pianino_new/i/sprite.svg#car"></use>
					</svg>
				</div>

				<div class="block-inf__text">
					<p>Доставка по Минску</p>
					<p><strong><?php echo $dost_minsk . ' ' . $dost_srok_minsk; ?></strong></p>
				</div>
			</li>

			<li class="block-inf__item">
				<div class="block-inf__icon">
					<svg class="regions-icon">
						<use class="regions-icon__part" xlink:href="/templates/pianino_new/i/sprite.svg#regions"></use>
					</svg>
				</div>

				<div class="block-inf__text">
					<p>Доставка по РБ</p>
					<p><strong><?php echo $dost_rb . ' ' . $dost_srok_rb; ?></strong></p>
				</div>
			</li>

			<li class="block-inf__item">
				<div class="block-inf__icon">
					<svg class="tower-icon">
						<use class="tower-icon__part" xlink:href="/templates/pianino_new/i/sprite.svg#tower"></use>
					</svg>
				</div>

				<div class="block-inf__text">
					<p>Доставка по Москве и МО</p>
					<p><strong><?php echo $dost_mo . ' ' . $dost_srok_rb; ?></strong></p>
				</div>
			</li>


			<? if ( ($product->ball >= 10) && ($product->sklad==0) ) { ?>
			<li class="block-inf__item">
				<a href="#"
				   class="block-inf__wrap"
				   data-get-popup="expressDelivery"
				   onclick="fbq('track','Lead'); event_send('Kupit_v_1klik1', 'Kupitv1klik1'); $('#product_express').val('Экспресс-доставка'); $('#product_express_id').val('<?php echo $product->product_id; ?>');"
				   >
					<div class="block-inf__icon">
						<svg class="electro-icon">
							<use class="electro-icon__part" xlink:href="/templates/pianino_new/i/sprite.svg#electro"></use>
						</svg>
					</div>

					<div class="block-inf__text">
						<span>Экспресс-доставка!</span>
					</div>
				</a>
			</li>
			<? } ?>

		</ul>
	</div>
<? } ?>