<?php
/**
 * Variable product add to cart
 *
 * @author 		WooThemes
 * @package 	WooCommerce/Templates
 * @version     2.1.0
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

global $woocommerce, $product, $post;
?>
<script type="text/javascript">
    var product_variations_<?php echo $post->ID; ?> = <?php echo json_encode( $available_variations ) ?>;
</script>

<?php //do_action( 'woocommerce_before_add_to_cart_form' ); ?>

<form class="variations_form cart" method="post" enctype='multipart/form-data' data-product_id="<?php echo $post->ID; ?>" data-product_variations="<?php echo esc_attr( json_encode( $available_variations ) ) ?>">
	<?php if ( ! empty( $available_variations ) ) : ?>
	<?php /*/endforeach;?>
		<table class="variations" cellspacing="0">
			<tbody>
				<?php 
				sort($available_variations);
				//$loop = 0; foreach ( $attributes as $name => $options ) : $loop++; 
				?>
					<tr>
						<td class="label"><label for="<?php echo sanitize_title($name); ?>"><?php echo wc_attribute_label( $name ); ?></label></td>
					</tr> 
					<tr>
						<td class="value"><fieldset>
                        <?php
                            if ( is_array( $options ) ) {
								
								
                                if ( empty( $_POST ) ) {
									$selected_value = ( isset( $selected_attributes[ sanitize_title( $name ) ] ) ) ? $selected_attributes[ sanitize_title( $name ) ] : '';
								} else {
									$selected_value = isset( $_POST[ 'attribute_' . sanitize_title( $name ) ] ) ? $_POST[ 'attribute_' . sanitize_title( $name ) ] : '';
								}
								echo "<ul>";
								// Get terms if this is a taxonomy - ordered
                                if ( taxonomy_exists( sanitize_title( $name ) ) ) {
 
                                    $terms = get_terms( sanitize_title($name), array('menu_order' => 'ASC') );
									sort($terms);
                                    foreach ( $terms as $term ) {
                                        if ( ! in_array( $term->slug, $options ) ) continue;
                                        echo '<li><input type="radio" value="' . strtolower($term->slug) . '" ' . checked( strtolower ($selected_value), strtolower ($term->slug), false ) . ' id="'. esc_attr( sanitize_title($name) ) .'" name="attribute_'. sanitize_title($name).'"><label for="'.esc_attr( sanitize_title($name) ).'">' . apply_filters( 'woocommerce_variation_option_name', $term->name ).' ml </label></li>';
                                    }
                                } else {
                                    sort($options);
									foreach ( $options as $option )
                                        echo '<li><input type="radio" value="' .esc_attr( sanitize_title( $option ) ) . '" ' . checked( sanitize_title( $selected_value ), sanitize_title( $option ), false ) . ' id="'. esc_attr( sanitize_title($name) ) .'" name="attribute_'. sanitize_title($name).'"><label for="'.esc_attr( sanitize_title($name) ).'">' . apply_filters( 'woocommerce_variation_option_name', $option ) . ' ml </label></li>';
                                }
								echo "</ul>";
                            }
                        ?>
                    </fieldset>
					<?php
							if ( sizeof($attributes) == $loop )
								//echo '<a class="reset_variations" href="#reset">' . __( 'Clear selection', 'woocommerce' ) . '</a>';
					?>
					</td>
					</tr>
		        <?php //endforeach;?>
			</tbody>
		</table>
		<?php //endforeach;*/?>
		<section class="variations">
			<?php 
				echo "<ul>";
				$i=1;
				foreach($available_variations as $variation) {
					$size=$variation['attributes']['attribute_pa_size'];
					$variation_id=$variation['variation_id'];
					$display_price=$variation['display_price'];
					$checked="";
					if($i==1) { 
						$checked = 'checked';
					}
					echo "<li><input type='radio' value='".$size."' ".$checked." id='size_".$variation_id."'  name='attribute_pa_size'><label for='size_".$variation_id."'>".$size."ml - $".$display_price."</li>";
					$i++;	
				}
				echo "</ul>";
			
			?>
				
		</section>

		<?php do_action( 'woocommerce_before_add_to_cart_button' ); ?>

		<div class="single_variation_wrapper">
			<?php do_action( 'woocommerce_before_single_variation' ); ?>

			<div class="single_variation"></div>

			<div class="variations_button">
				<?php //woocommerce_quantity_input(); ?>
				<button type="submit" class="single_add_to_cart_button button alt"><?php echo "Buy as is";//$product->single_add_to_cart_text(); ?></button>
			</div>

			<input type="hidden" name="add-to-cart" value="<?php echo $product->id; ?>" />
			<input type="hidden" name="product_id" value="<?php echo esc_attr( $post->ID ); ?>" />
			<input type="hidden" name="variation_id" id="variation_id" value="" />

			<?php do_action( 'woocommerce_after_single_variation' ); ?>
		</div>

		<?php do_action( 'woocommerce_after_add_to_cart_button' ); ?>

	<?php else : ?>

		<p class="stock out-of-stock"><?php _e( 'This product is currently out of stock and unavailable.', 'woocommerce' ); ?></p>

	<?php endif; ?>

</form>

<?php do_action( 'woocommerce_after_add_to_cart_form' ); ?>
