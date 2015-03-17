jQuery.urlParam = function(name){
	var results = new RegExp('[\?&]' + name + '=([^&#]*)').exec(window.location.href);
	if (results==null){
	   return null;
	}
	else{
	   return results[1] || 0;
	}
}

(function($) {
    $.fn.displayProducts = function( options ) {
        // Establish our default settings
		$('.list-products').show();
		$('.single-product-detail').empty().hide();
        var settings = $.extend({
            container    : $(this),
			page         : 1,
			category     : $('.by-category .dk-select-options .dk-option-selected').attr('data-value'),
            body_system  : '',
            indication   : '',
			action 		 : '',
			sort_by_name : '',
			loader    	 : $('.loading')
        }, options);
		settings.loader.loaderShow();
		$.ajax({
			type: 'POST',
			url: ajaxurl,
			data:{
				action: 'load_products',
				'filter_type_category':settings.category,
				'filter_type_body_system':settings.body_system,
				'filter_type_action':settings.action,
				'filter_type_indication':settings.indication,
				'sort_by_name':settings.sort_by_name,
				'paged':settings.page 
			},
			beforeSend: function() {
				settings.loader.loaderShow();
			},
			success: function(html) {
				settings.loader.loaderHide();
				if(html) {
					settings.container.append(html);
					settings.container.showProduct();
				}
			}
		});	
    },
	$.fn.loaderShow = function() {
		$(this).show();
	},
	$.fn.loaderHide = function() {
		$(this).hide();
	},
	$.fn.filterCategory = function() {
		$(this).change(function(e){
			var category=$(this).val();
			$('section[role="body-systems"]').fetchBodysystems(category);
			$('section[role="indications"]').fetchIndications(category);
			$('.product-list').empty();
			$('.product-list').displayProducts({category:category,page:1});
			e.preventDefault();
		});
	},
	$.fn.filterBodysystems = function(body_system) {
		$('.product-list').empty();
		$('.product-list').displayProducts({body_system:body_system,page:1});
		e.preventDefault();
	},
	$.fn.filterIndications = function(indication) {
		$('.product-list').empty();
		$('.product-list').displayProducts({indication:indication,page:1});
		e.preventDefault();
	},
	$.fn.fetchBodysystems = function(category) {
		var $this = $(this);
		$this.empty();
		$('.filter-actions-items').empty();
		$this.addClass('small-loader');
		$.ajax({
			type: 'POST',
			url: ajaxurl,
			data:{action: 'get_body_systems','category':category },
			success: function(html) {
				$this.removeClass('small-loader');
				if(html!=''){
					$this.append(html);
					$this.find('select').dropkick({
						mobile: true,
						change:function() {
							var dk = this;
							$this.filterBodysystems(dk.value);
						}
					});
				}	
			}
		});			
	},
	$.fn.fetchIndications = function(category) {
		var $this = $(this);
		$this.empty();
		$this.addClass('small-loader');
		$.ajax({
			type: 'POST',
			url: ajaxurl,
			data:{action: 'get_indications','category':category },
			success: function(html) {
				$this.removeClass('small-loader');
				if(html!=''){
					$this.append(html);
					$this.find('select').dropkick({
						mobile: true,
						change:function() {
							var dk = this;
							$this.filterIndications(dk.value);
						}
					});
				}	
			}
		});			
	},
	$.fn.showProduct= function() {
		var $this =$(this);
		$this.find('li a').click(function(){
			var product_id=$(this).attr('rel');
			$('.list-products').hide();
			$('.single-product-detail').show().addClass('loader');
			$.ajax({
				type: 'POST',
				url: ajaxurl,
				data:{action: 'get_product_detail','product_id':product_id },
				success: function(html) {
					//$this.removeClass('small-loader');
					if(html!=''){
						$('.single-product-detail').removeClass('loader').append(html);
						$('.back-to-results').click(function(){												
							$('.list-products').show();
							$('.single-product-detail').empty().hide();
							return false;
						});
					}	
				}
			});
			return false;
		});
	}
}(jQuery));

jQuery(document).ready(function($){
	var product_container = $('.product-list');
	$('select.by-category').dropkick({
		mobile: true
	});
	product_container.displayProducts();
	$('.by-category').filterCategory();
	var current_category=$('.by-category .dk-select-options .dk-option-selected').attr('data-value');
	$('section[role="body-systems"]').fetchBodysystems(current_category);
	$('section[role="indications"]').fetchIndications(current_category);
});