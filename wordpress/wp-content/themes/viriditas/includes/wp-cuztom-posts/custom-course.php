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
            'name'          => 'course_description',
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
                'value1'    => 'John Redden',
            ),
            'default_value' => 'value1'
        ),
        array(
            'name'          => 'deadline',
            'label'         => 'Registration Deadline',
            'description'   => 'Enter date when course registration ends',
            'type'          => 'date',
        ),
        array(
            'name'          => 'course_start',
            'label'         => 'Course Starts',
            'description'   => 'Course start date',
            'type'          => 'date'
        ),
        array(
            'name'          => 'course_end',
            'label'         => 'Course Ends',
            'description'   => 'Course end date',
            'type'          => 'date'
        ),
        array(
            'name'          => 'time_start',
            'label'         => 'Start Time',
            'description'   => 'Time the course starts',
            'type'          => 'time',
        ),
        array(
            'name'          => 'time_end',
            'label'         => 'End Time',
            'description'   => 'Time the course starts',
            'type'          => 'time',
        ),
        array(
            'name'          => 'spots',
            'label'         => 'Spots Available',
            'description'   => 'Number of spots left in course',
            'type'          => 'select',
            'options'       => array(
                'value1'    => '1',
                'value2'    => '2',
                'value3'    => '3',
                'value4'    => '4',
                'value5'    => '5',
                'value6'    => '6',
                'value7'    => '7',
                'value8'    => '8',
                'value9'    => '9',
                'value10'    => '10',
                'value11'    => '11',
                'value12'    => '12',
                'value13'    => '13',
                'value14'    => '14',
                'value15'    => '15',
                'value16'    => '16',
                'value17'    => '17',
                'value18'    => '18',
                'value19'    => '19',
                'value20'    => '20',
                'value21'    => '21',
                'value22'    => '22',
                'value23'    => '23',
                'value24'    => '24',
                'value25'    => '25',
                'value26'    => '26',
                'value27'    => '27',
                'value28'    => '28',
                'value29'    => '29',
                'value30'    => '30',
            ),
            'default_value' => 'value1'
        ),
    )
);
?>