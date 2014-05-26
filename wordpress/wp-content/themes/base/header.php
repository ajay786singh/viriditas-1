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
    <script>document.cookie='resolution='+Math.max(screen.width,screen.height)+'; path=/';</script>
    
    <?php wp_head(); ?>
    
    <script type="text/javascript">
        /*Typekit
        (function() {
        var config = {
        kitId: 'set7rcj',
        scriptTimeout: 3000
        };
        var h=document.getElementsByTagName("html")[0];h.className+=" wf-loading";var t=setTimeout(function(){h.className=h.className.replace(/(\s|^)wf-loading(\s|$)/g," ");h.className+=" wf-inactive"},config.scriptTimeout);var tk=document.createElement("script"),d=false;tk.src='//use.typekit.net/'+config.kitId+'.js';tk.type="text/javascript";tk.async="true";tk.onload=tk.onreadystatechange=function(){var a=this.readyState;if(d||a&&a!="complete"&&a!="loaded")return;d=true;clearTimeout(t);try{Typekit.load(config)}catch(b){}};var s=document.getElementsByTagName("script")[0];s.parentNode.insertBefore(tk,s)
        })();

        /*Google Analytics
        var _gaq = _gaq || [];
        _gaq.push(['_setAccount', 'UA-1322597-2']);
        _gaq.push(['_trackPageview']);

        (function() {
            var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
            ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
            var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
        })();
        */
    </script>

</head>

<body <?php body_class();?> data-page="index">

<!-- Pushy Menu -->
<nav class="pushy pushy-right">
    <?php wp_nav_menu();?>        
    <div class="additional-links">
        <ul>
            <li><a href="#">Link 1</a></li>
            <li><a href="#">Link 2</a></li>
            <li><a href="#">Link 3</a></li>
        </ul>  
    </div>
    <span class="social-icons">
        <!--
        <a href="#" target="_blank"><img alt="youtube icon" src="<?php bloginfo( 'template_url' ) ?>/images/icon-youtube.png"></a>
        <a href="#" target="_blank"><img alt="pintrest icon" src="<?php bloginfo( 'template_url' ) ?>/images/icon-pintrest.png"></a>
        <a href="#" target="_blank"><img alt="instagram icon" src="<?php bloginfo( 'template_url' ) ?>/images/icon-instagram.png"></a>
        -->
    </span>  
</nav>

<!-- Site Overlay for Pushy offcanvas to work -->
<div class="site-overlay"></div>


<!--Wrapper Starts for Pushy to work-->
<div class="wrapper">
    <header>
        <div class="topbar">
           <div class="container" >
                <div class="span-12">
                    <a href="<?php bloginfo( 'url' ) ?>"><span class="logo">Logo</span></a><span class="sublogo">Tagline</span>
                </div>
                <div class="menu-btn">Menu &#9776;</div>
            </div>
            
            <div id="search">
                <?php get_search_form() ?>
            </div>
        </div>
        <div class="banner">
            <h3>Banner</h3> 
            <h4>Tagline</h4>
        </div>
    </header>