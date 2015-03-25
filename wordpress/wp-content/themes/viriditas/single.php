<?php get_header(); ?>

<?php if ( have_posts() ) : while ( have_posts() ) : the_post(); ?>

<section role="content">
	<article>
		<div class="inner">
			<h1><?php the_title(); ?></h1>
			<small><?php the_author_posts_link(); ?>, <?php echo get_the_date(); ?> in <?php echo get_the_category_list(' / '); ?></small>    
			<?php the_content(); 
				single_posts_nav();
			?>
			
		</div>
	</article>
</section>

<?php endwhile; endif; ?>

<?php get_footer(); ?>