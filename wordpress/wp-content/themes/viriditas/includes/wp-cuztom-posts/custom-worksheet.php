<?php
//Create team custom post type
$args = array(
    'has_archive' => true,
    //'menu_position' => 5,
    'menu_icon' => 'dashicons-universal-access', //http://melchoyce.github.io/dashicons/
    'supports'  => array( 'title')
    );
$worksheet = register_cuztom_post_type( 'worksheet', $args);
$worksheet->add_taxonomy( 'Worksheet Category' );
$worksheet->add_meta_box(
    'Worksheet Details',
    'worksheet_details',
    array(
        array(
            'name'          => 'file',
            'label'         => 'Upload File',
            'description'   => 'Please upload file.',
            'type'          => 'file',
        ),
	)
);
?>