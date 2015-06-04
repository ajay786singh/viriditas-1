<?php get_header(); ?>
<section role="content">
    <div class="container">
		<?php get_sidebar();?>
		<div class="column-7">
			<?php 
				if ( have_posts() ) : while ( have_posts() ) : the_post();
					get_template_part( 'template', 'loop' );
				endwhile;endif;
				pagination();
				wp_reset_query();
			?>
		</div>
	</div>
</section>	
<?php get_footer(); ?>