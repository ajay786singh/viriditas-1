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
	<div class="past-courses">
		<div class="course-heading">
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
				$course_description=$course_type->description;
				echo "<h5>".$course."</h5>";
				if($course_description) {
					echo "<h6>".$course_description."</h6>";
				}
				$args['tax_query'] = array(
					array(
						'taxonomy' => $tax,
						'field' => 'term_id',
						'terms' => array($course_type->term_id)
					)
				);
				$query = new WP_Query($args);
				if($query->have_posts()):
					echo "<ul>";
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
			$meta='term_meta_courses_type_'.$course_type->term_id;
			$register_on_off=get_option($meta);
	?>
			<div class="course-heading">
				<h3><?php echo $course;?></h3>
				<?php 
					if($register_on_off['_register_on_off']=='on') {
				?>
				<a href="<?php echo $course_register_url;?>" class="button">Register Now</a>
				<?php 
					}
				?>
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
					$course_in_week       = get_post_meta($id, '_course_details_course_in_week', true);
					$duration       = get_post_meta($id, '_course_details_duration', true);
					$schedule       = get_post_meta($id, '_course_details_schedule', true);
					$register_open       = get_post_meta($id, '_course_details_register_open', true);
					$register_form_id       = get_post_meta($id, '_course_details_register_form_id', true);
					$course_register_url=get_bloginfo('url').'/course-registration?form='.$register_form_id;
			?>
					<div class="post-course" id="<?php echo $row_id;?>">
						<?php the_title("<h4>","</h4>");?>
						<div class="meta">
							<ul>
								<?php if($course_in_week):
									echo '<li>'.$course_in_week.'</li>';         
								endif ?>
								<?php if($duration):
									echo '<li>'.$duration.'</li>';         
								endif ?>
								<?php if($schedule):
									echo '<li>'.$schedule.'</li>';         
								endif ?>
								<?php if($price):
									echo '<li>$'.$price.'</li>';         
								endif ?>
							</ul>
						</div>
						 <?php if($description):
							echo "<div class='content'>";
								echo $description;
								echo "<div class='course-action'>";
								if($price):
									echo "<h4>$".$price."+tax</h4>";
								endif;
								if($register_on_off['_register_on_off']=='on') {	
									if($register_open=='on') {
										echo '<a href="'.$course_register_url.'" class="button">Register Now</a>';
									}else {
										echo '<a href="#" class="button">Register Soon</a>';
									}									
								}
								echo "</div>";
							echo "</div>";
						endif ?>
					</div>
			<?php
				endwhile;endif;wp_reset_query();
		}
	}
}

add_action( 'wp_ajax_get_course', 'get_course' );
add_action( 'wp_ajax_nopriv_get_course', 'get_course' );
function get_course() {
	global $wp_query;
	$id=$_POST['id'];
	query_posts('post_type=course&p='.$id);
	if(have_posts()):while(have_posts()):the_post();
		$price = get_post_meta(get_the_ID(), '_course_details_price', true);
		echo json_encode(array('title'=>get_the_title(), 'price'=>$price));
	endwhile;endif;wp_reset_query();
	die();
}
?>