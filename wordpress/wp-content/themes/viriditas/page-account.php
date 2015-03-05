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
			<?php if(is_page('login')) { ?> 
				<?php the_title("<h1>","</h1>");?>
			<?php } else { ?>
				<h1>Account Setup</h1>
			<?php } ?>
				<?php the_content();?>
		</article>	
	</div>
</section>

<?php endwhile; endif; ?>

<?php get_footer(); ?>