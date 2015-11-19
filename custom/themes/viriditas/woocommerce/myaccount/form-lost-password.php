<?php
/**
 * Lost password form
 *
 * @author  WooThemes
 * @package WooCommerce/Templates
 * @version 2.3.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

?>

<?php wc_print_notices(); ?>
<h2><?php _e( 'Lost password', 'woocommerce' ); ?></h2>
<form method="post" class="account-form lost_reset_password">

	<?php if( 'lost_password' == $args['form'] ) : ?>
		
		<p><?php echo apply_filters( 'woocommerce_lost_password_message', __( 'Lost your password? Please enter your username or email address. You will receive a link to create a new password via email.', 'woocommerce' ) ); ?></p>
		<div class="form-group">
			<label for="user_login"><?php _e( 'Username or email', 'woocommerce' ); ?></label> 
			<input class="input-text" type="text" name="user_login" id="user_login" />
		</div>

	<?php else : ?>

		<p><?php echo apply_filters( 'woocommerce_reset_password_message', __( 'Enter a new password below.', 'woocommerce') ); ?></p>

		<div class="form-group">
			<label for="password_1"><?php _e( 'New password', 'woocommerce' ); ?> <span class="required">*</span></label>
			<input type="password" class="input-text" name="password_1" id="password_1" />
		</div>
		<div class="form-group">
			<label for="password_2"><?php _e( 'Re-enter new password', 'woocommerce' ); ?> <span class="required">*</span></label>
			<input type="password" class="input-text" name="password_2" id="password_2" />
		</div>

		<input type="hidden" name="reset_key" value="<?php echo isset( $args['key'] ) ? $args['key'] : ''; ?>" />
		<input type="hidden" name="reset_login" value="<?php echo isset( $args['login'] ) ? $args['login'] : ''; ?>" />

	<?php endif; ?>

	<div class="clear"></div>

	<div class="form-group">
		<input type="hidden" name="wc_reset_password" value="true" />
		<input type="submit" class="button" value="<?php echo 'lost_password' == $args['form'] ? __( 'Reset Password', 'woocommerce' ) : __( 'Save', 'woocommerce' ); ?>" />
	</div>

	<?php wp_nonce_field( $args['form'] ); ?>

</form>