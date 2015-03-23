<?php
//Include cuztom helper files https://github.com/Gizburdt/Wordpress-Cuztom-Helper
include('includes/wp-cuztom-helper/cuztom.php');

//Include post custom posts type. Dependent on /wp-cuztom-helper classes.
include('includes/wp-cuztom-posts/custom-course.php');
include('includes/wp-cuztom-posts/custom-page.php');
include('includes/wp-cuztom-posts/custom-product.php');
include('includes/wp-cuztom-posts/custom-services.php');
include('includes/wp-cuztom-posts/custom-team.php');

//Load custom functions
require_once('includes/functions/add-classes-to-body.php');
require_once('includes/functions/enqueue-style.php');
require_once('includes/functions/enqueue-script.php');
require_once('includes/functions/first-image.php');
require_once('includes/functions/image-support.php');
require_once('includes/functions/page-excerpts.php');
require_once('includes/functions/pagination.php');
require_once('includes/functions/recent-post.php');
require_once('includes/functions/register-menu.php');
require_once('includes/functions/remove-menu-id.php');
require_once('includes/functions/add-woocommerce-support.php');
require_once('includes/functions/add-cart-link-header.php');
require_once('includes/functions/woocommerce-ajax.php');
require_once('includes/functions/get-term-top-level-parent.php');
require_once('includes/functions/register-widgets.php');
require_once('includes/functions/courses.php');
// Load Login/Register
require_once('includes/login-register/register.php');
require_once('includes/login-register/login.php');
//Load shortcodes
//require_once('includes/shortcodes/button.php');
//require_once('includes/shortcodes/content.php');
//require_once('includes/shortcodes/content-sidebar.php');
//require_once('includes/shortcodes/readmore.php');
require_once('includes/shortcodes/tab.php');
?>