<?php
/**
 * Bundled Item Title Template.
 * Note: bundled product properties accessible from $bundled_item->product .
 *
 * @version 4.8.8
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( $title === '' ) {
	return;
}

?><h4 class="bundled_product_title product_title"><?php
		echo $title . ( $quantity > 1 && $bundled_item->get_quantity( 'max' ) === $quantity ? ' &times; ' . $quantity : '' ) . ( $optional ? __( ' &ndash; optional', 'woocommerce-product-bundles' ) : '' );
?></h4>
