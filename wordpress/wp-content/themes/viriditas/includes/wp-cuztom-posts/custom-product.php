<?php
//Create product custom post type
$products = new Cuztom_Post_Type('product');
add_action( 'init', 'product_remove_post_type_support', 10 );
function product_remove_post_type_support() {
    remove_post_type_support( 'product', 'editor' );
	remove_post_type_support( 'product', 'custom-fields' );
}
$products->add_taxonomy( 'Body system' );
//$products->add_taxonomy( 'Actions' );
$products->add_taxonomy( 'Indication' );
$products->add_meta_box(
    'Product Details',
    'product_details',
    array(
        array(
            'name'          => 'folk_name',
            'label'         => 'Folk Name',
            'description'   => 'Please enter folk name for this herb.',
            'type'          => 'text',
        ),
		array(
			'name'          => 'expensive_herb',
			'label'         => 'Expensive Herb',
			'description'   => 'Please check this box, if herb is expensive.',
			'type'          => 'checkbox'
        ),
		array(
			'name'          => 'monograph_link',
			'label'         => 'Monograph Link',
			'description'   => 'Please enter monograph link for this herb.',
			'type'          => 'text'
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