<?php // Resources Post type

$args = array(
    'has_archive' => true,
    //'menu_position' => 5,
    'menu_icon' => 'dashicons-welcome-learn-more', //http://melchoyce.github.io/dashicons/
    'supports'  => array( 'title' )
    );

$resource = register_cuztom_post_type( 'Resource', $args);
$resource->add_taxonomy( 'Indication' );
$resource->add_taxonomy( 'Actions' );

$resource->add_meta_box(
    'Resource Details',
    'resource_details',
    array(
        array(
            'name'          => 'composition',
            'label'         => 'Composition',
            'description'   => '',
            'type'          => 'wysiwyg',
        ),
		array(
            'name'          => 'preparation',
            'label'         => 'Preparation',
            'description'   => '',
            'type'          => 'wysiwyg',
        ),
		array(
            'name'          => 'synthesis',
            'label'         => 'Synthesis and Mechanism of Action',
            'description'   => '',
            'type'          => 'wysiwyg',
        ),
		array(
            'name'          => 'contradictions',
            'label'         => 'Contradictions, Warnings and Interactions',
            'description'   => '',
            'type'          => 'wysiwyg',
        ),
		array(
            'name'          => 'synergy',
            'label'         => 'Synergy & Compounding',
            'description'   => '',
            'type'          => 'wysiwyg',
        ),
    )
);
?>