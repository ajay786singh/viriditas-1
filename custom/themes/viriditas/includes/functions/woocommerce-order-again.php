<?php
//add_filter('woocommerce_before_cart', 'add_order_again');
add_action( 'wp_ajax_woocommerce_order_again', 'woocommerce_order_again' );
add_action( 'wp_ajax_woocommerce_order_again', 'woocommerce_order_again' );
function woocommerce_order_again() {
	global $woocommerce;
	$orderID=$_POST['orderID'];	
	// Clear current cart
	WC()->cart->empty_cart();
	if($orderID!='') {
		$order = new WC_Order($orderID);
		$items = $order->get_items();
		if ( sizeof( $items ) > 0 ) {
			foreach ( $items as $item_id => $item ) {
				$item_meta=$item['item_meta_array'];
				$_product_id="";
				foreach($item_meta as $meta) {
					if($meta->key=='_product_id'){
						$_product_id=$meta->value;	
					}
				}
				WC()->cart->add_to_cart($_product_id,1);
			}
		}	
	}
	die();
}
?>