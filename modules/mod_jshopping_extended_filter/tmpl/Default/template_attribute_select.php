<?php
// no direct access
defined('_JEXEC') or die('Restricted access');
?>
	
	<div class="filter-field-attr-select">
		<span class="h3_1">
			<?php echo $filter->title; ?>
		</span>
		
		<select name="attr<?php echo $filter->id; ?>">
			
			<option value=""><?php echo '--- '.$filter->title.' ---'; ?></option>
			
			<?php
			if($attr_vals) {
				foreach ($attr_vals as $value) {
					echo '<option value="'.$value->value_id.'" ';
					if (JRequest::getVar('attr'.$filter->id) == $value->value_id) {echo 'selected="selected"';}
					echo '>'.$value->name.'</option>';
				}
			}
			?>
			
		</select>
		
	</div>