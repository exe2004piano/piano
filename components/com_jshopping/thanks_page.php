<?php

    $order_z_id = (int)$_SESSION['order_z_id'];
    if($order_z_id<=0)
        return;

	defined('_JEXEC') or die;
	$db = JFactory::getDBO();
	global $valute;
	$kurs_ye = $valute[2]['value'];

	$order = $db->setQuery("SELECT * FROM #__z_orders WHERE id={$order_z_id}")->loadObject();
	$temp = explode("||", $order->basket);
	$nums = Array();
	$google_gp = "";
	$pos = 0;


	$z_user = get_current_user_z();

	foreach ($temp AS $t)
    {
        $t = explode("//", $t);
        $num = (int)trim($t[3]);
    	$price = (float)str_replace(Array(" ", ",", "р"), Array("", ".", ""), $t[2])/$kurs_ye;
        $pid = ((int)trim($t[0]));
		$pos++;

		$q = "
        SELECT  p.`name_ru-RU` title, p.product_ean, p.`alias_ru-RU` alias, p.product_price, p.price_reg, p.product_old_price, p.image,
                cat.`alias_ru-RU` cat_alias, cat_parent.`alias_ru-RU` cat_parent_alias, c.category_id, p.product_id, p.sklad,
                cat.`name_ru-RU` cat_name, cat_parent.`name_ru-RU` cat_parent_name,
                m.`name_ru-RU` m_name
        FROM #__jshopping_products p
        LEFT JOIN #__jshopping_products_to_categories AS c USING (product_id)
        LEFT JOIN #__jshopping_categories AS cat ON c.category_id=cat.category_id
        LEFT JOIN #__jshopping_categories AS cat_parent ON cat.category_parent_id=cat_parent.category_id
        LEFT JOIN #__jshopping_manufacturers AS m ON m.manufacturer_id=p.product_manufacturer_id
        WHERE p.product_id=".$pid."
        ";
		$db->setQuery($q);
		$res = $db->loadObject();

		$res->is_promo = false;
		if($z_user && $res->product_price > $res->price_reg)
		{
			$res->product_price = $res->price_reg;
			$res->is_promo = true;
		}

		$google_gp .= "
				{
					'id': '".$pid."',
					'name': '".str_replace(Array("'", '"'), '`', $res->title)."',
					'list_name': 'Listing',
					'brand': '".$res->m_name."',
					'category': '".str_replace(Array("'", '"'), '`', $res->cat_parent_name.'/'.$res->cat_name)."',
					'list_position': ".$pos.",
					'quantity': ".$num.",
					'price': '".round($res->product_price/$kurs_ye, 2)."'
				},
			";
	}


	$doc = JFactory::getDocument();
	$google_gp = substr($google_gp, 0, strrpos($google_gp, ','));
	$google_gp = "
        gtag('event', 'purchase', {
          'transaction_id': '{$order_z_id}',
          'affiliation': 'Pianoby',
          'value': ".round($order->summ/$kurs_ye, 2).",
          'currency': 'USD',
              'items': [
  				{$google_gp}
		 	],
  			'coupon': '{$order->promo}'
		});	
		
		console.log('gtag_purchase');
		";

	$doc->addScriptDeclaration(" {$google_gp} ");


	$text = "";

	$doc = JFactory::getDocument();
	$doc->addScriptDeclaration("document.addEventListener('DOMContentLoaded', function() { setTimeout(function(){fbq('track','Purchase'); fbq('track','Lead'); console.log('fb_ok'); }, 500); });");

	$summ = (float)$_SESSION['order_summ'];
	$order_id = (int)$_SESSION['order_id'];
	$oplata = (int)$_SESSION['oplata'];


	if( ($summ!=(float)$_GET['summ'])
		||
		($order_id!=(int)$_GET['zakaz'])
		||
		($order_id==0)
		||
		($oplata!=(int)$_GET['oplata'])
	)
		return;

	$summ = echo_price($summ);

	$btn = '<a href="/" class="b-basket__endLink">ПРОДОЛЖИТЬ ПОКУПКИ</a>';
	if($oplata==3)
	{
		$btn = "

		<script src='https://js.bepaid.by/widget/be_gateway.js'></script>
		<a onclick='payment(); return false;' href='#' class='bv-btn bv-btn--third width280px'>ОПЛАТИТЬ ОНЛАЙН</a>
		";
	}

	$text .=
		'
    <section class="b-basket">
			<div class="container">
				<a href="/" class="b-main__orderBack">Продолжить покупки</a>
				<ul class="b-basket__step">
					<li class="b-basket__stepItem b-basket__stepItem--step1">Ваша корзина</li>
					<li class="b-basket__stepItem b-basket__stepItem--step2">Детали получения</li>
					<li class="b-basket__stepItem b-basket__stepItem--step3 active">Покупка совершена!</li>
				</ul>

    <h2 class="b-basket__endTitle">СПАСИБО! ВАШ ЗАКАЗ ПРИНЯТ!</h2>
				<div class="row">

					<div class="col-sm-8 col-sm-9">
						<div class="b-basket__end">

							<div class="b-basket__endContent">
								<p>Заказ <span>#'.$order_id.'</span> от ' . JHTML::_('date', 'now', JText::_('d F Y')) . '</p>
								<p>Ваш заказ принят для исполнения.</p>
								<p>Ожидайте звонка оператора, в ближайшее время он свяжется с Вами для уточнения даты доставки и необходимых деталей.</p>
								<p>Если заказ оформлен в ночное время, оператор свяжется с Вами после 9:00.</p>
								<!-- <p>Нажмите «Детали заказа», если хотите распечатать заказ или узнать его статус.</p> -->
								<div class="b-basket__endNav">
									'.$btn.'
								</div>
							</div>
						</div>
					</div>


					<div class="col-sm-4 col-md-3">
						<div class="b-main__orderInto">
							<h3 class="b-main__orderInto-title">Детали заказа</h3>
							<div class="b-main__orderInto-table">
								<div class="b-main__orderInto-tr">
									<div class="b-main__orderInto-td">Стоимость товара</div>
									<div class="b-main__orderInto-td">'.$summ.'</div>
								</div>
								<!--
								<div class="b-main__orderInto-tr">
									<div class="b-main__orderInto-td">Доставка</div>
									<div class="b-main__orderInto-td">800 р.</div>
								</div>

								<div class="b-main__orderInto-tr">
									<div class="b-main__orderInto-td">Скидка</div>
									<div class="b-main__orderInto-td">р.</div>
								</div>

								<div class="b-main__orderInto-tr">
									<div class="b-main__orderInto-td">Бонусы</div>
									<div class="b-main__orderInto-td"> р</div>
								</div>
								-->
								<div class="b-main__orderInto-tr b-main__orderInto-tr--total">
									<div class="b-main__orderInto-td">Итого:</div>
									<div class="b-main__orderInto-td"><span>'.$summ.'</span></div>
								</div>
							</div>
							<!-- <a href="#" class="b-main__orderInto-print"><span>РАСПЕЧАТАТЬ ЗАКАЗ</span></a> -->
						</div>
					</div>
				</div>


            </div>
        </section>
        ';




?>
	<script>
        function payment()
        {
            var params = {
                checkout_url: "https://checkout.bepaid.by",
                checkout: {
                    iframe: true,
                    test: false,
                    transaction_type: "payment",
                    public_key: "MIIBIjANBgkqhkiG9w0BAQEFAAOCAQ8AMIIBCgKCAQEAqClu0qb6Zcur/L21khPw47tq19PIQTa1C7EA9vqTQkValOMngvEIY1U/qeG1WvWKYH022bKS9eKcSPQWDoQYGcj2qNypRlSvnk/tAF4gF6ORURGvSern/+cD9ykXLfpOaNw+iS161WUMV04IRQt8wfZ4UsUe3oM6vvWJsSeYAxquPJ4rvsFL3JEwktGl3o80AXPjrreMfDuFIF43beuiK4GDv+sAKkTmIg8X+7SDfDvZbl6IDoQSwzzTEZcw6UqBltZZ7YFu3Ht34m3uAKNAq6JeZsres/dLMyUfWHswly53aAifc94hA7XziuOJSFDukMngK353KBGV5qIxjsWdcQIDAQAB",
                    order: {
                        amount: <?=((float)$_SESSION['order_summ']*100);?>,
                        currency: "BYN",
                        description: "Оплата заказа <?=$order_id;?>",
                        tracking_id: "<?=$order_id;?>"
                    },
                    settings: {
                        language: "ru"
                    },
                    customer: {
                        first_name: "<?=$_SESSION['name'];?>",
                        phone: "<?=$_SESSION['phone'];?>",
                        address: "<?=$_SESSION['adr'];?>",

                    },

                },
                payment_method: {
                    types: ["credit_card", "erip"],
                    erip: {
                        test: false,
                        "account_number": "11170",
                        "service_no": "11170",
                        "service_info": [
                            "Оплата заказа <?=$order_id;?>"
                        ]
                    }
                },
                closeWidget: function (status) {
                    console.debug(status)
                }
            };

            new BeGateway(params).createWidget();
        }
	</script>
<?

	return $text;