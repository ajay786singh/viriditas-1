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

//Add or Remove column into Product Page
// add_filter('manage_edit-product_columns', 'product_list_into_product_list');
// function product_list_into_product_list($defaults) {
	// unset($defaults['product_tag']);
    // $defaults['_product_details_folk_name'] = 'Folk name';
    // return $defaults;
// }


// Show Coloumn for Products List into Admin
add_filter('manage_product_posts_columns', 'hype_columns_head_only_products', 10);
add_action('manage_product_posts_custom_column', 'hype_columns_content_only_products', 10, 2);

// Functions for Products List into Admin
function hype_columns_head_only_products($defaults) {
	unset($defaults['product_tag']);
    $defaults['_product_details_folk_name'] = 'Folk';
    return $defaults;
}
function hype_columns_content_only_products($column_name, $post_ID) {
    if ($column_name == '_product_details_folk_name') {
        $folk_name=get_post_meta($post_ID,'_product_details_folk_name',true);
		if($folk_name=='') {
			echo "-";
		}
    }
}
?>