<?php

/**
* Content shortcode
* @example [content]Text/HTML content[/content]
*/

function content_shortcode( $atts, $content = null  ) {
	extract( shortcode_atts( array(), $atts ) );
	return '<div class="radius-lightblue">' . do_shortcode($content) . '</div>';
	}

add_shortcode( 'content', 'content_shortcode' );

?>