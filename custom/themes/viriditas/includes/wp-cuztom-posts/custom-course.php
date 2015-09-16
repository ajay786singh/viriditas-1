<?php // Courses and Registration CPT

$args = array(
    'has_archive' => true,
    //'menu_position' => 5,
    'menu_icon' => 'dashicons-welcome-learn-more', //http://melchoyce.github.io/dashicons/
    'supports'  => array( 'title' )
    );

$course = register_cuztom_post_type( 'Course', $args);
$course_type = register_cuztom_taxonomy( 'Courses type', 'course' );
// Add option to enable/disable register button to course
/*$course_type->add_term_meta (
	array(
		array(
			'name'        => 'register_on_off',
			'label'       => 'Register ON/OFF',
			'description' => 'Select to enable registration to this course type.',
			'type'        => 'checkbox'
		)
	)
);*/

$course->add_meta_box(
    'Course Details',
    'course_details',
    array(
		array(
            'name'          => 'register_open',
            'label'         => 'Registration Open',
            'description'   => 'Please select this to make registration open for this course.(Default is closed.)',
			'type'          => 'checkbox',
			'default_value' => 'off'
        ),
		array(
            'name'          => 'register_form_id',
            'label'         => 'Registration Form ID',
            'description'   => 'Please enter gravity form id.',
			'type'          => 'text'
        ),
        /*array(
            'name'          => 'image',
            'label'         => 'Course Banner Image',
            'description'   => 'Dimensions 1200px x 600px',
            'type'          => 'image',
        ),*/
        array(
            'name'          => 'description',
            'label'         => 'Course Description',
            'description'   => 'Description of course',
            'type'          => 'wysiwyg',
        ),
        array(
            'name'          => 'price',
            'label'         => 'Price',
            'description'   => 'Example: 899.00',
            'type'          => 'text',
        ),
        array(
            'name'          => 'course_in_week',
            'label'         => 'Course no. of days in week',
            'description'   => 'Enter course no. of days in week',
            'type'          => 'text'
        ),
		array(
            'name'          => 'duration',
            'label'         => 'Course Duration',
            'description'   => 'Enter period when course ends',
            'type'          => 'text'
        ),
		array(
            'name'          => 'schedule',
            'label'         => 'Course schedule',
            'description'   => 'Enter schedule/season',
            'type'          => 'text'
        ),
		array(
            'name'          => 'end_of_course',
            'label'         => 'Course End Date',
            'description'   => 'Enter course end date',
            'type'          => 'date'
        ),
    )
);


//Organize admin columns
function course_columns( $cols ) {
  $cols = array(
    'cb'        => '<input type="checkbox" />',
    'title'     => __( 'Title', 'trans' ),
    '_course_details_price' => __( 'Price', 'trans' ),
    '_course_details_instructor' => __( 'Instructor', 'trans' ),
    '_course_details_spots' => __( 'Availability', 'trans' ),
    'date'      => __( 'Date', 'trans' )
);
  
  return $cols;
}

add_filter( "manage_course_posts_columns", "course_columns" );
?>