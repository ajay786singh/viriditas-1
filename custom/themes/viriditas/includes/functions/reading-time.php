<?php

/**
 * Estimate time required to read the article
 *
 * @return string
 */
function estimated_reading_time() {

	$post = get_post();

	$words = str_word_count( strip_tags( $post->post_content ) );
	$minutes = floor( $words / 200 );
	$seconds = floor( $words % 200 / ( 200 / 60 ) );

	if ( 1 <= $minutes && $seconds >= 30 ) {
		//$estimated_time = $minutes . ' minute' . ($minutes == 1 ? '' : 's') . ', ' . $seconds . ' second' . ($seconds == 1 ? '' : 's');
		//$estimated_time = ($minutes + 1) . ' minute' . ($minutes == 1 ? '' : 's');
		$estimated_time = ($minutes + 1) . ' minute read';
	} elseif ( 1 <= $minutes && $seconds <= 30 ) {
		//$estimated_time = $minutes . ' minute' . ($minutes == 1 ? '' : 's');
		$estimated_time = $minutes . ' minute read';
	} else {
		//$estimated_time = $seconds . ' second' . ($seconds == 1 ? '' : 's');
		$estimated_time = ' Less than 1 minute';
	}

	return $estimated_time;

}
?>