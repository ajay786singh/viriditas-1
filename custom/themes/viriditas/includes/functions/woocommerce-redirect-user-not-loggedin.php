<?php 
/*
* Function to redirect not logged in user to login page
*/
function user_redirect_woocommerce() {
    if (
        ! is_user_logged_in()
        && (is_woocommerce() || is_cart() || is_checkout() || is_page('2219') || is_page('your-custom-formulas') || is_singular('monograph'))
    ) {
        // feel free to customize the following line to suit your needs
        wp_redirect(wp_login_url(current_page_url()));
        exit;
    }
}

add_filter('woocommerce_login_redirect', 'woocommerce_after_login_redirect');
function woocommerce_after_login_redirect( $redirect_to ) {
	$redirect_to=$_REQUEST['redirect_to'];
     return $redirect_to;
}

function wp_login_screen_redirect() {
    global $pagenow;
    if ($pagenow == 'wp-login.php' && !is_user_logged_in()) {
		$redirect_to=$_REQUEST['redirect_to'];
		$loginPageURL=get_bloginfo('url')."/my-account/";
		if($redirect_to!='') {
			$loginPageURL.="?redirect_to=".$redirect_to;
		}
        wp_redirect($loginPageURL);
    }
}
add_action('init', 'wp_login_screen_redirect');
?>