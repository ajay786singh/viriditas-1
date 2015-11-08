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
add_action('template_redirect', 'user_redirect_woocommerce');
?>