<?php
function get_cart_bundled() {
	global $woocommerce,$product,$post;
	
	$attributes = $product->get_attributes();
	$attribute=$attributes['pa_size-price'];
	
	if ($attribute && $attribute['is_taxonomy'] ) {

		$terms = wp_get_post_terms( $product->id, $attribute['name'], 'all' );

		if($terms) { 
			$size_prices="";
			foreach ( $terms as $term ) {	
				$size_price=explode("/",$term->name);
				$size_prices[]=array('size'=>$size_price[0],'price'=>$size_price[1]);
				
			}
			print_r($size_prices);	
		}	
    }    
	
	echo "<pre>";
		print_r($pa_size_price);
	echo "</pre>";
}
?>