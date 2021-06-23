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
	
	.groups_container
	{
		
	}
	
	.groups
	{
		
	}
	
	.group_container
	{
		background: #ddeeee;
		display: block;
		margin-left: 5px;
		min-width: 225px;
		padding: 0px 0 175px 0;
		position: relative;
	}
	
	.group
	{
		padding: 5px 5px 30px 5px;
	}
	
	.group_container .group_header
	{
		background: #eeeeee;
		cursor: move,
		height: 8px;
		padding: 4px 0 11px 0;
		position: relative;
		text-align: center;
		text-transform: uppercase;
		width: 100%;
	}
	
	.group_container .group_header .ui-icon-close
	{
		position: absolute;
		right: 3px;
		top: 3px;
	}
	
	.group_container .group_params
	{
		display: block;
		padding: 4px;
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
	function jSelectProduct_<?php echo $this->field->id; ?>(product_id, product_name, column_index)
	{
		// 
		var portlet = jQuery('<div class="portlet"><div class="portlet-header">' + product_name + '</div><div class="portlet-content"><input name="<?php echo $this->field->name; ?>[groups][' + column_index + '][products][' + product_id + '][product_name]" type="hidden" value="' + product_name + '" /><input name="<?php echo $this->field->name; ?>[groups][' + column_index + '][products][' + product_id + '][title]" type="text" value="' + product_name + '" /></div></div>');
		// 
		portlet.addClass("ui-widget ui-widget-content ui-helper-clearfix ui-corner-all")
			.find(".portlet-header")
			.addClass("ui-widget-header ui-corner-all")
			.prepend("<span class='ui-icon ui-icon-close portlet-toggle'></span>");
		jQuery('.groups_container .groups .group_container:eq(' + column_index + ') .group .products').append(portlet);
		// 
		SqueezeBox.close();
	}
	
	
	jQuery(document).ready
	(
		function()
		{
			jQuery('.products').sortable
			(
				{
					cursor: 'move',
					handle: '.portlet-header'
				}
			);
			
			jQuery(".portlet").addClass("ui-widget ui-widget-content ui-helper-clearfix ui-corner-all")
			.find(".portlet-header")
			.addClass("ui-widget-header ui-corner-all")
			.prepend("<span class='ui-icon ui-icon-close portlet-toggle'></span>");
			
			// Добавляем кнопку удаления новому последнему столбцу
			jQuery('.groups .group_container:last-child .group_header').append('<span class="ui-icon ui-icon-close"></span>');
			
			// Обработчик клика по кнопке удаления товара
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
			
			// Обработчик клика по кнопке добавления группы
			jQuery('.add_group').click
			(
				function()
				{
					// 
					jQuery('.group_header .ui-icon-close').remove();
					// 
					jQuery('.groups').append(jQuery('<div class="group_container"><div class="group"><div class="group_header">' + jQuery('.groups .group').length + '<span class="ui-icon ui-icon-close"></span></div><fieldset class="group_params"><label>Заголовок:</label><input class="input-small" name="<?php echo $this->field->name; ?>[groups][' + jQuery('.groups .group').length + '][title]" type="text" value="" /><label>Высота:</label><input class="input-small" name="<?php echo $this->field->name; ?>[groups][' + jQuery('.groups .group').length + '][height]" type="text" value="" /><div>сьдесе буди редахторъ</div></div></fieldset></div></div>'));
					// 
					jQuery('.products').sortable
					(
						{
							cursor: 'move',
							handle: '.ui-icon-arrow-4-diag'
						}
					);
					
				}
			);
			
			// Обработчик клика по кнопке удаления группы
			jQuery(document).on
			(
				'click',
				'.group_header > .ui-icon-close',
				function()
				{
					// Удаляем столбец
					jQuery(this).parent().parent().parent().remove();
					// Добавляем кнопку удаления новому последнему столбцу
					jQuery('.groups .group_container:last-child .group_header').append('<span class="ui-icon ui-icon-close"></span>');
				}
			);
		}
	);
</script>