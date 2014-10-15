<?php get_header(); ?>

<?php if ( have_posts() ) : while ( have_posts() ) : the_post(); ?>

<section role="main">
    <article>
        <div class="inner">
            <h1><?php the_title(); ?></h1>
            <?php the_content(); ?>
        </div>
    </article>
</section>

<?php endwhile; endif; ?>

<?php get_footer(); ?>