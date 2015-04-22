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
<section role="content">
	<div class="container">
		<!-- Include Sidebar -->
		<?php get_template_part( 'woocommerce/sidebar-filter', 'woocommerce'); ?>
		<div class="column-9">
			<div class="list-products">
				<div class="shop-header">
					<h6 class="heading">Shop</h6>
					<div class="filter-side">	
						<ul>
							<li><a href="#" class="sort_by" id="title">Sort by Latin Name</a></li>
							<li><a href="#" class="sort_by" id="folk_name">Sort by Folk Name</a></li>
							<li>
								<a href="#" class="order_by" id="asc">ASC</a>
								<a href="#" class="order_by" id="desc"> | DESC</a>
							</li>
							<li><a href="#" class="switch_view" id="thumb_view"><i class="fa fa-th-large"></i></a></li>
							<li><a href="#" class="switch_view" id="list_view"><i class="fa fa-list"></i></a></li>
						</ul>
					</div>
				</div>
				<!-- Include Breadcrumbs -->
				<?php /* <div class="breadcrumb">
					<?php do_action( 'woocommerce_before_main_content' );?>
				</div>
				</div>
				</div> */?>
				<!-- Include Products -->
				<div class="product-list"></div>
				<div class="message"></div>
			</div>
			<!-- Container for single product -->
			<div class="single-product-detail">
				
			</div>
		</div>
	</div>
</section>	
<?php get_footer( 'shop' );?>