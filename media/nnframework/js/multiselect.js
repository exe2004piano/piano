/**
 * MultiSelect JavaScript file
 *
 * @package         NoNumber Framework
 * @version         13.5.3
 *
 * @author          Peter van Westen <peter@nonumber.nl>
 * @link            http://www.nonumber.nl
 * @copyright       Copyright © 2012 NoNumber All Rights Reserved
 * @license         http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
 */

(function($)
{
	$(document).ready(function()
	{
		$('.nn_multiselect').each(function()
		{
			var el = $(this);
			var controls = el.find('div.nn_multiselect-controls');
			var list = el.find('ul.nn_multiselect-ul');
			var menu = el.find('div.nn_multiselect-menu-block').html();
			var maxheight = list.css('max-height');

			list.find('li').each(function()
			{
				var li = $(this);
				var div = li.find('div.nn_multiselect-item:first');

				// Add icons
				li.prepend('<i class="pull-left icon-"></i>');

				// Append clearfix
				div.after('<div class="clearfix"></div>');

				if (li.find('ul.nn_multiselect-sub').length) {
					// Add classes to Expand/Collapse icons
					li.find('i').addClass('nn_multiselect-toggle icon-minus');

					// Append drop down menu in nodes
					div.find('label:first').after(menu);

					if (!li.find('ul.nn_multiselect-sub ul.nn_multiselect-sub').length) {
						li.find('div.nn_multiselect-menu-expand').remove();
					}
				}
			});

			// Takes care of the Expand/Collapse of a node
			list.find('i.nn_multiselect-toggle').click(function()
			{
				var i = $(this);

				// Take care of parent UL
				if (i.parent().find('ul.nn_multiselect-sub').is(':visible')) {
					i.removeClass('icon-minus').addClass('icon-plus');
					i.parent().find('ul.nn_multiselect-sub').hide();
					i.parent().find('ul.nn_multiselect-sub i.nn_multiselect-toggle').removeClass('icon-minus').addClass('icon-plus');
				} else {
					i.removeClass('icon-plus').addClass('icon-minus');
					i.parent().find('ul.nn_multiselect-sub').show();
					i.parent().find('ul.nn_multiselect-sub i.nn_multiselect-toggle').removeClass('icon-plus').addClass('icon-minus');
				}
			});

			// Takes care of the filtering
			controls.find('input.nn_multiselect-filter').keyup(function()
			{
				var text = $(this).val().toLowerCase();
				list.find('li').each(function()
				{
					var li = $(this);
					if (li.text().toLowerCase().indexOf(text) == -1) {
						li.hide();
					} else {
						li.show();
					}
				});
			});

			// Checks all checkboxes in the list
			controls.find('a.nn_multiselect-checkall').click(function()
			{
				list.find('input').attr('checked', 'checked');
			});

			// Unchecks all checkboxes in the list
			controls.find('a.nn_multiselect-uncheckall').click(function()
			{
				list.find('input').attr('checked', false);
			});

			// Toggles all checkboxes in the list
			controls.find('a.nn_multiselect-toggleall').click(function()
			{
				list.find('input').each(function()
				{
					var input = $(this);
					if (input.attr('checked')) {
						input.attr('checked', false);
					} else {
						input.attr('checked', 'checked');
					}
				});
			});

			// Expands all sub-items in the list
			controls.find('a.nn_multiselect-expandall').click(function()
			{
				list.find('ul.nn_multiselect-sub').show();
				list.find('i.nn_multiselect-toggle').removeClass('icon-plus').addClass('icon-minus');
			});

			// Hides all sub-items in the list
			controls.find('a.nn_multiselect-collapseall').click(function()
			{
				list.find('ul.nn_multiselect-sub').hide();
				list.find('i.nn_multiselect-toggle').removeClass('icon-minus').addClass('icon-plus');
			});

			// Shows all selected items in the list
			controls.find('a.nn_multiselect-showall').click(function()
			{
				list.find('li').show();
			});

			// Shows all selected items in the list
			controls.find('a.nn_multiselect-showselected').click(function()
			{
				list.find('li').each(function()
				{
					var li = $(this);
					var hide = 1;
					li.find('input').each(function()
					{
						var input = $(this);
						if (input.attr('checked')) {
							hide = 0;
							return;
						}
					});
					if (hide) {
						li.hide();
					} else {
						li.show();
					}
				});
			});

			// Maximizes the list
			controls.find('a.nn_multiselect-maximize').click(function()
			{
				list.css('max-height', '');
				controls.find('a.nn_multiselect-maximize').hide();
				controls.find('a.nn_multiselect-minimize').show();
			});

			// Minimizes the list
			controls.find('a.nn_multiselect-minimize').click(function()
			{
				list.css('max-height', maxheight);
				controls.find('a.nn_multiselect-minimize').hide();
				controls.find('a.nn_multiselect-maximize').show();
			});

		});

		// Take care of children check/uncheck all
		$('div.nn_multiselect a.checkall').click(function()
		{
			$(this).parent().parent().parent().parent().parent().parent().find('ul.nn_multiselect-sub input').attr('checked', 'checked');
		});
		$('div.nn_multiselect a.uncheckall').click(function()
		{
			$(this).parent().parent().parent().parent().parent().parent().find('ul.nn_multiselect-sub input').attr('checked', false);
		});

		// Take care of children toggle all
		$('div.nn_multiselect a.expandall').click(function()
		{
			$parent = $(this).parent().parent().parent().parent().parent().parent().parent();
			$parent.find('ul.nn_multiselect-sub').show();
			$parent.find('ul.nn_multiselect-sub i.nn_multiselect-toggle').removeClass('icon-plus').addClass('icon-minus');
			;
		});
		$('div.nn_multiselect a.collapseall').click(function()
		{
			$parent = $(this).parent().parent().parent().parent().parent().parent().parent();
			$parent.find('li ul.nn_multiselect-sub').hide();
			$parent.find('li i.nn_multiselect-toggle').removeClass('icon-minus').addClass('icon-plus');
			;
		});
	});
})(jQuery);
