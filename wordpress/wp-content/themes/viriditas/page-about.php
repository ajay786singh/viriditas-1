<?php get_header(); ?>

<?php if ( have_posts() ) : while ( have_posts() ) : the_post(); ?>

<section role="content">
    <div class="container">
		<div class="content-grid">
			<?php
				$our_philosophy_page_id=1012;
				query_posts('post_type=page&p='.$our_philosophy_page_id);
				if(have_posts()):
					while(have_posts()):the_post();
						the_title("<h1>","</h1>");
						get_template_part( 'template', 'sub_heading' );
						echo "<div class='content-grid'>";
						the_content();
						echo "</div>";
					endwhile;
				endif; wp_reset_query();	
			?>
		</div>	
	</div>

	<div class="container divider" id="services">
		<div class="content-grid">
			<?php
				$our_services_page_id=1014;
				query_posts('post_type=page&p='.$our_services_page_id);
				if(have_posts()):
					while(have_posts()):the_post();
						the_title("<h1>","</h1>");
						get_template_part( 'template', 'sub_heading' );
						echo "<div class='content-grid'>";
						the_content();
						echo "</div>";
					endwhile;
				endif; wp_reset_query();	
			?>
		</div>	
	</div>
	<?php 
		query_posts("post_type=service&showposts=-1");
		if(have_posts()):
		while(have_posts()):the_post();
	?>
	<div class="oddeven">
		<div class="container">
			<span class="image">
				<?php echo get_the_post_thumbnail($post->ID, 'full'); ?>
			</span>
			<span class="content">
				<?php the_title("<h3>","</h3>"); ?>
				<?php the_content();?>
			</span>  
		</div>	
	</div>
	<?php endwhile; endif; wp_reset_query(); ?>
	
</section>

<?php endwhile; endif; ?>

<?php get_footer(); ?>