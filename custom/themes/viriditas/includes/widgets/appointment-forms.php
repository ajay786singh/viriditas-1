<?php
/*
* Create A widget with form to select pages for appointment page sidebar
*/
// Block direct requests
if ( !defined('ABSPATH') )
	die('-1');
	
add_action( 'widgets_init', function(){
    register_widget( 'Appointment_Forms' );
});	

/**
 * Adds Appointment Forms widget.
 */
class Appointment_Forms extends WP_Widget {
	/**
	 * Register widget with WordPress.
	 */
	function __construct() {
		parent::__construct(
			'Appointment_Forms', // Base ID
			__('Appointment Forms', 'text_domain'), // Name
			array( 'description' => __( 'This is a widget to show forms with pages and pdf download link.', 'text_domain' ), ) // Args
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
		if ( ! empty( $instance['description'] ) ) {
			echo "<p>".apply_filters( 'widget_description', $instance['description'] )."</p>";
		}
		if ( ! empty( $instance['form_pages'] ) ) {
			//echo "<p>".apply_filters( 'widget_description', $instance['form_pages'] )."</p>";
			$pages=$instance['form_pages'];
			global $wp_query;
			$argss=array(
				'post_type'=>array('page'),
				'post__in'=> explode(",",$pages)
			);
			$results=new WP_Query($argss);
			if($results->have_posts()):
				echo "<ul>";
					while($results->have_posts()):$results->the_post();
						echo "<li><a href='".get_the_permalink()."'>".get_the_title()."</a></li>";
					endwhile;
				echo "</ul>";
			endif;wp_reset_query();
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
		//print_r($instance);
		if ( isset( $instance[ 'title' ] ) ) {
			$title = $instance[ 'title' ];
		}
		else {
			$title = __( 'New title', 'text_domain' );
		}
		if ( isset( $instance[ 'description' ] ) ) {
			$description = $instance[ 'description' ];
		}
		else {
			$description = __( 'Description', 'text_domain' );
		} 
		if ( isset( $instance[ 'form_pages' ] ) ) {
			$form_pages = $instance[ 'form_pages' ];
		}
		else {
			$form_pages = __( 'Form pages', 'text_domain' );
		}
		?>
		<p>
			<label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e( 'Title:' ); ?></label> 
			<input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>">
		</p>
		
		<p>
			<label for="<?php echo $this->get_field_id( 'description' ); ?>"><?php _e( 'Description:' ); ?></label> 
			<textarea class="widefat" id="<?php echo $this->get_field_id( 'description' ); ?>" name="<?php echo $this->get_field_name( 'description' ); ?>"><?php echo esc_attr( $description ); ?></textarea>
		</p>
		<p>
			<label for="form_pages_options"><?php _e( 'Enter Page id(s) separated with comma(,):' ); ?></label> 
		</p>
		<p>
			<textarea class="widefat" id="<?php echo $this->get_field_id( 'form_pages' ); ?>" name="<?php echo $this->get_field_name( 'form_pages' ); ?>"><?php echo esc_attr( $form_pages ); ?></textarea>
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
		//print_r($new_instance);
		$instance = array();
		$instance['title'] = ( ! empty( $new_instance['title'] ) ) ? strip_tags( $new_instance['title'] ) : '';
		$instance['description'] = ( ! empty( $new_instance['description'] ) ) ? strip_tags( $new_instance['description'] ) : '';
		$instance['form_pages'] = ( ! empty( $new_instance['form_pages'] ) ) ? strip_tags( $new_instance['form_pages'] ) : '';
		return $instance;
	}
} // class Appointment_Forms