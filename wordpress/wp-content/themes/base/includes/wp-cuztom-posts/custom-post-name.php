<?php
//Create article custom post type
$heroes = register_cuztom_post_type( 'Heroes', array( 'supports' => array('title','editor', 'excerpt','thumbnail')));
    $heroes->add_taxonomy( 'post_tag' );

//Organize admin columns for article
function heroes_columns( $cols ) {
  $cols = array(
    'cb'        => '<input type="checkbox" />',
    'title'     => __( 'Title', 'trans' ),
    'tags'      => __( 'Tags', 'trans' ),
    'date'      => __( 'Date', 'trans' )
);
  
  return $cols;
}

add_filter( "manage_heroes_posts_columns", "heroes_columns" );
?>