<?php JHtml::stylesheet(JURI::base().'modules/mod_vitalik_jsh_categories/css/style.css', array(), true); ?>

<?php

// --- проверим есть ли файл меню
// --- если нет - то меню полюбому создавать
// --- если есть, то загрузим его

if(file_exists("menu.tmp"))
{
    // текущее время
    $time_sec=time();
    // время изменения файла
    $time_file=filemtime("menu.tmp");
    // тепрь узнаем сколько прошло времени (в секундах)
    $time=$time_sec-$time_file;
    if($time>3600)  // создавался больше часа назад, значит удалим
        unlink("menu.tmp");
}



if(!file_exists("menu.tmp"))
{
ob_start();
?>

<ul class = "catalog-menu">
<?php
$script_ = "";
$db = JFactory::getDbo();
$db->setQuery("SELECT * FROM #__jshopping_currencies WHERE currency_id=2");
$res = $db->loadObject();
$kurs = $res->currency_value;


  foreach($categories as $category){ ?>
  <?php $category_class = 'category';
		if (is_array($category->subcategories) && count($category->subcategories))
		{
			$category_class .= ' parent';
		}
		if ($category->category_link==$_SERVER['REQUEST_URI'])
		{
			// $category_class .= ' active';
		}
		$r1 = str_replace($category->category_link, '', $_SERVER['REQUEST_URI']);
		if ($category->category_link.$r1 == $_SERVER['REQUEST_URI'])
		{
            $subindicator='closed';
		// $indicator='open';
		//$category_ul="block";
            $category_ul="none";
		$script .= "<script>show_cat('{$category->category_id}');</script>\n";
		} else {
        $indicator='closed';
		$category_ul="none";
		}
	?>
	<li class="<?php echo $category_class; ?>">
    		<span id="spoiler" onClick='return toggleShow("<?php echo '#sub'.$category->category_id ?>" ,this)' rel='spo<?php echo $category->category_id; ?>'></span>
    		<a href = "<?php echo $category->category_link?>">
				<?php print $category->name?>
                <?php if ($show_image && $category->category_image){?>
                    <img src = "<?php print $jshopConfig->image_category_live_path."/".$category->category_image?>" alt = "<?php print $category->name?>" />
                <?php } ?>
            </a>
            <?php if($category->products) {
                // style="display:   echo $category_ul
                ?>
                	<ul class="products" id="<?php echo 'sub'.$category->category_id ?>" >
						<?php foreach($category->products as $product): ?>
                        	<?php $product_class = '';
		if ($product->product_link==$_SERVER['REQUEST_URI'])
		{
			$product_class = 'active';
		}
	?>
							<li class="product-<?php echo $product->product_id; ?> <?php echo $product_class; ?>">
								<a href="<?php echo $product->product_link?>">
                                	<?php if ($product->product_ean){?>
                                		<span 1><?php echo $product->product_ean; ?></span>
                                    <?php } else { ?>
                                    	<span 2><?php echo $product->name; ?></span>
                                    <?php } ?>
                                	<?php if ($show_product_image && $product->image){?>
                   						<img class="parent_<?php echo $category->category_id; ?>" src="/images/blank.png" rel="<?php echo str_replace("http://pianino.by" , "", $product->image);?>" alt = "<?php print $product->name?>" />
                					<?php } ?>
                    			</a>
                        	</li>
                        <?php endforeach ?>
                    </ul>
            <?php } ?>
            <?php if($category->subcategories) {
                //  style="display:     echo $category_ul
                ?>
            <ul class="subcategories" id="<?php echo 'sub'.$category->category_id ?>" >
				<?php  foreach($category->subcategories as $subcategory): ?>
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
		// $indicator='open 1';
		//$category_ul="block";
            $category_ul="none";
		echo "<script>
					jQ( document ).ready(
						function()
						{
							show_cat('{$subcategory->category_id}');
						}
					);
			  </script> \n";
		} else {
        $indicator='closed';
		$category_ul="none";
		}
	?>
                <li class="<?php echo $category_class; ?>">
                	<?php if (is_array($subcategory->subcategories) && count($subcategory->subcategories) or (is_array($subcategory->products) && count($subcategory->products))){ ?>
        	<span id="spoiler" onClick='return toggleShow("<?php echo '#sub'.$subcategory->category_id ?>" ,this)' rel='spo<?php echo $subcategory->category_id; ?>'></span>
        <?php } ?>
					<a href="<?php echo $subcategory->category_link?>">
						<?php echo $subcategory->name; ?>
                        <?php if ($show_image && $subcategory->category_image){?>
                    <img src = "<?php print $jshopConfig->image_category_live_path."/".$subcategory->category_image?>" alt = "<?php print $subcategory->name?>" />
                <?php } ?>
                    </a>


                    <?php if($subcategory->products) {

                        // style="display:  echo $category_ul
                        ?>
                		<ul class="products" id="<?php echo 'sub'.$subcategory->category_id ?>" >
							<?php $non_prod="";

                            foreach($subcategory->products as $product): ?>
                                <?php
                                    if($product->cz*1==1)
                                    $product->product_price=0;
                                ?>


                                <li class="product-<?php echo $product->product_id; ?> <?php echo $category_class; ?>" id="prod_link_<?php echo $product->product_id; ?>" onmouseover="show_price(<?php echo $product->product_id; ?> , <?php echo (round($product->product_price)*$kurs); ?>);" >
                                <?php


                                    if( ($product->sklad==3) && ($non_prod=="") )
                                    {   // --- нарвались на первый товар, снятый с производства:
                                            $non_prod = " class=\"none_prod\" ";
                                            echo "<br /><span style='font-size: 0.8em;'>Сняты с производства:</span><br />\n";
                                    }

                                ?><a <?php echo $non_prod;?> href="<?php echo $product->product_link?>" >
                                	<?php if ($product->product_ean){?>
                                		<span 3><?php echo $product->product_ean?></span>
                                    <?php } else { ?>
                                    	<span 4><?php echo $product->name; ?></span>
                                    <?php } ?>
                                	<?php if ($show_product_image && $product->image){?>
                   						<img class="parent_<?php echo $subcategory->category_id; ?>" src="/images/blank.png" rel="<?php echo str_replace("http://pianino.by" , "", $product->image); ?>" alt = "<?php print $product->name?>" />
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
            <?php } ?>
	</li>
  <?php
  }
?>
</ul>

<?php

    $my_menu = ob_get_contents();
    ob_end_clean();

    $my_menu = preg_replace('/\s/', ' ', $my_menu);
    $my_menu = str_replace(array("\n", "\r\n", "\n\r", "    "), " ", $my_menu);
    $my_menu = str_replace('  ', ' ', $my_menu);
    $my_menu = str_replace('  ', ' ', $my_menu);
    $my_menu = str_replace(Array('> ', ' <', ' <', '> ', '> <', ' ,', ', '), Array('>', '<', '<', '>', '><', ',', ','), $my_menu);

    echo $my_menu;

    file_put_contents('menu.tmp', ($my_menu));
}


else
{
    $my_menu = readfile('menu.tmp');
}

