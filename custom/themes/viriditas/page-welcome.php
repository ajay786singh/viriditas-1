<?php 
/*
*Template Name: Registration almost complete
*/

get_header(); ?>

<?php if ( have_posts() ) : while ( have_posts() ) : the_post(); ?>

<section role="content">
    <div class="container">
		<div class="content-grid">
			<?php the_title("<h1>","</h1>");?>
			<?php 
				the_content();	
			?>
		</div>	
	</div>
</section>

<?php endwhile; endif; ?>

<?php get_footer(); ?>