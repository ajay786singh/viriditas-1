jQuery.urlParam = function(name){
	var results = new RegExp('[\?&]' + name + '=([^&#]*)').exec(window.location.href);
	if (results==null){
	   return null;
	}
	else{
	   return results[1] || 0;
	}
}
function get_products(){
	var c = $('.product-list');
		var category=$.urlParam('filter-type-category'); 
		c.empty();
		$.ajax({
			type: 'POST',
			url: ajaxurl,
			data: {action: 'load_products',filter_type_category:category },
			success: function(response) {
				c.empty().html(response);
				c.find('li .product-title').equalHeights();	
				return false;
			}
		});
}

jQuery(document).ready(function($){
	
	get_products();
	$('.filter.by-category li a').click(function(){
		var	pageurl = $(this).attr('href');
		if(pageurl!=window.location){
			window.history.pushState({path:pageurl},'',pageurl);
		}
		get_products();
		return false;
	});
});