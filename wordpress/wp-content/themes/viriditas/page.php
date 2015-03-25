<?php get_header(); ?>

<?php if ( have_posts() ) : while ( have_posts() ) : the_post(); ?>

<section role="content">
    <div class="container">
		<div class="content-grid">
			<?php the_title("<h1>","</h1>");?>
			<?php 
				if(is_page('course-registration')) { 
					$form_id=$_REQUEST['form'];
					if($form_id!=''){
						echo do_shortcode('[gravityform id="'.$form_id.'" title="false" description="false" ajax="true"]');
					}else{
						the_content();
					}
				} else {
					the_content();
				}	
			?>
		</div>	
	</div>
</section>

<?php endwhile; endif; ?>

<?php get_footer(); ?>