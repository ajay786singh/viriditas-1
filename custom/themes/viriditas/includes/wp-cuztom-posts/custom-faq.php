<?php
//Create team custom post type
$args = array(
    'has_archive' => true,
    //'menu_position' => 5,
    'menu_icon' => 'dashicons-universal-access', //http://melchoyce.github.io/dashicons/
    'supports'  => array( 'title','editor')
    );
$faq = register_cuztom_post_type( 'faq', $args);
?>