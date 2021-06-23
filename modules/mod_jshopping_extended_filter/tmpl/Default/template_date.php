<?php
// no direct access
defined('_JEXEC') or die('Restricted access');
?>

<script type="text/javascript">
	jQuery(document).ready(function () {
		jQuery("input.datepicker").datepicker({ dateFormat: 'yy-mm-dd' });
	});
</script>

	<div class="k2filter-field-date">
	
		<span class="h3_1">
			<?php echo JText::_('MOD_JSHOP_EFILTER_FIELDS_PRODUCT_DATE_TITLE'); ?>
		</span>

		<input style="width: 40%; max-width: 100px;" class="datepicker inputbox" name="date-from" type="text" <?php if (JRequest::getVar('date-from')) echo ' value="'.JRequest::getVar('date-from').'"'; ?> /> - 
		<input style="width: 40%; max-width: 100px;" class="datepicker inputbox" name="date-to" type="text" <?php if (JRequest::getVar('date-to')) echo ' value="'.JRequest::getVar('date-to').'"'; ?> />
	</div>

