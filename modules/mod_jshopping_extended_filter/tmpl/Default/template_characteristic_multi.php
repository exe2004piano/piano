<?php
// no direct access
defined('_JEXEC') or die('Restricted access');

$checked = JRequest::getVar("char".$filter->id);

?>
	
	<div class="filter-field-char-multi">
		<span class="h3_1">
			<?php echo $filter->title; ?>
		</span>
		
		<select name="char<?php echo $filter->id; ?>[]" multiple="multiple">			
		<?php
		if($char_vals) {
			foreach ($char_vals as $value) {
				$selected = '';
				if($checked) {
					foreach ($checked as $check) {
						if ($check == $value->id) $selected = ' selected="selected"';
					}
				}
				echo "<option value='".$value->id."'".$selected.">".$value->name."</option>";
			}
		}
		?>			
		</select>	
	</div>