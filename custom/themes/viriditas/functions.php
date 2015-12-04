<?php
//Include cuztom helper files https://github.com/Gizburdt/Wordpress-Cuztom-Helper
include('includes/wp-cuztom-helper/cuztom.php');
//Include post custom posts type. Dependent on /wp-cuztom-helper classes.
include('includes/wp-cuztom-posts/custom-course.php');
include('includes/wp-cuztom-posts/custom-page.php');
include('includes/wp-cuztom-posts/custom-services.php');
include('includes/wp-cuztom-posts/custom-team.php');
include('includes/wp-cuztom-posts/custom-monograph.php');
include('includes/wp-cuztom-posts/custom-faq.php');
include('includes/wp-cuztom-posts/custom-worksheet.php');
include('includes/wp-cuztom-posts/custom-product.php');

//Load custom functions
require_once('includes/functions/add-classes-to-body.php');
require_once('includes/functions/custom-login-logo.php');
require_once('includes/functions/woocommerce-redirect-user-not-loggedin.php');
require_once('includes/functions/add-current-page-url.php');
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
require_once('includes/functions/woocommerce-compound-product-settings.php');
require_once('includes/functions/woocommerce-compound.php');
require_once('includes/functions/woocommerce-add-to-cart.php');
require_once('includes/functions/woocommerce-order-again.php');
require_once('includes/functions/add-cart-link-header.php');
require_once('includes/functions/get-term-top-level-parent.php');
require_once('includes/functions/register-widgets.php');
require_once('includes/functions/courses.php');
require_once('includes/functions/appointments.php');
require_once('includes/functions/faq.php');
require_once('includes/functions/edit-tag-cloud.php');
require_once('includes/functions/add-favicon.php');
require_once('includes/functions/user-email-message-change.php');
require_once('includes/functions/remove-query-strings-from-static-resources.php');
require_once('includes/functions/force-ssl-gravity-forms.php');
//Remove Emoji Script From Site
require_once('includes/functions/remove-wordpress-emoji.php');
// Woocommerce Functions
require_once('includes/functions/woocommerce-edit-user-details.php');
require_once('includes/functions/woocommerce-ajax.php');
require_once('includes/functions/add-single-cart.php');

// Load Login/Register
require_once('includes/login-register/register.php');
require_once('includes/login-register/login.php');

//Load shortcodes
require_once('includes/widgets/appointment-forms.php');

//Load Theme Options
require_once('includes/theme-options/theme-options.php');
//Load shortcodes
require_once('includes/shortcodes/tab.php');
?>