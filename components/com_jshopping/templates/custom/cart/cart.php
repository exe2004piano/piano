<?php defined( '_JEXEC' ) or die(); ?>
<?php $countprod = count($this->products); ?>
<?php $all_items=""; ?>

<div class="jshop">
<h1>Корзина</h1>
<form action="<?php
print SEFLink('index.php?option=com_jshopping&controller=cart&task=refresh')
//    echo "/send_basket.php";
?>" method="post" name="updateCart" id="basket_form" >
<?php print $this->_tmp_ext_html_cart_start?>
<?php if ($countprod>0){?>
    <table class="jshop cart" border="1">
        <tr>
            <th width="20%" class="first">
                <?php print _JSHOP_IMAGE?>
            </th>
            <th>
                <?php print _JSHOP_ITEM?>
            </th>
            <th width="15%">
                <?php print _JSHOP_SINGLEPRICE?>
            </th>
            <th width="15%">
                <?php print _JSHOP_NUMBER?>
            </th>
            <th width="15%">
                <?php print _JSHOP_PRICE_TOTAL?>
            </th>
            <th width="10%" class="last">
                <?php print _JSHOP_REMOVE?>
            </th>
        </tr>
        <?php
        $i=1;
        $cc=0;
        $counter = count($this->products);
        $prod_id=0;
        $prod_price=0;
        $prod_img = "";
        $currency = JTable::getInstance('currency', 'jshop');
        $currency->load(2);
        $all_ids = "";
        $cart_ids = "";

        foreach($this->products as $key_id=>$prod){
            $prod_id = $prod['product_id'];
            $all_ids .= "'{$prod_id}' , ";
            $cart_ids .= $prod_id . "~";
            $prod_price = $prod['price']*$currency->currency_value;
            $prod_img = str_replace("pianino.bu", "piano.by", $this->image_product_path . "/" . $prod['thumb_image']);

            $class="";
            if($cc == $counter - 1) {
                $class .= ' last ';
            }
            $calc_price = str_replace('USD', '',formatprice($prod['price']))*$currency->currency_value.' '.$currency->currency_code;
            ?>
            <tr class="jshop_prod_cart <?php  print $class; if ($i%2==0) print "even"; else print "odd"?>">
                <td class="jshop_img_description_center">
                    <a href="<?php print $prod['href']?>">
                        <img src="<?php print $this->image_product_path ?>/<?php if ($prod['thumb_image']) print $prod['thumb_image']; else print $this->no_image; ?>" alt="<?php print htmlspecialchars($prod['product_name']);?>" class="jshop_img" />
                    </a>
                </td>
                <td class="product_name">
                    <a href="<?php print $prod['href']?>"><?php print $prod['product_name']?></a>
                    <?php $all_items .= $prod['product_id']; ?>
                    <?php if ($this->config->show_product_code_in_cart){?>
                        <span class="jshop_code_prod">(<?php print $prod['ean']?>)</span>
                    <?php }?>
                    <?php if ($prod['manufacturer']!=''){?>
                        <div class="manufacturer"><?php print _JSHOP_MANUFACTURER?>: <span><?php print $prod['manufacturer']?></span></div>
                    <?php }?>
                    <?php print sprintAtributeInCart($prod['attributes_value']);?>
                    <?php print sprintFreeAtributeInCart($prod['free_attributes_value']);?>
                    <?php print sprintFreeExtraFiledsInCart($prod['extra_fields']);?>
                    <?php print $prod['_ext_attribute_html']?>
                </td>
                <td>
                    <?php print /*formatprice($prod['price'])."<br/>".*/ number_format($calc_price, 2, ",", " ") . " р.";?>
                    <?php print $prod['_ext_price_html']?>
                    <?php if ($this->config->show_tax_product_in_cart && $prod['tax']>0){?>
                        <span class="taxinfo"><?php print productTaxInfo($prod['tax']);?></span>
                    <?php }?>
                </td>
                <td style="text-align: center; vertical-align: middle;">
                    <input type = "text" name = "quantity[<?php print $key_id ?>]" value = "<?php print $prod['quantity'] ?>" class = "inputbox" style = "width: 25px" onchange="save_basket_form(); document.updateCart.submit();" />
                    <?php print $prod['_qty_unit'];?>
                    <?php $all_items .= ("=" . $prod['quantity'] . "~"); ?>
                    <!--      <span class = "cart_reload"><img style="cursor:pointer" src="<?php print $this->image_path ?>/images/reload.png" title="<?php print _JSHOP_UPDATE_CART ?>" alt = "<?php print _JSHOP_UPDATE_CART ?>" onclick="document.updateCart.submit();" /></span> -->
                </td>
                <td>
                    <?php print number_format(formatprice($prod['price'])*$currency->currency_value*$prod['quantity'], 2, ",", " ").' '.$currency->currency_code; ?>
                    <?php print $prod['_ext_price_total_html']?>
                    <?php if ($this->config->show_tax_product_in_cart && $prod['tax']>0){?>
                        <span class="taxinfo"><?php print productTaxInfo($prod['tax']);?></span>
                    <?php }?>
                </td>
                <td class="delete"  style="text-align: center; vertical-align: middle;">
                    <a href="<?php print $prod['href_delete']?>" onclick="save_basket_form(); return confirm('<?php print _JSHOP_CONFIRM_REMOVE?>')"><img src = "<?php print $this->image_path ?>images/remove.png" alt = "<?php print _JSHOP_DELETE?>" title = "<?php print _JSHOP_DELETE?>" /></a>
                </td>
            </tr>
            <?php
            $i++;
            $cc++;
        }
        ?>
    </table>

    <?php if ($this->config->show_weight_order){?>
        <div class="weightorder">
            <?php print _JSHOP_WEIGHT_PRODUCTS?>: <span><?php print formatweight($this->weight);?></span>
        </div>
    <?php }?>

    <?php if ($this->config->summ_null_shipping>0){?>
        <div class="shippingfree">
            <?php printf(_JSHOP_FROM_PRICE_SHIPPING_FREE, formatprice($this->config->summ_null_shipping, null, 1));?>
        </div>
    <?php } ?>

    <br/>
    <table class="jshop jshop_subtotal">
        <?php if (!$this->hide_subtotal){?>
            <tr>
                <td class="name">
                    <?php print _JSHOP_SUBTOTAL ?>
                </td>
                <td class="value">
                    <?php print formatprice($this->summ);?><?php print $this->_tmp_ext_subtotal?>
                </td>
            </tr>
        <?php } ?>
        <?php if ($this->discount > 0){ ?>
            <tr>
                <td class="name">
                    <?php print _JSHOP_RABATT_VALUE ?>
                </td>
                <td class="value">
                    <?php print formatprice(-$this->discount);?><?php print $this->_tmp_ext_discount?>
                </td>
            </tr>
        <?php } ?>
        <?php if (!$this->config->hide_tax){?>
            <?php foreach($this->tax_list as $percent=>$value){ ?>
                <tr>
                    <td class = "name">
                        <?php print displayTotalCartTaxName();?>
                        <?php if ($this->show_percent_tax) print formattax($percent)."%"?>
                    </td>
                    <td class = "value">
                        <?php print formatprice($value);?><?php print $this->_tmp_ext_tax[$percent]?>
                    </td>
                </tr>
            <?php } ?>
        <?php } ?>
        <tr class="total">
            <td class = "name">
                <?php print _JSHOP_PRICE_TOTAL ?>
            </td>
            <td class = "value" style="text-align:right;padding-left:30px;">
                <strong><?php print /*'&nbsp;&nbsp;&nbsp;'.formatprice($this->fullsumm).'<br/>   '.*/ number_format(formatprice($this->fullsumm)*$currency->currency_value, 2, ",", " ") . ' '.$currency->currency_code;?></strong><?php print $this->_tmp_ext_total?>
            </td>
        </tr>
        <?php if ($this->config->show_plus_shipping_in_product){?>
            <tr>
                <td colspan="2" align="right">
                    <span class="plusshippinginfo"><?php print sprintf(_JSHOP_PLUS_SHIPPING, $this->shippinginfo);?></span>
                </td>
            </tr>
        <?php }?>
        <?php if ($this->free_discount > 0){?>
            <tr>
                <td colspan="2" align="right">
                    <span class="free_discount"><?php print _JSHOP_FREE_DISCOUNT;?>: <?php print formatprice($this->free_discount); ?></span>
                </td>
            </tr>
        <?php }?>
    </table>




    <?php
        $all_ids = substr($all_ids, 0, strrpos($all_ids, ","));
    ?>

    <input type="hidden" id="cart_ids" value="<?php echo $cart_ids;?>" />
    <input type="hidden" id="cart_total" value="<?php echo formatprice($this->fullsumm)*$currency->currency_value;  ?>" />

    <!-- Rating@Mail.ru rem -->
    <script type="text/javascript">
        var _tmr = _tmr || [];
        _tmr.push({
            type: 'itemView',
            productid: [<?php echo $all_ids; ?>],
            pagetype: 'cart', totalvalue: '<?php echo formatprice($this->fullsumm)*$currency->currency_value;  ?>',
            list: '1' });
    </script>
    <!-- Rating@Mail.ru rem -->

    <br />
    <h3>Заполните данные для покупки:</h3>

    <?php

    $basket_user_name = "";
    $basket_user_phone = "";
    $basket_user_adr = "";
    $basket_user_email = "";
    $basket_user_comment = "";

    if ( (isset($_COOKIE['user_data_all'])) && (trim($_COOKIE['user_data_all'])!="") )
    {
        // --- автозаполнение данных пользователя:
        try
        {
            $all = trim($_COOKIE['user_data_all']);
            $all = explode("|||", $all);
            $basket_user_name = $all[0];
            $basket_user_phone = $all[1];
            $basket_user_adr = $all[2];
            $basket_user_comment = $all[3];
            $basket_user_email = $all[4];
        }
        catch (Exception $e)
        {
        }
    }


    ?>


    <table>
        <tr>
            <td style="vertical-align: top;">
                <input style="margin-bottom: 5px; width: 300px;" type="text" placeholder="Ваше имя" name="basket_user_name" id="basket_user_name" value="<?php echo $basket_user_name; ?>" onchange="save_basket_form();" /><br />
                <input style="margin-bottom: 5px; width: 300px;" type="text" placeholder="Номер телефона (+375-29-ххххххх)" name="basket_user_phone" id="basket_user_phone" value="<?php echo $basket_user_phone; ?>"  onchange="save_basket_form();" /><br />
                <input style="margin-bottom: 5px; width: 300px;" type="text" placeholder="Адрес доставки" name="basket_user_adr" id="basket_user_adr" value="<?php echo $basket_user_adr; ?>"  onchange="save_basket_form();" /><br />
                <input style="margin-bottom: 5px; width: 300px;" type="text" placeholder="Ваш email" name="basket_user_email" id="basket_user_email"  value="<?php echo $basket_user_email; ?>"  onchange="save_basket_form();" /><br />
                <textarea style="display: none;" placeholder="Ваш комментарий к заказу" name="basket_user_comment" id="basket_user_comment"  onchange="save_basket_form();"><?php echo $basket_user_comment; ?></textarea><br />

                <table class="jshop-buttons">
                    <tr id = "checkout">
                        <td class = "td_2" >
                            <a href="#" onclick="send_basket();"><?php echo "&nbsp;" . _JSHOP_CHECKOUT . "&nbsp;"; ?></a>
                        </td>
                    </tr>
                </table>
            </td>


            <?php
            /*
            if($counter==1)
            {
                $db = JFactory::getDbo();
                $db->setQuery("SELECT komplekts FROM #__jshopping_products WHERE product_id={$prod_id}");
                $komplekts_temp = $db->loadObject();
                if(trim($komplekts_temp->komplekts)!='')
                {
                    ?>
                    <td style="vertical-align: middle; text-align: center;">
                        <a href="#" onclick="jQ('#komplekts_div').show('slow');">
                            <img src="/images/super_bonus.jpg" /><br />
                            <b>Всего один клик мышкой <br />может сэкономить деньги!</b>
                        </a>
                    </td>
                <?php

                }
            }
            */
            ?>


        </tr>
    </table>


    <?php
    if($counter==1)
    {
        echo "<div id=\"komplekts_div\" style=\"display: none;\">";

        $komplekts = "";
        $komplekt = explode("~", $komplekts_temp->komplekts);
        $kurs = $currency->currency_value;

        foreach($komplekt AS $k)
            if(trim($k)!="")
            {
                // --- получим инфу о всех товарах из данного комплекта:
                $db->setQuery("SELECT k.*,
                           p1.product_price price1, p2.product_price price2, p3.product_price price3, p4.product_price price4,
                           p1.image image1, p2.image image2, p3.image image3, p4.image image4
                           FROM #__z_komplekt AS k
                           LEFT JOIN #__jshopping_products AS p1 ON p1.product_id = k.prod1
                           LEFT JOIN #__jshopping_products AS p2 ON p2.product_id = k.prod2
                           LEFT JOIN #__jshopping_products AS p3 ON p3.product_id = k.prod3
                           LEFT JOIN #__jshopping_products AS p4 ON p4.product_id = k.prod4
                           WHERE id={$k}");
                $res = $db->loadObject();

                $kom = "";
                $summ = 0;
                $kom_num = 0;

                $kom .= "<tr>";
                $kom .= "<td style='width: 30px;'><input type='radio' name='komplekt' id='komplekt' value='{$res->id}' style='width: 16px; margin: 5px;'/></td>";
                $kom .= "<td style='width: 80px;'><img src='{$prod_img}'/></td>\n";

                // --- каждый из товаров 1,2,3,4 обработаем:
                if($res->prod1*1>0)
                {
                    $kom_num++;
                    $summ += 1.0*$res->price1*$kurs;
                    $kom .= "<td style='width:16px;'><img src='https://piano.by/images/plus.jpg' /></td><td style='width:80px;'><img src='https://piano.by/components/com_jshopping/files/img_products/thumb_{$res->image1}' /></td>\n";
                }

                if($res->prod2*1>0)
                {
                    $kom_num++;
                    $summ += 1.0*$res->price2*$kurs;
                    $kom .= "<td style='width:16px;'><img src='https://piano.by/images/plus.jpg' /></td><td style='width:80px;'><img src='https://piano.by/components/com_jshopping/files/img_products/thumb_{$res->image2}' /></td>\n";
                }

                if($res->prod3*1>0)
                {
                    $kom_num++;
                    $summ += 1.0*$res->price3*$kurs;
                    $kom .= "<td style='width:16px;'><img src='https://piano.by/images/plus.jpg' /></td><td style='width:80px;'><img src='https://piano.by/components/com_jshopping/files/img_products/thumb_{$res->image3}' /></td>\n";
                }

                // --- каждый из товаров 1,2,3,4 обработаем:
                if($res->prod4*1>0)
                {
                    $kom_num++;
                    $summ += 1.0*$res->price4*$kurs;
                    $kom .= "<td style='width:16px;'><img src='https://piano.by/images/plus.jpg' /></td><td style='width:80px;'><img src='https://piano.by/components/com_jshopping/files/img_products/thumb_{$res->image4}' /></td>\n";
                }

                $summ_skidka = number_format($prod_price+floor($summ*(100-$res->skidka)/10000)*100, 0, " ", " ");

                $kom .= "
            <td style='width:16px;'><img src='https://piano.by/images/ravno.png' /></td>
            <td style='text-align: center;'>
            <big><s>" . number_format($prod_price+$summ, 0, " ", " ") . "</s></big><br />
            <b style='color: #FF0000; font-size: 1.2em;' >{$summ_skidka}</b><br />
            </td>";


                if($kom_num++<4)
                    $kom .= "<td style='width:16px;'></td><td style='width:80px;'></td>\n";

                if($kom_num++<4)
                    $kom .= "<td style='width:16px;'></td><td style='width:80px;'></td>\n";

                if($kom_num++<4)
                    $kom .= "<td style='width:16px;'></td><td style='width:80px;'></td>\n";


                $kom .= "</tr>";

                $kom .= "<tr><td colspan=10 style='padding: 3px 10px;'>{$res->info}</td></tr>";
                /*
                                $kom .= "
                            <tr class='zakaz_komplekt' id='zakaz_komplekt_{$k}'>
                                <td colspan=12 >
                                    <span id='zakaz_result_{$k}'></span>
                                    <div id='zakaz_div_{$k}'>
                                        <input id='user_name_{$k}' type='text' placeholder='Ваше имя' />
                                        <input id='user_phone_{$k}' type='text' placeholder='Контактный телефон' />
                                        <input id='user_adr_{$k}' type='text' placeholder='Адрес доставки' />
                                        <button onclick='send_zakaz({$cur_id}, {$k}); return false;' >Заказать</button>
                                    </div>
                                </td>
                            </tr>";

                                $kom = "<tr style='background-color: #F0F0F0;'><td colspan=12 style='padding:5px;'><b>Успей купить набор по суперцене!</b></td>" .
                                    "</tr><tr><td colspan=12>&nbsp;</td></tr>\n" . $kom;
                */

                $kom =
                    "<table style=\"width:100%; border: 1px solid #BBB; border-radius: 3px; margin-bottom: 15px;\" cellpadding=0 cellspacing=0>\n" .
                    $kom .
                    "</table>";
                $komplekts .= $kom;
            }


        if($komplekts!="")
        {
            $komplekts =
                "<br /><br /><h3 style='color: red; text-align: center;'>Предлагаем Вам готовые комплекты со скидкой!</h3><br /><br />" .
                $komplekts .
                "<table style=\"width:100%; border: 1px solid #BBB; border-radius: 3px; margin-bottom: 15px;\" cellpadding=0 cellspacing=0><tr>\n" .
                "<td style='width: 30px;'><input checked=checked type='radio' name='komplekt' id='komplekt' value='0' style='width: 16px; margin: 5px;'/></td><td>Комплект не нужен</td></tr>" .
                "</table>";


        }
// ---------------------------/ Комплекты

        echo $komplekts;
        echo
            "<table class=\"jshop-buttons\">
                <tr id = \"checkout\">
                    <td class = \"td_2\" >
                        <a href=\"#\" onclick=\"send_basket();\">&nbsp;" . _JSHOP_CHECKOUT . "&nbsp;</a>
                    </td>
                </tr>
            </table>";

        echo "</div>";
    }
    ?>


<?php }else{?>
    <div class="cart_empty_text"><?php print _JSHOP_CART_EMPTY?></div>
<?php }?>




</form>

<?php print $this->_tmp_ext_html_before_discount?>
<?php if ($this->use_rabatt && $countprod>0){ ?>
    <br /><br />
    <form name="rabatt" method="post" action="<?php print SEFLink('index.php?option=com_jshopping&controller=cart&task=discountsave')?>">
        <table class="jshop">
            <tr>
                <td>
                    <?php print _JSHOP_RABATT ?>
                    <input type = "text" class = "inputbox" name = "rabatt" value = "" />
                    <input type = "submit" class = "button" value = "<?php print _JSHOP_RABATT_ACTIVE ?>" />
                </td>
            </tr>
        </table>
    </form>
<?php }?>
</div>