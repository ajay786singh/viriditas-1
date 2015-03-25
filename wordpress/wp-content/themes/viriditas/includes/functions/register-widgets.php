<?php

#REGISTER WIDGETS

	if ( function_exists('register_sidebar') )
	register_sidebar(array('name'=>'Sidebar',
		'before_widget' => '<div class="widget-sidebar">',
		'after_widget' => '</div>',
		'before_title' => '<h5>',
		'after_title' => '</h5>',
	));
	register_sidebar(array('name'=>'Sidebar: Appointments',
		'before_widget' => '<div class="appointment-sidebar">',
		'after_widget' => '</div>',
		'before_title' => '<h5>',
		'after_title' => '</h5>',
	));
?>