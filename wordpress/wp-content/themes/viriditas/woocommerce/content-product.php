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
<li <?php post_class( $classes ); ?>>

	<?php //do_action( 'woocommerce_before_shop_loop_item' ); ?>
    <?php	$thumb = wp_get_attachment_image_src( get_post_thumbnail_id($post->ID), 'product-thumb' );
			$img = $thumb['0']; 
			if($img == '') {
				$img="http://placehold.it/350x550&text=".get_the_title();
			}
	?>
	<a class="product-image" href="<?php the_permalink();?>">
		<div class="product-img" style="background-image:url(<?php echo $img;?>);">
			<img src="<?php echo $img;?>" />		
		</div>
	</a>
	<div class="product-meta">
		<div class="product-title">    
			<div class="title">
				<a href="<?php the_permalink();?>"><?php the_title(); ?></a>
			</div>
			<div class="price"><?php if ( $price_html = $product->get_price_html() ) : ?><?php echo $price_html; ?><?php endif; ?></div>
		</div>
		<div class="product-action">
			<?php do_action( 'woocommerce_after_shop_loop_item' ); ?>
		</div>
	</div>
</li>