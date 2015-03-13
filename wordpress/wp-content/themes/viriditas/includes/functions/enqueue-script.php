<?php

/**
* Enqueue scripts
*/

function my_scripts() {
	wp_deregister_script('jquery');
	wp_register_script('jquery', includes_url(). 'js/jquery/jquery.js', null, '',false);
	wp_enqueue_script('jquery');
	wp_enqueue_script( 'app', get_template_directory_uri() . '/dist/js/app.min.js', array(), '', false);
}

add_action( 'wp_enqueue_scripts', 'my_scripts' );
?>