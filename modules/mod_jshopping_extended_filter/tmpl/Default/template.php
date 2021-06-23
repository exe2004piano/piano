<?php

/*
// Joomshopping Extended Filter and Search module by Andrey M
// molotow11@gmail.com
*/

// no direct access
defined('_JEXEC') or die('Restricted access');

?>

<script type="text/javascript">
		
	var FilterPath = '<?php echo JURI::root(true); ?>/modules/mod_jshopping_extended_filter/assets/';
	
	if (typeof jQuery == 'undefined') {
		document.write('<scr'+'ipt type="text/javascript" src="'+FilterPath+'js/jquery-1.8.2.min.js"></scr'+'ipt>');
		document.write('<scr'+'ipt>jQuery.noConflict();</scr'+'ipt>');
	}
	
	if (typeof jQuery.ui == 'undefined') {
		document.write('<scr'+'ipt type="text/javascript" src="'+FilterPath+'js/jquery-ui-1.9.1.custom.min.js"></scr'+'ipt>');
		document.write('<link type="text/css" href="'+FilterPath+'css/smoothness/jquery-ui-1.9.1.custom.css" rel="stylesheet" />');
	}
	
	document.write('<scr'+'ipt type="text/javascript" src="'+FilterPath+'js/jquery.multiselect.js"></scr'+'ipt>');
	document.write('<link type="text/css" href="'+FilterPath+'css/smoothness/jquery.multiselect.css" rel="stylesheet" />');
	
	jQuery(document).ready(function() {

		jQuery("#ExtendedFilterContainer<?php echo $module->id; ?> form").submit(function() {
			jQuery(this).find("input, select").each(function() {
				if(jQuery(this).val() == '') {
					jQuery(this).attr("name", "");
				}
			});
		});
		<?php if($auto_submit) : ?>
		jQuery("#ExtendedFilterContainer<?php echo $module->id; ?> select, #ExtendedFilterContainer<?php echo $module->id; ?> input[type=checkbox]").change(function() {
			submit_form_<?php echo $module->id; ?>();
		});
		<?php endif; ?>
		
		//multi select box
		jQuery(".filter-field-char-multi select, .filter-field-attr-multi select").multiselect({
			selectedList: 4,
			checkAllText: '<?php echo JText::_("MOD_JSHOP_EFILTER_CHECK_ALL_TEXT"); ?>',
			uncheckAllText: '<?php echo JText::_("MOD_JSHOP_EFILTER_UNCHECK_ALL_TEXT"); ?>',
			noneSelectedText: '<?php echo JText::_("MOD_JSHOP_EFILTER_SELECT_OPTIONS_TEXT"); ?>'
		});
		
		jQuery(".filter-field-category-multi select").multiselect({
			selectedList: 4,
			checkAllText: '<?php echo JText::_("MOD_JSHOP_EFILTER_CHECK_ALL_TEXT"); ?>',
			uncheckAllText: '<?php echo JText::_("MOD_JSHOP_EFILTER_UNCHECK_ALL_TEXT"); ?>',
			noneSelectedText: '<?php echo JText::_("MOD_JSHOP_EFILTER_SELECT_CATEGORY_OPTIONS_TEXT"); ?>'
		});
		
		jQuery(".filter-field-manufacturer-multi select").multiselect({
			selectedList: 4,
			checkAllText: '<?php echo JText::_("MOD_JSHOP_EFILTER_CHECK_ALL_TEXT"); ?>',
			uncheckAllText: '<?php echo JText::_("MOD_JSHOP_EFILTER_UNCHECK_ALL_TEXT"); ?>',
			noneSelectedText: '<?php echo JText::_("MOD_JSHOP_EFILTER_SELECT_MANUFACTURERS_OPTIONS_TEXT"); ?>'
		});
		
		jQuery(".filter-field-label-multi select").multiselect({
			selectedList: 4,
			checkAllText: '<?php echo JText::_("MOD_JSHOP_EFILTER_CHECK_ALL_TEXT"); ?>',
			uncheckAllText: '<?php echo JText::_("MOD_JSHOP_EFILTER_UNCHECK_ALL_TEXT"); ?>',
			noneSelectedText: '<?php echo JText::_("MOD_JSHOP_EFILTER_SELECT_LABEL_OPTIONS_TEXT"); ?>'
		});
	});

	function submit_form_<?php echo $module->id; ?>() {
		jQuery("#ExtendedFilterContainer<?php echo $module->id; ?> form").find("input, select").each(function() {
			if(jQuery(this).val() == '') {
				jQuery(this).attr("name", "");
			}
		});
		jQuery("#ExtendedFilterContainer<?php echo $module->id; ?> form").submit();
	}

</script>

<div id="ExtendedFilterContainer<?php echo $module->id; ?>" class="ExtendedFilterContainer">
	
	<form name="ExtendedFilter<?php echo $module->id; ?>" action="<?php echo JRoute::_('index.php?option=com_jshopping&controller=search&task=result'); ?>" method="get">
	
  		<?php $app =& JFactory::getApplication(); if (!$app->getCfg('sef')): ?>
		<input type="hidden" name="option" value="com_jshopping" />
		<input type="hidden" name="controller" value="search" />
		<input type="hidden" name="task" value="result" />
		<?php endif; ?>
	
		<?php $counter = 0; ?>
		<?php foreach($list as $k=>$filter) : ?>
			
			<?php
				
				$cell_style = '';
				if($cols > 1) {
					$width = number_format(96 / $cols, 0);
					$cell_style = " style='float: left; width: ".$width."%;'";
				}
			
			?>
			
			<?php if(@$skip_div != 1) : ?>
			<div class="filter-cell filter-cell<?php echo $k; ?>"<?php echo $cell_style; ?>>
			<?php else : ?>
				<?php $skip_div = 0; ?>
			<?php endif; ?>
			
			<?php
			
			switch($filter->type) {
				case "price" :
					if(@$filter->slider == 1) {
						require (JModuleHelper::getLayoutPath('mod_jshopping_extended_filter', $getTemplate.DS.'template_price_slider'));
					}
					else {
						require (JModuleHelper::getLayoutPath('mod_jshopping_extended_filter', $getTemplate.DS.'template_price'));
					}
				break;
				
				case "title_text" :
					require (JModuleHelper::getLayoutPath('mod_jshopping_extended_filter', $getTemplate.DS.'template_title_text'));
				break;
				
				case "title_az" :
					require (JModuleHelper::getLayoutPath('mod_jshopping_extended_filter', $getTemplate.DS.'template_title_az'));
				break;
				
				case "categories" :
					$categories = modJShopExtendedFilterHelper::buildTreeCategory($restrict, $restcat, $restsub);
					require (JModuleHelper::getLayoutPath('mod_jshopping_extended_filter', $getTemplate.DS.'template_category_multi'));
				break;
				
				case "manufacturer" :
					$manufacturers = modJShopExtendedFilterHelper::getAllManufacturers();
					require (JModuleHelper::getLayoutPath('mod_jshopping_extended_filter', $getTemplate.DS.'template_manufacturers_multi'));
				break;
				
				case "labels" :
					$labels = modJShopExtendedFilterHelper::getLabels();
					require (JModuleHelper::getLayoutPath('mod_jshopping_extended_filter', $getTemplate.DS.'template_labels_multi'));
				break;
				
				case "date" :
					require (JModuleHelper::getLayoutPath('mod_jshopping_extended_filter', $getTemplate.DS.'template_date'));
				break;
				
				case "characteristic" :
					$characteristic = modJShopExtendedFilterHelper::getCharacteristic($filter->id);
					
					if($restrict == 1) {
						if(!$characteristic->allcats == 1) {
							$cats = Array();
							$temp = explode("s", $characteristic->cats);
							foreach($temp as $tmp) {
								$catid = explode("\"", $tmp);
								if(count($catid) > 1) {
									$catid = $catid[1];
									$cats[] = $catid;
								}
							}
						
							$checker = 0;
							$restcats = explode(",", $restcat);
							
							foreach($restcats as $catid) {
								if(in_array($catid, $cats)) {
									$checker = 1;
								}
							}
							
							if($checker == 0) {
								$skip_div = 1;
								continue 2;
							}
						}
					}
					
					if($characteristic->type == 1) {
						if(@$filter->slider == 1) {
							require (JModuleHelper::getLayoutPath('mod_jshopping_extended_filter', $getTemplate.DS.'template_characteristic_slider'));
						}
						else {
							require (JModuleHelper::getLayoutPath('mod_jshopping_extended_filter', $getTemplate.DS.'template_characteristic_text'));
						}
					}
					else if($characteristic->type == 0 && $characteristic->multilist == 0) {
						$char_vals = modJShopExtendedFilterHelper::getCharacteristicValues($filter->id);
						//require (JModuleHelper::getLayoutPath('mod_jshopping_extended_filter', $getTemplate.DS.'template_characteristic_select'));
						require (JModuleHelper::getLayoutPath('mod_jshopping_extended_filter', $getTemplate.DS.'template_characteristic_multi'));
					}
					else if($characteristic->type == 0 && $characteristic->multilist == 1) {
						$char_vals = modJShopExtendedFilterHelper::getCharacteristicValues($filter->id);
						require (JModuleHelper::getLayoutPath('mod_jshopping_extended_filter', $getTemplate.DS.'template_characteristic_multi'));
					}
				break;
				
				case "attribute" :
					$attribute = modJShopExtendedFilterHelper::getAttribute($filter->id);
					
					if($restrict == 1) {
						if(!$attribute->allcats == 1) {
							$cats = Array();
							$temp = explode("s", $attribute->cats);
							foreach($temp as $tmp) {
								$catid = explode("\"", $tmp);
								$catid = $catid[1];
								$cats[] = $catid;
							}
						
							$checker = 0;
							$restcats = explode(",", $restcat);
							
							foreach($restcats as $catid) {
								if(in_array($catid, $cats)) {
									$checker = 1;
								}
							}
							
							if($checker == 0) {
								$skip_div = 1;
								continue 2;
							}
						}
					}
					
					$attr_vals = modJShopExtendedFilterHelper::getAttributeValues($filter->id);

					if($attribute->attr_type == 1) {
						require (JModuleHelper::getLayoutPath('mod_jshopping_extended_filter', $getTemplate.DS.'template_attribute_multi'));
					}
					else if($attribute->attr_type == 2) {
						require (JModuleHelper::getLayoutPath('mod_jshopping_extended_filter', $getTemplate.DS.'template_attribute_radio'));
					}
				break;

			}
			
			?>
			
			</div>
			
			<?php 
			
				if($cols > 1) {
					if((($counter+1) % $cols == 0) && (($counter+1) != $k)) {
						echo "<div class='clear'></div>";
					}
				}
				$counter++;
			
			?>
			
		<?php endforeach; ?>
		
		<div class='clear'></div>
		
		<?php if($button || $clear_btn) : ?>
		<p></p>
		<div class="filter-cell filter-cell-submit">
			<?php if($button) : ?>
				<input type="submit" value="<?php echo $button_text; ?>" class="button submit <?php echo $moduleclass_sfx; ?>" />
			<?php endif; ?>

			<?php if ($clear_btn):?>
				<script type="text/javascript">
					<!--
					function clearSearch_<?php echo $module->id; ?>() {
						jQuery("#ExtendedFilterContainer<?php echo $module->id; ?> form select").each(function () {
							jQuery(this).val(-1);
						});
						
						jQuery(".filter-field-attr-multi select").each(function() {
							jQuery(this).multiselect("uncheckAll");
						});	
						jQuery(".filter-field-char-multi select").each(function() {
							jQuery(this).multiselect("uncheckAll");
						});		
						
						jQuery(".filter-field-category-multi select").multiselect("uncheckAll");
						
						jQuery(".filter-field-manufacturer-multi select").multiselect("uncheckAll");
						
						jQuery(".filter-field-label-multi select").multiselect("uncheckAll");
									
						jQuery("#ExtendedFilterContainer<?php echo $module->id; ?> form input.inputbox").each(function () {
							jQuery(this).val("");
						});		

						jQuery(".filter-field-char-slider").each(function() {
							var slider_min = jQuery(this).find('.ui-slider').slider("option", "min");
							var slider_max = jQuery(this).find('.ui-slider').slider("option", "max");
							jQuery(this).find('.ui-slider').slider("values", 0, slider_min);
							jQuery(this).find('.ui-slider').slider("values", 1, slider_max);
						});
						jQuery("#ExtendedFilterContainer<?php echo $module->id; ?> form input.slider_val").each(function () {
							jQuery(this).val("");
						});
								
						jQuery("#ExtendedFilterContainer<?php echo $module->id; ?> form input[type=checkbox]").each(function () {
							jQuery(this).removeAttr('checked');
						});						
						
						jQuery("#ExtendedFilterContainer<?php echo $module->id; ?> form input[type=radio]").each(function () {
							jQuery(this).removeAttr('checked');
						});	

						//jQuery("#ExtendedFilterContainer<?php echo $module->id; ?> form").submit();
					}
					//-->
				</script>	

				<input type="button" value="<?php echo JText::_('MOD_JSHOP_EFILTER_BUTTON_CLEAR'); ?>" class="button submit <?php echo $moduleclass_sfx; ?>" onclick="clearSearch_<?php echo $module->id; ?>()" />
			<?php endif; ?>
		</div>
		<?php endif; ?>
		
		<input name="extended" value="1" type="hidden" />

		<?php if($restrict == 1 && $restmode == 1) : ?>
		<input name="restcata" value="<?php echo $restcat; ?>" type="hidden" />
		<?php endif; ?>
		
		<input name="orderby" value="<?php echo JRequest::getVar('orderby'); ?>" type="hidden" />
		<input name="orderto" value="<?php echo JRequest::getVar('orderto'); ?>" type="hidden" />
		<input name="limit" value="<?php echo JRequest::getVar('limit'); ?>" type="hidden" />
		
		<input name="moduleId" value="<?php echo $module->id; ?>" type="hidden" />
		<input name="Itemid" value="<?php echo(JRequest::getVar("Itemid") == "101" ? "111" : JRequest::getVar("Itemid")); ?>" type="hidden" />
	
	</form>
	
	<div class="clear"></div>
</div>