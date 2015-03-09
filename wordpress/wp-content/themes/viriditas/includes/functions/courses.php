<?php
/*
* Function to display Past Courses
* $before_date represents posts published before date.
*/

function past_posts($post_type='post',$before_date=false) {

	$args=array(
		'post_type' => $post_type,
		'post_status' => 'publish',
		'showposts' => '-1',
	);
	if($before_date!='') {
		$args['date_query'] = array(
			array(
				'before' => $today,
			),
		);
	}

	$query = new WP_Query( $args );
	if($query->have_posts()):
	?>
		<div class="course_heading">
			<h3>Past Courses</h3>
		</div>
		<div class="accordion">
	<?php
		while($query->have_posts()):$query->the_post();
		$id=get_the_ID();
		$description    = wpautop(get_post_meta($id, '_course_details_description', true));
			echo "<div class='accordion-panel'>";
				echo '<h5 class="accordion-panel-header">'.get_the_title().'</h5>';		
				echo "<div class='accordion-panel-content'>";
					if($description):
						echo $description;         
					endif;
				echo "</div>";
			echo "</div>";
		endwhile;
	endif;
	?>
	</div>
<?php	
	wp_reset_query();
}
function get_sidebar_courses(){
		$args=array(
			'post_type' => 'course',
			'post_status' => 'publish',
			'showposts' => '-1',
		);
		$tax='courses_type';
		$courses_type = get_terms($tax, 'orderby=count&order=desc&hide_empty=1&hierarchical=0&parent=0');	
		if($courses_type) {
			foreach($courses_type as $course_type) {
				$course=$course_type->name;
				$course_slug=$course_type->slug;
				echo "<h5>".$course."</h5>";
				$args['tax_query'] = array(
					array(
						'taxonomy' => $tax,
						'field' => 'term_id',
						'terms' => array($course_type->term_id)
					)
				);
				$query = new WP_Query($args);
				if($query->have_posts()):
					echo "<ul class='post-list'>";
						while($query->have_posts()):$query->the_post();
						$id="post-".$course_slug."-".get_the_ID();
						echo "<li><a href='#' rel='".$id."'>".get_the_title()."</a></li>";
						endwhile;
					echo "</ul>";
				endif;wp_reset_query();
			}
		}
}
function get_courses() {
	$tax='courses_type';
	$courses_type = get_terms($tax, 'orderby=count&order=desc&hide_empty=1&hierarchical=0&parent=0');	
	$args=array(
		'post_type' => 'course',
		'post_status' => 'publish',
		'showposts' => '-1',
	);
	if($courses_type) {		
		foreach($courses_type as $course_type) {
			$course=$course_type->name;
			$course_slug=$course_type->slug;
	?>
			<div class="course_heading">
				<h3><?php echo $course;?></h3>
				<a href="<?php bloginfo('url');?>/course-registration" class="button">Register Now</a>
			</div>
			<?php 
				$args['tax_query'] = array(
					array(
						'taxonomy' => $tax,
						'field' => 'term_id',
						'terms' => array($course_type->term_id)
					)
				);
				$query = new WP_Query($args);
				if($query->have_posts()):while($query->have_posts()):$query->the_post();
					$id=get_the_ID();
					$row_id="post-".$course_slug."-".$id;
					// Date/Time Format
					$dateFormat     = 'F j, Y';
					$timeFormat     =  'g:i a';
					// Get custom meta values
					$imageID        = get_post_meta($id, '_course_details_image', true);
					$imageUrl       = wp_get_attachment_image_src($imageID,'banner', true);
					$description    = wpautop(get_post_meta($id, '_course_details_description', true));
					$price          = get_post_meta($id, '_course_details_price', true);
					$instructor     = get_post_meta($id, '_course_details_instructor', true);
					$deadline       = get_post_meta($id, '_course_details_deadline', true);
					$dateStart      = get_post_meta($id, '_course_details_date_start', true);
					$dateEnd        = get_post_meta($id, '_course_details_date_end', true);
					$timeStart      = get_post_meta($id, '_course_details_time_start', true);
					$timeEnd        = get_post_meta($id, '_course_details_time_end', true);
					$available      = get_post_meta($id, '_course_details_spots', true);
			?>
					<div class="post-course">
						<a href="" id="<?php echo $row_id;?>"></a>
						<?php the_title("<h4>","</h4>");?>
						<div class="meta">
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
						</div>
						 <?php if($description):
							echo $description;         
						endif ?>
					</div>
			<?php
				endwhile;endif;wp_reset_query();
		}
	}
}