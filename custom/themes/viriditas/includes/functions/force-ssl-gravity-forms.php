<?php
// redirect all gravity forms to ssl
add_action( 'template_redirect', 'sav_force_ssl_for_contact_forms', 1 );
function sav_force_ssl_for_contact_forms() {
	if ( (is_single() || is_page()) && ! is_ssl() ) {
		global $post;
		
		if ( ! preg_match( '#\[gravityform#msiU', $post->post_content ) )
			return;

		if ( preg_match( '#^http://#', $_SERVER['REQUEST_URI'] ) ) {
			wp_redirect( preg_replace( '#^http://#', 'https://', $_SERVER['REQUEST_URI'] ), 301 );
			exit();
		} else {
			wp_redirect( 'https://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'], 301 );
			exit();
		}
	}
}
?>