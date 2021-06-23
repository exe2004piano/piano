<?php

defined('_JEXEC') or die;
$db = JFactory::getDBO();
$doc = JFactory::getDocument();
$doc->addScriptDeclaration(" document.addEventListener('DOMContentLoaded', function() { fbq('track','InitiateCheckout'); }); ");

$text = "";


$text .=
    '<section class="b-basket">
			<div class="container">
				<a href="/" class="b-main__orderBack">Продолжить покупки</a>
				<ul class="b-basket__step">
					<li class="b-basket__stepItem b-basket__stepItem--step1">Ваша корзина</li>
					<li class="b-basket__stepItem b-basket__stepItem--step2 active">Детали получения</li>
					<li class="b-basket__stepItem b-basket__stepItem--step3">Покупка совершена!</li>
				</ul>';



$text .='
    <div class="row-order">
				
						<form action="/exe/send_basket.php" class="b-basket__form" method="post" id="basket_form">
							<div class="b-basket__formBlock">
								<h3 class="b-basket__formTitle">Данные получателя</h3>
								<div class="b-basket__formContent">
									<div class="b-basket__formLine">
										<div class="b-basket__formLeft">
											<label for="basket1_1" class="b-basket__formLabel">Ваше имя<span>*</span></label>
										</div>
										<div class="b-basket__formRight">
											<input type="text" id="basket1_1" name="user_name" class="b-basket__input" placeholder="Ваше имя">
										</div>
									</div>
									<div class="b-basket__formLine">
										<div class="b-basket__formLeft">
											<label for="basket1_2"  class="b-basket__formLabel">Телефон<span>*</span></label>
										</div>
										<div class="b-basket__formRight">
											<input type="tel" id="basket1_2" name="user_phone" class="b-basket__input" placeholder="Телефон">
										</div>
									</div>
									<div class="b-basket__formLine">
										<div class="b-basket__formLeft">
											<label for="basket1_3" class="b-basket__formLabel">Электронная почта</label>
										</div>
										<div class="b-basket__formRight">
											<input type="email" id="basket1_3" name="user_mail" class="b-basket__input" placeholder="Электронная почта">
										</div>
									</div>
									<div class="b-basket__formLine">
										<div class="b-basket__formLeft">
											<label for="basket2_7"  class="b-basket__formLabel">Комментарий к заказу</label>
										</div>
										<div class="b-basket__formRight">
											<textarea  id="basket2_7"  name="user_comment" class="b-basket__input b-basket__input--textarea"  placeholder="Комментарий к заказу"></textarea>
										</div>
    								</div>

								</div>
							</div>

							<br />

							<div class="b-basket__formBlock">

								    <center>
    										<label class="radio-lable">
                                                <input class="radio" type="radio" name="dostavka_type" id="dost_kurier" value="1" checked>
                                                <span class="radio-custom"></span>
                                                <span class="label">Доставка курьером</span>
                                            </label>

    										<label class="radio-lable">
                                                <input class="radio" type="radio" name="dostavka_type" id="dost_samov" value="2">
                                                <span class="radio-custom"></span>
                                                <span class="label">Самовывоз</span>
                                            </label>
                                    </center>

						    </div>

                            <br />
                            
                            
                            <!--oplata-->


							<div class="b-basket__formBlock" id="block_adr">

								<h3 class="b-basket__formTitle" id="block_adr_title">Адрес доставки</h3>
								<div class="clr"></div>
								<div class="b-basket__formContent" >
									<div class="b-basket__formLine">
										<div class="b-basket__formLeft">
											<label for="basket2_1" class="b-basket__formLabel">Населённый пункт<span>*</span></label>
										</div>
										<div class="b-basket__formRight">
											<input type="text" id="basket2_1" name="user_city" class="b-basket__input" placeholder="Населённый пункт">
										</div>
									</div>
									<div class="b-basket__formLine">
										<div class="b-basket__formLeft">
											<label for="basket2_2"  class="b-basket__formLabel">Адрес<span>*</span></label>
										</div>
										<div class="b-basket__formRight">
											<input type="text" id="basket2_2" name="user_street" class="b-basket__input" placeholder="Улица">
										</div>
									</div>
							    </div>
							</div>

							<div class="b-basket__formBlock" id="block_sam" style="display: none; padding: 0px;">
							<center>
								<h3 class="b-basket__formTitle" style="margin: 10px; padding: 0px;">Забрать заказ самостоятельно Вы можете по адресу:<br />г. Минск, ул. Сурганова, 57б, пом.8</h3>
							</center>
						    </div>
							<br />

							<div class="b-basket__formBlock" id="block_time" >
								<h3 class="b-basket__formTitle">Дата доставки / самовывоза</h3>
								<div class="b-basket__formContent">
									<div class="b-basket__formLine">
										<div class="b-basket__formLeft">
											<label for="basket2_7"  class="b-basket__formLabel">Дата </label>
										</div>
										<div class="b-basket__formRight">
												<input type="date"   name="user_date" class="b-basket__data">
										</div>
									</div>
									<!--
									<div class="b-basket__formLine">
										<div class="b-basket__formLeft">
											<label for="basket2_7"  class="b-basket__formLabel">Время </label>
										</div>
										<div class="b-basket__formRight">
											<div class="b-filter__select">
												<select name="filterSelect">
												    <option value="">Выберите время</option>
													<option value="8_11">с 8:00 до 11:00</option>
													<option value="11_15">с 11:00 до 15:00</option>
													<option value="15_18">с 15:00 до 18:00</option>
													<option value="18_22">с 18:00 до 22:00</option>
												</select>
											</div>
										</div>
									</div>
									-->
								</div>
							</div>

						</form>
					
                        <div class="wrap-order">
                       
						<div class="b-main__orderInto">
							<h3 class="b-main__orderInto-title">
								<span>Состав заказа</span>
								<a href="/basket" class="b-main__orderInto-change">Изменить</a>
							</h3>
							<ul class="b-main__orderInto-list">';




if(isset($_COOKIE['new_basket']))
{
    $summ = 0;
    $num = 0;
// --- из корзины удалим всё кроме: цифр, ~, =, символа _ (комплект)
    $basket = trim(preg_replace('/[^0-9=~_]/', '', $_COOKIE['new_basket']));
    $temp = explode("~", $basket);

    unset($items);
    unset($komplekts);


    foreach($temp AS $t)
        if(trim($t!=''))
        {
// --- получим массив ID => kolvo
            $arr = explode("=", $t);
            if(trim($arr[0])!='')
            {
                if(strpos("  ".$arr[0], "_")<1)
                    $items[$arr[0]] = $arr[1];
                else
                    $komplekts[$arr[0]] = $arr[1];
            }
        }

    if($items)
    {
        $skidka = 0;

        $promo = $promo_items = $promo_cats = $promo_sklad = null;
        try {
// --- если промокод еще не был зафиксирован, но передан нам в пост-запросе:
            if (isset($_SESSION['promocode']))
            {
                $promocode = trim($_SESSION['promocode']);

                $db->setQuery("SELECT * FROM #__z_promo WHERE promo=".$db->quote($promocode));
                $promo=$db->loadObject();

                if($promo)
                {
                    // --- промокод найден, проверим его на годность:
                    $start_time = strtotime($promo->start_time);
                    $end_time = strtotime($promo->end_time);
                    $time = time();

                    $promo_items = explode("~", $promo->products_id);
                    $promo_cats = explode("~", $promo->categories_id);
                    $promo_sklad = explode("~", $promo->sklad);

                    // --- проверим временные ограничения:
                    if(($end_time>0) && ($time>$end_time))
                    {
                        $promo_text = str_replace('<!--promo_info-->', 'Действие данного промокода завершено<br /><br />', $promo_text);
                        throw new Exception();
                    }

                    if(($start_time>0)&&($time<$start_time))
                    {
                        $promo_text = str_replace('<!--promo_info-->', 'Действие данного промокода еще не началось<br /><br />', $promo_text);
                        throw new Exception();
                    }

                    $s_h = $promo->start_h * 1;
                    $e_h = $promo->end_h * 1;

                    if($s_h+$e_h>0)
                    {
                        // --- указано временное ограничение промокода
                        $h = date("H", time())*1;

                        // --- если финальное время меньше 24 часов, то просто посмотрим диапазон
                        if ( ($e_h<24) && (($h<$s_h) || ($h>=$e_h)) )
                        {
                            $promo_text = str_replace('<!--promo_info-->', 'Действие данного промокода ограничено по времени<br /><br />', $promo_text);
                            throw new Exception();
                        }
                        else
                        {
                            // --- если финальное время больше 24, то обработке подлежит "завтрашняя ночь"
                            // например 22-32 значит до 8 утра завтра
                            // тогда к текущему времени нужно добавить 24, если оно меньше 12
                            // например если сейчас час ночи, то сравнивать будет как 25
                            if($h<12)
                                $h += 24;

                            if (($h<$s_h) || ($h>=$e_h))
                            {
                                $promo_text = str_replace('<!--promo_info-->', 'Действие данного промокода ограничено по времени<br /><br />', $promo_text);
                                throw new Exception();
                            }
                        }
                    }


                    // --- до сюда можем дойти только если все проверки по промокоду пройдены
                    $_SESSION['promocode'] = $promocode;

                    $promo_text = 'Применен промокод: <b>'.$promocode.'</b><br />';

                }
                else
                {
                    $promo_text = str_replace('<!--promo_info-->', 'Введеный промокод не существует<br /><br />', $promo_text);
                    $error=1;
                }
            }
        }
        catch (Exception $e) {
            unset($_SESSION['promocode']);
            // выход из проверок если промокод не состоялся
        }

		global $valute;
		$kurs_ye = $valute[2]['value'];

		$google_gp = "";
		$all_sklad = true;
        $pos = 0;
        foreach($items AS $key=>$value)
        {
        	$pos++;

            $num += $value;
            $key = 1*$key;
            $q = "
    SELECT  p.`name_ru-RU` title, p.product_ean, p.`alias_ru-RU` alias, p.product_price, p.product_old_price, p.image,
            cat.`alias_ru-RU` cat_alias, cat_parent.`alias_ru-RU` cat_parent_alias, c.category_id, p.product_id, p.sklad,
            cat.`name_ru-RU` cat_name, cat_parent.`name_ru-RU` cat_parent_name,
            m.`name_ru-RU` m_name
    FROM #__jshopping_products p
    LEFT JOIN #__jshopping_products_to_categories AS c USING (product_id)
    LEFT JOIN #__jshopping_categories AS cat ON c.category_id=cat.category_id
    LEFT JOIN #__jshopping_categories AS cat_parent ON cat.category_parent_id=cat_parent.category_id
    LEFT JOIN #__jshopping_manufacturers AS m ON m.manufacturer_id=p.product_manufacturer_id
    WHERE p.product_id={$key}
    ";

            $db->setQuery($q);
            $res = $db->loadObject();

			$google_gp .= "
				{
					'id': '".$key."',
					'name': '".str_replace(Array("'", '"'), '`', $res->title)."',
					'list_name': 'Listing',
					'brand': '".$res->m_name."',
					'category': '".str_replace(Array("'", '"'), '`', $res->cat_parent_name.'/'.$res->cat_name)."',
					'list_position': ".$pos.",
					'quantity': ".$value.",
					'price': '".round($res->product_price/$kurs_ye, 2)."'
				},
			";


			if($res->sklad>0)	// --- если все выбранные товары есть на складе
				$all_sklad=false;

            // проверим на принадлежность к промокоду по набору ограничений если они есть:
            if(isset($_SESSION['promocode']))
            {
                // --- считаем что изначально для данного товара ниодно из условий промокода не выполнено
                $in_sklad = $in_summ = $in_product = 0;



                // --- если есть ограничение по складу
                if(sizeof($promo_sklad)<=1)
                    // --- ограничений на принадлежность по складу не установлена, значит помечаем что всё ок
                    $in_sklad = 1;
                else
                    if(in_array($res->sklad, $promo_sklad))
                        $in_sklad = 1;


                // --- если нет ограничения по стоимости либо оно подходит под наш товар
                if($promo->min_price<=$res->product_price)
                    $in_summ = 1;


                // --- теперь проверим есть ли ограничения по ID товара либо ID категории:
                if ( (sizeof($promo_cats)<=1) && (sizeof($promo_items)<=1) )
                {
                    // --- ограничений на принадлежность к категории или товарам нет:
                    $in_product = 1;
                }
                else
                {
                    // --- если ограничение есть, то либо товар в списке либо его категория в списке:
                    if (
                        (in_array($res->product_id, $promo_items))
                        ||
                        (in_array($res->category_id, $promo_cats))
                    )
                        $in_product = 1;
                }

            }

            $is_promo = $in_sklad * $in_product * $in_summ;
            $promo_info_text = '';

            if($is_promo)
            {
                // --- товар попал под промокод
                if($promo->proc_skidka>0)
                {
                    $res->product_price = round($res->product_price * (1-$promo->proc_skidka/100), 2);
                    $promo_info_text .= '<br />Скидка '.$promo->proc_skidka.'%<br /> по промокоду';
                }

                if($promo->fix_skidka>0)
                {
                    $res->product_price = $res->product_price - $promo->fix_skidka;
                    $promo_info_text .= '<br />Скидка '.echo_price($promo->fix_skidka).'<br /> по промокоду';
                }

                if($promo_info_text=='')
                    $promo_info_text .= '<br />'.$promo->info;

            }








            $pre_summ = $value*$res->product_price;
            $summ += $pre_summ;
            $old_price='';


            $v_id = 1*$_COOKIE['currency'];
            if(isset($_GET['prods_from_search_valute']))
                $v_id = $_GET['prods_from_search_valute']*1;
// --- если не задана валюта либо задана неверно, то установим по-умолчанию:
            if( ($v_id<1) || ($v_id>3) )
                $v_id = 1;


            if($res->product_old_price>0)
            {
                $skidka += ((1.0*$res->product_old_price - 1.0*$res->product_price)*$value);
                $old_price = '<s>'.echo_price($res->product_old_price, $v_id, -1).'</s><br />';
            }


            $sklad_title = '';
            $sklad_status = '';

            switch($res->sklad)
            {
                case '0' :
                    $sklad_title = 'в наличии';
                    $sklad_status = 'inStock';
                    break;
                case '1' :
                    $sklad_title = 'на складе';
                    $sklad_status = 'order';
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



            $link = SEFLink('index.php?option=com_jshopping&controller=product&task=view&category_id='.$res->category_id.'&product_id='.$res->product_id, 1);


            $text .=
                '<li class="b-main__orderInto-item z_product_item" data-num="'.($value).'" data-id="'.($res->product_id).'" data-price="'.(number_format($res->product_price*1.0, 2, ".", "")).'">
                                            <div class="b-main__orderInto-img">
                                                <img src="/components/com_jshopping/files/img_products/thumb_'.$res->image.'" alt="">
									</div>
									<div class="b-main__orderInto-text">
										<a href="'.$link.'" class="b-main__orderInto-link">'.$res->title.'</a>
										<span class="b-main__orderInto-price">'.echo_price($res->product_price, $v_id, -1).'</span>
										<span class="b-slider__optionStatus b-slider__optionStatus--'.$sklad_status.'">'.$sklad_title.'<small>'.$promo_info_text.'</small></span>
									</div>
								</li>';
        }
    }

    if(true)
        $skidka =
            '<div class="b-main__orderInto-tr">
                <div class="b-main__orderInto-td">Скидка</div>
                <div class="b-main__orderInto-td">' . echo_price($skidka, $v_id, -1) .'</div>
			</div>';





    $text .='
                            </ul>
						</div>
						<div class="b-main__orderInto">
							<h3 class="b-main__orderInto-title">Детали заказа</h3>
							<div class="b-main__orderInto-table">
								<div class="b-main__orderInto-tr b-main__orderInto-tr--mobile">
									<div class="b-main__orderInto-td">Стоимость товара</div>
									<div class="b-main__orderInto-td">'.echo_price($summ, $v_id, -1).'</div>
								</div>

                                ' .  $skidka . '

								<div class="b-main__orderInto-tr b-main__orderInto-tr--total">
									<div class="b-main__orderInto-td">Итого:</div>
									<div class="b-main__orderInto-td"><span>'.echo_price($summ, $v_id, -1).'</span></div>
								</div>
							</div>
							<a href="#" class="bv-btn bv-btn--third step2" onclick="event_send(\'oformil_zakaz\', \'oformilZakaz\'); send_basket(); return false;"><span class="bv-btn__text">ЗАВЕРШИТЬ ОФОРМЛЕНИЕ</span></a>
							<div class="b-main__orderInto-detal">Нажатием кнопки «Завершить оформление» я даю свое согласие на обработку персональных данных.</div>
                        </div>
                        
                         </div>
					</div>

';


}


$text .=
    '
        </div>
    </section>
    ';






	$oplata =
		'
	<div class="b-basket__formBlock">
		<center>
				<label class="radio-lable">
					<input class="radio" type="radio" name="oplata_type" id="oplata_nal" value="1" checked>
					<span class="radio-custom"></span>
					<span class="label">Наличными при получении</span>
				</label>

				<label class="radio-lable">
					<input class="radio" type="radio" name="oplata_type" id="oplata_salon" value="2">
					<span class="radio-custom"></span>
					<span class="label">Через терминал или наличными в салоне</span>
				</label>';

	if($all_sklad)
	{
		$oplata .=
			'<label class="radio-lable">
				<input class="radio" type="radio" name="oplata_type" id="oplata_cart" value="3">
				<span class="radio-custom"></span>
				<span class="label">Картой через интернет</span>
			</label>';
	}


	$oplata .=
		'</center>
	</div>
	<br />
	';
// --- методы оплаты:
	$text = str_replace('<!--oplata-->', $oplata, $text);

	$gp_promo = '';
	if($promo)
		$gp_promo = $promo->promo;

	$doc = JFactory::getDocument();
	$google_gp = substr($google_gp, 0, strrpos($google_gp, ','));
	$google_gp = "
		gtag('event', 'checkout_progress', {
  			'items': [
  				{$google_gp}
		 	],
  			'coupon': '{$gp_promo}'
		});	
		
		console.log('gtag_checkout_progress');
		";

	$doc->addScriptDeclaration(" {$google_gp} ");

	return $text;

