jQuery(document).ready(function($) {
	$('.button-order-again').click(function(){
		var orderID=$(this).attr('id');
		orderID=orderID.replace('order-','');
		$(this).hide();
		$('.order-again').addClass('small-loader');
		var data= {'action':'woocommerce_order_again','orderID':orderID};		
		$.ajax({		
			type: 'POST',		
			url: ajaxurl,		
			data:data,	
			success: function(html) {									
				//alert(html);
				$('.order-again').removeClass('small-loader');
				$('.order-again').empty().html("Your order has been added to cart again and We are redirecting you to cart page...");
				window.location=cart_page;
			}		
		});
		return false;
	});	
});