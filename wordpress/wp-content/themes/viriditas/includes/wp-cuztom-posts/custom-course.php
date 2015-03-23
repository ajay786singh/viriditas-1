<?php // Courses and Registration CPT

$args = array(
    'has_archive' => true,
    //'menu_position' => 5,
    'menu_icon' => 'dashicons-welcome-learn-more', //http://melchoyce.github.io/dashicons/
    'supports'  => array( 'title' )
    );

$course = register_cuztom_post_type( 'Course', $args);
$course->add_taxonomy( 'Courses type' );

$course->add_meta_box(
    'Course Details',
    'course_details',
    array(
        array(
            'name'          => 'image',
            'label'         => 'Course Banner Image',
            'description'   => 'Dimensions 1200px x 600px',
            'type'          => 'image',
        ),
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