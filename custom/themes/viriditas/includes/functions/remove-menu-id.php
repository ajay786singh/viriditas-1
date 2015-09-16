<?php

/**
* Clean up menu ids and classes but leave current-menu-item
*/

add_filter('nav_menu_css_class', 'remove_css_attributes_filter', 100, 1);
add_filter('nav_menu_item_id', 'remove_css_attributes_filter', 100, 1);
add_filter('page_css_class', 'remove_css_attributes_filter', 100, 1);
function remove_css_attributes_filter($var) {
	return is_array($var) ? array_intersect($var, array('current-menu-item')) : '';
}
?>