<?php
/*
*  Template Name: Login / Register Page Template
*/
?>
<?php get_header(); ?>

<?php if ( have_posts() ) : while ( have_posts() ) : the_post(); ?>

<section role="content">
    <div class="container">
		<article class="secondary">
			<?php 
				if ( !is_user_logged_in() )  {
					if(is_page('login')) { 
						the_title("<h1>","</h1>");
					} else if(is_page('register'))  {
						echo "<h1>Account Setup</h1>";
					}
					the_content();
				}
			?>
		</article>	
	</div>
</section>
<?php endwhile; endif; ?>
<?php get_footer(); ?>