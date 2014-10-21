<?php
/*
Template Name: Course Homepage
*/
get_header(); ?>

<section role="main">
    
    <?php if ( have_posts() ) : while ( have_posts() ) : the_post(); ?>

    <?php // Get custom meta values

        $imageID        = get_post_meta($post->ID, '_course_image', true);
        $imageUrl       = wp_get_attachment_image_src($image_id,'banner', true);
        $description    = get_post_meta($post->ID, '_course_description', true);
        $price          = get_post_meta($post->ID, '_course_price', true);
        $instructor     = get_post_meta($post->ID, '_course_instructor', true);
        $deadline       = get_post_meta($post->ID, '_course_deadline', true);
        $dateStart      = get_post_meta($post->ID, '_course_date_start', true);
        $dateEnd        = get_post_meta($post->ID, '_course_date_end', true);
        $timeStart      = get_post_meta($post->ID, '_course_time_start', true);
        $timeEnd        = get_post_meta($post->ID, '_course_time_end', true);
        $available      = get_post_meta($post->ID, '_course_spots', true);
    ?>

    <?php if($headline): ?>
        <div><?php     
    <?php endif ?>

        <div class="archive">
            <article>
                <div class="inner">
                    <h4><a href="<?php the_permalink(); ?>">Courses</a></h4>                    
                    <p><?php echo wp_trim_words(get_the_excerpt(), 20, '...'); ?></p>
                    <small><?php the_author_posts_link(); ?>, <?php echo get_the_date(); ?> in <?php echo get_the_category_list(' / '); ?></small>  
                </div>
            </article>
        </div>
    
    <?php endwhile; endif; ?>
  
</section>

<?php pagination(); ?>
<?php get_footer(); ?>