<?php
/**
 * Related Products
 *
 * @author 		WooThemes
 * @package 	WooCommerce/Templates
 * @version     1.6.4
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

global $product, $woocommerce_loop;

$related = $product->get_related( $posts_per_page );

if ( sizeof( $related ) == 0 ) return;

$args = apply_filters( 'woocommerce_related_products_args', array(
	'post_type'            => 'product',
	'ignore_sticky_posts'  => 1,
	'no_found_rows'        => 1,
	'posts_per_page'       => $posts_per_page,
	'orderby'              => $orderby,
	'post__in'             => $related,
	'post__not_in'         => array( $product->id )
) );

$products = new WP_Query( $args );

$woocommerce_loop['columns'] = $columns;

if ( $products->have_posts() ) : ?>

	<div class="related products">
			<hr />
		<h4><?php _e( 'Related Products', 'woocommerce' ); ?></h4>

		<?php //woocommerce_product_loop_start(); ?>
			<ul class="products related-products">
			<?php while ( $products->have_posts() ) : $products->the_post(); ?>

				<?php 				
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
				$classes = array('block-grid-4');
				if ( 0 == ( $woocommerce_loop['loop'] - 1 ) % $woocommerce_loop['columns'] || 1 == $woocommerce_loop['columns'] )
					$classes[] = 'first';
				if ( 0 == $woocommerce_loop['loop'] % $woocommerce_loop['columns'] )
					$classes[] = 'last';
				?>
                <li <?php post_class( $classes ); ?>>
                
                    <?php //do_action( 'woocommerce_before_shop_loop_item' ); ?>
                    <div class="product-img">
                        <a href="<?php the_permalink();?>"><?php do_action( 'woocommerce_before_shop_loop_item_title' ); ?></a>
                    </div>
                    <div class="product-title">
                            <div class="title">
                                <a href="<?php the_permalink();?>"><?php the_title(); ?></a>
                            </div>
                            <div class="price">
								<?php if ( $price_html = $product->get_price_html() ) : ?><?php echo $price_html; ?><?php endif; ?>
								<?php
									$_product_height=get_post_meta($product->id,'_height',true);
									$_product_stem=get_post_meta($product->id,'_sku',true);

										if($_product_height) echo '<span>Height: '.$_product_height.'"</span><br>';
										if($_product_stem) echo '<span>Stems: '.$_product_stem.'</span>';
								?>
							</div>
                    </div>
                    <div class="product-action">
                            <?php do_action( 'woocommerce_after_shop_loop_item' ); ?>
                    </div>
                </li>
	
			<?php endwhile; // end of the loop. ?>
			</ul>
		<?php //woocommerce_product_loop_end(); ?>

	</div>

<?php endif;

wp_reset_postdata();
