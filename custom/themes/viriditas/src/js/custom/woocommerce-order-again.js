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
				$('.order-again').empty().html("Thanks! This order has been added to your cart. In a moment you will be re-directed to the cart page.");
				window.location=cart_page;
			}		
		});
		return false;
	});	
});