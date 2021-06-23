<?php
// no direct access
defined('_JEXEC') or die('Restricted access');
?>
	
	<div class="filter-field-char-text">
		<span class="h3_1">
			<?php echo $filter->title; ?>
		</span>
		
		<input class="inputbox" style="width: 100%; max-width: 218px; text-align: left;" name="char<?php echo $filter->id; ?>" type="text" <?php if (JRequest::getVar('char'.$filter->id)) echo ' value="'.JRequest::getVar('char'.$filter->id).'"'; ?> />
	</div>