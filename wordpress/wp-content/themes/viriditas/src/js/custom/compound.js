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
				var herbSize=$('#herb-size').val();
				var totalSize=$('#total_size').html();
				totalSize=parseInt(totalSize);
				var baseSize=100;
				if(totalSize !='' && totalSize > baseSize) {
					return false;
				}
				if(herbSize=="" || herbSize==0){
					var id=$('.herb-name').attr('id');
					if(confirm("Are you sure to close without adding this herb to your recipe?")){
						$(this).removeHerb(id);
					}
				} else {
					$('.popup-compound').closePopup();
				}  
			} // esc
		});
		
		//function to close pop up compound on close button click
		$('.popup-compound .close-button').click(function(e){
			var herbSize=$('#herb-size').val();
			var totalSize=$('#total_size').html();
			var baseSize=100;
			if(parseInt(totalSize) > baseSize) {
				return false;
			} else {				
				if(herbSize=="" || herbSize==0){
					var id=$('.herb-name').attr('id');
					if(confirm("Are you sure to close without adding this herb to your recipe?")){
						$(this).removeHerb(id);
					}
				} else {
					$('.popup-compound').closePopup();
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
			if(herbPricy =='*' && herbSize > 60) {
				$('.pop-up-error').empty().show().html("The maximum amount for this herb is 60% when ordering online. To request a greater amount please place your order by phone: 416-767-3428.");
			}
			var total_sizes=$('.herb-sizes').calculateSize();
			if(total_sizes > 100) {
				$('.pop-up-error').empty().show().html("You've reached the max % that can be added to a combination online.");
			}
		});
		
		$('.add-recipe-herb').submit(function(){
			return false;
		});
		
		$('.add-herb').unbind('click').bind('click',function(e) {
			var herbSize=$('#herb-size').val();
			var herbId=$('.herb-name').attr("id");
			var herbPricy=$('.herb-name').attr("data-pricy");
			if(herbSize !='' && herbSize > 0) {
					//alert(herbSize);
				if(herbPricy =='*' && herbSize > 60) {
					 $('.pop-up-error').empty().show().html("The maximum amount for this herb is 60% when ordering online. To request a greater amount please place your order by phone: 416-767-3428.");
				} else {
					$('.pop-up-error').empty().hide();
					$('#size_'+herbId).val(herbSize);
					var total_sizes=$('.herb-sizes').calculateSize();
					var total_size=$('#total_size').html();
					var baseSize=$('.base-size').html();
					if(parseInt(total_size) <= 100) {
						$('.popup-compound').closePopup();
					} else {						
						$('.pop-up-error').empty().show().html("You've reached the max % that can be added to a combination online.");
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
			var total_sizes=$('.herb-sizes').calculateSize();
			var total_size=100;
			var title = $('#recipe-name').val();
			var cartSize = $('#cart_size').val();
			var cartPrice = $('#cart_price').val();
			var compound_id=$('#recipe-compound-id').val();
			var size_price=$('.recipe-size:checked').val();	
			var product_type=$('#product_type').val();			
			var herbs=[];
			var compound_herbs=$('#recipe-compound-herbs').val();
			var additional_price=$('.recipe-size:checked').attr('data-additional');
			size_price=size_price.split("-");		
			var size=size_price[0];		
			var price=size_price[1];		
			$('.herb-sizes').each(function(e){
				var id = $(this).attr('id');
				var val= $(this).val();
				var herb=id+"_"+val;
				herbs.push(herb);
			});
			var compound_products=$('#compound-products').val();
			var data= {'action':'manage_compound','title':title,'product_type':product_type,'cart_size':cartSize,'cart_price':cartPrice,'size':size,'price':price,'compound_products':compound_products,'herbs':herbs,'additional_price':additional_price,'compound_id':compound_id,'compound_herbs':compound_herbs};		
			var message=$('.errors');		
			message.show();		
			message.loaderShow();		
			if(title == '') {
				message.empty();
				message.loaderHide();
				message.html("Please enter recipe name.");
			} else if(total_sizes < total_size) {
				message.empty();
				message.loaderHide();
				message.html("Herb sizes should be 100%.");
			} else if(total_sizes > total_size) {
				message.empty();
				message.loaderHide();
				message.html("You've reached the max % that can be added to a combination online.");
			} else {
				message.empty();
				message.loaderShow();
				$.ajax({		
					type: 'POST',		
					url: ajaxurl,		
					data:data,	
					success: function(html) {				
						message.loaderHide();		
						message.empty();		
						//alert(html.search( 'Congrats!!!'));
						message.append(html);		
					}		
				});	
			}	
		});
	}
});