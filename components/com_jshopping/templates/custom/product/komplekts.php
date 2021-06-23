<?php


defined( '_JEXEC' ) or die();

// ----------------------------- Комплекты если есть:
$komplekts = "";
$all_num = 0;
if(trim($this->product->komplekts)!="")
{
    $komplekt = explode("~", $this->product->komplekts);

    $prod_price = $prod_price_old = $this->product->product_price;
    if($this->product->product_old_price*1.0>0)
        $prod_price_old = $this->product->product_old_price;

    foreach($komplekt AS $k)
        if(trim($k)!="")
        {
            // --- получим инфу о всех товарах из данного комплекта:
            $db->setQuery("SELECT k.*,
                           p1.product_price price1, p2.product_price price2, p3.product_price price3, p4.product_price price4,
                           p1.image image1, p2.image image2, p3.image image3, p4.image image4,
                           p1.`name_be-BY` ean1, p2.`name_be-BY` ean2, p3.`name_be-BY` ean3, p4.`name_be-BY` ean4,
                           k.active

                           FROM #__z_komplekt AS k
                           LEFT JOIN #__jshopping_products AS p1 ON p1.product_id = k.prod1
                           LEFT JOIN #__jshopping_products AS p2 ON p2.product_id = k.prod2
                           LEFT JOIN #__jshopping_products AS p3 ON p3.product_id = k.prod3
                           LEFT JOIN #__jshopping_products AS p4 ON p4.product_id = k.prod4
                           WHERE id={$k}");
            $res = $db->loadObject();
            if($res->active==0)
                continue;


            $kom = "";
            $summ = 0;
            $kom_num = 0;

            $price_old = '';
            if($prod_price_old!=$prod_price)
                $price_old = '<span>'.echo_price($prod_price_old).'</span> &nbsp; ';

            $kom .=
                '     <li class="b-slider__item">
                             <div class="b-kit__content">


                                 <div class="b-kit__itemWrap">
                                     <div class="b-kit__itemImg">
                                         <img src="/components/com_jshopping/files/img_products/'.$this->product->image.'" alt="">
                            </div>
                            <a href="#"  onclick="return false;" class="b-slider__title">'.$this->product->name.'</a>
                            <div class="b-slider__price">'.$price_old.echo_price($prod_price).'</div>
                        </div>
                        <span class="b-kit__plus">+</span>
                        <ul class="b-kit__list">';

            // --- каждый из товаров 1,2,3,4 обработаем:
            $stiker = '';
            if($res->skidka*1==100)
                $stiker = '<img class="stiker_gift" src="/images/podarok.png" />';


            if($res->prod1*1>0)
            {
                $kom_num++;
                $summ += 1.0*$res->price1;
                $summ_skidka = echo_price($res->price1*(100-$res->skidka));

                $kom .=
                    '<li class="b-kit__item">
                        <div class="b-kit__img">
                            <!--<span class="b-kit__gift"></span>-->
                            <img src="/components/com_jshopping/files/img_products/thumb_'.$res->image1.'" alt="">
                                </div>
                                <a href="#" onclick="return false;" class="b-slider__title">'.$res->ean1.'</a>
                                <div class="b-slider__price">'.echo_price($res->price1).'</div>
                            </li>'.$stiker;
            }

            if($res->prod2*1>0)
            {
                $kom_num++;
                $summ += 1.0*$res->price2;
                $summ_skidka = echo_price($res->price2*(100-$res->skidka));

                $kom .=
                    '<li class="b-kit__item">
                        <div class="b-kit__img">
                            <!--<span class="b-kit__gift"></span>-->
                            <img src="/components/com_jshopping/files/img_products/thumb_'.$res->image2.'" alt="">
                                </div>
                                <a href="#" onclick="return false;"  class="b-slider__title">'.$res->ean2.'</a>
                                <div class="b-slider__price">'.echo_price($res->price2).'</div>
                            </li>'.$stiker;
            }

            if($res->prod3*1>0)
            {
                $kom_num++;
                $summ += 1.0*$res->price3;
                $summ_skidka = echo_price($res->price3*(100-$res->skidka));

                $kom .=
                    '<li class="b-kit__item">
                        <div class="b-kit__img">
                            <!--<span class="b-kit__gift"></span>-->
                            <img src="/components/com_jshopping/files/img_products/thumb_'.$res->image3.'" alt="">
                                </div>
                                <a href="#" onclick="return false;"  class="b-slider__title">'.$res->ean3.'</a>
                                <div class="b-slider__price">'.echo_price($res->price3).'</div>
                            </li>'.$stiker;
            }

            if($res->prod4*1>0)
            {
                $kom_num++;
                $summ += 1.0*$res->price4;
                $summ_skidka = echo_price($res->price4*(100-$res->skidka));

                $kom .=
                    '<li class="b-kit__item">
                        <div class="b-kit__img">
                            <!--<span class="b-kit__gift"></span>-->
                            <img src="/components/com_jshopping/files/img_products/thumb_'.$res->image4.'" alt="">
                                </div>
                                <a href="#" onclick="return false;"  class="b-slider__title">'.$res->ean4.'</a>
                                <div class="b-slider__price">'.echo_price($res->price4).'</div>
                    </li>'.$stiker;
            }


            $summ_skidka = ($prod_price+$summ*(100-$res->skidka)/100);


            $kom .=
                '</ul>
                    <span class="b-kit__plus">=</span>
                            <div class="b-kit__total">
                                <div class="b-option__tabPrice">
                                    <span class="b-option__tabPrice-old">'.echo_price($prod_price_old+$summ).'</span>
                                    <span class="b-option__tabPrice-new">'.echo_price($summ_skidka).'</span>
                                </div>
                                <div class="b-kit__savings">Экономия: '.echo_price($prod_price_old+$summ-$summ_skidka).'</div>
                                <a 
									data-get-popup="fastOrder" 
									href="#" 
									class="b-option__busket one-click-new" 
	
									data-id="'.$this->product->product_id.'|'.$k.'"
									data-title="'.str_replace(Array('"', "'"), "", $this->product->name . " + комплект " . $res->title).'"
									data-price="'.$summ_skidka.'"
	
									onclick="event_send(\'komplekt\', \'komplekt\');"
                                >
                                <span>Купить сейчас</span>
                                </a>
                            </div>
                        </div>
                    </li>';

            $komplekts .= $kom;
            $all_num++;
        }



    if($komplekts!="")
        $komplekts =
            '<section class="b-kit">
                <div class="container">
                    <div class="b-section__title b-section__title--type2">
                        <span>В комплекте дешевле</span>
                    </div>
                        <ul class="b-slider__list b-slider__list--kit slick-carousel">' .
                        $komplekts .
                        '</ul>

    </div>
</section>
';

    echo $komplekts;
}
