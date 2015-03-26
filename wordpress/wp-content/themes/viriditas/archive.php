<?php
/*
Template Name: Blog Archive
*/
get_header(); ?>

<section role="content">
    <div class="container">
		<?php get_sidebar();?>
		<div class="column-7">
			<?php 
				$args  = array(
					'posts_per_page' => 10,
					'paged' => $paged,
				);
				query_posts($args);
				if ( have_posts() ) : while ( have_posts() ) : the_post(); ?>
                <div class="post-course">
                    <h4><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h4> 
					<div class="meta">
						<ul>
							<li><?php the_author_posts_link(); ?></li>
							<li><?php echo get_the_date(); ?> in <?php echo get_the_category_list(' / ');?></li>
						</ul>
					</div>			
					<p><?php echo wp_trim_words(get_the_excerpt(), 50, '...'); ?></p>
                </div>
			<?php 
				endwhile;endif;
				pagination();
				wp_reset_query();
			?>
		</div>
	</div>
</section>	
<?php get_footer(); ?>