<?php get_header(); ?>

<?php if ( have_posts() ) : while ( have_posts() ) : the_post(); ?>

<section role="content">
    <div class="container">
		<div class="content-grid">
			<a class="back-to-products" href="<?php bloginfo('url');?>/products">&larr; Back to products</a>
		</div>
		<div class="content-grid">
			<h1><?php the_title();?></h1>
			<?php the_content();?>
		</div>	
	</div>
</section>

<?php endwhile; endif; ?>

<?php get_footer(); ?>