<?php
/*
# ------------------------------------------------------------------------
# Templates for Joomla 2.5 - 3.5
# ------------------------------------------------------------------------
# Copyright (C) 2011-2013 Jtemplate.ru. All Rights Reserved.
# @license - PHP files are GNU/GPL V2.
# Author: Makeev Vladimir
# Websites:  http://www.jtemplate.ru 
# ---------  http://code.google.com/p/jtemplate/   
# 
*/
// No direct access.
defined('_JEXEC') or die;
$currency = JTable::getInstance('currency', 'jshop');
$currency->load(2);
if ($jt_mode == 'vertical') {$jt_vertical='_v';} 
	else {$jt_vertical='';}
$document->addCustomTag('
<style>
#jt_jshopping_bestseller_slider ul li { background: none;  width:'.$jt_width.'px; height:'.$jt_height.'px;}
#jt_jshopping_bestseller_slider .jt_button_prev_b_'.$jt_id_sfx.' a, #jt_jshopping_bestseller_slider .jt_button_next_b_'.$jt_id_sfx.' a {height:'.$jt_height.'px;}
</style>');

if ($jt_load_jquery == 2) { ?>
	<?php if ($jquery == 1) { ?>
	<script type = "text/javascript">
			var jQ = false;
			function initJQ() {
			  if (typeof(jQuery) == 'undefined') {
				if (!jQ) {
				  jQ = true;
				  document.write('<scr' + 'ipt type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.6.2/jquery.min.js"></scr' + 'ipt>');
				}
				setTimeout('initJQ()', 50);
			  } 
			}
			initJQ();
	</script>
	<?php } if ($jt_script_bx == 1 ) { ?>
	<script type = "text/javascript" src = "<?php echo JURI::root() ?>/modules/mod_jt_jshopping_bestseller/js/jquery.bxSlider.min.js"></script>
	<?php } ?>
	<script type = "text/javascript">if (jQuery) jQuery.noConflict();</script>
<?php } ?>

<script type="text/javascript">
jQuery(document).ready(function(){
	jQuery('#bestseller_slider<?php if ($jt_id_sfx !='') echo $jt_id_sfx; ?>').bxSlider({
	prevSelector:'.jt_button_prev_b_<?php echo $jt_id_sfx;?>',
	nextSelector:'.jt_button_next_b_<?php echo $jt_id_sfx;?>',
	mode: '<?php echo $jt_mode;?>', 
	speed: <?php echo $jt_speed;?>,
	controls: <?php echo $jt_controls; ?>,
	auto: <?php echo $jt_auto;?>,
	pause: <?php echo $jt_pause?>,
	autoDelay: <?php echo $jt_auto_delay; ?>,
	autoHover: <?php echo $jt_autohover; ?>,
	pager: <?php echo $jt_pager;?>,
	pagerType: '<?php echo $jt_pager_type;?>',
	pagerLocation: '<?php echo $jt_pager_location;?>',
	pagerShortSeparator: '<?php echo $jt_pager_saparator;?>',
	displaySlideQty: <?php echo $jt_display_slide_qty;?>,
	moveSlideQty: <?php echo $jt_move_slide_qty;?>		
	});
});
</script>		

<div class="mod_jt_jshopping_bestseller <?php echo $moduleclass_sfx;?>">
	<div id="jt_jshopping_bestseller_slider">
		<div class="jt_button_prev_b_<?php echo $jt_id_sfx;?> jt_prev_b<?php echo $jt_vertical;?>"></div>
		<ul id="bestseller_slider<?php if ($jt_id_sfx !='') echo $jt_id_sfx; ?>">
		<?php foreach($last_prod as $curr){ ?>
			<li>
			   <div class="block_item">
					<div class="item_name">
					   <h3><a href="<?php print $curr->product_link?>"><?php print $curr->name?></a></h3>
					</div>
			   
				   <?php if ($show_image) { ?>
					<div class="item_image">
						
						<?php if ($curr->label_id AND $jt_label_prod > 0){?>
							<div class="product_label">
								<?php if ($curr->_label_image){?>
									<img src="<?php print $curr->_label_image?>" alt="<?php print htmlspecialchars($curr->_label_name)?>" />
								<?php }else{?>
									<span class="label_name"><?php print $curr->_label_name;?></span>
								<?php }?>
							</div>
						<?php }?>
						<span>
							<a href="<?php print $curr->product_link?>"><img src = "<?php print $jshopConfig->image_product_live_path?>/<?php if ($curr->product_thumb_image) print $curr->product_thumb_image; else print $noimage?>" alt="<?php print $curr->name?>" /><?php if ($curr->_display_price){?>
					<div class="item_price"> 
                    <?php 
$calc_price = str_replace('$', '',formatprice($curr->product_price))*$currency->currency_value.' '.$currency->currency_code;
$calc_old_price = 	str_replace('$', '',formatprice($curr->product_old_price))*$currency->currency_value.' '.$currency->currency_code; if (formatprice($curr->product_old_price) > 0){?>
             <div class="old_price">
         <span class="old_price" id="old_price"><?php print formatprice($curr->product_old_price)?></span><span class="calc_old_price">(<?php echo $calc_old_price ?>)</span>
    </div>
        <?php }?>
                <div class="prod_price">
        <span id="block_price"><?php print formatprice($curr->product_price)?></span><span class="calc_price">(<?php echo $calc_price ?>)</span>
    </div>					</div>
					<?php }?></a>
						</span>
					</div>
				   <?php } ?>
				   				   
					<?php if ($jt_short_desc > 0 AND $curr->short_description) { ?>
					<div class="short_description">
						<?php print $curr->short_description?>
					</div>
					<?php } ?>
					
					
			   </div>
			</li>	
		<?php } ?>
		</ul>
		<div class="jt_button_next_b_<?php echo $jt_id_sfx;?> jt_next_b<?php echo $jt_vertical;?>"></div>
	</div>
	<div style="clear:both"></div>
</div>