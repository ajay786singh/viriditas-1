<?php
/**
 * The Template for displaying all single products.
 *
 * Override this template by copying it to yourtheme/woocommerce/single-product.php
 *
 * @author 		WooThemes
 * @package 	WooCommerce/Templates
 * @version     1.6.4
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

get_header( 'shop' ); ?>
<section role="main">
	<div class="container content">
		<div class="span-10 center">
		<?php
			/**
			 * woocommerce_before_main_content hook
			 *
			 * @hooked woocommerce_output_content_wrapper - 10 (outputs opening divs for the content)
			 * @hooked woocommerce_breadcrumb - 20
			 */
			//do_action( 'woocommerce_before_main_content' );
		?>
		<a href="<?php bloginfo('url');?>/shop"><< See more products</a>
	<?php /*?>		<a href="<?php bloginfo('url');?>/products" class="back-to-products">Back to Products</a><?php */?>
			<?php while ( have_posts() ) : the_post(); ?>
				
				<?php the_title("<h1>","</h1>");?>
				
				<?php wc_get_template_part( 'content', 'single-product' ); ?>

			<?php endwhile; // end of the loop. ?>

		<?php
			/**
			 * woocommerce_after_main_content hook
			 *
			 * @hooked woocommerce_output_content_wrapper_end - 10 (outputs closing divs for the content)
			 */
			//do_action( 'woocommerce_after_main_content' );
		?>

		<?php
			/**
			 * woocommerce_sidebar hook
			 *
			 * @hooked woocommerce_get_sidebar - 10
			 */
		//	do_action( 'woocommerce_sidebar' );
		?>
	</div>
	</div>
</section>
<?php get_footer( 'shop' ); ?>