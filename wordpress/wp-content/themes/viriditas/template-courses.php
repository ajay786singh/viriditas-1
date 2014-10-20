<?php
/*
Template Name: Course Homepage
*/
get_header(); ?>


<?php 
    $args  = array(
        'posts_per_page' => 10,
        'paged' => $paged,
    );
    query_posts($args);
?>

<section role="main">
    
    <?php if ( have_posts() ) : while ( have_posts() ) : the_post(); ?>
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