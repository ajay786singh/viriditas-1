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
global $product;
?>
<div class="column-9">
<div class="shop-header">
	<h6 class="heading"><a href="#" class="back-to-results">Back to Results</a></h6>
</div>
<div class="single-product-content">
	
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
	$product_page_url=get_bloginfo('url')."/products"; 
?>
<div itemscope itemtype="<?php echo woocommerce_get_product_schema(); ?>" id="product-<?php the_ID(); ?>" <?php post_class(); ?>>
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
			<div class="secondary">
				<img src="<?php echo $img;?>" class="attachment-shop_single wp-post-image" alt="beef osso bucco" title="Beef Osso Bucco">
			</div>
			<?php
		}else {
	?>
		<div class="secondary">
			<?php echo apply_filters( 'woocommerce_single_product_image_html', sprintf( '<img src="%s" alt="Placeholder" />', woocommerce_placeholder_img_src() ), $post->ID ); ?>
		</div>
	<?php  } ?>
		<div class="secondary">
			<div class="product-title">
				<?php the_title("<h4>","</h4>");?>
				<?php
					$monograph=get_post_meta($post->ID,'_product_details_monograph',true);
					if($monograph) {
				?>
				<a href="<?php echo get_permalink($monograph[0]);?>" class="view-monograph">view monograph</a>
				<?php } ?>
			</div>
			<?php 
				do_action( 'woocommerce_single_product_summary' );
			?>
			<?php 
				if(get_the_content()) { 
					echo "<hr>";
					the_content();
				}
			?>
			<div class="accordion">
				<?php 
					$warnings=get_post_meta($post->ID,'_product_details_warnings',true);
					if($warnings) {
				?>
					<div class="accordion-panel">
						<h5 class="accordion-panel-header">Warnings & Interactions</h5>
						<div class="accordion-panel-content">
							<?php
								echo apply_filters('the_content', $warnings);
							?>
						</div>
					</div>
				<?php } ?>
				
				<?php 
					$dosage=get_post_meta($post->ID,'_product_details_dosage',true);
					if($dosage) {
				?>
					<div class="accordion-panel">
						<h5 class="accordion-panel-header">Dosage</h5>
						<div class="accordion-panel-content">
							<?php echo apply_filters('the_content', $dosage);?>
						</div>
					</div>
				<?php } ?>
				
				<?php 
					$body_systems = get_the_terms( $post->ID, 'body_system' ); 
					if($body_systems) {
				?>
					<div class="accordion-panel">
						<h5 class="accordion-panel-header">Associated Body Systems</h5>
						<div class="accordion-panel-content">
							<ul class="list">
								<?php
									foreach($body_systems as $body_system) {
										echo "<li><a href='".$product_page_url."/?pb=".$body_system->term_id."'>".$body_system->name."</a></li>";
									}
								?>	
							</ul>
						</div>
					</div>
				<?php } ?>
				
				<?php 
					$actions = get_the_terms( $post->ID, 'actions' ); 
					if($actions) {
				?>
					<div class="accordion-panel">
						<h5 class="accordion-panel-header">Associated Actions</h5>
						<div class="accordion-panel-content">
							<ul class="list">
								<?php
									foreach($actions as $action) {
										echo "<li><a href='".$product_page_url."/?pa=".$action->term_id."'>".$action->name."</a></li>";
									}
								?>	
							</ul>
						</div>
					</div>
				<?php } ?>
				
				<?php 
					$indications = get_the_terms( $post->ID, 'indication' ); 
					if($indications) {
				?>
					<div class="accordion-panel">
						<h5 class="accordion-panel-header">Associated Indications</h5>
						<div class="accordion-panel-content">
							<ul class="list">
								<?php
									foreach($indications as $indication) {
										echo "<li><a href='".$product_page_url."/?pi=".$indication->term_id."'>".$indication->name."</a></li>";
									}
								?>	
							</ul>
						</div>
					</div>
				<?php } ?>				
				<div class="accordion-panel">
					<h5 class="accordion-panel-header">Questions? Contact Us</h5>
					<div class="accordion-panel-content">
						
					</div>
				</div>
			</div>

		</div>
</div>		
</div>
</div>
<?php do_action( 'woocommerce_after_single_product' ); ?>
</div>