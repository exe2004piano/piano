<?php

$script = "";

if($subcategory->subcategories) {

    $db = JFactory::getDbo();
    $db->setQuery("SELECT * FROM #__jshopping_currencies WHERE currency_id=2");
    $res = $db->loadObject();
    $kurs = $res->currency_value;

    // style="display:     echo $category_ul

    ?>
    <ul class="subcategories" id="<?php echo 'sub'.$subcategory->category_id ?>" >
        <?php  foreach($subcategory->subcategories as $subcategory): ?>

            <?php $category_class = 'category';
            if (is_array($subcategory->subcategories) && count($subcategory->subcategories))
            {
                $category_class .= ' parent';
            }
            if ($subcategory->category_link==$_SERVER['REQUEST_URI'])
            {
                // $category_class .= ' active';
            }
            $r1 = str_replace($subcategory->category_link, '', $_SERVER['REQUEST_URI']);
            if ($subcategory->category_link.$r1 == $_SERVER['REQUEST_URI'])
            {
                $subindicator='closed';
                // $subindicator='open';
                // $category_ul="block";
                $category_ul="none";
                $script .= "<script>show_cat('{$subcategory->category_id}');</script>";
            } else {
                $subindicator='closed';
                $category_ul="none";
            }


            ?>

            <li class="<?php echo $category_class; ?>">
                <?php if (is_array($subcategory->subcategories) && count($subcategory->subcategories) or (is_array($subcategory->products) && count($subcategory->products))){ ?>
                    <span id="spoiler" onClick='return toggleShow("<?php echo '#sub'.$subcategory->category_id ?>" ,this)' rel="spo<?php echo $subcategory->category_id; ?>"></span>
                <?php } ?>
                <a href="<?php echo $subcategory->category_link?>">
                    <?php echo $subcategory->name; ?>
                    <?php if ($show_image && $subcategory->category_image){?>
                        <img src = "<?php print $jshopConfig->image_category_live_path."/".$subcategory->category_image?>" alt = "<?php print $subcategory->name?>" />
                    <?php } ?>
                </a>
                <?php if($subcategory->products) {
                    // style="display:    echo $category_ul
                    ?>
                    <ul class="products" id="<?php echo 'sub'.$subcategory->category_id ?>" >
                        <?php $non_prod="";

                        foreach($subcategory->products as $product): ?>
                            <?php $product_class = 'category';
                            if ($product->product_link==$_SERVER['REQUEST_URI'])
                            {
                                $product_class .= ' active';
                            }

                            if($product->cz*1==1)
                                $product->product_price=0;

                            if( ($product->sklad==3) && ($non_prod=="") )
                            {   // --- нарвались на первый товар, снятый с производства:
                                $non_prod = " class=\"none_prod\" ";
                                echo "<br /><span style='font-size: 0.8em;'>Сняты с производства:</span><br />\n";
                            }

                            ?>
                            <li class="product-<?php echo $product->product_id; ?> <?php echo $product_class; ?>" id="prod_link_<?php echo $product->product_id; ?>" onmouseover="show_price(<?php echo $product->product_id; ?> , <?php echo (round($product->product_price)*$kurs); ?>);">
                                <a <?php echo $non_prod;?> href="<?php echo $product->product_link?>">
                                    <?php if ($product->product_ean){?>
                                        <span><?php echo $product->product_ean; ?></span>
                                    <?php } else { ?>
                                        <span><?php echo $product->name; ?></span>
                                    <?php } ?>
                                    <?php if ($show_product_image && $product->image){?>
                                        <img class="parent_<?php echo $subcategory->category_id; ?>" src="/images/blank.png" rel="<?php echo str_replace("http://pianino.by" , "", $product->image);?>" alt="<?php print $product->name?>" />
                                    <?php } ?>
                                </a>
                            </li>
                        <?php endforeach ?>
                    </ul>
                <?php } ?>
                <?php if($subcategory->subcategories) {
                    require JModuleHelper::getLayoutPath('mod_vitalik_jsh_categories', $params->get('layout', 'default').'_subcategories');
                } ?>
            </li>
        <?php endforeach; ?>
    </ul>
<?php }

echo $script;

?>




