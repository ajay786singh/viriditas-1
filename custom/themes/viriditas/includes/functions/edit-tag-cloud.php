<?php
	add_filter('widget_tag_cloud_args','edit_args_tag_cloud_widget');
	function edit_args_tag_cloud_widget($args) {
		$args = array('format' => 'list');
		return $args;
	}

	// function wp_tag_cloud_remove_style_attributes($return) {
			//This function uses single quotes
			// $return = preg_replace("` style='(.+)'`", "", $return);
		// return $return;
	// }
	// add_filter('wp_tag_cloud', 'wp_tag_cloud_remove_style_attributes');
	add_filter( 'wp_tag_cloud', 'my_highlight_tags' );
	function my_highlight_tags($cloud) {
		global $wpdb;
		$tags = single_tag_title('', false);
		$tags_array = explode(" + ", $tags);
		foreach ($tags_array as $tag_name) {
			$tag_id = $wpdb->get_var("SELECT term_id FROM $wpdb->terms WHERE name = '".$tag_name."'");
			$cloud = str_replace( "tag-link-$tag_id", "current-term tag-link-$tag_id", $cloud);
		}
		$cloud = preg_replace("` style='(.+)'`", "", $cloud);
		return $cloud;
	}
	
	
// Block direct requests
if ( !defined('ABSPATH') )
	die('-1');
	
	
add_action( 'widgets_init', function(){
     register_widget( 'Tag_Title_Widget' );
});	
/**
 * Adds Tag_Title_Widget widget.
 */
class Tag_Title_Widget extends WP_Widget {
	/**
	 * Register widget with WordPress.
	 */
	function __construct() {
		parent::__construct(
			'tag_title', // Base ID
			__('Tag Title', 'text_domain'), // Name
			array( 'description' => __( 'Tag title widget!', 'text_domain' ), ) // Args
		);
	}
	/**
	 * Front-end display of widget.
	 *
	 * @see WP_Widget::widget()
	 *
	 * @param array $args     Widget arguments.
	 * @param array $instance Saved values from database.
	 */
	public function widget( $args, $instance ) {
	
     	echo $args['before_widget'];
		if ( ! empty( $instance['title'] ) ) {
			echo $args['before_title'] . apply_filters( 'widget_title', $instance['title'] ). $args['after_title'];
		}
		echo $args['after_widget'];
	}
	/**
	 * Back-end widget form.
	 *
	 * @see WP_Widget::form()
	 *
	 * @param array $instance Previously saved values from database.
	 */
	public function form( $instance ) {
		if ( isset( $instance[ 'title' ] ) ) {
			$title = $instance[ 'title' ];
		}
		else {
			$title = __( 'New title', 'text_domain' );
		}
		?>
		<p>
			<label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e( 'Title:' ); ?></label> 
			<input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>">
		</p>
		<?php 
	}
	/**
	 * Sanitize widget form values as they are saved.
	 *
	 * @see WP_Widget::update()
	 *
	 * @param array $new_instance Values just sent to be saved.
	 * @param array $old_instance Previously saved values from database.
	 *
	 * @return array Updated safe values to be saved.
	 */
	public function update( $new_instance, $old_instance ) {
		$instance = array();
		$instance['title'] = ( ! empty( $new_instance['title'] ) ) ? strip_tags( $new_instance['title'] ) : '';
		return $instance;
	}
} 
?>