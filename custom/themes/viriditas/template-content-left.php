<?php 
/*
* Template Name: Page Content left Align
*/
get_header(); ?>

<?php if ( have_posts() ) : while ( have_posts() ) : the_post(); ?>

<section role="content">
    <div class="container">
		<div class="inner content-left">
			<?php the_title("<h1>","</h1>");?>
			<?php the_content();?>
		</div>	
	</div>
</section>

<?php endwhile; endif; ?>

<?php get_footer(); ?>