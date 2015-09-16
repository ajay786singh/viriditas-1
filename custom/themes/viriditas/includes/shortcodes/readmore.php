<?php

/**
* Readmore button shortcode
* @example [readmore url=http://www.example.com]Text[/readmore]
*/
	
function readmore_shortcode( $atts, $content = null  ) {
	extract( shortcode_atts( array("url" => ''), $atts ) );
	return '<p class="read-more"><a href="' . $url . '">' . do_shortcode($content) . '</a></p>';
	}

add_shortcode( 'readmore', 'readmore_shortcode' );

?>