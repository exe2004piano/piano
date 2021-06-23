<?php
// no direct access
defined('_JEXEC') or die('Restricted access');

$checked = JRequest::getVar("label");

?>
	
	<div class="filter-field-label-multi">
		<span class="h3_1">
			<?php echo JText::_('MOD_JSHOP_EFILTER_FIELDS_PRODUCT_LABEL_TITLE'); ?>
		</span>
		
		<select name="label[]" multiple="multiple">		
		<?php
			if($labels) {
				foreach($labels as $label) {
					$selected = '';
					if($checked) {
						foreach ($checked as $check) {
							if ($check == $label->id) $selected = ' selected="selected"';
						}
					}
					echo "<option value='".$label->id."'".$selected.">".$label->name."</option>";
				}
			}
		?>		
		</select>		
	</div>