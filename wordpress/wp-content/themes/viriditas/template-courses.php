<?php
/*
Template Name: Course Homepage
*/
get_header(); ?>

<section role="main" class="course">

    <?php
        $args1 =  array(
            'post_type' => 'course',
            'posts_per_page' => 1
        );
        $query1 = new WP_Query( $args1 );
    ?>
    <?php while ( $query1->have_posts() ) : $query1->the_post(); ?>

    <?php 
        // Date/Time Format
        $dateFormat     = 'F m, Y';
        $timeFormat     =  'g:i a';
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
    
    <?php if($imageUrl): ?>
       <img src="<?php echo $imageUrl[0]; ?>">   
    <?php endif ?>

    <article>
        <div class="description">
            <h4><a href="<?php the_permalink(); ?>"><?php the_title();?></a></h4> 
            <?php if($description):
                echo '<p>' . $description .'</p>';         
            endif ?>
            <button>Register Now</button>                  
        </div>

        <aside>
                
            <?php if($instructor):
                echo '<p><i class="fa fa-graduation-cap" style="color: blue;"></i> ' . $instructor .'</p>';         
            endif ?>
            <?php if($deadline):
                echo '<p><i class="fa fa-calendar-o" style="color: orange;"></i> ' . date($dateFormat, $deadline) .'</p>';         
            endif ?>
            <?php if($dateStart):
                echo '<p><i class="fa fa-calendar" style="color: green;"></i> ' . date($dateFormat, $dateStart) .'</p>';         
            endif ?>
            <?php if($timeStart):
                echo '<p><i class="fa fa-clock-o" style="color: green;"></i> ' . date($timeFormat, $timeStart) .'</p>';         
            endif ?>
            <?php if($dateEnd):
                echo '<p><i class="fa fa-calendar" style="color: red;"></i> ' . date($dateFormat, $dateEnd) .'</p>';         
            endif ?>
            <?php if($timeEnd):
                echo '<p><i class="fa fa-clock-o" style="color: red;"></i> ' . date($timeFormat, $timeEnd) .'</p>';         
            endif ?>
            <?php if($available):
                echo '<p><i class="fa fa-users" style="color: purple;"></i> ' . $available .'</p>';         
            endif ?>
            <?php if($price):
                echo '<p><i class="fa fa-usd" style="color: gray;"></i> ' . $price .'</p>';         
            endif ?>
        </aside>
    </article>
    <?php wp_reset_postdata(); ?>
    <?php endwhile; ?>

    <!--//////////////////////////////-->

    <?php
        $args2 =  array(
            'post_type' => 'course',
            'posts_per_page' => 1,
            'offset' => 1
        );
        $query2 = new WP_Query( $args2 );
    ?>

    <?php while ( $query2->have_posts() ) : $query2->the_post(); ?>

    <?php
        // Get custom meta values
        $imageID        = get_post_meta($post->ID, '_course_details_image', true);
        $imageUrl       = wp_get_attachment_image_src($imageID,'banner', true);
        $description    = get_post_meta($post->ID, '_course_details_description', true);
    ?>
        
    <article>
        <aside>
            asdf
        </aside>
        <div class="description">
            <h4><a href="<?php the_permalink(); ?>"><?php the_title();?></a></h4> 
            <?php if($description):
                echo '<p>' . $description .'</p>';         
            endif ?>                
        </div>
    </article>
    <?php wp_reset_postdata(); ?>
    <?php endwhile; ?>

    <!--//////////////////////////////-->

    <?php
        $args3 =  array(
            'post_type' => 'course',
            'posts_per_page' => 3,
            'offset' => 2
        );
        $query3 = new WP_Query( $args3 );
    ?>

    <?php while ( $query3->have_posts() ) : $query3->the_post(); ?>

    <?php
        // Get custom meta values
        $imageID        = get_post_meta($post->ID, '_course_details_image', true);
        $imageUrl       = wp_get_attachment_image_src($imageID,'banner', true);
        $description    = get_post_meta($post->ID, '_course_details_description', true);
    ?>
        
    <article>
        <div class="description">
            <h4><a href="<?php the_permalink(); ?>"><?php the_title();?></a></h4> 
            <?php if($description):
                echo '<p>' . $description .'</p>';         
            endif ?>                
        </div>
    </article>
    <?php wp_reset_postdata(); ?>
    <?php endwhile; ?>
  
</section>

<?php //pagination(); ?>
<?php get_footer(); ?>