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
<!--<div class="body-overlay"></div>-->
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
							<li>
								<input type="text" name="by_folk_name" class="search-box" id="by_folk_name" value="<?php if($_REQUEST['s']) { echo $_REQUEST['s'];} ?>" placeholder="Search products" />
							</li>
							<li><a href="#" class="sort_by" id="title">Sort by Latin Name</a></li>
							<li><a href="#" class="sort_by" id="folk_name">Sort by Folk Name</a></li>
							<li>
								<label>Alphabetical:</label>
								<?php 
									$alphas = range('A', 'Z');
									echo "<select name='sort_by_alpha' class='sort_by_alpha'>";
									if($_REQUEST['sort_by_alpha']=='') {
										echo "<option value=''>--</option>";
									}
									foreach($alphas as $alphabet) {
										if($_REQUEST['sort_by_alpha']==lcfirst($alphabet)) {
											echo "<option value='".lcfirst($alphabet)."' selected>".$alphabet."</option>";
										}else {
											echo "<option value='".lcfirst($alphabet)."'>".$alphabet."</option>";
										}
									}
									echo "</select>";
								?>
							</li>
							
							<li>
								<a href="#" class="switch_view" id="thumb_view"><i class="fa fa-th-large"></i></a>
								<a href="#" class="switch_view" id="list_view"><i class="fa fa-list"></i></a>
							</li>
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