/**
 * Main JavaScript file
 *
 * @package         Tabs
 * @version         3.1.3
 *
 * @author          Peter van Westen <peter@nonumber.nl>
 * @link            http://www.nonumber.nl
 * @copyright       Copyright © 2012 NoNumber All Rights Reserved
 * @license         http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
 */

(function($)
{
	nnTabs = {
		show: function(id, scroll)
		{
			$('a[href="#' + id + '"]').tab('show');
		},
	}

	$(document).ready(function()
	{
		if (nn_tabs_use_hash) {
			if (window.location.hash) {
				id = window.location.hash.replace('#', '');
				if(!nn_tabs_urlscroll && $('.nn_tabs > .tab-content > #' + id).length > 0) {
					// scroll to top to prevent browser scrolling
					$('html,body').animate({ scrollTop: 0 });
				}
				nnTabs.show(id, nn_tabs_urlscroll);
			}
			$('.nn_tabs-tab a[data-toggle="tab"]').on('show', function(e)
			{
				window.location.hash = String(e.target).substr(String(e.target).indexOf("#") + 1);
				e.stopPropagation();
			});
		}
	});
})(jQuery);
