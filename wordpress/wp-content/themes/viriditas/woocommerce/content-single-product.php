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
			// $cats = get_the_terms( $post->ID, 'product_cat' ) ;
			// $body_systems = get_the_terms( $post->ID, 'body_system' );
			// $actions = get_the_terms( $post->ID, 'actions' );
			// if($cats !='') {
				// $cat_terms="";
				// echo '<p class="single-product-meta">';
				// if($cats) {
					// echo "<span>Categories: </span>";
					// foreach($cats as $cat) {
						// $cat_terms[]="<a href='".get_term_link( $cat->slug, 'product_cat' )."'>".$cat->name."</a>";
					// }
					// echo implode(', ',$cat_terms);
				// }
				// echo "</p>";
			// }
			// if($body_systems !='') {
				// echo '<p class="single-product-meta">';
				// if($body_systems) {
					// $body_system_terms='';
					// echo "<span>Body Systems: </span>";
					// foreach($body_systems as $body_system) {
						// $body_system_terms[] ="<a href='".get_term_link( $body_system->slug, 'body_system' )."'>".$body_system->name."</a>";
					// }
					// echo implode(', ',$body_system_terms);
				// }
				// echo "</p>";
			// }
			// if($actions !='') {
				// echo '<p class="single-product-meta">';
				// if($actions) {
					// $action_terms="";
					// echo "<span>Actions: </span>";
					// foreach($actions as $action) {
						// $action_terms[] ="<a href='".get_term_link( $action->slug, 'actions' )."'>".$action->name."</a>";
					// }
					// echo implode(', ',$action_terms);
				// }
				// echo "</p>";
			// }
			
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
			<div class="span-5 images">
				<img src="<?php echo $img;?>" class="attachment-shop_single wp-post-image" alt="beef osso bucco" title="Beef Osso Bucco">
			</div>
			<?php
		}else {
	?>
		<div class="span-5 images">
			<?php echo apply_filters( 'woocommerce_single_product_image_html', sprintf( '<img src="%s" alt="Placeholder" />', woocommerce_placeholder_img_src() ), $post->ID ); ?>
		</div>
	<?php  } ?>
		<div class="span-7">
			<?php the_excerpt();?>
			<div class="price"><?php if ( $price_html = $product->get_price_html() ) : ?><?php echo $price_html; ?><?php endif; ?></div>
		</div>
</div>		
</div>
</div>
<div class="span-11 center woocommerce-tabs">
	<?php
		$tab_1_title="Description";
		
		$tab_1_content="<h5>Product Description</h5>";
		$tab_1_content.=get_the_content();
		
		$tab_2_title="Review (0)";
		$tab_2_content="Ajay 2";
	?>
	<?php echo do_shortcode('[tabsgroup][tab title="'.$tab_1_title.'"]'.$tab_1_content.'[/tab][tab title="'.$tab_2_title.'"]'.$tab_2_content.'[/tab][/tabsgroup]');?>
</div>
<?php do_action( 'woocommerce_after_single_product' ); ?>
