<?php defined( '_JEXEC' ) or die();

//print_r($product);
//die;

if(isset($product->credit))
    $_COOKIE['credit'] = 1;

$calc_price = round($product->product_price, 2);
$calc_old_price = round($product->product_old_price, 2) . " р.";
// $calc_price = str_replace('$', '',formatprice(formatprice($product->product_price)))*$currency->currency_value.' '.$currency->currency_code;
// $calc_old_price = 	str_replace('$', '',formatprice($product->product_old_price))*$currency->currency_value.' '.$currency->currency_code;

?>
<?php print $product->_tmp_var_start?>
<?php if ($product->image){?>
    <span class="h2">
        <a href="<?php print $product->product_link?>"><div class="name_div"><?php print $product->name?></div></a>
        <?php if ($this->config->product_list_show_product_code){?><span class="jshop_code_prod">(<?php print _JSHOP_EAN?>: <span><?php print $product->product_ean;?></span>)</span><?php }?>
    </span>
    <div class="image_block">
        <?php if ($product->label_id){?>
            <div class="product_label">
                <?php if ($product->_label_image){?>
                    <img src="<?php print $product->_label_image?>" alt="<?php print htmlspecialchars($product->_label_name)?>" />
                <?php }else{?>
                    <span class="label_name"><?php print $product->_label_name;?></span>
                <?php }?>
            </div>
        <?php }?>
        <a href="<?php print $product->product_link?>">
            <div class="img">
                <img class="jshop_img" src="<?php print str_replace(array('thumb_', '.bu'), array('', '.by'),$product->image) ?>" alt="<?php print htmlspecialchars($product->name);?>" />
            </div>
            <?php if ($product->product_old_price > 0){?>
                <div class="old_price <?php if($_COOKIE['credit']*1==1) echo " non_stroke "; ?> " >
                    <span class="old_price <?php if($_COOKIE['credit']*1==1) echo " non_stroke "; ?> " id="old_price">
                        <?php
                            if($_COOKIE['credit']*1!=1)
                                echo $calc_old_price;
                            else
                                echo "В кредит";
                        ?>
                    </span>
                    <span class="calc_old_price">
                        <!--(<?php print ($product->product_old_price)?>)-->
                    </span>
                </div>
            <?php }?>
            <?php if ($product->product_price_default > 0 && $this->config->product_list_show_price_default){?>
                <div class="default_price"><?php print _JSHOP_DEFAULT_PRICE.": ";?><span><?php print ($product->product_price_default)?></span></div>
            <?php }?>
            <?php if ($product->_display_price){?>
                <?php if ($this->config->product_list_show_price_description) print _JSHOP_PRICE.": ";?>
                <?php if ($product->show_price_from) print _JSHOP_FROM." ";?>
                <div class="prod_price" style="font-size: 1.1em!important;">
                    <span id="block_price" style="font-size: 1.1em!important;">
                    	<?php
                            if($product->cz==0)
                            {
                                // <small>12 x</small></span> <b style="font-size: 1.5em; color:red; line-height: 24px;"><?php echo number_format(floor($jshop_product_price/12*1.235/100)*100, 0, " ", " "); р.</b></center>
                                if($_COOKIE['credit']*1!=1)
                                    echo number_format($calc_price, 2, ",", " ") . " р.&nbsp;";
                                else
                                    echo "<small style='color: #000; font-size: 11px;'>12 x</small> " . number_format(($calc_price/12*1.235), 2, ",", " ") . " р.&nbsp;";
                            }
                            else
                                echo "Лучшая цена!";
                        ?>
                    </span>
                    <div class="clr"></div>
                    <span class="calc_price" style="font-size: 1.0em!important;">
                        &nbsp;<?php echo number_format($calc_price*10000, 0, " ", " ") . "<b>*</b> руб."; ?>
                        <!--(<?php print formatprice(formatprice($product->product_price))?><?php print $this->product->_tmp_var_price_ext;?>)-->
                    </span>
                </div>
            <?php }?>
        </a>
        <center>
            <?php
            if( ($product->sklad*1==5) || ($product->sklad*1==3) || ($_COOKIE['credit']*1==1) )
                echo "<small>&nbsp;</small>";
            else
                echo "<small onclick=\"open_my_price('{$product->name}');\" style=\"cursor: pointer;\">Предложить свою цену</small>";
            ?>
        </center>
    </div>
<?php }?>

<?php print $product->_tmp_var_bottom_foto;?>

<?php print $product->_tmp_var_bottom_price;?>
<?php if ($this->config->show_tax_in_product && $product->tax > 0){?>
    <span class="taxinfo"><?php print productTaxInfo($product->tax);?></span>
<?php }?>
<?php if ($product->basic_price_info['price_show']){?>
    <div class="base_price"><?php print _JSHOP_BASIC_PRICE?>: <?php if ($product->show_price_from) print _JSHOP_FROM;?> <span><?php print formatprice($product->basic_price_info['basic_price'])?> / <?php print $product->basic_price_info['name'];?></span></div>
<?php }?>
<?php if ($this->config->product_list_show_weight && $product->product_weight > 0){?>
    <div class="productweight"><?php print _JSHOP_WEIGHT?>: <span><?php print formatweight($product->product_weight)?></span></div>
<?php }?>
<?php if ($product->delivery_time != ''){?>
    <div class="deliverytime"><?php print _JSHOP_DELIVERY_TIME?>: <span><?php print $product->delivery_time?></span></div>
<?php }?>
<?php if (is_array($product->extra_field)){?>
    <div class="extra_fields">
        <?php foreach($product->extra_field as $extra_field){?>
            <div><?php print $extra_field['name'];?>: <?php print $extra_field['value']; ?></div>
        <?php }?>
    </div>
<?php }?>
    <div class="description">
        <?php print JHtml::_( 'string.truncate', $product->short_description, 130, true, true );?>
    </div>
<?php if ($product->vendor){?>
    <div class="vendorinfo"><?php print _JSHOP_VENDOR?>: <a href="<?php print $product->vendor->products?>"><?php print $product->vendor->shop_name?></a></div>
<?php }?>
<?php if ($this->config->product_list_show_qty_stock){?>
    <div class="qty_in_stock"><?php print _JSHOP_QTY_IN_STOCK?>: <span><?php print sprintQtyInStock($product->qty_in_stock)?></span></div>
<?php }?>
<?php print $product->_tmp_var_top_buttons;?>
<div class="buttons">
<?php
$tov=0;

$cart = JModelLegacy::getInstance('cart', 'jshop');
$cart->load("cart");
$countprod = 0;
$array_products = array();
foreach($cart->products as $value) {
    $array_products [$countprod] = $value;
    if ($product->product_id==$array_products [$countprod]["product_id"]) { $tov=1; }
}


$sklad[0] = 'В наличии';
$sklad[1] = 'Под заказ на ' . date("d.m.Y", time() + 7*24*60*60);
$sklad[2] = 'Нет в наличии';
$sklad[3] = 'Снят с производства';
$sklad[4] = 'Заказная позиция';
$sklad[5] = 'Анонсированный';

if($product->cz==0)
    echo "<br /><center><b style='color:#ff5500;'>" . $sklad[$product->sklad*1] . "</b></center>";

if ($tov==1)
{
    if ($product->buy_link) { ?> <a class="button_go_to_cart" href="/cart" title="Перейти в корзину">В корзине</a> &nbsp; <?php }
}
else
{
    if( ($product->sklad*1<2) || ($product->sklad*1==4) ) { ?><a class="button_buy" href="<?php print $product->buy_link?>"><?php print _JSHOP_BUY?></a> &nbsp;<?php }
    else
    {
        ?>


        <div class="rsform" style="z-index: 9999;">
            <form method="post"  id="userForm<?php echo $product->product_id;?>" enctype="multipart/form-data" action="">
                <div id="one_click" rel="item<?php echo $product->product_id;?>" style="display:none; margin-top: -320px; height: 290px!important;">
                    <div style="float:right; margin: -25px 10px 25px -10px; color: red; cursor: pointer; font-weight: bolder;" onClick="jQuery('[rel=item<?php echo $product->product_id;?>]').slideToggle(200); return false;">X</div>
                    <fieldset class="formFieldset">
                        <ol class="formContainer" id="rsform_3_page_0">
                            <li class="rsform-block rsform-block-text">
                                <div class="formBody" id="info<?php echo $product->product_id;?>">
                                    <center>
                                    Вы в одном шаге от удачной покупки!<br/><br/>
                                    Укажите только имя и телефон, и мы
                                    предложим Вам оптимальный товар!
                                    </center>
                                </div>
                                <div class="clear"></div>
                            </li>
                            <li class="rsform-block rsform-block-name">
                                <div class="formBody"><input type="text" value="" size="20"  name="form[client]" id="client<?php echo $product->product_id;?>"  class="rsform-input-box"/>
                                </div>
                                <div class="formCaption">Имя</div>
                                <div class="clear"></div>
                            </li>
                            <li class="rsform-block rsform-block-phone">
                                <div class="formBody"><input type="text" value="" size="20"  name="form[phone]" id="phone<?php echo $product->product_id;?>"  class="rsform-input-box"/>
                                </div>
                                <div class="formCaption">Телефон<span class="formRequired">+ 375</span></div>
                                <div class="clear"></div>
                            </li>
                            <li class="rsform-block rsform-block-send">
                                <center>
                                	<input type="hidden" id="item__<?php echo $product->product_id; ?>" value="<?php echo $product->product_ean; ?>" />
                                    <button class="but_send" onclick="callback(<?php echo $product->product_id;?>); return false;">Заказать звонок</button>
                                </center>
                            </li>
                        </ol>
                    </fieldset>
                </div>
                <input type="hidden" name="form[formId]" value="<?php echo $product->product_id;?>"/>
            </form>
        </div>

        <a class="button_buy" style="background-image: url(/templates/pianino/images/add_to_cart1.png); padding:0px!important; font-size:1.1em;" onClick="jQuery('[rel=item<?php echo $product->product_id;?>]').slideToggle(200); return false;" href="#">
            <?php
                if($product->cz==0)
                    echo "Поможем подобрать аналог";
                else
                    echo "Узнать стоимость";
            ?>
        </a> &nbsp;
    <?php

    }
}
?>

<?php print $product->_tmp_var_buttons;?>
    </div>
<?php print $product->_tmp_var_bottom_buttons;?>
<?php print $product->_tmp_var_end?>