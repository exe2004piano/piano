<? defined( '_JEXEC' ) or die(); ?>

<?php
	$db = JFactory::getDbo();
    // --- если рекомендованных к товару нет вообще:
	if(!$recommended = $db->setQuery("SELECT * FROM #__z_prods_recomended WHERE product_id={$product->product_id}")->loadObject())
		calc_recomended($product->product_categories[0]->category_id);
	// --- либо если время последнего обновления больше 2 суток:
    elseif($recommended->data<(time()-48*60*60))
		calc_recomended($product->product_categories[0]->category_id);

	if(!$recommended = $db->setQuery("SELECT * FROM #__z_prods_recomended WHERE product_id={$product->product_id}")->loadObject())
	    return;

	$ids = implode(",", unserialize($recommended->recomended_id));
	$all = $db->setQuery("
	SELECT 
	    p.*,
	    c.category_id cat_id, c.`name_ru-RU` c_name,
	    c1.category_id pcat_id, c1.`name_ru-RU` pc_name
	FROM #__jshopping_products AS p
	LEFT JOIN #__jshopping_products_to_categories AS pc ON pc.product_id=p.product_id
	LEFT JOIN #__jshopping_categories AS c ON c.category_id=pc.category_id
	LEFT JOIN #__jshopping_categories AS c1 ON c1.category_id=c.category_parent_id
	WHERE p.product_id IN ({$ids}) AND p.product_publish=1 AND p.sklad=0
	ORDER BY pc.category_id, c.category_id
	")->loadObjectList();

?>
	<div class="new-section-block">
	<h2 class="new-section-title">Этот товар хорошо дополняют</h2>

        <? $cats = Array(); ?>
        <div class="new-section-categories">
        <? foreach ($all AS $p) { ?>

			<? if ( ((int)$p->pcat_id>0) && (!isset($cats[$p->pcat_id])) ) { ?>
                <a href="#" class="new-section-category <?if(sizeof($cats)==0) echo ' active ';?>" data-cat="<?=$p->pcat_id;?>"><?=$p->pc_name;?></a>
				<? $cats[$p->pcat_id] = $p->pc_name; ?>
			<? } ?>

            <? if((int)$p->pcat_id==0) { ?>
                <? if ( ((int)$p->cat_id>0) && (!isset($cats[$p->cat_id])) ) { ?>
                    <a href="#" class="new-section-category" data-cat="<?=$p->cat_id;?>"><?=$p->c_name;?></a>
                    <? $cats[$p->cat_id] = $p->c_name; ?>
                <? } ?>
            <? } ?>

		<? } ?>
        </div>





    <? foreach ($cats AS $cat_id=>$c) { ?>
	<div class="new-section-slider slick-carousel data_cat_<?=$cat_id;?>" >
		<? foreach ($all AS $p) {
		    if( ($p->pcat_id!=$cat_id) && ($p->cat_id!=$cat_id) ) continue;
		    ?>
            <div class="new-section-slide ">
                <a href="<?=$p->real_link;?>" class="new-section-card">
                    <div class="new-section-card-img">
                        <img src="/components/com_jshopping/files/img_products/full_<?=$p->image;?>" alt="" />
                    </div>

                    <h2 class="new-section-card-title"><?=$p->{'name_ru-RU'};?></h2>

                    <p class="new-section-card-price">
                        <span><?=echo_price($p->product_price); ?></span>
                    </p>

                    <div class="bv-btn bv-btn--third" data-get-popup="toCart" onclick="add_to_basket('<?=$p->product_id;?>',1); return false;">
                        <span class="bv-btn__text">В корзину</span>
                    </div>
                </a>
            </div>
        <? } ?>
	</div>
    <?} ?>



</div>