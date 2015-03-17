<?php get_header(); ?>

<?php if ( have_posts() ) : while ( have_posts() ) : the_post(); ?>

<section role="content">
    <div class="container">
		<?php //the_title("<h1>","</h1>");?>
		<h1>Book an Appointment</h1>
		<div class="column-9 border-right">
			<?php the_content();?>
		</div>	
		<?php get_sidebar('appointment');?>
	</div>
</section>

<?php endwhile; endif; ?>

<?php get_footer(); ?>