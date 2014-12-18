<?php

/**
* Enqueue styles
*/

function my_styles() {
	wp_register_style('style', get_template_directory_uri() . '/dist/css/style.css');
 	wp_enqueue_style( 'style' );
 	wp_enqueue_style( 'font-awesome', '//maxcdn.bootstrapcdn.com/font-awesome/4.2.0/css/font-awesome.min.css' );
}

add_action('wp_enqueue_scripts', 'my_styles');

?>