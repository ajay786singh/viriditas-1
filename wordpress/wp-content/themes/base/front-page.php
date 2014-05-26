<?php get_header(); ?>

<?php if ( have_posts() ) : while ( have_posts() ) : the_post(); ?>

    <?php // Get the feature banner image
        if (has_post_thumbnail()) {
            $image_id = get_post_thumbnail_id();
            $image_url = wp_get_attachment_image_src($image_id,'banner', true);
    ?>
    <div class="banner">
        <span class="bannerimage" style="background-image:url('<?php echo $image_url[0]; ?>'); display: block;">
            <span class="inner"></span>
        </span>
    </div>
    <?php } ?>

    <div class="container">
        <section class="span-12">
            <?php the_title( '<h1>', '</h1>' ); ?>
            <?php the_content(); ?>
        </section>
    </div>

<?php endwhile; endif; ?>
<?php get_footer(); ?>