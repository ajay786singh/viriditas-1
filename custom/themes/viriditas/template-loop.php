<div class="post-course">
	<h4><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h4> 
	<div class="meta">
		<ul>
			<li><?php the_author_posts_link(); ?></li>
			<li><?php echo get_the_date(); ?> in <?php echo get_the_category_list(' / ');?></li>
		</ul>
	</div>			
	<p><?php echo wp_trim_words(get_the_excerpt(), 50, '...'); ?></p>
	<a href="<?php the_permalink();?>" class="read-more">Read More</a>
</div>