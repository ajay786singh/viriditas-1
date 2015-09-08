function getUrlVars() {
	var vars = {};
	var parts = window.location.href.replace(/[?&]+([^=&]+)=([^&]*)/gi, function(m,key,value) {
		vars[key] = value;
	});
	return vars;
}
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
	jQuery.each( params, function( key, value ) {
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
	var url = window.location.href;
	var urlparts= url.split('?');   
    if (urlparts.length>=2) {

        var prefix= encodeURIComponent(parameter)+'=';
        var pars= urlparts[1].split(/[&;]/g);

        //reverse iteration as may be destructive
        for (var i= pars.length; i-- > 0;) {    
            //idiom for string.startsWith
            if (pars[i].lastIndexOf(prefix, 0) !== -1) {  
                pars.splice(i, 1);
            }
        }
		if(pars.length==0) {
			url= urlparts[0];
		} else {
			url= urlparts[0]+'?'+pars.join('&');
		}
        return url;
    } else {
        return url;
    }
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
            container    		: $(this),
			page          		: 1,
            body_system   		: getParameterByName('pb'),
			action 		  		: getParameterByName('pa'),
			search_folk   		: getParameterByName('keyword'),
			sort_by_alpha 		: getParameterByName('sort_by_alpha'),
			sort_by       		: getParameterByName('sort_by'),
			compound_id   		: getParameterByName('compound'),
			mono_compound_id	: getParameterByName('mono-compound'),
			loader    	  		: $('.message')
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
				'sort_by':settings.sort_by,
				'sort_by_alpha':settings.sort_by_alpha,
				'compound_id':settings.compound_id,
				'mono_compound_id':settings.mono_compound_id
			},
			success: function(html) {
				settings.container.loaderHide();
				if(html!='' && html!=1) {
					settings.container.empty().html(html);
					
					jQuery(".compound-product").unbind('click').bind("click", function(e){
						var id=$(this).attr('data-id');
						var name=$(this).attr('data-name');
						var expensive=$(this).attr('data-pricy');
							if($(this).hasClass('added')==false) {
								$(this).addHerb(id,name,expensive);
							}
						return false;
					});
					
					jQuery(".alphabets-list li a").unbind('click').bind("click", function(e){
						e.preventDefault();
						var id=$(this).attr('id');
						id=id.replace('sort-','');
						jQuery(".alphabets-list li a").each(function(e){
							$(this).removeClass('alphabet-active ');
						});
						$(this).addClass('alphabet-active ');
						if(id!='') {
							var url = replaceParam('sort_by_alpha', id);
							window.history.pushState({path:url},'',url);				
						}else {
							var url=removeURLParameter('sort_by_alpha');					
							window.history.pushState({path:url},'',url);
						}
						$('.compound-list .product-list').showCompound();
						
					});
				} else if(html==1) {
					settings.container.empty().html("<h6>No records found.</h6>");
				}
			}
		});	
	},
	$.fn.onlyNumbers = function(event) {
		if ( event.keyCode == 46 || event.keyCode == 8 || event.keyCode == 9 || event.keyCode == 27 || event.keyCode == 13 || 
			 // Allow: Ctrl+A
			(event.keyCode == 65 && event.ctrlKey === true) || 
			
			// Allow: Ctrl+C
			(event.keyCode == 67 && event.ctrlKey === true) || 
			
			// Allow: Ctrl+V
			(event.keyCode == 86 && event.ctrlKey === true) || 
			
			// Allow: home, end, left, right
			(event.keyCode >= 35 && event.keyCode <= 39)) {
			  // let it happen, don't do anything
			  return;
		} else {
			// Ensure that it is a number and stop the keypress
			if (event.shiftKey || (event.keyCode < 48 || event.keyCode > 57) && (event.keyCode < 96 || event.keyCode > 105 )) {
				event.preventDefault(); 
			}				
		}
	},
	$.fn.openPopup =function() {
		$(this).show();
	},
	$.fn.closePopup =function() {
		$(this).find('input').val('');
		$(this).find('.herb-name').html('');
		$(this).find('.herb-name').attr('id','');
		$(this).find('.herb-name').attr('data-pricy','');
		$('.pop-up-error').empty().hide();
		$(this).hide();
	},
	$.fn.checkEditCompoundLimit = function () {
		var baseSize=$(this).html();
		baseSize=parseInt(baseSize);
		return baseSize;
	},
	$.fn.calculateSize = function( ) {
		var sum = 0;
		var compound_id=$('#recipe-compound-id').val();
		$(this).each(function(e) {
			if(!isNaN(this.value) && this.value.length!=0) {
				sum += parseFloat(this.value);
			}
		});
		if(compound_id!=""){
			var baseSize=100;	
			if(parseInt(sum) != 0 || parseInt(sum) != ''){
				var val=parseInt(baseSize)-parseInt(sum);	
				$('.base-size').html(val+"%");
			}
			$('#total_size').html("100%");
		} else {
			$('#total_size').html(sum+"%");	
		}
		return sum;
	},
	$.fn.calculateAdditionalPrice = function( ) {
		var extra=$('.recipe-size:checked').attr('data-additional');	
		var extra_expensive=$('.recipe-size:checked').attr('data-extra-expensive');	
		var i=0;
		var j=0;
		var totalExtra=0;
		$('.herb-sizes').each(function(e) {
			var pricey=$(this).attr('data-pricy');
			if(pricey=='*') {
				i++;
			} 
			if(pricey=='**') {
				j++;
			}
		});
		if(i>0 || j>0) {
			$('#recipe-service-fee').val(0);	
		} else {
			var serviceFee=$('#recipe-service-fee').attr("data-value");
			$('#recipe-service-fee').val(serviceFee);
		}
		totalExtra=parseInt(extra*i) + parseInt(extra_expensive*j);
		$(this).val(totalExtra);
	},
	$.fn.addHerb = function( id, name, expensive ) {
		if($('.popup-compound').show()==true){
			$('.popup-compound').closePopup();
		}
		$(".pop-up-note").hide();
		$('.pop-up-error').empty().hide();
		var number_size="size_"+id;
		var values =[];
		$('.addition-box').show();
		$('.hide-info').hide();
		var totalSize = $('.herb-sizes').calculateSize();
		var compound_id=$('#recipe-compound-id').val();
		var baseHerbs=limitHerbForNewCompound;
		var baseSize=100;
		if(compound_id !='') {
			baseHerbs=limitHerbForEditCompound;
		}
		if(totalSize==baseSize) {
			$('.popup-compound').closePopup();
			alert("Please review your percentages for each herb, the total cannot be greater than 100%.");
		} else { 	
			if($('.additions ul li').length < baseHerbs) {
				if(expensive!='' && expensive!='-1' && expensive=='*') {
					$(".pop-up-note.message_expensive").show();
					$(".pop-up-note.message_extra_expensive").hide();
				} else if(expensive!='' && expensive!='-1' && expensive=='**') {
					$(".pop-up-note.message_expensive").hide();
					$(".pop-up-note.message_extra_expensive").show();
				}
				if(totalSize <= baseSize) {
					var html="<li id='remove-product-"+id+"'><div class='box'>";
						html+="<a data-pricy='"+expensive+"' href='#' class='remove-compound' id='remove-"+id+"'>X</a></div> <div class='box'><i>"+name+expensive+"</i></div>";
						html+="<div class='box'>";
						html+="<input type='text' data-pricy='"+expensive+"' maxlength='2' name='"+number_size+"' class='herb-sizes' id='"+number_size+"' value='0'>";
						html+="<div>%</div></div></li>";
					$('#compound-'+id).addClass('added');
					$('.additions ul').append(html);	
					$("#additional_price").calculateAdditionalPrice();					
					$('.herb-name').html(name+expensive);
					$('.herb-name').attr("id",'').attr("id",id);
					$('.herb-name').attr("data-pricy",'').attr("data-pricy",expensive);
					$('.popup-compound').openPopup();
					$('.additions ul li a').each(function(){
						var id = $(this).attr('id');
						id=id.replace('remove-','');
						values.push(id);
					});
					$('#compound-products').val(values);			
					if($('#compound-products').val() != '') {
						$('.addition-box').show();
						$('.hide-info').hide();
					} else {
						$('.hide-info').show();
						$('.addition-box').hide();
					}
				}
			} else {
				$('.popup-compound').closePopup();
				var msg="You can add max "+baseHerbs+" herbs to your compound.";
				alert(msg);
			}
		}
		jQuery(".remove-compound").unbind('click').bind("click", function(e){
			e.preventDefault();
			var id=$(this).attr('id');
			id = id.replace('remove-','');
			$(this).removeHerb(id);
		});

		$('.herb-sizes').unbind('blur').bind('blur',function(e){
			var total_size = $('.herb-sizes').calculateSize();
		});
		
		$('.herb-sizes').unbind('keydown').bind('keydown',function(e){
			$(this).onlyNumbers(e);
		});		
	},
	$.fn.removeHerb = function( id ) {
		var values =[];
		$('#compound-'+id).removeClass('added');
		$('#remove-product-'+id).remove();
		$('.herb-sizes').calculateSize();
		$("#additional_price").calculateAdditionalPrice();
		$('.additions ul li a').each(function(){
			var id = $(this).attr('id');
			id=id.replace('remove-','');
			values.push(id);
		});
		$('#compound-products').empty().val(values);
		if($('#compound-products').val() != '') {
			$('.addition-box').show();
			$('.hide-info').hide();
		} else {
			$('.hide-info').show();
			$('.addition-box').hide();
		}
		$('.popup-compound').closePopup();
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
			search_folk  : getParameterByName('keyword'),
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
					$('.equal-height-item').each(function(e){
						if(settings.sort_by !='folk_name') {
							$(this).find('.folk_name').hide();
						}else {
							$(this).find('.folk_name').show();
						}
					});
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
		//alert(10);
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
		if($('.compounds').length) {
			$('.compound-list .product-list').showCompound();
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
							if($('body').hasClass('single-product')==true) {
								var shop_page_url=shop_page+'?'+filter+'='+dk.value;
								window.location = shop_page_url;
							} else {	
								if(dk.value=="") {
									if($('body').hasClass('single-product')==true) {
										var shop_page_url=shop_page;
										window.location = shop_page_url;
									} else {	
										this.reset(true);
										var pc=getUrlVars().pc || '';
										if(dk.value!='' && pc!='1391') {
											$('section[role="actions"]').fetchActions('body_system',dk.value,'pa','');
										} else {
											$('section[role="actions"]').hide();
										}
										var url=removeURLParameter('pb');					
										window.history.pushState({path:url},'',url);
										var url=removeURLParameter('pa');					
										window.history.pushState({path:url},'',url);
										var url=removeURLParameter('pi');					
										window.history.pushState({path:url},'',url);
										if($('.compounds').length) {
											$('.compound-list .product-list').showCompound();
										} else {
											$('.product-list').empty();
											$('.product-list').showProducts({page:1});
										}
										//$this.filterSelectTerms(filter,dk.value);
									}
								} else {	
									if(taxonomy =='body_system') {
										if(getParameterByName('pa')) {
											var url=removeURLParameter('pa');					
											window.history.pushState({path:url},'',url);
										}
										var pc=getUrlVars().pc || '';
										if(dk.value!='' && $('.compounds').length==1) {
											$('section[role="actions"]').show();
											$('section[role="actions"]').fetchActions('body_system',dk.value,'pa','');
										}else {												
											if(dk.value!='' && pc!='1391') {
												$('section[role="actions"]').fetchActions('body_system',dk.value,'pa','');
											}else {
												$('section[role="actions"]').dropkick();
											}
										}
									}
									$this.filterSelectTerms(filter,dk.value);
								}
							}
						}
					});
				}	
			}
		});
	},
	$.fn.fetchActions = function(taxonomy,pb,filter,active_val) {
		var $this = $(this);
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
							if($('body').hasClass('single-product')==true) {
								var shop_page_url=shop_page+'?'+filter+'='+dk.value;
								window.location = shop_page_url;
							} else {		
								if(dk.value=="") {
									if($('body').hasClass('single-product')==true) {
										var shop_page_url=shop_page;
										window.location = shop_page_url;
									} else {
										this.reset(true);
										var url=removeURLParameter('pa');					
										window.history.pushState({path:url},'',url);
										if($('.compounds').length) {
											$('.compound-list .product-list').showCompound();
										} else {
											$('.product-list').empty();
											$('.product-list').showProducts({page:1});
										}
									}
									
								}else {	
									$this.filterSelectTerms(filter,dk.value);
								}
							}
						}
					});
				}	
			}
		});			
	}
}(jQuery));

jQuery(document).ready(function($){
	$.ajaxSetup({cache:false});
	if($('body').hasClass('post-type-archive-product') || $('body').hasClass('single-product')) {
		var product_container = $('.product-list');
		// Current Filter values
		var pa=getUrlVars().pa || '';
		var pb=getUrlVars().pb || '';
		var pc=getUrlVars().pc || '';
		var pi=getUrlVars().pi || '';
		$('select.by-category').dropkick({
			mobile: true,
			change:function() {
				var dk = this;
				if(dk.value=="") {
						if($('body').hasClass('single-product')==true) {
							var shop_page_url=shop_page;
							window.location = shop_page_url;
						} else {
							this.reset(true);
							var url = window.location.href;
							url=url.split("?");
							if(url.length>1){
								window.history.pushState({path:url[0]},'',url[0]);
							}
							$(".by-body_system").dropkick();
							$(".by-actions").dropkick();
							$(".by-indication").dropkick();
							$('section[role="body-systems"]').fetchSelectTerms('body_system','pb',pb);
							$('section[role="indications"]').fetchSelectTerms('indication','pi',pi);
							//alert('a');
							//product_container.empty();
							//product_container.showProducts();
						}	
				} else if(dk.value=='2219') {
					window.location=compound_page;
				} else {
					if($('body').hasClass('single-product')==true) {
						var shop_page_url=shop_page+'?pc='+dk.value;
						window.location = shop_page_url;
					} else {			
						var url = replaceParam('pc', dk.value);
						window.history.pushState({path:url},'',url);
						var url=removeURLParameter('pb');					
						window.history.pushState({path:url},'',url);
						var url=removeURLParameter('pa');					
						window.history.pushState({path:url},'',url);
						var url=removeURLParameter('pi');					
						window.history.pushState({path:url},'',url);	
						$('section[role="category"]').filterSelectTerms('pc',dk.value);
						$('section[role="body-systems"]').fetchSelectTerms('body_system','pb',pb);
						$('section[role="indications"]').fetchSelectTerms('indication','pi',pi);
						//alert('B');
						//product_container.empty();
						//product_container.showProducts();
					}
				}	
			}
		});
		product_container.empty();
		product_container.showProducts();
		$('section[role="body-systems"]').fetchSelectTerms('body_system','pb',pb);
		
		if((pa!='') || (pb!='null' && pb!='undefined' && pb!='' && pc!='1391')) {
			$('section[role="actions"]').fetchActions('body_system',pb,'pa',pa);
		}
		
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
					var url = replaceParam('keyword', keyword);
					window.history.pushState({path:url},'',url);	
				}else {
					var url=removeURLParameter('keyword');					
					window.history.pushState({path:url},'',url);
				}
				product_container.empty();
				product_container.showProducts();
				e.preventDefault();
			}
		});
	}	
});