<?php

	defined('_JEXEC') or die;
?>
<div class="custom_products">
	<?php $count_group = 0; foreach ($groups as $group_title => $products) { ?>
	<?php if (count($products)) { ?>
    <div class="clear"></div>
   <div class="custom_product<?php if ($count_group%2){echo '_right';} ?>">
    <div class="group_desc_part">
		<div class="group-title">
			<?php echo $group_title; ?>
		</div>
		<?php if (!empty($groups_description[$group_title])) { ?>
		<div class="group-description">
			<?php echo $groups_description[$group_title]; ?>
		</div>
		<?php } ?>
    </div>
	<div class="group_item_part">
	<?php $i=1;?>
	<?php foreach ($products as $curr) { ?>
    <?php if($i==$groups_height[$group_title]+1) { ?>
    <div class="hidden<?php echo $count_group; ?>" style="display:none;"> 
	<?php } ?>
	<div class="block_item<?php echo ' number'.$i; ?>">		
		<div class="item_name">
			<a href="<?php echo $curr->product_link; ?>">
				<?php echo $curr->title; ?>
                <?php if ($curr->_display_price) { ?> за 
		<span class="item_price">
			<?php echo formatprice($curr->product_price); ?>
		</span>
		<?php } ?>
			</a>
            
            <?php if ($show_image) { ?>
				<img src="<?php echo $jshopConfig->image_product_live_path; ?>/<?php if ($curr->product_name_image) echo $curr->product_name_image; else echo $noimage; ?>" alt="" />
		<?php } ?>
		</div>
	</div>
     <?php if($i==count($products)&& $i>$groups_height[$group_title]) { ?>   
     </div>
		<br />

     <a href="#" id="open" class='closed' onClick='return toggleShow("<?php echo '.hidden'.$count_group; ?>" ,this)'></a>
     <?php } ?>  
	<?php $i++; } ?>
    
    
    
    
    </div>
	<?php } ?>
    </div>
	<?php $count_group++; }  ?>
    <div class="clear"></div>
</div>