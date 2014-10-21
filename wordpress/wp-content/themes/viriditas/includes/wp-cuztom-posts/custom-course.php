<?php // Courses and Registration CPT

$args = array(
    'has_archive' => true,
    //'menu_position' => 5,
    'menu_icon' => 'dashicons-welcome-learn-more', //http://melchoyce.github.io/dashicons/
    'supports'  => array( 'title' )
    );

$course = register_cuztom_post_type( 'Course', $args);

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
            'description'   => 'Example: $899.00',
            'type'          => 'text',
        ),
        array(
            'name'          => 'instructor',
            'label'         => 'Instructor',
            'description'   => 'Name of instructor',
            'type'          => 'select',
            'options'       => array(
                'John Redden'  => 'John Redden',
            ),
            'default_value' => 'John Redden'
        ),
        array(
            'name'          => 'deadline',
            'label'         => 'Registration Deadline',
            'description'   => 'Enter date when course registration ends',
            'type'          => 'date',
                'args'       => array(
                'date_format' => 'F d, Y'
            )
        ),
        array(
            'name'          => 'date_start',
            'label'         => 'Course Starts',
            'description'   => 'Course start date',
            'type'          => 'date',
            'args'       => array(
                'date_format' => 'F d, Y'
            )
        ),
        array(
            'name'          => 'date_end',
            'label'         => 'Course Ends',
            'description'   => 'Course end date',
            'type'          => 'date',
            'args'       => array(
                'date_format' => 'F d, Y'
            )
        ),
        array(
            'name'          => 'time_start',
            'label'         => 'Start Time',
            'description'   => 'Time the course starts',
            'type'          => 'time'
        ),
        array(
            'name'          => 'time_end',
            'label'         => 'End Time',
            'description'   => 'Time the course starts',
            'type'          => 'time'
        ),
        array(
            'name'          => 'spots',
            'label'         => 'Spots Available',
            'description'   => 'Number of spots left in course',
            'type'          => 'select',
            'options'       => array(
                '1'    => '1',
                '2'    => '2',
                '3'    => '3',
                '4'    => '4',
                '5'    => '5',
                '6'    => '6',
                '7'    => '7',
                '8'    => '8',
                '9'    => '9',
                '10'    => '10',
                '11'    => '11',
                '12'    => '12',
                '13'    => '13',
                '14'    => '14',
                '15'    => '15',
                '16'    => '16',
                '17'    => '17',
                '18'    => '18',
                '19'    => '19',
                '20'    => '20',
                '21'    => '21',
                '22'    => '22',
                '23'    => '23',
                '24'    => '24',
                '25'    => '25',
                '26'    => '26',
                '27'    => '27',
                '28'    => '28',
                '29'    => '29',
                '30'    => '30',
            ),
            'default_value' => '1'
        ),
    )
);


//Organize admin columns
function course_columns( $cols ) {
  $cols = array(
    'cb'        => '<input type="checkbox" />',
    'title'     => __( 'Title', 'trans' ),
    //'tags'      => __( 'Tags', 'trans' ),
    '_course_details_price' => __( 'Price', 'trans' ),
    '_course_details_instructor' => __( 'Instructor', 'trans' ),
    '_course_details_spots' => __( 'Availability', 'trans' ),
    'date'      => __( 'Date', 'trans' )
);
  
  return $cols;
}

add_filter( "manage_course_posts_columns", "course_columns" );
?>