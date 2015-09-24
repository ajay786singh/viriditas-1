jQuery(document).ready(function($) {
	if($('.compounds').length) {
		$('.error-info').show();
		var pa=getParameterByName('pa');
		var pb=getParameterByName('pb');
		$('section[role="body-systems"]').fetchSelectTerms('body_system','pb',pb);
		$('section[role="actions"]').fetchActions('body_system',pb,'pa',pa);
		$('.compound-list .product-list').showCompound();
		
		// Function to close pop up for compound on press esc button
		$(document).keyup(function(e) {
			if (e.keyCode == 27) {
				//alert(2);
				var herbSize=$('#herb-size').val();
				var totalSize=$('#total_size').html();
				totalSize=parseInt(totalSize);
				var id=$('.herb-name').attr('id');
				var dataPricy=$('.herb-name').attr('data-pricy');
				var baseSize=100;
				if(totalSize !='' && totalSize > baseSize) {
					$(this).removeHerb(id);
					$('.popup-compound').closePopup();
					return false;
				}
				if(herbSize=="" || herbSize==0){
					$(this).removeHerb(id);
					$('.popup-compound').closePopup();
				} else {
					if(confirm("This herb will not be added to your formula.")){
						$(this).removeHerb(id);
						$('.popup-compound').closePopup();
					}	
					// if((dataPricy=='*' || dataPricy=='**') && herbSize > 60 ) {
						// $(this).removeHerb(id);
					// }
					// $('.popup-compound').closePopup();
				}  
			} // esc
		});
		
		//function to close pop up compound on close button click
		$('.popup-compound .close-button').click(function(e){
			var herbSize=$('#herb-size').val();
			var totalSize=$('#total_size').html();
			var id=$('.herb-name').attr('id');
			var dataPricy=$('.herb-name').attr('data-pricy');
			var baseSize=100;
			if(parseInt(totalSize) > baseSize) {
				$(this).removeHerb(id);
				$('.popup-compound').closePopup();
				return false;
			} else {				
				if(herbSize=="" || herbSize==0){
					$(this).removeHerb(id);
					$('.popup-compound').closePopup();
				} else {
					if(confirm("This herb will not be added to your formula.")){
						$(this).removeHerb(id);
						$('.popup-compound').closePopup();
					}	
					// if((dataPricy=='*' || dataPricy=='**') && herbSize > 60 ) {
						// $(this).removeHerb(id);
					// }
					// $('.popup-compound').closePopup();
				}	
			}
			return false;
		});
		
		$('#by_folk_name').keypress(function (e) {
			if(e.keyCode == '13'){
				var keyword=$(this).val();
				if(keyword !='') {
					var url = replaceParam('keyword', keyword);
					window.history.pushState({path:url},'',url);	
				} else {
					var url=removeURLParameter('keyword');					
					window.history.pushState({path:url},'',url);
				}
				$('.compound-list .product-list').showCompound();
				e.preventDefault();
			}
		});
		
		$('#compound-search').on('click',function (e) {
			var keyword=$('#by_folk_name').val();
			if(keyword !='') {
				var url = replaceParam('keyword', keyword);
				window.history.pushState({path:url},'',url);	
			} else {
				var url=removeURLParameter('keyword');					
				window.history.pushState({path:url},'',url);
			}
			$('.compound-list .product-list').showCompound();
			return false;
		});
		
		$('.numbers').unbind('keydown').bind('keydown',function(e){
			$(this).onlyNumbers(e);
		});
		
		$('#herb-size').on('blur',function(e){
			$('.pop-up-error').empty().hide();
			var herbSize=$(this).val();
			var herbId=$('.herb-name').attr("id");
			var herbPricy=$('.herb-name').attr("data-pricy");
			$('#size_'+herbId).val(herbSize);
			var compound_id=$('#recipe-compound-id').val();
			if((herbPricy =='*' || herbPricy =='**') && herbSize > 60) {
				$('.pop-up-error').empty().show().html("60% of this herb is the maximum that can be added to a formula online. Please contact Viriditas to request greater than 60%. 416-767-3428");
			}
			var totalSizes=$('.herb-sizes').calculateSize();
			if(totalSizes > 100) {
				$('.pop-up-error').empty().show().html("Please review your percentages for each herb, the total cannot be greater than 100%.");
			}
			if(compound_id!='') {
				var baseSize=$('.base-size').checkEditCompoundLimit();
				if(baseSize>=0 && baseSize < 25 ) {
					var errorMsg="The base formula cannot be less than 25% of the total. Please reduce your added herb amounts for a combined total of 75% of less. Alternatively, make a compound from scratch by choosing 'create a formula' in the product main menu.";
					$('.pop-up-error').empty().show().html(errorMsg);	
				}
			}
		});
		
		$('.add-recipe-herb').submit(function(){
			return false;
		});
		
		$('.add-herb').unbind('click').bind('click',function(e) {
			var herbSize=$('#herb-size').val();
			var herbId=$('.herb-name').attr("id");
			var herbPricy=$('.herb-name').attr("data-pricy");
			var compound_id=$('#recipe-compound-id').val();
			if(herbSize !='' && herbSize > 0) {
				if((herbPricy =='*' || herbPricy =='**') && herbSize > 60) {
					 $('.pop-up-error').empty().show().html("60% of this herb is the maximum that can be added to a formula online. Please contact Viriditas to request greater than 60%. 416-767-3428");
				} else {
					$('.pop-up-error').empty().hide();
					$('#size_'+herbId).val(herbSize);
					var totalSizes=$('.herb-sizes').calculateSize();
					var total_size=$('#total_size').html();
					var baseSize=$('.base-size').html();
					if(parseInt(total_size) <= 100) {
						if(compound_id!='') {
							var cbaseSize=$('.base-size').checkEditCompoundLimit();
							if(cbaseSize>=0 && cbaseSize < 25 ) {
								var errorMsg="The base formula cannot be less than 25% of the total. Please reduce your added herb amounts for a combined total of 75% of less. Alternatively, make a compound from scratch by choosing 'create a formula' in the product main menu.";
								$('.pop-up-error').empty().show().html(errorMsg);	
							} else {
								$('.popup-compound').closePopup();
							}
						} else {
							$('.popup-compound').closePopup();
						}
						
					} else {						
						$('.pop-up-error').empty().show().html("Please review your percentages for each herb, the total cannot be greater than 100%.");
					}
				}
			} else {
				$('.pop-up-error').empty().show().html("Please enter size of herb for recipe.");
			}
			return false;
		});
		
		$('a.sort_by').click(function(e){
			var id=$(this).attr('id');
			$('.sort_by').each(function(){
				$(this).removeClass('active');
			});	
			$(this).addClass('active');	
			var url = replaceParam('sort_by', id);
			window.history.pushState({path:url},'',url);				
			$('.compound-list .product-list').showCompound();
			e.preventDefault();
		});
		
		$('.recipe-form').submit(function(e){		
			e.preventDefault();	
			var totalSizes=$('.herb-sizes').calculateSize();
			var total_size=100;
			var baseSize;
			var title = $('#recipe-name').val();
			var cartSize = $('#cart_size').val();
			var cartPrice = $('#cart_price').val();
			var compound_id=$('#recipe-compound-id').val();
			var size_price=$('.recipe-size:checked').val();	
			var productType=$('#product_type').val();	
			var recipeMono=$('#recipe-mono').val();	
			var serviceFee=$('#recipe-service-fee').val();	
			//alert(recipeMono);	
			var herbs=[];
			var compound_herbs=$('#recipe-compound-herbs').val();
			var additionalPrice=$('#additional_price').val();
			size_price=size_price.split("-");		
			var size=size_price[0];		
			var price=size_price[1];				
			$('.herb-sizes').each(function(e){
				var id = $(this).attr('id');
				var val= $(this).val();
				var herb=id+"_"+val;
				herbs.push(herb);
			});
			if(compound_id!="") {
				totalSizes=100;
				baseSize=$('.base-size').html();
				baseSize=parseInt(baseSize);
			}
			var compound_products=$('#compound-products').val();
			var data= {'action':'manage_compound','title':title,'product_type':productType,'cart_size':cartSize,'cart_price':cartPrice,'size':size,'price':price,'compound_products':compound_products,'herbs':herbs,'additional_price':additionalPrice,'compound_id':compound_id,'compound_herbs':compound_herbs,'recipeMono':recipeMono,'service_fee':serviceFee};		
			var message=$('.errors');	
			message.show();		
			message.loaderShow();		
			if(title == '') {
				message.empty();
				message.loaderHide();
				message.html("Please enter recipe name.");
			} else if(totalSizes < total_size) {
				message.empty();
				message.loaderHide();
				message.html("Make sure your herb total equals 100%.");
			} else if(compound_id!="" && baseSize<25) {
				message.empty();
				message.loaderHide();
				var errorMsg="The base formula cannot be less than 25% of the total. Please reduce your added herb amounts for a combined total of 75% of less. Alternatively, make a compound from scratch by choosing 'create a formula' in the product main menu.";
				message.html(errorMsg);
			} else if(totalSizes > total_size) {
				message.empty();
				message.loaderHide();
				message.html("Please review your percentages for each herb, the total cannot be greater than 100%.");
			} else {
				var error=0;
				$('.herb-sizes').each(function(e){
					var val=$(this).val();
					if(val==0 || val=='') {
						$(this).css("border-color",'red');
						message.empty();
						message.loaderHide();	
						message.append("Please specify the percent of herb(s) or remove it from your recipe.");	
						error++;
					}else {
						$(this).css("border-color",'none');
						if(error!=0) {
							error--;	
						}
					}
				})
				if(error==0 || error<0) {
					message.empty();
					message.loaderShow();
					$.ajax({		
						type: 'POST',		
						url: ajaxurl,		
						data:data,	
						success: function(html) {				
							message.loaderHide();		
							message.empty();		
							if(html=='done') {
								var msg="<div class='error-header'>Thank you, this recipe has been added to your cart. <a href='"+cart_page+"'>View cart/ checkout</a></div>";
								$("#compound-products").val('');
								$("#recipe-name").val('');
								$('#additional_price').val('');
								$('.herb-sizes').each(function(e){
									var id = $(this).attr('id');
									id=id.replace("size_",'');
									$(this).removeHerb(id);
								});
								$('.base-size').html('100%');
								message.append(msg);		
							}else {
								message.append(html);
							}
						}		
					});		
				}	
			}	
		});
	}
});