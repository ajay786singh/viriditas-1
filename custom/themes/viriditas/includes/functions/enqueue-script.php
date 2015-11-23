<?php
/**
* Enqueue scripts
*/
function my_scripts() {
	wp_deregister_script('jquery');
	wp_register_script('jquery', includes_url(). 'js/jquery/jquery.js', null, '',false);
	wp_enqueue_script('jquery');
	wp_enqueue_script( 'map', '//maps.google.com/maps/api/js?sensor=true', array(), '1.0.0', true);
	wp_enqueue_script( 'app', get_template_directory_uri() . '/dist/js/app.min.js', array(), '', false);

}
add_action( 'wp_enqueue_scripts', 'my_scripts' );

/* Enqueue Script for datepick function for appointment+ plugin  */
function appointment_enqueue_admin_script( $hook ) {
    if ( 'user-edit.php' != $hook ) {
        return;
    }
    wp_enqueue_script( 'appintment-datepick', plugin_dir_url('appointments').'appointments/js/jquery.datepick.min.js', array(), '1.0' );
}
add_action( 'admin_enqueue_scripts', 'appointment_enqueue_admin_script' );
?>