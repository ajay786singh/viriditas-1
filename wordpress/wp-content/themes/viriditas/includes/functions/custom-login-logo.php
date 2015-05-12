<?php

/**
* Add custom logo to wp login screen
* Image dimensions = 310px/70px
*/

function my_custom_login_logo() {
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
    </style>';
}

add_action('login_head', 'my_custom_login_logo');

?>