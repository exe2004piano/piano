<?php
// no direct access
defined('_JEXEC') or die('Restricted access');

$checked = JRequest::getVar("manufacturer");

?>
	
	<div class="filter-field-manufacturer-multi">
		<span class="h3_1">
			<?php echo JText::_('MOD_JSHOP_EFILTER_FIELDS_PRODUCT_MANUFACTURER_TITLE'); ?>
		</span>
		
		<select name="manufacturer[]" multiple="multiple">		
		<?php
			if($manufacturers) {
				foreach($manufacturers as $manufacturer) {
					$selected = '';
					if($checked) {
						foreach ($checked as $check) {
							if ($check == $manufacturer->manufacturer_id) $selected = ' selected="selected"';
						}
					}
					echo "<option value='".$manufacturer->manufacturer_id."'".$selected.">".$manufacturer->name."</option>";
				}
			}
		?>		
		</select>		
	</div>