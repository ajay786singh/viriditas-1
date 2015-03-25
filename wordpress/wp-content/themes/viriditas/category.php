<?php get_header(); ?>

<section role="content" class="<?php if( !is_paged()) { echo 'paged-1'; }?>">

    <?php if ( have_posts() ) : while ( have_posts() ) : the_post(); ?>
        <div class="archive">
            <article>
                <div class="inner">
                    <h4><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h4>                    
                    <small><?php the_author_posts_link(); ?>, <?php echo get_the_date(); ?> in <?php echo get_the_category_list(' / '); ?></small>  
                    <p><?php echo wp_trim_words(get_the_excerpt(), 20, '...'); ?></p>
                </div>
            </article>
        </div>
    
    <?php endwhile; endif; ?>
	<div class="archive">
		<article>
			<div class="inner">
				<?php pagination(); ?>
			</div>
		</article>
	</div>			
</section>
<?php get_footer(); ?>