<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no">
    <title><?php wp_title( '|', true, 'right' ); ?><?php bloginfo('name') ?></title>
    <?php 
        $args   =array('post_type' => 'post','posts_per_page' => 1);query_posts($args);
        if (have_posts()) : while(have_posts()) : the_post();
        if (is_single()) { ?>
            <meta property="og:url" content="<?php the_permalink() ?>"/>
            <meta property="og:title" content="<?php single_post_title(''); ?>" />
            <meta property="og:description" content="<?php echo strip_tags(get_the_excerpt($post->ID)); ?>" />
            <meta property="og:type" content="article" />
            <meta property="og:image" content="<?php if (function_exists('catch_that_image')) {echo catch_that_image(); }?>" />
        <?php } else { ?>
            <meta property="og:site_name" content="<?php bloginfo('name'); ?>" />
            <meta property="og:description" content="<?php bloginfo('description'); ?>" />
            <meta property="og:type" content="website" />
            <meta property="og:image" content="<?php bloginfo('template_url' ); ?>/images/logo.png">
    <?php } endwhile; endif; ?>
    <?php wp_reset_query(); ?>    
    <?php wp_head(); ?>
	<script type="text/javascript">
		var ajaxurl = '<?php echo admin_url('admin-ajax.php'); ?>';
		var redirect = '<?php echo $_SERVER['REQUEST_URI']; ?>';
		var shop_page = '<?php echo get_permalink( woocommerce_get_page_id( 'shop' ) );?>';
		var cart_page = '<?php echo get_permalink( woocommerce_get_page_id( 'cart' ) );?>';
		var compound_page = '<?php echo get_permalink(2219);?>';
		var formulas_page = '<?php echo get_permalink(2859);?>';
		var limitHerbForNewCompound = '<?php echo get_option('wc_settings_tab_compound_limit_herb_new');?>';
		var limitHerbForEditCompound = '<?php echo get_option('wc_settings_tab_compound_limit_herb_edit');?>';
	</script>
	<script>
		(function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
		(i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
		m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
		})(window,document,'script','//www.google-analytics.com/analytics.js','ga');

		ga('create', 'UA-63681191-1', 'auto');
		ga('send', 'pageview');
	</script>
</head>
<body <?php body_class();?>>
<?php
	if(is_archive('post-type-archive-product') || is_singular('product') || is_page('2219') || is_page('your-custom-formulas')){ 
		if(!is_user_logged_in()){
			wp_redirect(wp_login_url( current_page_url()));
		}
	}
?>
<?php global $woocommerce; ?> 
<div id="mm-menu-toggle" class="mm-menu-toggle">Menu</div>
  <nav id="mm-menu" class="mm-menu">
    <div class="mm-menu__header">
      <h2 class="mm-menu__title"><a href="<?php bloginfo('url');?>"><img src="<?php bloginfo('template_url');?>/dist/images/logo.png" alt=""><span>Viriditas</span></a></h2>
    </div>
	<?php wp_nav_menu( array( 'theme_location' => 'header-menu', 'container' => false, 'items_wrap' => '<ul>%3$s</ul>') ); ?>      
  </nav><!-- /nav -->
<div id="wrapper" class="wrapper">
<!-- Header Starts Here -->
<header class="<?php echo add_header_class();?>">
	<div class="container">
		<div class="header-content">
			<div class="column-3 logo">
				<a href="<?php bloginfo('url');?>"><img src="<?php bloginfo('template_url');?>/dist/images/logo.png" alt=""><span>Viriditas</span></a>
			</div>
			<div class="column-9">								         
				<nav class="main-menu-desktop">
					<?php global $woocommerce; ?> 
						<?php 
							$host = $_SERVER['HTTP_HOST'];
							if($host=='192.168.1.13' || $host=='localhost' || $host=='hypelabs.ca') {
						?>
						<ul>
							<?php if ( is_user_logged_in() ) { ?>
								<li>
								<?php if(is_archive('post-type-archive-product') || is_singular('product') || is_page('2219')){ 
									$cart_url = $woocommerce->cart->get_cart_url();
								?>
								<a class="icon-cart" href="<?php echo $cart_url;?>">Cart</a>
								<a class="popup-modal" href="#faq-box">*FAQ*</a>								
								<?php 
									if(get_faqs_box_content()) echo get_faqs_box_content();
									} 
								?>
								<a href="<?php echo get_permalink( get_option('woocommerce_myaccount_page_id') ); ?>" title="<?php _e('Profile ','woothemes'); ?>"><?php _e('Profile','woothemes'); ?></a> |  
								<a href="<?php echo wp_logout_url( $_SERVER['REQUEST_URI'] ); ?>" title="<?php _e('Sign out ','woothemes'); ?>"><?php _e('Sign out','woothemes'); ?></a></li>
							<?php } 
							else { ?>
								<li>
									<div>
									<a href="#" class="login_button" id="show_login" title="<?php _e('Login','woothemes'); ?>"><?php _e('Login ','woothemes'); ?></a> | <a href="<?php echo get_bloginfo('url');?>/register" title="<?php _e('Register','woothemes'); ?>"><?php _e('Sign up','woothemes'); ?></a>
									<?php form_login();?>
									</div>
								</li>
						<?php }  ?>
						</ul>
						<?php } else { ?>
							<style>
								.main-menu-desktop ul:nth-child(2) {
									margin-top: 2.5em;
								}
							</style>
						<?php } ?>
						<?php wp_nav_menu( array( 'theme_location' => 'header-menu', 'container' => false, 'items_wrap' => '<ul>%3$s</ul>') ); ?>
				</nav>
			</div>
		</div>
	</div>
</header>
<!-- Header Ends Here -->