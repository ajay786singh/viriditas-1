<?php
/**
 * The Template for displaying product archives, including the main shop page which is a post type archive.
 *
 * Override this template by copying it to yourtheme/woocommerce/archive-product.php
 *
 * @author 		WooThemes
 * @package 	WooCommerce/Templates
 * @version     2.0.0
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

get_header( 'shop' );
?>
<section role="main">
	<div class="container content">
		<!-- Include Sidebar -->
		<?php get_template_part( 'woocommerce/sidebar-filter', 'woocommerce'); ?>
		<div class="column-9">
			<div class="shop-header">
				<h6 class="heading" style="width:10%; float:left;">Shop</h6>				
				<div class="sort-product" style="float:right;">
					<a href="#" class="sort_by_name">Sort By Name</a>
				</div>
			</div>
			<!-- Include Breadcrumbs -->
			<div class="breadcrumb">
				<?php do_action( 'woocommerce_before_main_content' );?>
			</div>
			</div></div>
			<!-- Include Products -->
			
			<div class="product-container">
				<ul class="product-list">
				</ul>
				<div class="loading">
					<img src="<?php bloginfo('template_url');?>/dist/images/loader.gif">
				</div>
				<div class="no-records">
					No Records Found.
				</div>
			</div>
		</div>
	</div>
</section>	
<?php get_footer( 'shop' );?>