<?php
/**
 * Order again button
 *
 * @author  WooThemes
 * @package WooCommerce/Templates
 * @version 2.3.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}
?>
<?php 
// echo $_REQUEST['orderagain'];
// $total=$order->get_order_item_totals();
// print_r($total['cart_subtotal']['value']);
?>
<p class="order-again">
	<a href="<?php echo esc_url( wp_nonce_url( add_query_arg( 'orderagain', $order->id ) , 'woocommerce-order_again' ) ); ?>" class="button"><?php _e( 'Order Again', 'woocommerce' ); ?></a>
</p>
