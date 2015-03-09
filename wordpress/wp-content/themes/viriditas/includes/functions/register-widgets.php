<?php

#REGISTER WIDGETS

	if ( function_exists('register_sidebar') )

	register_sidebar(array('name'=>'Sidebar: Appointments',
		'before_widget' => '<div class="sidebar-widget">',
		'after_widget' => '</div>',
		'before_title' => '<h5>',
		'after_title' => '</h5>',
	));
?>