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
	</script>

    <script>
        (function(d) {
        var config = {
        kitId: 'hzl3mkb',
        scriptTimeout: 3000
        },
        h=d.documentElement,t=setTimeout(function(){h.className=h.className.replace(/\bwf-loading\b/g,"")+" wf-inactive";},config.scriptTimeout),tk=d.createElement("script"),f=false,s=d.getElementsByTagName("script")[0],a;h.className+=" wf-loading";tk.src='//use.typekit.net/'+config.kitId+'.js';tk.async=true;tk.onload=tk.onreadystatechange=function(){a=this.readyState;if(f||a&&a!="complete"&&a!="loaded")return;f=true;clearTimeout(t);try{Typekit.load(config)}catch(e){}};s.parentNode.insertBefore(tk,s)
        })(document);
    </script>

</head>
<body <?php body_class();?>>
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
<?php 
	$header_class="";
	if(is_page() || is_archive()) {
		$header_class.=" active-header";
	}
?>

<header class="<?php echo $header_class;?>">
	<div class="container">
		<div class="header-content">
			<div class="column-3 logo">
				<a href="<?php bloginfo('url');?>"><img src="<?php bloginfo('template_url');?>/dist/images/logo.png" alt=""><span>Viriditas</span></a>
			</div>
			<div class="span-9">								         
				<nav class="main-menu-desktop">
					<?php global $woocommerce; ?> 
						<ul>
							<?php if ( is_user_logged_in() ) { ?>
								<li><a href="<?php echo get_permalink( get_option('woocommerce_myaccount_page_id') ); ?>" title="<?php _e('Profile ','woothemes'); ?>"><?php _e('Profile |','woothemes'); ?></a> 
								<a href="<?php echo wp_logout_url( get_permalink() ); ?>" title="<?php _e('Sign out ','woothemes'); ?>"><?php _e('Sign out','woothemes'); ?></a></li>
							<?php } 
							else { ?>
								<li><a href="<?php echo get_permalink( get_option('woocommerce_myaccount_page_id') ); ?>" title="<?php _e('Login / Sign up','woothemes'); ?>"><?php _e('Login | Sign up','woothemes'); ?></a></li>
						<?php } ?>
						</ul>
						<?php wp_nav_menu( array( 'theme_location' => 'header-menu', 'container' => false, 'items_wrap' => '<ul>%3$s</ul>') ); ?>
				</nav>
			</div>
			<?php /*
			<div class="span-2 login-button">
				<?php global $woocommerce; ?> 
				<a class="cart-contents" href="<?php echo $woocommerce->cart->get_cart_url(); ?>" title="<?php _e('View your shopping cart', 'woothemes'); ?>">
					<span class="count"><?php echo sprintf(_n('%d item', '%d items', $woocommerce->cart->cart_contents_count, 'woothemes'), $woocommerce->cart->cart_contents_count);?></span>
					<span class="fa icon-shopping-cart"></span>
				</a>
				<?php if ( is_user_logged_in() ) { ?>
					<a href="<?php echo get_permalink( get_option('woocommerce_myaccount_page_id') ); ?>" title="<?php _e('Profile ','woothemes'); ?>"><?php _e('Profile /','woothemes'); ?></a> 
					<a href="<?php echo wp_logout_url( get_permalink() ); ?>" title="<?php _e('Sign out ','woothemes'); ?>"><?php _e('Sign out','woothemes'); ?></a>
				 <?php } 
				 else { ?>
					<a href="<?php echo get_permalink( get_option('woocommerce_myaccount_page_id') ); ?>" title="<?php _e('Login / Sign up','woothemes'); ?>"><?php _e('Login / Sign up','woothemes'); ?></a>
				 <?php } ?>
			</div>
			*/?>
		</div>
	</div>
</header>
<!-- Header Ends Here -->