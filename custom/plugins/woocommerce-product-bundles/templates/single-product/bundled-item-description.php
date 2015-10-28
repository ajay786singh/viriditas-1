<?php
/**
 * Bundled Item Short Description Template.
 *
 * Override this template by copying it to 'yourtheme/woocommerce/single-product/bundled-item-description.php'.
 *
 * @version 4.2.0
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( $description === '' ){
	return;
}

?><div class="bundled_product_excerpt product_excerpt"><?php
		echo $description;
?></div>
