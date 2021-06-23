<div id = "jshop_module_cart">
<div class = "module_cart_detail" width = "100%">
<?php
 $currency = JTable::getInstance('currency', 'jshop');
 $currency->load(2);

  $countprod = 0;
  $array_products = array();
  foreach($cart->products as $value){
    $array_products [$countprod] = $value;
?>
      <div class="<?php  if ( ($countprod + 2) % 2 > 0) { print 'odd'; } else { print 'even'; }  ?>">
        <div class="name"><?php $productlink = 'index.php?option=com_jshopping&controller=product&task=view&category_id='.$array_products [$countprod]["category_id"].'&product_id='.$array_products [$countprod]["product_id"]; ?> <a href ="<?php echo JRoute::_($productlink) ?>" ><img width="100%" src="/components/com_jshopping/files/img_products/<?php print str_replace('thumb_', '', $array_products [$countprod]["thumb_image"]); ?>" /><?php print $array_products [$countprod]["product_name"]; ?></a></div>
        <?php if ($show_count =='1') {?>
        <div class="qtty"><?php print $array_products [$countprod]["quantity"]; ?> x </div>
        <div class="summ"><?php print formatprice($array_products [$countprod]["price"]); ?></div>
        <?php }else {?>
        <div class="qtty"> </div>
        <div class="summ"><?php
        	echo number_format($array_products [$countprod]["price"] * $array_products [$countprod]["quantity"] * $currency->currency_value, 0, " ", " ") . " р.";

        	/*print formatprice($array_products [$countprod]["price"] * $array_products [$countprod]["quantity"]);*/

        	?></div>
        <?php }?>
      </div>
    <?php $countprod++; ?>
<?php } ?>
</div>
<table width = "100%">
<tr>
    <td colspan=2>
      <!-- <span id = "jshop_quantity_products"><?php print $cart->count_product?></span>&nbsp;<?php print JText::_('PRODUCTS')?> -->
      <span id = "jshop_quantity_products"><strong><?php print JText::_('SUM_TOTAL')?>:</strong>&nbsp;</span>&nbsp;
    </td>
</tr>
<tr>
    <td colspan=2>
      <span id = "jshop_summ_product"><?php
		echo number_format($cart->getSum(0,1) * $currency->currency_value, 0, " ", " ") . " р.";
      // print formatprice($cart->getSum(0,1))
      ?></span>
    </td>
</tr>
<tr>
    <td colspan="2" align="right" class="goto_cart">
      <a href = "<?php print SEFLink('index.php?option=com_jshopping&controller=cart&task=view', 1)?>"><?php print JText::_('GO_TO_CART')?></a>
    </td>
</tr>
</table>
</div>

