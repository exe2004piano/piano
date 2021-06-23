<?php
defined('_JEXEC') or die;
$db = JFactory::getDBO();

// первый шаг корзины

$text .=
    '<section class="b-basket">
			<div class="container">
				<a href="/" class="b-main__orderBack">Продолжить покупки</a>
				<ul class="b-basket__step">
					<li class="b-basket__stepItem b-basket__stepItem--step1 active">Ваша корзина</li>
					<li class="b-basket__stepItem b-basket__stepItem--step2">Детали получения</li>
					<li class="b-basket__stepItem b-basket__stepItem--step3">Покупка совершена!</li>
				</ul>';


$text .='
    <div class="row b-basket__mainWrap">
		<div class="col-sm-8 col-md-9">
    		<ul class="b-basket__list">';


$promo = $promo_items = $promo_cats = $promo_sklad = null;
// ------------------------------ promocode:
$promo_text =
    '
                                    <hr /><br />
                                    <h3 class="b-main__orderInto-title color_red">Еще дешевле!</h3>
                                    <form action="" method="post">
                                        <input type="text" class="b-main__orderPromo-input" placeholder="Введите промокод" name="promocode">
                                        <!--promo_info-->
                                        <button type="submit" class="b-main__orderInto-print b-main__orderInto-print--type3 color_red"><span>Проверить</span></button>
                                    </form>
    ';



try {
// --- если промокод еще не был зафиксирован, но передан нам в пост-запросе:
    if( (isset($_SESSION['promocode'])) || (isset($_POST['promocode'])) )
    {
        if(isset($_SESSION['promocode']))
            $promocode = trim($_SESSION['promocode']);
        else
            $promocode = trim($_POST['promocode']);


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















if(isset($_COOKIE['new_basket']))
{
    $summ = 0;
    $summ_old = 0;
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
        $google_gp = "";

        foreach($items AS $key=>$value)
        {
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
            // --- гугл-торговля:
            $google_gp .= "
{
'name': '".str_replace(Array("'", '"'), '`', $res->title)."',
'id': '".$key."',
'price': '".$res->product_price."',
'brand': '".$res->m_name."',
'category': '".str_replace(Array("'", '"'), '`', $res->cat_parent_name.'/'.$res->cat_name)."',
'quantity': ".$value."
},
";




            $promo_info_text = '';
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
            if($res->product_old_price>0)
            {
                $skidka += ((1.0*$res->product_old_price - 1.0*$res->product_price)*$value);
                $old_price = '<s>'.echo_price($res->product_old_price, $_COOKIE['currency'], -1, $res).'</s><br />';
                $summ_old +=$value*$res->product_old_price;
            }
            else
                $summ_old +=$value*$res->product_price;

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
                    $sklad_title = '<b>под заказ.</b><br />Срок поставки уточняйте у менеджера';
                    $sklad_status = 'order';
                    break;
                case '5' :
                    $sklad_title = 'анонсируемая модель';
                    $sklad_status = 'notify';
                    break;
            }



            $link = SEFLink('index.php?option=com_jshopping&controller=product&task=view&category_id='.$res->category_id.'&product_id='.$res->product_id, 1);



            $text .='
                <li class="b-basket__item" id="product_in_basket_'.$res->product_id.'">
								<div class="b-basket__img">
									<img src="/components/com_jshopping/files/img_products/thumb_'.$res->image.'" alt="">
								</div>
								<div class="b-basket__content">
									<div class="b-basket__contentLine">
										<div class="b-basket__title">
											<a href="'.$link.'">'.$res->title.'</a>
											<span>Арт. '.$res->product_ean.'</span>
											<span class="b-slider__optionStatus b-slider__optionStatus--'.$sklad_status.'">'.$sklad_title.'</span>
										</div>
										<div class="b-basket__price">
                                            '.$old_price.'
										    <span>'.echo_price($res->product_price, $_COOKIE['currency'], -1, $res).'</span>
										    <small>'.$promo_info_text.'</small>
										</div>
										<div class="b-basket__num">
											<div class="b-counter">
												<span class="b-counter__nav b-counter__nav--minus" onclick="add_to_basket('.$res->product_id.',1); reload();"></span>
												<input type="text" class="b-counter__area" value="'.$value.'">
												<span class="b-counter__nav b-counter__nav--plus" onclick="add_to_basket('.$res->product_id.',-1); reload();"></span>
											</div>
										</div>
										<input type="hidden" id="product_'.$res->product_id.'_price" value="'.(round($res->product_price,2)).'" />
										<div class="b-basket__numTotal" id="product_summ_in_basket_'.$res->product_id.'"><span></span>'.echo_price($pre_summ, $_COOKIE['currency'], -1, $res).'</div>
										<a href="#" class="b-compare__titleItem-close" onclick="delete_from_basket(\''.$key.'\'); reload(); return false;"></a>
									</div>
								</div>
    			</li>
                ';
        }


        $doc = JFactory::getDocument();

        $google_gp = substr($google_gp, 0, strrpos($google_gp, ','));
        $google_gp_1 = "
dataLayer.push({
    'event': 'checkout',
    'ecommerce': {
      'checkout': {
            'actionField': {'step': 1, },
            'products': [" . $google_gp . "]
      }
    }
});
";

        $google_gp_2 = "
function onCheckout()
{
    dataLayer.push({
        'event': 'checkout',
        'ecommerce': {
                    'checkout': {
                        'actionField': {'step': 2, },
                        'products': [" . $google_gp . "]
                    }
        }
    });
}";

        $doc->addScriptDeclaration($google_gp_1);
        $doc->addScriptDeclaration($google_gp_2);

        $text .= '
                </ul>
            </div>';


        if($skidka>0)
            $skidka =
                '<div class="b-main__orderInto-tr">
    				<div class="b-main__orderInto-td">Скидка</div>
					<div class="b-main__orderInto-td">' . echo_price($skidka, $_COOKIE['currency'], -1) .'</div>
				</div>';





        $text .=
            '<div class="col-sm-4 col-md-3">
                    <div class="b-basket__order">
                        <div class="b-main__orderInto b-main__orderInto--type1">
                            <h3 class="b-main__orderInto-title">Детали заказа</h3>
                            <div class="b-main__orderInto-table" >
                                <div class="b-main__orderInto-tr ">
                                    <div class="b-main__orderInto-td">Стоимость товара</div>
                                    <div class="b-main__orderInto-td">'.echo_price($summ_old, $_COOKIE['currency'], -1).'</div>
								</div>
                                    ' . $skidka . '
									<div class="b-main__orderInto-tr b-main__orderInto-tr--total" >
										<div class="b-main__orderInto-td" >Итого:</div>
										<div class="b-main__orderInto-td"><span>'.echo_price($summ, $_COOKIE['currency'], -1).'</span></div>
									</div>
                            </div>
							    <a href="/basket-step2" onclick="onCheckout();" class="b-main__orderInto-print b-main__orderInto-print--type2" ><span>ОФОРМИТЬ ЗАКАЗ</span></a>
                                '.$promo_text.'
						</div>
			    </div>
			</div>';


        $text .=
            '
                </div>
            </section>
            ';

    }
}

return $text;