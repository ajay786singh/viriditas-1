<?php

/**
* Register Wordpress menus
*/

function register_my_menus() {
  register_nav_menus(
    array(
      'header-menu' => __( 'Header Menu' ),
      //'extra-menu' => __( 'Extra Menu' )
    )
  );
}
add_action( 'init', 'register_my_menus' );
/*Add Shop Page url*/
add_filter( 'wp_nav_menu_items', 'your_custom_menu_item', 10, 2 );
function your_custom_menu_item ( $items, $args ) {
    if (is_user_logged_in() && $args->theme_location == 'header-menu' ) {
		$shopPage=get_permalink( woocommerce_get_page_id( 'shop' ) );
        $items.= '<li><a href="'.$shopPage.'">Products</a></li>';
    }
    return $items;
}
?>