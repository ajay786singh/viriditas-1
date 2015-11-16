<?php get_header(); ?>
<?php if ( have_posts() ) : while ( have_posts() ) : the_post(); ?>
<section role="content">
    <div class="container">
		<?php
			if(is_user_logged_in()) {
		?>		
		<div class="content-grid">
			<?php
				//the_title("<h1>","</h1>");
				echo "<h1>My Account</h1>";
				the_content();
			?>
		</div>
		<?php } else {
			echo '<article class="secondary">';	
				the_content();
			echo '</article>';
		} ?>
	</div>
</section>
<?php endwhile; endif; ?>
<?php get_footer(); ?>