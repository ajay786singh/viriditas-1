<?php
/**
 * Bundled Item Title Template.
 *
 * Note: Bundled product properties are accessible via '$bundled_item->product'.
 *
 * Override this template by copying it to 'yourtheme/woocommerce/single-product/bundled-item-title.php'.
 *
 * @version 4.9.5
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( $title === '' ) {
	return;
}

?><h4 class="bundled_product_title product_title"><?php
		$quantity = ( $quantity > 1 && $bundled_item->get_quantity( 'max' ) === $quantity ) ? $quantity : '';
		$optional = $optional ? apply_filters( 'woocommerce_bundles_optional_bundled_item_suffix', __( 'optional', 'woocommerce-product-bundles' ), $bundled_item, $bundle ) : '';
		echo WC_PB_Helpers::format_product_shop_title( $title, $quantity, '', $optional );
?></h4>
