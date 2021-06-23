<?php
// no direct access
defined('_JEXEC') or die('Restricted access');
?>
	
	<div class="filter-field-label-select">
		<span class="h3_1">
			<?php echo JText::_('MOD_JSHOP_EFILTER_FIELDS_PRODUCT_LABEL_TITLE'); ?>
		</span>
		
		<select name="label">
			<option value=''><?php echo JText::_('MOD_JSHOP_EFILTER_SEARCH_ALL_LABELS'); ?></option>
		
		<?php
			if($labels) {
				foreach($labels as $label) {
					$selected = '';
					if(JRequest::getVar("label") == $label->id) {
						$selected = ' selected=selected';
					}
					echo "<option value='".$label->id."'".$selected.">".$label->name."</option>";
				}
			}
		?>		
		</select>
		
	</div>