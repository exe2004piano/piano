<?php
	defined('_JEXEC') or die;
	$db = JFactory::getDBO();

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