<?php

/**
* Content sidebar shortcode
* @example [contentside]Text/HTML content[/contentside]
*/

function content_sidebar_shortcode( $atts, $content = null  ) {
	extract( shortcode_atts( array(), $atts ) );
	return '<div class="radius-blue">' . do_shortcode($content) . '</div>';
	}

add_shortcode( 'contentside', 'content_sidebar_shortcode' );

?>
