jQuery(document).ready(function($) {
	$('#edit-formula').click(function(){
		var container=$(this).attr('id');
		$('body').addClass('open-popup');
	});
	$('.popup-overlay').click(function(){
		$('body').removeClass('open-popup');
	});
	if($('.variations').length>0) {
		var id=$('.variations ul li input:radio:checked').attr("id");
		var variation_id=id.replace('size_','');
		$('#variation_id').val(variation_id);
	}
	$('.variations ul li input:radio').unbind('click').bind("click", function(e){
		var id=$(this).attr('id');
		var variation_id=id.replace('size_','');
		//$('#variation_id').val('').change();
		$('#variation_id').val(variation_id);		
	});
	$('.bundle_variations ul li input:radio').unbind('click').bind("click", function(e){
		var size_price=$(this).attr('value');
		size_price=size_price.split("-");
		var size=size_price[0];
		var price=size_price[1];
		$('#cart_size').val(size);
		$('#cart_price').val(price);			
	});
	$('.compound-sizes ul li input:radio').unbind('click').bind("click", function(e){
		var size_price=$(this).attr('value');
		size_price=size_price.split("-");
		var size=size_price[0];
		var price=size_price[1];
		$('#cart_size').val(size);
		$('#cart_price').val(price);
		$("#additional_price").calculateAdditionalPrice();
	});
	$('table.shop_table.cart .cart_item').each(function(){
		var ProductType=$(this).find('td.product-price span.product_type');
		if(ProductType.html()=='bundle') {
			var ProductID=$(this).find('td.product-price span.product_type').attr("data-id");
			var ProductPrice=$(this).find('td.product-price span.amount').html();
			if(ProductPrice=='$1.00') {
				var data= {'action':'remove_bundle_product_from_cart','product_id':ProductID};		
				$.ajax({		
					type: 'POST',		
					url: ajaxurl,		
					data:data,	
					success: function(html) {									
					}		
				});	
			}
		}
	});
});