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
    $.fn.showCompound = function( options ) {
		var settings = $.extend({
            container    : $(this),
			page         : 1,
            body_system  : getParameterByName('pb'),
			action 		 : getParameterByName('pa'),
			search_folk  : getParameterByName('s'),
			sort_by : getParameterByName('sort_by'),
			loader    	 : $('.message')
        }, options);
		settings.container.empty();
		settings.container.empty().loaderShow();
		$.ajax({
			type: 'POST',
			url: ajaxurl,
			data:{
				action: 'show_compound_products',
				'filter_type_body_system':settings.body_system,
				'filter_type_action':settings.action,
				'search_folk':settings.search_folk,
			},
			success: function(html) {
				settings.container.loaderHide();
				if(html!='' && html!=1) {
					settings.container.empty().html(html);
					//settings.container.addCompound('a');
					jQuery(".compound-product").unbind('click').bind("click", function(e){
						e.preventDefault();
						var id=$(this).attr('data-id');
						var name=$(this).attr('data-name');
						if($(this).hasClass('added')==false) {
							$(this).addCompound(id,name);
						}
					});
				} else if(html==1) {
					settings.container.empty().html("<h6>No records found.</h6>");
				}
			}
		});	
	},
	$.fn.openPopup =function() {
		$(this).show();
	},
	$.fn.closePopup =function() {
		$(this).hide();
	},
	$.fn.allowNumberOnly =function(limit) {
		$(this).keypress(function(e) {
			alert(e.which);
			if (e.which != 8 && e.which != 0 && (e.which < 48 || e.which > 57)) {
				//display error message
				//$("#errmsg").html("Digits Only").show().fadeOut("slow");
				return false;
			}
        });
	},
	$.fn.addCompound = function( id,name ) {
		var number_size="size_"+id;
		var html="<li id='remove-product-"+id+"'><div class='left'><a href='#' class='remove-compound' id='remove-"+id+"'>X</a> "+name+"</div>";
			html+="<dib class='right'><input type='number' name='"+number_size+"' class='herb-sizes' id='"+number_size+"' value=''></div>";
			html+="</li>";
		var values =[];
		if($('.additions ul li').length <= 6) {
			$('#compound-'+id).addClass('added');
			$('.additions ul').append(html);
			//$('.popup-compound').openPopup();
			$('.herb-name').html(name);
			$('.additions ul li a').each(function(){
				var id = $(this).attr('id');
				id=id.replace('remove-','');
				values.push(id);
			});
			$('#compound-products').val(values);
		} else {
			alert("You can add max 7 herbs to your compound.");
		}
		jQuery(".remove-compound").unbind('click').bind("click", function(e){
			e.preventDefault();
			var id=$(this).attr('id');
			var values =[];
			id=id.replace('remove-','');
			$('#compound-'+id).removeClass('added');
			$('#remove-product-'+id).remove();
			$('.additions ul li a').each(function(){
				var id = $(this).attr('id');
				id=id.replace('remove-','');
				values.push(id);
			});
			$('#compound-products').empty().val(values);
		});	
	},
	$.fn.remvoeCompound = function( id ) {
		//var removeItem='remove-'
		$('.additions ul').find(id).remove();
	},
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
			view_mode    : getParameterByName('vm'),
			search_folk  : getParameterByName('s'),
			sort_by : getParameterByName('sort_by'),
			sort_by_alpha : getParameterByName('sort_by_alpha'),
			order : getParameterByName('order'),
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
				'search_folk':settings.search_folk,
				'sort_by':settings.sort_by,
				'sort_by_alpha':settings.sort_by_alpha,
				'order':settings.order,
				'view_mode':settings.view_mode,
				'paged':settings.page 
			},
			beforeSend: function() {
				settings.loader.loaderShow();
			},
			success: function(html) {
				settings.loader.loaderHide();
				if(html!='' && html!=1) {
					settings.container.append(html);
					//settings.container.showProduct();
					if(settings.view_mode == '' || settings.view_mode!='list_view') {
						$('.equal-height-item').equalHeights();	
					}
					$('.load-more').loadMore();
				} else if(html==1) {
					settings.loader.html("<h6>No records found.</h6>");
				}
			}
		});	
    },
	$.fn.loaderShow = function() {
		$(this).addClass('loading');
	},
	$.fn.loaderHide = function() {
		$(this).removeClass('loading');
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
	$.fn.filterSelectTerms = function(filter,val) {
		var url = replaceParam(filter, val);
		window.history.pushState({path:url},'',url);
		if($('.filter-compound').length) {
			$('.compound-content .product-list').showCompound();
		} else {
			$('.product-list').empty();
			$('.product-list').showProducts({page:1});
		}
	},
	$.fn.fetchSelectTerms = function(taxonomy,filter,active_val) {
		var $this = $(this);
		var pa=getParameterByName('pa'); //Products by Actions
		var pb=getParameterByName('pb'); //Products by Body Systems
		var pc=getParameterByName('pc'); //Products by Categories
		var pi=getParameterByName('pi'); //Products by Indications
		$this.empty();
		$this.addClass('small-loader');
		$.ajax({
			type: 'POST',
			url: ajaxurl,
			data:{action: 'get_product_terms','taxonomy':taxonomy,'pa':pa,'pb':pb,'pc':pc,'pi':pi,'active_val':active_val },
			success: function(html) {
				$this.removeClass('small-loader');
				if(html!=''){
					$this.append(html);					
					$this.find('select').dropkick({
						mobile: true,
						change:function() {
							var dk = this;
							if(taxonomy =='body_system') {
								//$('section[role="actions"]').fetchSelectTerms('actions','pa',pa);
								if(getParameterByName('pa')) {
									var url=removeURLParameter('pa');					
									window.history.pushState({path:url},'',url);
								}
								$('section[role="actions"]').fetchActions('body_system',dk.value,'pa','');
								//$('section[role="indications"]').fetchSelectTerms('indication','pi',pi);
							}
							$this.filterSelectTerms(filter,dk.value);
						}
					});
				}	
			}
		});
	},
	$.fn.fetchActions = function(taxonomy,pb,filter,active_val) {
		var $this = $(this);
		//var pb=getParameterByName('pb'); //Products by Body Systems
		$this.empty();
		$this.addClass('small-loader');
		$.ajax({
			type: 'POST',
			url: ajaxurl,
			data:{action: 'get_actions','pb':pb,'active_val':active_val},
			success: function(html) {
				$this.removeClass('small-loader');
				if(html!=''){
					$this.append(html);					
					$this.find('select').dropkick({
						mobile: true,
						change:function() {
							var dk = this;
							$this.filterSelectTerms(filter,dk.value);
							// if(taxonomy =='body_system') {
							//	$('section[role="actions"]').fetchSelectTerms('actions','pa',pa);
								// $('section[role="actions"]').fetchActions('body_system','pa',pa);
								// $('section[role="indications"]').fetchSelectTerms('indication','pi',pi);
							// } else if(taxonomy =='actions') {
								// $('section[role="indications"]').fetchSelectTerms('indication','pi',pi);
							// }
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
						$('.product-list').empty().showProducts({page:1});
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
	$.ajaxSetup({cache:false});
	if($('body').hasClass('post-type-archive-product') || $('body').hasClass('single-product')) {
		var product_container = $('.product-list');
		// Current Filter values
		var pa=getParameterByName('pa');
		var pb=getParameterByName('pb');
		var pc=getParameterByName('pc');
		var pi=getParameterByName('pi');
		$('select.by-category').dropkick({
			mobile: true,
			change:function() {
				var dk = this;
				var url = replaceParam('pc', dk.value);
				window.history.pushState({path:url},'',url);
				var url=removeURLParameter('show_product');					
				window.history.pushState({path:url},'',url);
				var url=removeURLParameter('pb');					
				window.history.pushState({path:url},'',url);
				var url=removeURLParameter('pa');					
				window.history.pushState({path:url},'',url);
				var url=removeURLParameter('pi');					
				window.history.pushState({path:url},'',url);
				$('section[role="category"]').filterSelectTerms('pc',dk.value);
				$('section[role="body-systems"]').fetchSelectTerms('body_system','pb',pb);
				//$('section[role="actions"]').fetchSelectTerms('actions','pa',pa);
				$('section[role="actions"]').fetchActions('body_system','pa',pa);
				$('section[role="indications"]').fetchSelectTerms('indication','pi',pi);
				// $('.product-list').empty();
				// $('.product-list').showProducts({page:1});
			}
		});
		var current_category=$('.by-category .dk-select-options .dk-option-selected').attr('data-value');
		var show_product=getParameterByName('show_product');
		
		if(show_product!='') {
			$('.single-product-detail').getProduct(show_product);
		} else {
			product_container.showProducts();
		}
		
		$('section[role="body-systems"]').fetchSelectTerms('body_system','pb',pb);
		$('section[role="actions"]').fetchActions('body_system',pb,'pa',pa);
		$('section[role="indications"]').fetchSelectTerms('indication','pi',pi);
		
		$('a.sort_by').click(function(){
			var id=$(this).attr('id');			
			var url = replaceParam('sort_by', id);
			window.history.pushState({path:url},'',url);
			product_container.empty();
			product_container.showProducts();
			return false;
		});
		$('a.order_by').click(function(){
			var id=$(this).attr('id');			
			var url = replaceParam('order', id);
			window.history.pushState({path:url},'',url);				
			product_container.empty();
			product_container.showProducts();
			return false;
		});
		
		$('select.sort_by_alpha').change(function(){
			var val=$(this).val();			
			if(val!='') {
				var url = replaceParam('sort_by_alpha', val);
				window.history.pushState({path:url},'',url);				
			}else {
				var url=removeURLParameter('sort_by_alpha');					
				window.history.pushState({path:url},'',url);
			}
			product_container.empty();
			product_container.showProducts();
			return false;
		});
		
		$('a.switch_view').click(function(){
			$('.switch_view').each(function() {
				if($(this).hasClass('active')) {
					$(this).removeClass('active');
				}
			});
			$(this).addClass('active');
			$('.equal-height-item').equalHeights();
			var id=$(this).attr('id');
			var url = replaceParam('vm', id);
			window.history.pushState({path:url},'',url);	
			product_container.find('ul').removeAttr('class');
			product_container.find('ul').addClass(id);
			product_container.empty();
			product_container.showProducts();
			return false;
		});	
		$('#by_folk_name').keypress(function (e) {
			if(e.keyCode == '13'){
				var keyword=$(this).val();
				if(keyword !='') {
					var url = replaceParam('s', keyword);
					window.history.pushState({path:url},'',url);	
				}else {
					var url=removeURLParameter('s');					
					window.history.pushState({path:url},'',url);
				}
				product_container.empty();
				product_container.showProducts();
				e.preventDefault();
			}
		});
	}	
});