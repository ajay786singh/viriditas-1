<?php
/**
 * My Orders
 *
 * Shows recent orders on the account page
 *
 * @author  WooThemes
 * @package WooCommerce/Templates
 * @version 2.3.10
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

$customer_orders = get_posts( apply_filters( 'woocommerce_my_account_my_orders_query', array(
	'numberposts' => $order_count,
	'meta_key'    => '_customer_user',
	'meta_value'  => get_current_user_id(),
	'post_type'   => wc_get_order_types( 'view-orders' ),
	'post_status' => array_keys( wc_get_order_statuses() )
) ) );

if ( $customer_orders ) : ?>
	<h2><?php echo apply_filters( 'woocommerce_my_account_my_orders_title', __( 'My Orders', 'woocommerce' ) ); ?></h2>
	<div class="my_account_orders my-orders">
		<ul>
			<?php 
				foreach ( $customer_orders as $customer_order ) { 
					$order = wc_get_order( $customer_order );
					$order->populate( $customer_order );
					$item_count = $order->get_item_count();
					$total_amount=$order->get_formatted_order_total();
			?>
				<li>
					<div class="order-head">
						<div class="secondary">
							<div class="orderId">
								<?php _e( 'Order ID', 'woocommerce' ); ?>: <?php echo $order->get_order_number();?> (<?php echo sprintf( _n( '%s item', '%s items', $item_count, 'woocommerce' ), $item_count ); ?>)
							</div>
						</div>
						<div class="secondary">
							<div class="orderDate">
								<?php _e( 'Placed on', 'woocommerce' ); ?>: <time datetime="<?php echo date( 'Y-m-d', strtotime( $order->order_date ) ); ?>" title="<?php echo esc_attr( strtotime( $order->order_date ) ); ?>"><?php echo date_i18n( get_option( 'date_format' ), strtotime( $order->order_date ) ); ?></time>
							</div>
						</div>
					</div>

					<div class="order-head">
						<div class="secondary">
							<div class="orderTotal">
								<?php _e( 'Total amount', 'woocommerce' ); ?>: <?php echo sprintf( _n( '%s', $total_amount, 'woocommerce' ), $total_amount); ?>
							</div>
						</div>
						<div class="secondary">
							<div class="orderStatus">
								<?php _e( 'Status', 'woocommerce' ); ?>: <?php echo wc_get_order_status_name( $order->get_status() ); ?>
							</div>
						</div>
					</div>		

					<div class="order-content">
						<?php
							$actions = array();
								if ( in_array( $order->get_status(), apply_filters( 'woocommerce_valid_order_statuses_for_payment', array( 'pending', 'failed' ), $order ) ) ) {
									$actions['pay'] = array(
										'url'  => $order->get_checkout_payment_url(),
										'name' => __( 'Pay for Order', 'woocommerce' )
									);
								}

								if ( in_array( $order->get_status(), apply_filters( 'woocommerce_valid_order_statuses_for_cancel', array( 'pending', 'failed' ), $order ) ) ) {
									$actions['cancel'] = array(
										'url'  => $order->get_cancel_order_url( wc_get_page_permalink( 'myaccount' ) ),
										'name' => __( 'Cancel Order', 'woocommerce' )
									);
								}

								$actions['view'] = array(
									'url'  => $order->get_view_order_url(),
									'name' => __( 'View Order', 'woocommerce' )
								);

								$actions = apply_filters( 'woocommerce_my_account_my_orders_actions', $actions, $order );

								if ( $actions ) {
									foreach ( $actions as $key => $action ) {
										echo '<a href="' . esc_url( $action['url'] ) . '" class="button ' . sanitize_html_class( $key ) . '">' . esc_html( $action['name'] ) . '</a>';
									}
								}
							?>
					</div>	
				</li>
			<?php } ?>
		</ul>
	</div>
<?php endif; ?>