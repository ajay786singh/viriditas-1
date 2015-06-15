<?php
/*
* Function to add favicon to wp admin
*/	
add_action('admin_head', 'show_favicon');
add_action('login_head', 'show_favicon');
add_action('wp_head', 'show_favicon');
function show_favicon() {
	$url=get_bloginfo('template_url')."/favicon.ico";
	echo '<link href="'.$url.'" rel="icon" type="image/x-icon">';
}