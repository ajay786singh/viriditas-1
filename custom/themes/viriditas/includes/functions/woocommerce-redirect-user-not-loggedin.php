<?php 
/*
* Function to redirect not logged in user to login page
*/
add_action('template_redirect', 'user_redirect_woocommerce');
function user_redirect_woocommerce() {
    if (
        ! is_user_logged_in()
        && (is_woocommerce() || is_cart() || is_checkout() || is_page('2219') || is_page('your-custom-formulas') || is_singular('monograph'))
    ) {
        // feel free to customize the following line to suit your needs
		$redirect=get_bloginfo('url')."/my-account?redirect_to=".current_page_url();
		//wp_redirect(wp_login_url(current_page_url()));
		wp_redirect($redirect);
        exit;
    }
}

/**
 * Redirect users to custom URL based on their role after login
 *
 * @param string $redirect
 * @param object $user
 * @return string
 */
function wc_custom_user_redirect( $redirect, $user ) {
	// Get the first of all the roles assigned to the user
	$role = $user->roles[0];
	$dashboard = admin_url();
	$myaccount = get_permalink( wc_get_page_id( 'myaccount' ) );
	if( $role == 'administrator' ) {
		//Redirect administrators to the dashboard
		$redirect = $dashboard;
	} elseif ( $role == 'shop-manager' ) {
		//Redirect shop managers to the dashboard
		$redirect = $dashboard;
	} elseif ( $role == 'editor' ) {
		//Redirect editors to the dashboard
		$redirect = $dashboard;
	} elseif ( $role == 'author' ) {
		//Redirect authors to the dashboard
		$redirect = $dashboard;
	} elseif ( $role == 'customer' || $role == 'subscriber' ) {
		//Redirect customers and subscribers to the "My Account" page
		if($_REQUEST['redirect_to']!='') {
			$redirect = $_REQUEST['redirect_to'];	
		}else {
			$redirect = $myaccount;	
		}
		
	} else {
		//Redirect any other role to the previous visited page or, if not available, to the home
		$redirect = wp_get_referer() ? wp_get_referer() : home_url();
	}
	return $redirect;
}
add_filter( 'woocommerce_login_redirect', 'wc_custom_user_redirect', 10, 2 );
?>