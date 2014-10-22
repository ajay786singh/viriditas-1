<?php get_header(); ?>

<section role="main">

<?php if ( have_posts() ) : while ( have_posts() ) : the_post(); ?>

<?php 
        // Date/Time Format
        $dateFormat     = 'F j Y';
        $timeFormat     =  'g:i a';
        // Get custom meta values
        $imageID        = get_post_meta($post->ID, '_course_details_image', true);
        $imageUrl       = wp_get_attachment_image_src($imageID,'banner', true);
        $description    = wpautop(get_post_meta($post->ID, '_course_details_description', true));
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
    	<div style="background-image:url(<?php echo $imageUrl[0]; ?>); background-size:cover; min-height:500px;">
    	</div>
    <?php endif ?>

    <article>
        <div class="description">
            <h4><a href="<?php the_permalink(); ?>"><?php the_title();?></a></h4> 
            <?php if($description):
                echo $description;         
            endif ?>
            <button>Register Now</button>                  
        </div>

        <aside>
        	<ul>
	            <?php if($instructor):
	                echo '<li><i class="fa fa-graduation-cap" style="color: blue;"></i> ' . $instructor .'</li>';         
	            endif ?>
	            <?php if($deadline):
	                echo '<li><i class="fa fa-calendar-o" style="color: orange;"></i> ' . date($dateFormat, $deadline) .'</li>';         
	            endif ?>
	            <?php if($dateStart):
	                echo '<li><i class="fa fa-calendar" style="color: green;"></i> ' . date($dateFormat, $dateStart) .'</li>';         
	            endif ?>
	            <?php if($timeStart):
	                echo '<li><i class="fa fa-clock-o" style="color: green;"></i> ' . date($timeFormat, $timeStart) .'</li>';         
	            endif ?>
	            <?php if($dateEnd):
	                echo '<li><i class="fa fa-calendar" style="color: red;"></i> ' . date($dateFormat, $dateEnd) .'</li>';         
	            endif ?>
	            <?php if($timeEnd):
	                echo '<li><i class="fa fa-clock-o" style="color: red;"></i> ' . date($timeFormat, $timeEnd) .'</li>';         
	            endif ?>
	            <?php if($available):
	                echo '<li><i class="fa fa-users" style="color: purple;"></i> ' . $available .'</li>';         
	            endif ?>
	            <?php if($price):
	                echo '<li><i class="fa fa-usd" style="color: gray;"></i> ' . $price .'</li>';         
	            endif ?>
        	</ul>
        </aside>
    </article>


<?php endwhile; endif; ?>

</section>
<?php get_footer(); ?>