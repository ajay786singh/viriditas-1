<?php

/**
* Remove junk from header
*/

remove_action('wp_head', 'rsd_link'); 				//Remove Really Simple Discovery (only really need this if you're using Flickr or similiar service)
remove_action('wp_head', 'wlwmanifest_link'); 		//Remove Windows Live Writer
remove_action('wp_head', 'start_post_rel_link');	//Remove Post Relational Links
remove_action('wp_head', 'index_rel_link');        	//Remove Post Relational Links
remove_action('wp_head', 'adjacent_posts_rel_link');//Remove Post Relational Links
remove_action('wp_head', 'wp_generator'); 			//Remove WP Generator

?>