<?php
	add_filter('widget_tag_cloud_args','edit_args_tag_cloud_widget');
	function edit_args_tag_cloud_widget($args) {
		$args = array('format' => 'list');
		return $args;
	}

	function wp_tag_cloud_remove_style_attributes($return) {
			// This function uses single quotes
			$return = preg_replace("` style='(.+)'`", "", $return);
		return $return;
	}
	add_filter('wp_tag_cloud', 'wp_tag_cloud_remove_style_attributes');
?>