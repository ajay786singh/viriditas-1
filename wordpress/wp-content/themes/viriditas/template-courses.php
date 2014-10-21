<?php
/*
Template Name: Course Homepage
*/
get_header(); ?>

<section role="main">

    <?php
        $args =  array(
            'post_type' => 'course'
        );
        query_posts( $args );
    ?>

    <?php if ( have_posts() ) : while ( have_posts() ) : the_post(); ?>

    <?php 
        // Date/Time Format
        $dateFormat     = 'F, m Y';
        $timeFormat     =  'g:ia';

        // Get custom meta values
        $imageID        = get_post_meta($post->ID, '_course_details_image', true);
        $imageUrl       = wp_get_attachment_image_src($imageID,'banner', true);
        $description    = get_post_meta($post->ID, '_course_details_description', true);
        $price          = get_post_meta($post->ID, '_course_details_price', true);
        $instructor     = get_post_meta($post->ID, '_course_details_instructor', true);
        $deadline       = get_post_meta($post->ID, '_course_details_deadline', true);
        $dateStart      = get_post_meta($post->ID, '_course_details_date_start', true);
        $dateEnd        = get_post_meta($post->ID, '_course_details_date_end', true);
        $timeStart      = get_post_meta($post->ID, '_course_details_time_start', true);
        $timeEnd        = get_post_meta($post->ID, '_course_details_time_end', true);
        $available      = get_post_meta($post->ID, '_course_details_spots', true);
    ?>
    
    <?php if($imageUrl):
        echo '<img src="' . $imageUrl[0] . '">';         
    endif ?>
    <?php if($description):
        echo '<p>' . $description .'</p>';         
    endif ?>
    <?php if($price):
        echo '<p>' . $price .'</p>';         
    endif ?>
    <?php if($instructor):
        echo '<p>' . $instructor .'</p>';         
    endif ?>
    <?php if($deadline):
        echo '<p>' . date($dateFormat, $deadline) .'</p>';         
    endif ?>
    <?php if($dateStart):
        echo '<p>' . date($dateFormat, $dateStart) .'</p>';         
    endif ?>
    <?php if($dateEnd):
        echo '<p>' . date($dateFormat, $dateEnd) .'</p>';         
    endif ?>
    <?php if($timeStart):
        echo '<p>' . date($timeFormat, $timeStart) .'</p>';         
    endif ?>
    <?php if($timeEnd):
        echo '<p>' . date($timeFormat, $timeEnd) .'</p>';         
    endif ?>
    <?php if($available):
        echo '<p>' . $available .'</p>';         
    endif ?>

        <div class="archive">
            <article>
                <div class="inner">
                    <h4><a href="<?php the_permalink(); ?>"><?php the_title();?></a></h4>                    
                </div>
            </article>
        </div>
    
    <?php endwhile; endif; ?>
  
</section>

<?php pagination(); ?>
<?php get_footer(); ?>