<?php

/**
* Enqueue styles
*/

function my_styles() {
	wp_enqueue_style( 'font-awesome', 'http://maxcdn.bootstrapcdn.com/font-awesome/4.2.0/css/font-awesome.min.css' );
 	wp_enqueue_style( 'font-playfair', 'http://fonts.googleapis.com/css?family=Playfair+Display:400,700,900,400italic,700italic,900italic' );
	wp_enqueue_style( 'font-roboto', 'http://fonts.googleapis.com/css?family=Roboto:400,100,100italic,300,300italic,400italic,500,500italic,700,700italic,900,900italic' );
	wp_register_style('style', get_template_directory_uri() . '/style.css');
 	wp_enqueue_style( 'style' );
}

add_action('wp_enqueue_scripts', 'my_styles');

?>