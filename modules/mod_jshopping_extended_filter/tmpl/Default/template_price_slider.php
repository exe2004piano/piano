<?php
// no direct access
defined('_JEXEC') or die('Restricted access');
?>

<script type="text/javascript">
	
	function addCommas(nStr)
	{
		nStr += '';
		x = nStr.split('.');
		x1 = x[0];
		x2 = x.length > 1 ? '.' + x[1] : '';
		var rgx = /(\d+)(\d{3})/;
		while (rgx.test(x1)) {
			x1 = x1.replace(rgx, '$1' + '.' + '$2');
		}
		return x1 + x2;
	}
	
	<?php 
		$from = JRequest::getVar("price-from", 0);
		$to = JRequest::getVar("price-to", 0);

		if($from != 0 && $to != 0)
			$value = number_format($from, 0, '', '.'). " - " .number_format($to, 0, '', '.');
		if($from == 0 && $to != 0)
			$value = number_format($filter->slider_from, 0, '', '.') . " - ".number_format($to, 0, '', '.');
		if($from == 0 && $to == 0)
			$value = number_format($filter->slider_from, 0, '', '.') . " - " . number_format($filter->slider_to, 0, '', '.');
	?>
	
	jQuery(document).ready(function() {
	
		jQuery("#slider_price<?php echo $module->id; ?>")[0].slide = null;
		jQuery("#slider_price<?php echo $module->id; ?>").slider({
			range: true,
			min: <?php echo $filter->slider_from; ?>,
			max: <?php echo $filter->slider_to; ?>,
			step: 1,
			values: [ <?php if($from != 0) echo $from; else echo $filter->slider_from; ?>, <?php if($to != 0) echo $to; else echo $filter->slider_to; ?> ],
			<?php if($auto_submit) : ?>
			stop: function(event, ui) {
				submit_form_<?php echo $module->id; ?>();
			},
			<?php endif; ?>
			slide: function(event, ui) {
				jQuery( "#slider_amount<?php echo $module->id; ?>" ).val( addCommas(ui.values[ 0 ]) + " - " + addCommas(ui.values[ 1 ]) );
				jQuery("input#slider_price<?php echo $module->id; ?>_val_from").val( ui.values[ 0 ] );
				jQuery("input#slider_price<?php echo $module->id; ?>_val_to").val( ui.values[ 1 ] );
			}
		});
		jQuery("#slider_amount<?php echo $module->id; ?>").val("<?php echo $value; ?>");
	});
	</script>

	<div class="filter-field-price-slider">
		<span class="h3_1">
			<?php echo JText::_('MOD_JSHOP_EFILTER_FIELDS_PRODUCT_PRICE_TITLE'); ?>
		</span>

		<div class="slider_price<?php echo $module->id; ?>_wrapper slider_wrapper">

			<input type="text" disabled id="slider_amount<?php echo $module->id; ?>" class="filter-slider-amount" />

			<div id="slider_price<?php echo $module->id; ?>"></div>
			
			<input id="slider_price<?php echo $module->id; ?>_val_from" class="slider_val" type="hidden" name="price-from" value="<?php if($from != 0) echo $from; ?>">
			
			<input id="slider_price<?php echo $module->id; ?>_val_to" class="slider_val" type="hidden" name="price-to" value="<?php if($to != 0) echo $to; ?>">
		
		</div>
	</div>

