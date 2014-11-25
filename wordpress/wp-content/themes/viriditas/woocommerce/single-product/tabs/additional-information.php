<?php
/**
 * Additional Information tab
 * 
 * @author        WooThemes
 * @package       WooCommerce/Templates
 * @version       2.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	// Exit if accessed directly
	exit;
}

global $product;

$heading = apply_filters( 'woocommerce_product_additional_information_heading', __( 'Additional Information', 'woocommerce' ) );
?>

<?php if ( $heading ): ?>
	<h2><?php echo $heading; ?></h2>
<?php endif; ?>

<?php //$product->list_attributes(); 
$_product_height=get_post_meta($product->id,'_height',true);
$_product_stem=get_post_meta($product->id,'_sku',true);
?>
<table class="shop_attributes">
	<tbody>
		<?php if($_product_height) { ?>
		<tr>
			<th>Height</th>
			<td class="product_dimensions"><?php echo $_product_height.'"';?></td>
		</tr>
		<?php } ?>
		<?php if($_product_stem) { ?>
		<tr>
			<th>Stems</th>
			<td class="product_dimensions"><?php echo $_product_stem;?></td>
		</tr>
		<?php } ?>
	</tbody>
</table>