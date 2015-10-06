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
	<h6 class="heading"><a href="<?php bloginfo('url');?>/products" class="back-to-results">Back to Products</a></h6>
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
				<img src="<?php echo $img;?>" class="attachment-shop_single wp-post-image" alt="<?php the_title();?>">
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
					$monograph_link = get_post_meta($post->ID,'_product_details_monograph_link',true);
					$monograph=get_post_meta($post->ID,'_product_details_monograph',true);
					if($monograph_link !='') {
				?>
				<a href="<?php echo $monograph_link;?>" class="view-monograph" target="_blank">view monograph</a>
				<?php } ?>
			</div>
			<div class="product-actions">
			<?php
			//print_r();
			if($product->post->post_status=='publish') {
				if($product->product_type=='bundle') {
			?>	
					<form class="product_bundle cart" action="" method="post" enctype="multipart/form-data">
					<?php 
						$sizes = get_the_terms( $product->id, 'pa_size');
						$prices = get_the_terms( $product->id, 'pa_price');
						$unit=get_post_meta($product->id,'_product_details_unit',true);
						if($unit=='') {
							$unit="mL";
						}
						if(count($sizes) == count($prices)) {
							$size_price="";
							for($i=0;$i<count($sizes);$i++) {
								$size_unit=explode(" ",$sizes[$i]->name);	
								$size=$size_unit[0];
								$size_price[]=array("size"=>$size,"price"=>$prices[$i]->name,"main-size"=>$sizes[$i]->name);
							}
							usort($size_price, function($a, $b) {
								return $a['size'] - $b['size'];
							});
							
							echo "<section class='bundle_variations'><ul>";
							for( $i=0; $i<count($size_price);$i++) {
								$size=$size_price[$i]['main-size'];	
								$price=$size_price[$i]['price'];	
								$checked="";
								if($i==0) { $checked='checked';}			
								$size=str_replace('-',' ', $size);
								//$size=str_replace('ml','mL', $size);
								echo "<li>";
									echo "<input type='radio' ".$checked." id='size-".$i."' name='product-size' class='product-size' value='".$size." - ".$price."'>";
									echo "<label for='size-".$i."'>".$size." - $".$price."</label>";
								echo "</li>";
							}
							
							echo "</ul></section>";
							$default_size=$size_price[0]['main-size'];
							$default_price=$size_price[0]['price'];
							echo '<input type="hidden" name="cart_size" id="cart_size" value="'.$default_size.'">';
							echo '<input type="hidden" name="cart_price" id="cart_price" value="'.$default_price.'">';
							echo '<input type="hidden" name="add-to-cart" value="'.$product->id.'">';
							echo '<input type="hidden" name="product_id" id="product_id" value="'.$product->id.'">';
							echo '<input type="hidden" name="product_type" id="product_type" value="bundle">';
							
							$compound_page_id=2219;
							$manage_compound_url=get_permalink($compound_page_id);
							?>
							<div class="cart-actions">
								<?php 
									$_allowed_bundle_user=get_post_meta($product->id,'_allowed_bundle_user',true);
									$button_text="Add to cart";
									if($_allowed_bundle_user =='') {
										$button_text="Buy as is";
								?>
								<div class="column-5"><a href="<?php echo $manage_compound_url;?>?compound=<?php echo get_the_ID();?>" id="" class="button edit-formula">EDIT FORMULA</a></div>
								<div class="column-2">or</div>
								<?php } ?>
								<div class="column-5">
									<!--<a href="#" id="add-to-cart_bundle" class="button">Buy as is</a>-->
									<button type="submit" class="single_add_to_cart_button button alt"><?php echo $button_text;?></button>
								</div>
							</div>
							<?php
						} else {
							echo '<p class="stock out-of-stock">This product is currently out of stock and unavailable.</p>';
						}
					?>
					</form>
					
				<?php	
				} else {
					do_action( 'woocommerce_single_product_summary');
				}
			} else {
				echo '<p class="stock out-of-stock">This item is not available for order online. Please call: 416-767-3428</p>';
			}		
			?>
			</div>
			<?php 
				if(get_the_excerpt()) { 
					echo '<div class="product-content">';
						the_excerpt();
					echo '</div>';
				}
			?>
			<div class="accordion">
				<?php 
					//Composition list will be for bundled data (Professional Herbal Combination or Make your own compound Products)
					if($product->product_type=='bundle') {
						$product_bundled_data=$product->bundle_data;
						if($product_bundled_data && count($product_bundled_data)> 0 ) {
				?>
					<div class="accordion-panel">
						<h5 class="accordion-panel-header">Composition List</h5>
						<div class="accordion-panel-content">
							<?php
								$compositions="";
								foreach($product_bundled_data as $key=>$value) {
									$id = $value['product_id'];
									$compositions[]=get_product_info($id);
								}
								echo implode(', ', $compositions );
							?>
						</div>
					</div>
				<?php 
					} 
				} //For Bundled Data
				?>
				
				<?php 
					$dosage=get_post_meta($post->ID,'_product_details_dosage',true);
				?>
					<div class="accordion-panel">
						<h5 class="accordion-panel-header">Dosage</h5>
						<div class="accordion-panel-content">
							<?php 
								if($dosage) { 
									echo apply_filters('the_content', $dosage); 
								} else { 
									echo "NONE known.";
								}
							?>
						</div>
					</div>
				
				<?php 
					$warnings=get_post_meta($post->ID,'_product_details_warnings',true);
				?>
					<div class="accordion-panel">
						<h5 class="accordion-panel-header">Warnings & Interactions</h5>
						<div class="accordion-panel-content">
							<?php
								if($warnings) {
									echo apply_filters('the_content', $warnings);
								} else {
									echo "NONE known.";
								}
							?>
						</div>
					</div>
				<?php //} ?>
				<?php 
					$body_systems = get_the_terms( $post->ID, 'body_system' ); 
					$body_systems_ids="";
					if($body_systems) {
				?>
					<div class="accordion-panel">
						<h5 class="accordion-panel-header">Associated Body Systems</h5>
						<div class="accordion-panel-content">
							<ul class="list">
								<?php
									foreach($body_systems as $body_system) {
										if ($body_system->parent == 0) {
											$body_systems_ids[]=$body_system->term_id;
											echo "<li><a href='".$product_page_url."/?pb=".$body_system->term_id."'>".$body_system->name."</a></li>";
										}
									}
								?>	
							</ul>
						</div>
					</div>
				<?php } ?>
				
				<?php 
					$actions = get_the_terms( $post->ID, 'body_system' ); 
					$action_terms=array();
					if(count($actions)>0) {
						for($i=0; $i<count($actions);$i++) {
							if ($actions[$i]->parent > 0) {
								$action_terms[] = "<li><a href='".$product_page_url."/?pa=".$actions[$i]->term_id."'>".$actions[$i]->name."</a></li>";
							}
						}
					}	
						if(count($action_terms)>0){
				?>
							<div class="accordion-panel">
								<h5 class="accordion-panel-header">Associated Actions</h5>
								<div class="accordion-panel-content">
									<ul class="list">
										<?php
											for($j=0;$j<count($action_terms);$j++) {
												echo $action_terms[$j];
											}
										?>	
									</ul>
								</div>
							</div>
					<?php  
						}
					?>
				
				<?php 
					/*$indications = get_the_terms( $post->ID, 'indication' ); 
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
				<?php }*/ ?>				
				<div class="accordion-panel">
					<h5 class="accordion-panel-header">Questions? Contact Us</h5>
					<div class="accordion-panel-content">
						<?php 
							$contact_form_id=9;
							$display_title = false;
							$display_description = false;
							$display_inactive = false;
							$field_values = null; 
							$ajax = "true";
							gravity_form($contact_form_id, $display_title, $display_description, $display_inactive, $field_values, $ajax);
						?>
					</div>
				</div>
			</div>

		</div>
</div>		
</div>
</div>
<?php do_action( 'woocommerce_after_single_product' ); ?>
</div>