<?php get_header(); ?>

<section> 
<?php if ( have_posts() ) : while ( have_posts() ) : the_post(); ?>
    <div class="container">
        <div class="span-12">
            <p class="meta"><i><?php echo get_the_date(); ?></i></p>
            <h1><?php the_title(); ?></h1>
            <p class="meta"><span class="sub-meta">By</span> <?php echo the_author_posts_link(); ?> <span class="sub-meta">in</span> <?php echo get_the_category_list(' / '); ?></p>
        </div>
        <div class="span-12">
            <?php the_content(); ?>
            <div style="float: left;">
            <a href="https://twitter.com/share" class="twitter-share-button" data-lang="en">Tweet</a>
            <script>!function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0];if(!d.getElementById(id)){js=d.createElement(s);js.id=id;js.src="https://platform.twitter.com/widgets.js";fjs.parentNode.insertBefore(js,fjs);}}(document,"script","twitter-wjs");</script>
            </div>
            <div class="fb-share-button" data-href="<?php bloginfo('url')?>" data-type="button_count"></div>
        </div>
    </div>
<?php endwhile; endif; ?>
</section>

<!-- Pagination for mobile -->
<div class="container">
    <div class="span-12 mobile-pagination">
		<?php previous_post_link( '<span class="prev">%link</span>', '<< Previous' );?>
        <?php next_post_link( '<span class="next">%link</span>', 'Next >>' );?>
    </div>
</div>
    
<!-- Pagination for desktop -->    
<div id="pagination">
    <?php previous_post_link( '%link', '%title' );?>
    <?php next_post_link( '%link', '%title' );?>
</div>

<div class="container">
    <div class="span-12">
        <?php comments_template() ?>
    </div>
</div>
<?php get_footer(); ?>