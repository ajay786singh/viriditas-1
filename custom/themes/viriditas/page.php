<?php get_header(); ?>

<?php if ( have_posts() ) : while ( have_posts() ) : the_post(); ?>

<section role="content">
    <div class="container">
		<?php 
			$class="";
			if(is_page('terms-of-condition')) {
				$class="content-left";
			} 
		?>
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
					echo "<div class='{$class}'>";
					the_content();
					echo "</div>";
				}	
			?>
		</div>	
	</div>
</section>

<?php endwhile; endif; ?>

<?php get_footer(); ?>