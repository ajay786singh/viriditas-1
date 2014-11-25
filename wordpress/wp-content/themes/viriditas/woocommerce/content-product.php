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
	
$height_unit = '"';//get_option('woocommerce_dimension_unit');	
?>
<li class="block-grid-4">

	<?php //do_action( 'woocommerce_before_shop_loop_item' ); ?>
	
	<?php	$thumb = wp_get_attachment_image_src( get_post_thumbnail_id($post->ID), 'product-thumb' );
			$img = $thumb['0']; 
			if($img == '') {
				$img="http://placehold.it/350x550&text=Viriditas";
			}
	?>
	<a href="<?php the_permalink(); ?>">
		<div class="product-img" style="background-image:url(<?php echo $img;?>);">
			<img src="<?php echo $img;?>" />		
		</div>
	</a>
	<div class="product-details">
		<h6 class="product-title"><?php the_title(); ?></h6>
		<?php if ( $price_html = $product->get_price_html() ) : ?>
			<?php echo "<span class='amount'>$".$price."</span>"; ?>
		<?php else: ?>
			<span class="amount">$0</span>
		<?php endif; ?>
	</div>	
    <div class="product-action">
        <?php
			$_product_height=get_post_meta($product->id,'_product_height',true);
			$_product_stem=get_post_meta($product->id,'_product_stem',true);
			$_product_price=get_post_meta($product->id,'_regular_price',true);
		?>
		
				<?php 
					if($_product_price) {
				?>
				<?php //do_action( 'woocommerce_after_shop_loop_item' ); ?>
					<a href="<?php bloginfo('url');?>?add-to-cart=<?php the_ID();?>" rel="nofollow" data-product_id="<?php the_ID();?>" data-product_sku="<?php echo $_product_stem;?>" class="add_to_cart_button product_type_simple">
						Pick Me!
					</a>
				<?php
					}else {
				?>
					<a href="javascript:void(0);" rel="nofollow"  class="add_to_cart_button product_type_simple">
						Pick Me!
					</a>
				<?php
					}
				?>
				
    </div>
</li>