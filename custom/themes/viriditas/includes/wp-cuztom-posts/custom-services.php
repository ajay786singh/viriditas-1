<?php
//Create services custom post type
$args = array(
    'has_archive' => true,
    //'menu_position' => 5,
    'menu_icon' => 'dashicons-portfolio', //http://melchoyce.github.io/dashicons/
    'supports'  => array( 'title','editor','thumbnail' )
    );

$course = register_cuztom_post_type( 'service', $args);

?>