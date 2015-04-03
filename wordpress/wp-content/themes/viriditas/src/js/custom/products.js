var parseQueryString = function( queryString,parameter ) {
    var params = {}, queries, temp, i, l;
	var url = [];
    // Split into key/value pairs
    queries = queryString.split("&");
	// Convert the array of strings into an object
    for ( i = 0, l = queries.length; i < l; i++ ) {
        temp = queries[i].split('=');
		if(temp[0]!=parameter) {
			params[temp[0]] = temp[1];
		}
    }
	$.each( params, function( key, value ) {
		url.push(key+"="+value);
	});
	return url.join("&");
};
function getParameterByName(name) {
    name = name.replace(/[\[]/, "\\[").replace(/[\]]/, "\\]");
    var regex = new RegExp("[\\?&]" + name + "=([^&#]*)"),
        results = regex.exec(location.search);
    return results === null ? "" : decodeURIComponent(results[1].replace(/\+/g, " "));
}
function replaceParam(key, value){
	var pathname = window.location.pathname;
	var params = toParams(window.location.search);
	params[key] = value;
	return pathname + "?" + jQuery.param(params)
}
function removeURLParameter(parameter) {
    //prefer to use l.search if you have a location/link object
	var url = window.location.search;
	url = url.substring(1);
	url=parseQueryString(url,parameter);
	return "?"+url;
}
function toParams(searchUrl) {
	var result = {}
	if(searchUrl == '')
	return result;

	var queryString = searchUrl.substr(1);

	var params = queryString.split("&");

	jQuery.each(params, function(index, param){
		var keyPair = param.split("=");

		var key = keyPair[0];
		var value = keyPair[1];

		if(result[key] == undefined)
		  result[key] = value
		else{

		  if(result[key] instanceof Array) //current var is an array just push another to it
			result[key].push(value)
		  else{ //duplicate var, then it must store as an array
			result[key] = [result[key]]
			result[key].push(value)
		  }
		}
	})
	return result;
}

(function($) {
    $.fn.showProducts = function( options ) {
        // Establish our default settings
		$('.list-products').show();
		$('.single-product-detail').empty().hide();
		
        var settings = $.extend({
            container    : $(this),
			page         : 1,
			category     : getParameterByName('pc'),
            body_system  : getParameterByName('pb'),
            indication   : getParameterByName('pi'),
			action 		 : getParameterByName('pa'),
			sort_by_name : '',
			loader    	 : $('.message')
        }, options);
		settings.loader.empty().loaderShow();
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
				if(html!='' && html!=1) {
					settings.container.append(html);
					settings.container.showProduct();
					$('.load-more').loadMore();
				}else if(html==1) {
					settings.loader.html("<h6>No records found.</h6>");
				}
			}
		});	
    },
	$.fn.loaderShow = function() {
		$(this).addClass('loader');
	},
	$.fn.loaderHide = function() {
		$(this).removeClass('loader');
	},
	$.fn.loadMore = function() {
		var $this=$(this), page=0;
		$this.find('a').click(function(){
				page = $('#current-page').val();
				page++;
				$('#current-page').val(page);
				var body_system='',indication='';
				if($('.by-body_system .dk-select-options .dk-option-selected').length){
					body_system=$('.by-body_system .dk-select-options .dk-option-selected').attr('data-value');
				}
				if($('.by-indication .dk-select-options .dk-option-selected').length){
					indication=$('.by-indication .dk-select-options .dk-option-selected').attr('data-value');
				}
				$('.product-list').showProducts({page:page});
				$this.remove();
			return false;
		});
	},
	$.fn.filterCategory = function() {
		$(this).change(function(e){
			var category=$(this).val();
			var url = replaceParam('pc', category);
			window.history.pushState({path:url},'',url);
			$('section[role="body-systems"]').fetchBodysystems(category);
			$('section[role="indications"]').fetchIndications(category);			
			var url=removeURLParameter('pb');					
			window.history.pushState({path:url},'',url);
			var url=removeURLParameter('pa');					
			window.history.pushState({path:url},'',url);
			var url=removeURLParameter('pi');					
			window.history.pushState({path:url},'',url);
			$('.product-list').empty();
			$('.product-list').showProducts({page:1});
		});
	},
	$.fn.filterBodysystems = function(body_system) {
		var url = replaceParam('pb', body_system);
		window.history.pushState({path:url},'',url);
		var url=removeURLParameter('pa');					
		window.history.pushState({path:url},'',url);
		$('.product-list').empty();
		$('.product-list').showProducts({page:1});
	},
	$.fn.filterIndications = function(indication) {
		$('.product-list').empty();
		$('.product-list').showProducts({page:1});
	},
	$.fn.fetchBodysystems = function(category) {
		var $this = $(this);
		$this.empty();
		$('section[role="actions"]').empty();
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
							$('section[role="actions"]').fetchActions(category,dk.value);
							$this.filterBodysystems(dk.value);
						}
					});
				}	
			}
		});			
	},
	$.fn.fetchActions = function(category,body_system) {
		var $this = $(this);
		$this.empty();
		$this.addClass('small-loader');
		$.ajax({
			type: 'POST',
			url: ajaxurl,
			data:{action: 'get_actions','category':category,'body_system':body_system },
			success: function(html) {
				$this.removeClass('small-loader');
				if(html!=''){
					$this.append(html);
					 $this.find('ul li a').click(function(){
						$(this).toggleClass('checked');
						var actions = [];
						$this.find('ul li a').each(function(){
							if($(this).hasClass('checked')==true) {
								var val=$(this).attr('data-value');
								actions.push(val);
							}
						});
						if(actions !='') {
							var url = replaceParam('pa', decodeURIComponent(actions.join(",")));
							window.history.pushState({path:url},'',url);
						} else {
							var url=removeURLParameter('pa');					
							window.history.pushState({path:url},'',url);
						}					
						$('.product-list').empty();
						$('.product-list').showProducts({page:1});
						return false;
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
	$.fn.getProduct= function(product_id) {
		var $this=$(this);
		$('.list-products').hide();
		$this.show().loaderShow();
		$.ajax({
			type: 'POST',
			url: ajaxurl,
			data:{action: 'get_product_detail','product_id':product_id },
			success: function(html) {
				if(html!=''){
					$this.loaderHide();
					$this.html(html);
					jQuery('.accordion-panel-header').click(function (e){
						jQuery('.accordion-panel').each(function(e) {
							jQuery(this).removeClass('active-header');
							jQuery('.accordion-panel-content').slideUp('fast').removeClass('active');
						});
						if(jQuery(this).next('.accordion-panel-content').css('display') != 'block'){
							jQuery('.active').slideUp('fast').removeClass('active');
							jQuery(this).addClass('active-header');
							jQuery(this).next('.accordion-panel-content').addClass('active').slideDown('slow');
							
						} else {
							jQuery('.active').slideUp('fast').removeClass('active');
							jQuery(this).removeClass('active-header');
						}
					});
					$('.back-to-results').click(function(){				
						var url=removeURLParameter('show_product');					
						window.history.pushState({path:url},'',url);
						$('.list-products').show();
						$this.empty().hide();
						return false;
					});
				}	
			}
		});
	}
	$.fn.showProduct= function() {
		var $this =$(this);
		$this.find('li a').click(function(){
			var rel=$(this).attr('rel');
			var url = replaceParam('show_product', rel);
			window.history.pushState({path:url},'',url);
			var product_id = getParameterByName('show_product');
			$('.single-product-detail').getProduct(product_id);
			return false;
		});
	}
}(jQuery));

jQuery(document).ready(function($){
	if($('body').hasClass('post-type-archive-product')) {
		var product_container = $('.product-list');
		$('select.by-category').dropkick({
			mobile: true
		});
		var current_category=$('.by-category .dk-select-options .dk-option-selected').attr('data-value');
		var pc=getParameterByName('pc');
		var show_product=getParameterByName('show_product');
		if(pc=='') {
			var url = replaceParam('pc', current_category);
			window.history.pushState({path:url},'',url);	
		}
		if(show_product!='') {
			$('.single-product-detail').getProduct(show_product);
		} else {
			product_container.showProducts();
		}
		$('.by-category').filterCategory();
		$('section[role="body-systems"]').fetchBodysystems(current_category);
		$('section[role="actions"]').fetchActions(current_category);
		$('section[role="indications"]').fetchIndications(current_category);
	}	
});