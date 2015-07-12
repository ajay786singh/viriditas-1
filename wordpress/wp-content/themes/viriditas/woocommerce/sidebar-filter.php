<div class="column-3 sidebar">
	<?php if(is_user_logged_in()){ 	
			$your_compound_page=1391;
			query_posts('post_type=page&p='.$your_compound_page);
			if(have_posts()):while(have_posts()):the_post();
	?>
	<section role="own-compound">
		<div class="shop-header">
			<h6 class="heading"><a href="<?php the_permalink();?>"><?php the_title();?></a></h6>
		</div>
	</section>
	<?php 
		endwhile;endif;wp_reset_query();
	}
	?>
	<section role="category"><?php get_product_categories();?></section>
	<section role="body-systems"></section>	
	<section role="actions"></section>	
	<section role="indications"></section>	
</div>