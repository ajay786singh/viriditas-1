/*Tabs*/
jQuery(document).ready(function(e) {
	jQuery('.tab-header a').click(function(){

	var tabs_container_id = jQuery(this).parents('div:eq(0)').attr('id');
	var id=jQuery(this).attr("href");
	jQuery('#'+tabs_container_id+' .tab-header a').each(function(index, element) {

	jQuery(this).removeClass('active');	
	});
	jQuery(this).addClass('active');
	jQuery('#'+tabs_container_id+' .tab').each(function(index, element) {
	jQuery(this).removeClass('active-tab');	
	});
	jQuery(id).addClass('active-tab');
	return false;	
	});
});