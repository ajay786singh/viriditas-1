<?php

#REGISTER WIDGETS

	if ( function_exists('register_sidebar') )
	register_sidebar(
	array(
		'name'=>'Sidebar',
		'id'=>'sidebar-1',
		'before_widget' => '<div class="widget-sidebar">',
		'after_widget' => '</div>',
		'before_title' => '<h5>',
		'after_title' => '</h5>',
	));
	register_sidebar(array(
		'name'=>'Sidebar: Appointments',
		'id'=>'sidebar-2',
		'before_widget' => '<div class="appointment-sidebar">',
		'after_widget' => '</div>',
		'before_title' => '<h5>',
		'after_title' => '</h5>',
	));
	register_sidebar(array(
		'name'=>'Sidebar: Footer For Newsletter',
		'id'=>'sidebar-3',
		'before_widget' => '<div class="newsletter">',
		'after_widget' => '</div>',
		'before_title' => '',
		'after_title' => '',
	));
?>