<?php

/**
* Get first image from post
* @example <?php catch_that_image(); ?>
*/

function catch_that_image() {
 	global $post, $posts;
  	$first_img = '';
  	ob_start();
  	ob_end_clean();
  	$output = preg_match_all('/<img.+src=[\'"]([^\'"]+)[\'"].*>/i', $post->post_content, $matches);
  	$first_img = $matches [1] [0];
  	if(empty($first_img)){
  		//Defines a default image
  		//$first_img = bloginfo('template_url' ) . "/images/logo_hare.png";
    	}
  	return $first_img;
}
?>