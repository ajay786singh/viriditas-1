<?php

/**
* Button shortcode
* @example [button url=http://www.example.com]Text[/button]
*/

function button_shortcode( $atts, $content = null ) {
	extract( shortcode_atts( array( "url" => ''), $atts ) );
	return '<div class="radius-blue" style="text-align: center; padding: 2px;"><h3><a href="' . $url . '">' . do_shortcode($content) . '</a></h3></div>';
	}

add_shortcode( 'button', 'button_shortcode' );

?>