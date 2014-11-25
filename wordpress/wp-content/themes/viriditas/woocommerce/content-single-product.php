<?php
/**
 * The template for displaying product content in the single-product.php template
 *
 * Override this template by copying it to yourtheme/woocommerce/content-single-product.php
 *
 * @author 		WooThemes
 * @package 	WooCommerce/Templates
 * @version     1.6.4
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
?>
<div class="single-product-content-section">
<?php
	/**
	 * woocommerce_before_single_product hook
	 *
	 * @hooked wc_print_notices - 10
	 */
	 do_action( 'woocommerce_before_single_product' );

	 if ( post_password_required() ) {
	 	echo get_the_password_form();
	 	return;
	 }
?>

<div itemscope itemtype="<?php echo woocommerce_get_product_schema(); ?>" id="product-<?php the_ID(); ?>" <?php post_class(); ?>>
		<?php 
			$cats = get_the_terms( $post->ID, 'product_cat' ) ;
			$tags = get_the_terms( $post->ID, 'product_tag' );
			if($cats !='' || $tags !='') {
				echo '<p class="single-product-meta">';
				if($cats) {
					echo "<span>Categories:</span>";
					foreach($cats as $cat) {
						echo "<a href='".get_term_link( $cat->slug, 'product_cat' )."'>".$cat->name."</a> ";
					}
				}
				if($tags) {
					echo "<span>Sub categories:</span>";
					foreach($tags as $tag) {
						echo "<a href='".get_term_link( $tag->slug, 'product_tag' )."'>".$tag->name."</a> ";
					}	
				}
				echo "</p>";
			}
			
		?>
        
	<?php
		/**
		 * woocommerce_before_single_product_summary hook
		 *
		 * @hooked woocommerce_show_product_sale_flash - 10
		 * @hooked woocommerce_show_product_images - 20
		 */
		//do_action( 'woocommerce_before_single_product_summary' );
		
		if ( has_post_thumbnail()  ) {
			$thumb_id = get_post_thumbnail_id($post->ID);
			$thumb_url = wp_get_attachment_image_src($thumb_id,'full', true);
			$img = $thumb_url[0];
			?>
			<div class="images">
				<img src="<?php echo $img;?>" class="attachment-shop_single wp-post-image" alt="beef osso bucco" title="Beef Osso Bucco">
			</div>
			<?php
		}else {
	?>
		<div class="images">
			<?php echo apply_filters( 'woocommerce_single_product_image_html', sprintf( '<img src="%s" alt="Placeholder" />', woocommerce_placeholder_img_src() ), $post->ID ); ?>
		</div>
	<?php  } ?>

	<div class="summary entry-summary">
		
		<?php
			do_action( 'woocommerce_after_single_product_summary' );
			/**
			 * woocommerce_single_product_summary hook
			 *
			 * @hooked woocommerce_template_single_title - 5
			 * @hooked woocommerce_template_single_rating - 10
			 * @hooked woocommerce_template_single_price - 10
			 * @hooked woocommerce_template_single_excerpt - 20
			 * @hooked woocommerce_template_single_add_to_cart - 30
			 * @hooked woocommerce_template_single_meta - 40
			 * @hooked woocommerce_template_single_sharing - 50
			 */
			do_action( 'woocommerce_single_product_summary' );
		?>

	</div><!-- .summary -->
</div>
</div>
<?php
/* Up-sells*/
global $product, $woocommerce_loop;

$upsells = $product->get_upsells();

if ( sizeof( $upsells ) == 0 ) return;

$meta_query = WC()->query->get_meta_query();

$args = array(
	'post_type'           => 'product',
	'ignore_sticky_posts' => 1,
	'no_found_rows'       => 1,
	'posts_per_page'      => $posts_per_page,
	'orderby'             => $orderby,
	'post__in'            => $upsells,
	'post__not_in'        => array( $product->id ),
	'meta_query'          => $meta_query
);

$products = new WP_Query( $args );

$woocommerce_loop['columns'] = $columns;

if ( $products->have_posts() ) : ?>
<hr />
<h4><?php _e( 'You may also like&hellip;', 'woocommerce' );?></h4>
<div class="related products">

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
                            <div class="price"><?php if ( $price_html = $product->get_price_html() ) : ?><?php echo $price_html; ?><?php endif; ?></div>
                    </div>
                    <div class="product-action">
                            <?php do_action( 'woocommerce_after_shop_loop_item' ); ?>
                    </div>
                </li>
	
			<?php endwhile; // end of the loop. ?>
			</ul>

</div>
<?php endif;
wp_reset_postdata();
?>
	<?php
		/**
		 * woocommerce_after_single_product_summary hook
		 *
		 * @hooked woocommerce_output_product_data_tabs - 10
		 * @hooked woocommerce_output_related_products - 20
		 */
		//do_action( 'woocommerce_after_single_product_summary' );

    
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
                            <div class="price"><?php if ( $price_html = $product->get_price_html() ) : ?><?php echo $price_html; ?><?php endif; ?></div>
                    </div>
                    <div class="product-action">
                            <?php do_action( 'woocommerce_after_shop_loop_item' ); ?>
                    </div>
                </li>
	
			<?php endwhile; // end of the loop. ?>
			</ul>
		<?php //woocommerce_product_loop_end(); ?>

	</div>

<?php endif; wp_reset_postdata();?>
	<meta itemprop="url" content="<?php the_permalink(); ?>" />

</div><!-- #product-<?php the_ID(); ?> -->

<?php do_action( 'woocommerce_after_single_product' ); ?>
