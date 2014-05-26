<?php get_header(); ?>

<section>
    <div class="container">
    <h4><?php echo get_the_author(); ?> </h4>
    <br>
    <br>
    </div>
    <?php if ( have_posts() ) : while ( have_posts() ) : the_post(); ?>
    <div class="container">
        <aside>
            <h6 style="text-transform: uppercase;"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h6>
            <p class="meta"><?php echo get_the_date(); ?>
            <br><?php echo get_the_category_list(' / '); ?>
            </p>
        </aside>
        <article>
            <?php the_excerpt(); ?>
        </article>       
    </div>
    <?php endwhile; endif; ?>
</section>

<div class="container">
    <div class="pagi row">
        <?php pagination(); ?>
    </div>
</div>
<?php get_footer(); ?>