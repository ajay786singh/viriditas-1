<?php get_header(); ?>

<?php if ( have_posts() ) : while ( have_posts() ) : the_post(); ?>


<section role="content">
    <div class="container">
		<div class="span-11 center">
			<div class="span-6">
				<img src="<?php bloginfo('template_url');?>/dist/images/what-we-do.png">
			</div>
			<div class="span-6">
				<h2>What we do</h2>
				<p>Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages, and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum.</p>
				<p><a href="#" class="button purple">Learn more</a></p>
			</div>
		</div>

		<div class="span-11 center divider">
			<div class="span-6">
				<img src="<?php bloginfo('template_url');?>/dist/images/what-we-do.png">
			</div>
			<div class="span-6">
				<h2>What we do</h2>
				<p>Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages, and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum.</p>
				<p><a href="#" class="button purple">Learn more</a></p>
			</div>
		</div>
	</div>
</section>


<?php endwhile; endif; ?>

<?php get_footer(); ?>