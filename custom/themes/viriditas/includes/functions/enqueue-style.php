<?php

/**
* Enqueue styles
*/

function theme_styles() {
	wp_enqueue_style( 'google-fonts', '//fonts.googleapis.com/css?family=Playfair+Display:400,700,900,400italic,700italic,900italic|Roboto:400,100,100italic,300,300italic,400italic,500,500italic,700,700italic,900,900italic' );
 	wp_register_style('style', get_template_directory_uri() . '/style.css');
 	wp_enqueue_style( 'style' );
}
add_action('wp_enqueue_scripts', 'theme_styles');

function plugins_deregister_styles() {
	wp_deregister_style('woocommerce-layout');
	wp_deregister_style('woocommerce-smallscreen');
	wp_deregister_style('woocommerce-general');
	wp_deregister_style('wc-bundle-style');
	wp_dequeue_style( "appointments" ); 
	wp_deregister_style('tablepress-default');
	wp_deregister_style('tablepress-responsive-tables');
}
add_action('wp_print_styles', 'plugins_deregister_styles',100);

add_action( 'gform_enqueue_scripts', 'gform_dequeue_script_list' );
function gform_dequeue_script_list() { 
    global $wp_styles; 
    
	if( isset($wp_styles->registered['gforms_reset_css']) ) {
		unset( $wp_styles->registered['gforms_reset_css'] );
	}	
	if( isset($wp_styles->registered['gforms_browsers_css']) ) {
        unset( $wp_styles->registered['gforms_browsers_css'] );
    }
	if( isset($wp_styles->registered['gforms_formsmain_css']) ) {
		unset( $wp_styles->registered['gforms_formsmain_css'] );
	}
	if( isset($wp_styles->registered['gforms_ready_class_css']) ) {
		unset( $wp_styles->registered['gforms_ready_class_css'] );
	}
}
?>