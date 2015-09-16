<?php
// Functions to Customize wp-login.php form
/**
* Add custom logo to wp login screen
* Image dimensions = 310px/70px
*/

function hype_login_logo() {
    echo '<style type="text/css">
		body {
			background:#fff;
		}
        h1 a { 
			background-size: contain!important;
			background-image:url('.get_bloginfo('template_directory').'/dist/images/logo.png) !important;
			height:85px!important;
			width:68px!important;	
		}
		h4 {
			color:#777;
			text-align:center;
			text-transform:uppercase;
		}
    </style>';
}
add_action('login_head', 'hype_login_logo');

/* Function to add login form*/
add_action('login_message', 'hype_login_message');
function hype_login_message() {
	echo "<h4>To order Viriditas Herbal Products <br>Please log in</h4>";
}
/*change instances of 'Register' to 'New? Register' on login / register page*/
add_filter(  'gettext',  'register_text'  );
add_filter(  'ngettext',  'register_text'  );
function register_text( $translated ) {
     $translated = str_ireplace(  'Register',  'New? Register',  $translated );
     return $translated;
}

add_action( 'login_form_register', 'register_page_redirect' );
/**
 * Redirects to `wp-login.php?action=register` to 
 * site register page
 */
function register_page_redirect() {
    wp_redirect( home_url( '/register' ) );
    exit(); // always call `exit()` after `wp_redirect`
}
?>