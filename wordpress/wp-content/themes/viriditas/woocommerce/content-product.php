<?php
/**
 * The template for displaying product content within loops.
 *
 * Override this template by copying it to yourtheme/woocommerce/content-product.php
 *
 * @author 		WooThemes
 * @package 	WooCommerce/Templates
 * @version     1.6.4
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
	<a class="product-image" rel="<?php echo $product->id;?>" href="<?php the_permalink();?>">
		<div class="product-img" style="background-image:url(<?php echo $img;?>);">
			<img src="<?php echo $img;?>" />		
		</div>
	</a>
	<div class="product-meta">
		<div class="product-title">    
			<div class="title">
				<a href="<?php the_permalink();?>" rel="<?php echo $product->id;?>">
					<?php 
						$product_title = get_the_title();
						$folk_name=get_post_meta($product->id,'_product_details_folk_name',true);
						if($folk_name) {
							echo "<div class='folk_name'>".$folk_name."</div>";
						}
						$terms = get_the_terms( $product->id, 'product_cat' );
						if(count($terms)>0) {
							//echo $terms[0]->slug;
							if($terms[0]->slug == 'single-herb-tincture') {
								$product_title = "<em>".$product_title."</em>";	
							}
						}
						echo $product_title;
					?>
				</a>
			</div>
			<div class="price">
				<?php 
					if($product->product_type=='bundle'){
						$prices = get_the_terms( $product->id, 'pa_price');	
							if(count($prices)>0) {
								$amount="";	
								for($i=0;$i<count($prices);$i++) {
									$amount[]=number_format($prices[$i]->name,2);
								}
								sort($amount);
								$amount = array_map(function($value) { return '$'.$value; }, $amount);
								echo '<span class="amount">'.implode("-",$amount)."</span>";
							}
					}else {
						if ( $price_html = $product->get_price_html() ) {
							echo $price_html;
						}	
					}
				?>
			</div>
		</div>
		<?php /*
		<div class="product-action">
			<?php do_action( 'woocommerce_after_shop_loop_item' ); ?>
		</div>
		*/ ?>
	</div>
</div>