<?php

/**
* Get recent posts
* @example <?php recentposts(); ?>
*/
	
function recentposts() {
	$args = array( 'numberposts' => '5', 'post_status' => 'publish', 'cat' => -743 );
	$recent_posts = wp_get_recent_posts( $args );
	echo '<ul>';
	foreach( $recent_posts as $recent ){
	echo '<li><a href="' . get_permalink($recent["ID"]) . '" >' .   $recent["post_title"].'</a></li> ';
	}
	echo '</ul>';
}
?>