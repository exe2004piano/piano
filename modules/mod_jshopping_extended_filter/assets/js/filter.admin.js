
jQuery.noConflict();

jQuery(document).ready(function() {

	var filtersVal = jQuery("#FiltersListVal");
	if(filtersVal.val() != '') {
		var filterValues = filtersVal.val().split("\n");
		for(var i = 0; i < filterValues.length; i++) {
			var title = filterValues[i].split(":")[1];
			jQuery("#sortableFields").append("<li><span class='val' rel='"+filterValues[i]+"'>"+ title +"</span><span class='deleteFilter'>x</span></li>");
		}
	}
	
	jQuery("#sortableFields").sortable({
		update: updateFiltersVal
	});
	
	jQuery("#sortableFields .deleteFilter").live('click', function() {
		jQuery(this).parent().remove();
		updateFiltersVal();
	});
	
	jQuery('.FilterSelect').change(function() {
	
		var selected = jQuery(this).find('option:selected');
		
		if(selected.val() != '' && selected.val() != 0) {
			jQuery("#sortableFields").append("<li><span class='val' rel='"+selected.val()+"'>"+ selected.val().split(":")[1] +"</span><span class='deleteFilter'>x</span></li>");
			
			updateFiltersVal();
		}
		
		jQuery('.FilterSelect').val(0);
		
	});
	
});

function updateFiltersVal() {
	var FiltersVal = '';
	jQuery("#sortableFields li span.val").each(function(count) {
		if(count > 0) {
			FiltersVal = FiltersVal + "\r\n";
		}
		FiltersVal = FiltersVal + jQuery(this).attr("rel");
	});
	jQuery("#FiltersListVal").val(FiltersVal);
}