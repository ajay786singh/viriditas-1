<?php
//Create product custom post type
$products = new Cuztom_Post_Type('product');
$products->add_taxonomy( 'Body system' );
$products->add_taxonomy( 'Actions' );
$products->add_taxonomy( 'Indication' );
?>