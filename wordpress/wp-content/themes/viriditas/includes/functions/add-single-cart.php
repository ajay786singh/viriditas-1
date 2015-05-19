<?php
function sort_by_size($a, $b) {
    if ($a['size'] == $b['size']) return 0;
    return (strtotime($a['size']) < strtotime($b['size'])) ? 1 : -1;
}


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
				$size=substr($size_price[0],0,-2);
				$size_prices[]=array('size'=>$size,'price'=>$size_price[1]);
			}
			usort($size_prices, "sort_by_size");
			if($size_prices) {
				echo "<ul>";
					$i=1;
					foreach($size_prices as $size_price) {
						$size=$size_price['size'];
						$price=$size_price['price'];
						$checked = ($i==1 ? 'checked' : '');
						echo "<li><input type='radio' name='pa_size-price' $checked value=''><label>".$size." ml - ".$price."</label></li>";
						$i++;
					}
					?>
					<li>
						<span><a href="#" id="edit-formula" class="button">Edit Formula</a></span>
						<span>or</span> 
						<span><a href="#" id="buy-product" class="button">Buy as is</a></span>
					</li>
				</ul>
			<?php	
			}
			
		}	
    }
}
?>