<?php
/**
 * The template for displaying product content within loops.
 *
 * Override this template by copying it to yourtheme/woocommerce/content-product.php
 *
 * @author 		WooThemes
 * @package 	WooCommerce/Templates
 * @version     2.4.0
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

global $product, $woocommerce_loop,$post;

// Store loop count we're currently on
if ( empty( $woocommerce_loop['loop'] ) )
	$woocommerce_loop['loop'] = 0;

// Store column count for displaying the grid
if ( empty( $woocommerce_loop['columns'] ) )
	$woocommerce_loop['columns'] = apply_filters( 'loop_shop_columns', 4 );

// Ensure visibility
if ( ! $product || ! $product->is_visible() )
	return;

// Increase loop count
$woocommerce_loop['loop']++;

// Extra post classes
$classes = array();
if ( 0 == ( $woocommerce_loop['loop'] - 1 ) % $woocommerce_loop['columns'] || 1 == $woocommerce_loop['columns'] )
	$classes[] = 'first';
if ( 0 == $woocommerce_loop['loop'] % $woocommerce_loop['columns'] )
	$classes[] = 'last';
?>
<div <?php post_class( $classes ); ?>>
	<?php //do_action( 'woocommerce_before_shop_loop_item' ); ?>
    <?php	
		$thumb = wp_get_attachment_image_src( get_post_thumbnail_id($post->ID), 'full' );
		$img = $thumb['0']; 
		if($img == '') {
			$img=get_bloginfo('template_url')."/dist/images/product_default.jpg";
		}
	?>
	<div class="product-image">
		<div class="product-img" style="background-image:url(<?php echo $img;?>);">
			<img src="<?php echo $img;?>" />		
		</div>
	</div>
	<div class="product-meta">
		<div class="product-title">    
			<div class="title">
				<?php 
					$product_title = get_the_title();
					echo $product_title;
				?>
			</div>
		</div>	
		<div class="price">
			<?php 
				$totalPrice=get_post_meta($post->ID,'_selling_price',true);
				$size=get_post_meta($post->ID,'_selling_size',true);
				if($totalPrice=="") {
					$totalPrice=0;
				}
				if($size=="") {
					$size=0;
				}
				echo '<span class="amount">'.$size.' - $'.$totalPrice."</span>";
				// if($product->product_type=='bundle'){
					// $prices = get_the_terms( $product->id, 'pa_price');	
						// if(count($prices)>0) {
							// $amount="";	
							// for($i=0;$i<count($prices);$i++) {
								// $amount[]=number_format($prices[$i]->name,2);
							// }
							// sort($amount);
							// $amount = array_map(function($value) { return '$'.$value; }, $amount);
							// echo '<span class="amount">'.implode("-",$amount)."</span>";
						// }
				// }else {
					// if ( $price_html = $product->get_price_html() ) {
						// echo $price_html;
					// }	
				// }
			?>
		</div>
		<div class="herbs">
			<?php 
				$herbs=get_post_meta($post->ID,'_bundle_data',true);
				echo get_bundle_info($post->ID,$size);	
				// if($herbs) {
					// $herbsHtml='';	
					// foreach($herbs as $herb) {
						// $herb_id=$herb['product_id'];
						// $herbName=get_the_title($herb_id);
						// $expensive_herb = get_post_meta($herb_id,'_product_details_expensive_herb',true);	
						// $required_size=$herb['bundle_required_size'];
						// $herbsHtml[]=$herbName.$expensive_herb." - ".$required_size."%";
					// }
					// echo "<div class='herbs-name'>".implode(", ", $herbsHtml)."</div>";
				// }
			?>
		</div>
		<div class="cart-action">
			<a class="button add-to-cart-formulas" id="cart-button-<?php echo $post->ID;?>" href="#" data-id="<?php echo $post->ID;?>" data-price="<?php echo $totalPrice;?>" data-size="<?php echo $size;?>">Add to cart</a>
			<div id="cart-action-<?php echo $post->ID;?>"></div>
		</div>
		<?php /*
		<div class="product-action">
			<?php do_action( 'woocommerce_after_shop_loop_item' ); ?>
		</div>
		*/ ?>
	</div>
</div>