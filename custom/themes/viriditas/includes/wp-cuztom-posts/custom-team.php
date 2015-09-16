<?php
//Create team custom post type
$args = array(
    'has_archive' => true,
    //'menu_position' => 5,
    'menu_icon' => 'dashicons-groups', //http://melchoyce.github.io/dashicons/
    'supports'  => array( 'title','editor','thumbnail','page-attributes' )
    );
	
$team = register_cuztom_post_type( 'team', $args);

$team->add_taxonomy( 'team category' );
$team->add_meta_box(
    'content_block',
    'Content Area (Optional)', 
    array(
        array(
            'name'          => 'designation',
            'label'         => 'Designation',
            'description'   => 'This will display under Team Member Name.',
            'type'          => 'text',
            
        )        
    )
);
?>