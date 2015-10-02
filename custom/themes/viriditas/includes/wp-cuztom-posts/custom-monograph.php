<?php // Resources Post type

$args = array(
    'has_archive' => true,
    //'menu_position' => 5,
    'menu_icon' => 'dashicons-welcome-learn-more', //http://melchoyce.github.io/dashicons/
    'supports'  => array( 'title','thumbnail' )
    );

$monograph = register_cuztom_post_type( 'Monograph', $args);

$monograph->add_meta_box(
    'Monograph Details',
    'monograph_details',
    array(
        array(
            'name'          => 'sub_heading',
            'label'         => 'Sub heading',
            'description'   => '',
            'type'          => 'text'
        ),
		array(
            'name'          => 'composition',
            'label'         => 'Composition',
            'description'   => 'Please enter product id(s) separated with comma(,).',
            'type'          => 'text'
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
            'name'          => 'dosage',
            'label'         => 'Dosage',
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