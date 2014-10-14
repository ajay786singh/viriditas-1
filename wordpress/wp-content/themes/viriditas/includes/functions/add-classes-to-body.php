<?php

/**
* Add Slug in Body class
* @example <?php body_class();?>
*/

function add_slug_to_body_class($classes) {
	global $post;
    	//$classes[] = $post->post_name . ' wrap push';
		$classes[] = ' wrap push';
	return $classes;
}
add_filter('body_class', 'add_slug_to_body_class');
?>