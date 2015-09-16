<?php
/*
Template Name: Course Homepage
*/
get_header(); ?>

<section role="content">
    <div class="container">
		<?php get_sidebar('courses');?>
		<div class="column-7">
				<?php 
					get_courses();
					past_courses();
				?>
		</div>	
	</div>
</section>

<?php get_footer(); ?>