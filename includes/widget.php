<?php
/**
 * This file creates the widget.
 *
 * @package Lidd's Mortgage Calculator
 * @since 2.0.0
 */

// Call the hook to register the widget.
add_action( 'widgets_init', 'lidd_mc_register_widget' );

/**
 * Callback function to register the widget.
 */
function lidd_mc_register_widget() {
	register_widget( 'lidd_mc_widget' );
}

/**
 * Create the widget class.
 */
class lidd_mc_widget extends WP_Widget
{
	
	/**
	 * Constructor
	 */
	function lidd_mc_widget()
	{
		$widget_options = array(
			'classname' => 'lidd_mc_widget',
			'description' => 'Display a responsive mortgage calculator.'
		);
		
		// Pass the options to WP_Widget to create the widget.
		$this->WP_Widget( 'lidd_mc_widget', 'Responsive Mortgage Calculator' );
	}
	
	/**
	 * Build the widget settings form.
	 *
	 * Responsible for creating the elements of the widget settings form.
	 */
	function form( $instance )
	{
		$defaults = array( 'title' => 'Calculate Mortgage Payments' );
		$instance = wp_parse_args( (array) $instance, $defaults );
		$title = $instance['title'];
		
		// Exit PHP and display the widget settings form.
		?>
		
		<p><?php _e( 'Title' ); ?>: <input class="widefat" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>" /></p>
		
		<?php
		
	}
	
	/**
	 * A method to save the settings.
	 */
	function update( $new_instance, $old_instance )
	{
		$instance = $old_instance;
		$instance['title'] = strip_tags( $new_instance['title'] );
		
		return $instance;
		
	}
	
	/**
	 * A method to display the widget on the front end.
	 */
	function widget( $args, $instance )
	{
		extract( $args );
		
		echo $before_widget;
		
		$title = apply_filters( 'widget_title', $instance['title'] );
		if ( !empty( $title ) ) {
			echo $before_title . $title . $after_title;
		}
		
		// Display the widget form.
		echo lidd_mc_display_form();
		
		echo $after_widget;
		
	}
	
}
