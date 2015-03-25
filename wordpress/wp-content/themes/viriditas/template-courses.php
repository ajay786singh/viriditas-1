<?php
/*
Template Name: Course Homepage
*/
get_header(); ?>

<section role="content">
    <div class="container">
		<?php get_sidebar('courses');?>
		<div class="column-7">
				<?php get_courses();?>
				<?php 
					$post_type='course';
					$before_date = date('F d, Y');
					past_posts($post_type,$before_date);
				?>
		</div>	
	</div>
</section>

<?php get_footer(); ?>