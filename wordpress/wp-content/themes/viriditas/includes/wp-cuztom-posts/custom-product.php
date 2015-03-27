<?php
//Create product custom post type
$products = new Cuztom_Post_Type('product');
$products->add_taxonomy( 'Body system' );
$products->add_taxonomy( 'Actions' );
$products->add_taxonomy( 'Indication' );
$products->add_meta_box(
    'Product Details',
    'product_details',
    array(
        array(
            'name'          => 'latin_name',
            'label'         => 'Product Latin Name',
            'description'   => '',
            'type'          => 'text',
        ),
		array(
            'name'          => 'monograph',
            'label'         => 'Monograph',
            'description'   => '',
            'type'          => 'post_checkboxes',
			'args'       => array(
				'post_type' => 'monograph',
			)
        ),
		array(
            'name'          => 'warnings',
            'label'         => 'Warnings & Interactions',
            'description'   => '',
            'type'          => 'wysiwyg'
        ),
		
		array(
            'name'          => 'dosage',
            'label'         => 'Dosage',
            'description'   => '',
            'type'          => 'wysiwyg'
        ),
	)
);	
?>