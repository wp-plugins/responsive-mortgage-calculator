<?php
/*
Plugin Name: Responsive Mortgage Calculator
Plugin URI: http://davewilder.ca
Description: Adds a responsive mortgage calculator widget or using the shortcode [responsive-mortgage-calculator].
Version: 1.1
Author: liddweaver
Author URI: http://davewilder.ca
License: GPLv2
*/

// Call the hook to register the widget.
add_action( 'widgets_init', 'lidd_rmc_register_widget' );

function lidd_rmc_register_widget() {
	register_widget( 'lidd_rmc_widget' );
}

// Create the widget class.
class lidd_rmc_widget extends WP_Widget {
	
	// Constructor
	function lidd_rmc_widget() {
		$widget_options = array(
			'classname' => 'lidd_rmc_widget',
			'description' => 'Display a responsive mortgage calculator.'
		);
		
		// Pass the options to WP_Widget to create the widget.
		$this->WP_Widget( 'lidd_rmc_widget', 'Responsive Mortgage Calculator' );
	}
	
	// Build the widget settings form.
	function form( $instance ) {
		
		$defaults = array( 'title' => 'Calculate Mortgage Payments' );
		$instance = wp_parse_args( (array) $instance, $defaults );
		$title = $instance['title'];
		
		// Exit PHP and display the widget settings form.
		?>
		
		<p><?php _e( 'Title' ); ?>: <input class="widefat" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>" /></p>
		
		<?php
		
	}
	
	// Create a method to save the settings.
	function update( $new_instance, $old_instance ) {
		
		$instance = $old_instance;
		$instance['title'] = strip_tags( $new_instance['title'] );
		
		return $instance;
		
	}
	
	// Create a method to display the widget on the front end.
	function widget( $args, $instance ) {
		
		extract( $args );
		
		echo $before_widget;
		
		$title = apply_filters( 'widget_title', $instance['title'] );
		if ( !empty( $title ) ) {
			echo $before_title . $title . $after_title;
		}
		
		// Display the widget form.
		lidd_rmc_display_form();
		
		echo $after_widget;
		
	}
	
}

// Add a shortcode.
add_shortcode( 'mortgagecalculator', 'lidd_rmc_shortcode' );
add_shortcode( 'rmc', 'lidd_rmc_shortcode' );

// Callback function for the shortcode.
function lidd_rmc_shortcode() {
	
	// Include the jQuery script.
	return lidd_rmc_display_form();
}

// Make sure the stylesheet and jquery is included in the header if the shortcode is called.
//add_action( 'wp', 'lidd_rmc_detect_shortcode' );

function lidd_rmc_detect_shortcode() {
	global $post;
	
	$pattern = get_shortcode_regex();
	
	// Check the content.
	if ( preg_match_all( '/' . $pattern . '/s', $post->post_content, $matches )
		&& array_key_exists( 2, $matches ) 
		&& ( in_array( 'mortgagecalculator', $matches[2] ) || in_array( 'rmc', $matches[2] ) ) ) {
		
		// The shortcode is being used, so include the stylesheet.
		wp_enqueue_style( 'lidd_rmc_style', plugin_dir_url( __FILE__ ) . 'css/style.css', '', '1.0', 'screen' );
		wp_enqueue_script( 'lidd_rmc', plugin_dir_url( __FILE__) . 'js/lidd_rmc.js', 'jquery', '1.0' );
		
	}
}

// A function to build inputs.
function lidd_rmc_build_input( $type, $label, $name, $placeholder = null, $options = array() ) {
	
	// Make sure the field type is set.
	if ( !$type ) {
		return;
	}
	
	$fieldset = '';
	
	// Open a div.
	$fieldset .= '
			<div class="lidd_rmc_input">
				';
	
	// Create a label.
	$fieldset .= '<label for="' . $name . '">' . $label . '</label>
				';
	
	// Create a text input.
	if ( $type == 'text' ) {
		$fieldset .= '<input type="text" name="' . $name . '" id="' . $name . '" ';
		$fieldset .= $placeholder ? 'placeholder="' . $placeholder . '"' : '';
		$fieldset .= '/>';
	}
	// or a select box.
	elseif ( $type == 'select' ) {
		// Open the select box.
		$fieldset .= '<span class="lidd_rmc_select"><select name="'. $name . '" id="' . $name . '">';
		
		// Create the options.
		foreach ( $options as $k => $v ) {
			$fieldset .= '<option value="' . $k . '">' . $v . '</option>';
		}
		
		// Close the select box.
		$fieldset .= '</select></span>';
	}
	
	
	// Append an error reporting span.
	$fieldset .= '
				<span id="' . $name . '-error"></span>
			';
	
	// Close the div.
	$fieldset .= '</div>';
	
	return $fieldset;
	
}

// Create a function to create the calculator form.
function lidd_rmc_display_form() {
	
	echo "<form action=\"http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]\" id=\"lidd_rmc_form\" class=\"lidd_rmc_form\" method=\"post\">";
	
	// The form requires fields for...
	// - Total amount
	// - Down payment
	// - Interest rate
	// - Ammortization period
	// - Payment period
	
	echo lidd_rmc_build_input( 'text', 'Total Amount', 'lidd_rmc_total_amount', '$' );
	echo lidd_rmc_build_input( 'text', 'Down Payment', 'lidd_rmc_down_payment', '$' );
	echo lidd_rmc_build_input( 'text', 'Interest Rate', 'lidd_rmc_interest_rate', '%' );
	echo lidd_rmc_build_input( 'text', 'Amortization Period', 'lidd_rmc_amortization', 'years' );
	
	// Create a select box for the payment period.
	echo lidd_rmc_build_input( 'select', 'Payment Period', 'lidd_rmc_payment_period', '', array( 12 => 'Monthly', 26 => 'Bi-Weekly', 52 => 'Weekly' ) );
	
	// Create a button to calculate the amount.
	echo '<p><input type="submit" name="lidd_rmc_submit" id="lidd_rmc_submit" value="Calculate" /></p>';
	
	// Close the form.
	echo '</form>';
	
	// Create a display area for results.
	echo '
		<div id="lidd_rmc_details" style="display: none;">
			<div id="lidd_rmc_results"></div>
			<img id="lidd_rmc_inspector" src="' . plugins_url( 'img/icon_inspector.png', __FILE__ ) . '" alt="Details">
			<div id="lidd_rmc_summary" style="display: none;"></div>
		</div>
	';
	
}

// Load JS and CSS if the widget is active.
add_action( 'init', 'lidd_rmc_check_widget' );

function lidd_rmc_check_widget() {
	
	if ( is_active_widget( '', '', 'lidd_rmc_widget' ) ) {
		
		// Make sure CSS is included to make it responsive.
		wp_enqueue_style( 'lidd_rmc', plugins_url( 'css/style.css', __FILE__ ), false, 1.1, 'screen' );
		
		// Make sure JS is include, or else it won't function.
		wp_enqueue_script( 'lidd_rmc', plugins_url( 'js/lidd-rmc.js', __FILE__ ), 'jquery', 1.0, true );
		
	}
}