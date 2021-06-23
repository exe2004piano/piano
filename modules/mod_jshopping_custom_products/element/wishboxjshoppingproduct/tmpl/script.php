<?php
	
	defined('JPATH_PLATFORM') or die;
	
	// 
	JHtml::_('behavior.modal', 'a.modal_'.$this->field->id);
	// 
	JHtml::_('jquery.ui', array('core', 'sortable'));
?>
<style>
	.portlet
	{
		margin: 0 1em 1em 0;
		padding: 0.3em;
	}
	
	.portlet-header
	{
		padding: 0.2em 0.3em;
		margin-bottom: 0.5em;
		position: relative;
	}
	
	.portlet-toggle
	{
		position: absolute;
		top: 50%;
		right: 0;
		margin-top: -8px;
	}
	
	.portlet-content
	{
		padding: 0.4em;
	}
	
	.portlet-placeholder
	{
		border: 1px dotted black;
		margin: 0 1em 1em 0;
		height: 50px;
	}

	#wishboxjshoppingproduct_<?php echo $this->field->fieldname; ?>_products
	{
		list-style-type: none; margin: 0; padding: 0; width: 60%;
	}
	
	#wishboxjshoppingproduct_<?php echo $this->field->fieldname; ?>_products li
	{
		margin: 0 5px 5px 5px; padding: 5px; font-size: 1.2em; height: 1.5em;
	}
	
	
	html > body #wishboxjshoppingproduct_<?php echo $this->field->fieldname; ?>_products li
	{
		height: 1.5em; line-height: 1.2em;
	}
	
	
	.ui-state-highlight
	{
		height: 1.5em; line-height: 1.2em;
	}
</style>
<link rel="stylesheet" href="//code.jquery.com/ui/1.10.4/themes/smoothness/jquery-ui.css">
<script type="text/javascript">
	function jSelectProduct_<?php echo $this->field->id; ?>(product_id, product_name)
	{
		// 
		var portlet = jQuery('<div class="portlet"><div class="portlet-header">' + product_name + '</div><div class="portlet-content"><input name="<?php echo $this->field->name; ?>[' + product_id + '][product_name]" type="hidden" value="' + product_name + '" /><input name="<?php echo $this->field->name; ?>[' + product_id + '][title]" type="text" value="' + product_name + '" /></div></div>');
		// 
		portlet.addClass("ui-widget ui-widget-content ui-helper-clearfix ui-corner-all")
			.find(".portlet-header")
			.addClass("ui-widget-header ui-corner-all")
			.prepend("<span class='ui-icon ui-icon-close portlet-toggle'></span>");
		jQuery('#wishboxjshoppingproduct_<?php echo $this->field->fieldname; ?>_products').append(portlet);
		// 
		SqueezeBox.close();
	}
	
	
	jQuery(document).ready
	(
		function()
		{
			jQuery("#wishboxjshoppingproduct_<?php echo $this->field->fieldname; ?>_products").sortable
			(
				{
					cursor: 'move',
					handle: '.portlet-header',
					placeholder: "portlet-placeholder ui-corner-all"
					
				}
			);
			
			jQuery(".portlet").addClass("ui-widget ui-widget-content ui-helper-clearfix ui-corner-all")
			.find(".portlet-header")
			.addClass("ui-widget-header ui-corner-all")
			.prepend("<span class='ui-icon ui-icon-close portlet-toggle'></span>");
			
			// Обработчик клика по кнопке удаления поля
			jQuery(document).on
			(
				'click',
				'.portlet-header .ui-icon-close',
				function()
				{
					// 
					jQuery(this).parent().parent().remove();
				}
			);
			
			// 
			jQuery('.portlet-header').disableSelection();
		}
	);
</script>