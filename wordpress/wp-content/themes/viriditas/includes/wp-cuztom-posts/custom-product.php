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
	)
);	
?>