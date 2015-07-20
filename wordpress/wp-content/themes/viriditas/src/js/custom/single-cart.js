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
	});
	/*$('#add-to-cart_bundle').click(function(){
		var size_price=$('.bundle_variations ul li input:radio:checked').attr("value");
			size_price=size_price.split("-");
			var size=size_price[0];
			var price=size_price[1];
			var product_id=$('#product_id').val();
			var product_type=$('#product_type').val();
			alert(product_type);
			$(".product-actions").addClass("spinner");
			$(".cart-actions .button").each(function(){
					$(this).attr("disabled",true);
			});
			$.ajax({
				type: 'POST',
				url: ajaxurl,
				data:{action: 'add_to_cart_bundle','id':product_id,'size':size,'price':price,'product_type':product_type},
				success: function(html) {
					alert(html);	
					$(".product-actions").removeClass("spinner");
					$(".cart-actions .button").each(function(){
						$(this).attr("disabled",false);
					});
				}
			});	
	});*/
});