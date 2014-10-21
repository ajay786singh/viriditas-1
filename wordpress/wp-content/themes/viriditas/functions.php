<?php

//Include cuztom helper files https://github.com/Gizburdt/Wordpress-Cuztom-Helper
include('includes/wp-cuztom-helper/cuztom.php');

//Include post custom posts type. Dependent on /wp-cuztom-helper classes.
include('includes/wp-cuztom-posts/custom-course.php');

//Load custom functions
//require_once('includes/functions/add-classes-to-body.php');
//require_once('includes/functions/admin-tinymce.php');
//require_once('includes/functions/custom-login-logo.php');
require_once('includes/functions/enqueue-style.php');
require_once('includes/functions/enqueue-script.php');
require_once('includes/functions/first-image.php');
require_once('includes/functions/image-support.php');
require_once('includes/functions/page-excerpts.php');
require_once('includes/functions/pagination.php');
require_once('includes/functions/reading-time.php');
require_once('includes/functions/recent-post.php');
require_once('includes/functions/register-menu.php');
//require_once('includes/functions/remove-header-meta.php');
require_once('includes/functions/remove-menu-id.php');
//require_once('includes/functions/remove-wp-version.php');
//require_once('includes/functions/walker-menu-description.php');

//Load shortcodes
//require_once('includes/shortcodes/accordion.php');
//require_once('includes/shortcodes/button.php');
//require_once('includes/shortcodes/content.php');
//require_once('includes/shortcodes/content-sidebar.php');
//require_once('includes/shortcodes/readmore.php');
//require_once('includes/shortcodes/tab.php');

?>