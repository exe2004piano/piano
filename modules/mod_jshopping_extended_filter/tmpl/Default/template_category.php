<?php
// no direct access
defined('_JEXEC') or die('Restricted access');
?>
	
	<div class="filter-field-category-select">
		<span class="h3_1">
			<?php echo JText::_('MOD_JSHOP_EFILTER_FIELDS_PRODUCT_CATEGORY_TITLE'); ?>
		</span>
		
		<select name="category">
			<option value=''><?php echo JText::_('MOD_JSHOP_EFILTER_SEARCH_ALL_CATEGORIES'); ?></option>
		
		<?php
			if($categories) {
				foreach($categories as $category) {
					$selected = '';
					if(JRequest::getVar("category") == $category->category_id) {
						$selected = ' selected=selected';
					}
					echo "<option value='".$category->category_id."'".$selected.">".$category->name."</option>";
				}
			}
		?>		
		</select>
		
	</div>