<?php
// no direct access
defined('_JEXEC') or die('Restricted access');
?>
	
	<div class="filter-field-title-text">
		<span class="h3_1">
			<?php echo JText::_('MOD_JSHOP_EFILTER_FIELDS_PRODUCT_TITLE_TITLE'); ?>
		</span>
		
		<input class="inputbox" style="width: 100%; max-width: 218px; text-align: left;" name="title" type="text" <?php if (JRequest::getVar('title')) echo ' value="'.JRequest::getVar('title').'"'; ?> />
	</div>