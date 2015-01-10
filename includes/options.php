<?php
/**
 * This file creates the plugin settings page
 *
 * @package Lidd's Mortgage Calculator
 * @since 1.0.0
 */

// Create a settings page.
add_action( 'admin_menu', 'lidd_mc_add_settings_page' );

/**
 * Callback function to register the settings page and menu option.
 */
function lidd_mc_add_settings_page() {
	add_options_page( 'Lidd Mortgage Calculator', 'Lidd\'s Mortgage Calc', 'manage_options', LIDD_MC_OPTIONS, 'lidd_mc_settings_page' );
}

// Register the specific sections and settings on the settings page.
add_action( 'admin_init', 'lidd_mc_admin_init' );

/**
 * Callback function to register the specific sections and settings on the settings page.
 */
function lidd_mc_admin_init() {
	
	// Register the settings
	register_setting( LIDD_MC_OPTIONS, LIDD_MC_OPTIONS, 'lidd_mc_validate_options' );
	
	// Create a new options object in order to validate and store options
	global $lidd_mc_options_object;
	$lidd_mc_options_object = new LiddMCOptions();
	
	
	// Create settings page sections
	// --------------------------------------------
	// Calculator settings
	add_settings_section( 'lidd_mc_calcsettings', 'Calculator Settings', 'lidd_mc_options_calcsettings_text', LIDD_MC_OPTIONS );
	// Compounding period
	add_settings_field( 'lidd_mc_compounding_period', 'Compounding period for the mortgage interest', 'lidd_mc_settings_compounding_period', LIDD_MC_OPTIONS, 'lidd_mc_calcsettings' );
	// Currency symbol
	add_settings_field( 'lidd_mc_currency', 'Currency symbol', 'lidd_mc_settings_currency', LIDD_MC_OPTIONS, 'lidd_mc_calcsettings' );
	// Include Down Payment field
	add_settings_field( 'lidd_mc_down_payment_visible', 'Include the down payment field', 'lidd_mc_settings_down_payment_visible', LIDD_MC_OPTIONS, 'lidd_mc_calcsettings' );

	// --------------------------------------------
	// General styling
	add_settings_section( 'lidd_mc_css', 'Layout and Styling (CSS)', 'lidd_mc_options_css_text', LIDD_MC_OPTIONS );
	// Theme (light, dark, none)
	add_settings_field( 'lidd_mc_theme', 'Choose a theme', 'lidd_mc_settings_theme', LIDD_MC_OPTIONS, 'lidd_mc_css' );
	// Include fancy payment period styles
	add_settings_field( 'lidd_mc_select_style', 'Make the Payment Period select box look fancy', 'lidd_mc_settings_select_style', LIDD_MC_OPTIONS, 'lidd_mc_css' );
	// Fancy payment period down arrow position
	add_settings_field( 'lidd_mc_select_pointer', 'Adjust the vertical position of the down arrow on the fancy select box', 'lidd_mc_settings_select_pointer', LIDD_MC_OPTIONS, 'lidd_mc_css' );
	// Include responsive styles
	add_settings_field( 'lidd_mc_css_layout', 'Make it responsive', 'lidd_mc_settings_css_layout', LIDD_MC_OPTIONS, 'lidd_mc_css' );

	// --------------------------------------------
	// Results
	add_settings_section( 'lidd_mc_results', 'Results', 'lidd_mc_options_results_text', LIDD_MC_OPTIONS );
	// Additional information panel (0 = hide, 1 = toggle, 2 = always show)
	add_settings_field( 'lidd_mc_summary', 'Set the result summary visibility', 'lidd_mc_settings_summary', LIDD_MC_OPTIONS, 'lidd_mc_results' );

	// --------------------------------------------
	// Labels
	add_settings_section( 'lidd_mc_labels', 'Input Labels', 'lidd_mc_options_labels_text', LIDD_MC_OPTIONS );
	// Total Amount label
	add_settings_field( 'lidd_mc_total_amount_label', 'Total Amount label', 'lidd_mc_settings_total_amount_label', LIDD_MC_OPTIONS, 'lidd_mc_labels' );
	// Down Payment label
	add_settings_field( 'lidd_mc_down_payment_label', 'Down Payment label', 'lidd_mc_settings_down_payment_label', LIDD_MC_OPTIONS, 'lidd_mc_labels' );
	// Interest Rate label
	add_settings_field( 'lidd_mc_interest_rate_label', 'Interest Rate label', 'lidd_mc_settings_interest_rate_label', LIDD_MC_OPTIONS, 'lidd_mc_labels' );
	// Amortization Period label
	add_settings_field( 'lidd_mc_amortization_period_label', 'Amortization Period label', 'lidd_mc_settings_amortization_period_label', LIDD_MC_OPTIONS, 'lidd_mc_labels' );
	// Payment Period label
	add_settings_field( 'lidd_mc_payment_period_label', 'Payment Period label', 'lidd_mc_settings_payment_period_label', LIDD_MC_OPTIONS, 'lidd_mc_labels' );
	// Submit label
	add_settings_field( 'lidd_mc_submit_label', 'Submit button label', 'lidd_mc_settings_submit_label', LIDD_MC_OPTIONS, 'lidd_mc_labels' );

	// --------------------------------------------
	// Classes
	add_settings_section( 'lidd_mc_classes', 'Input Classes', 'lidd_mc_options_classes_text', LIDD_MC_OPTIONS );
	// Total Amount class
	add_settings_field( 'lidd_mc_total_amount_class', 'Total Amount class', 'lidd_mc_settings_total_amount_class', LIDD_MC_OPTIONS, 'lidd_mc_classes' );
	// Down Payment class
	add_settings_field( 'lidd_mc_down_payment_class', 'Down Payment class', 'lidd_mc_settings_down_payment_class', LIDD_MC_OPTIONS, 'lidd_mc_classes' );
	// Interest Rate class
	add_settings_field( 'lidd_mc_interest_rate_class', 'Interest Rate class', 'lidd_mc_settings_interest_rate_class', LIDD_MC_OPTIONS, 'lidd_mc_classes' );
	// Amortization Period class
	add_settings_field( 'lidd_mc_amortization_period_class', 'Amortization Period class', 'lidd_mc_settings_amortization_period_class', LIDD_MC_OPTIONS, 'lidd_mc_classes' );
	// Payment Period class
	add_settings_field( 'lidd_mc_payment_period_class', 'Payment Period class', 'lidd_mc_settings_payment_period_class', LIDD_MC_OPTIONS, 'lidd_mc_classes' );
	// Submit class
	add_settings_field( 'lidd_mc_submit_class', 'Submit button class', 'lidd_mc_settings_submit_class', LIDD_MC_OPTIONS, 'lidd_mc_classes' );
	
}

// --------------------------------------------
// Settings section text functions.
function lidd_mc_options_calcsettings_text() {
	echo '<p>Change the basic functioning and parameters for the calculator.</p>';
}
function lidd_mc_options_css_text() {
	echo '<p>Toggle layout and styling. Remove styling to prevent CSS from loading (but it won\'t be responsive any more).</p>';
}
function lidd_mc_options_results_text() {
	echo '<p>Change the additional information panel settings.</p>';
}
function lidd_mc_options_labels_text() {
	echo '<p>Set your own labels for the inputs.</p>';
}
function lidd_mc_options_classes_text() {
	echo '<p>Add CSS classes to override styles or to hook into your theme\'s styling.</p>';
}


// --------------------------------------------
// Settings input functions

/**
 * Generic function to create a text input on the settings page.
 */
function lidd_mc_settings_text_input( $key ) {
	// Get the option.
	global $lidd_mc_options_object;
	$value = $lidd_mc_options_object->getOption( $key );
	// Display the input.
	echo '<input type="text" id="' . $key . '" name="' . LIDD_MC_OPTIONS . '[' . $key . ']" value="' . esc_attr( $value ) . '" />';
}
/**
 * Generic function to create a checkbox on the settings page.
 */
function lidd_mc_settings_checkbox( $key ) {
	// Get the option.
	global $lidd_mc_options_object;
	$value = $lidd_mc_options_object->getOption( $key );
	// Display the input.
	echo '<input type="checkbox" id="' . $key . '" name="' . LIDD_MC_OPTIONS . '[' . $key . ']" ' . checked( $value, 1, false ) . '/>';
}
/**
 * Generic function to create a select box on the settings page.
 */
function lidd_mc_settings_selectbox( $key, $options ) {
	// Get the option.
	global $lidd_mc_options_object;
	$value = $lidd_mc_options_object->getOption( $key );
	// Display the input.
	$select = '
		<select name="' . LIDD_MC_OPTIONS . '[' . $key . ']">';
	foreach ( $options as $k => $v ) {
		$select .= '
			<option value="' . $k . '" ' . selected( $value, $k, false ) . '>' . $v . '</option>';
	}
	$select .= '
		</select>';
	echo $select;
}

// Specific functions
/**
 * Function to create compounding period settings input.
 */
function lidd_mc_settings_compounding_period() {
	$options = array(
		1 => 'Annually',
		2 => 'Semi-Annually', 
		4 => 'Quarterly',
		12 => 'Monthly'
	);
	lidd_mc_settings_selectbox( 'compounding_period', $options );
}
/**
 * Function to create currency settings input.
 */
function lidd_mc_settings_currency() {
	$options = array(
		'$' => '$ - Dollar',
		'€' => '€ - Euro',
		'£' => '£ - Pound',
		'¥' => '¥ - Yen'
	);
	lidd_mc_settings_selectbox( 'currency', $options );
}
/**
 * Function to create down payment visibility settings input.
 */
function lidd_mc_settings_down_payment_visible() {
	lidd_mc_settings_checkbox( 'down_payment_visible' );
}
/**
 * Function to create theme settings input.
 */
function lidd_mc_settings_theme() {
	$options = array(
		'light' => 'Light', 
		'dark' => 'Dark',
		'none' => 'Use my theme\'s default styling'
	);
	lidd_mc_settings_selectbox( 'theme', $options );
}
/**
 * Function to create select box styling settings input.
 */
function lidd_mc_settings_select_style() {
	lidd_mc_settings_checkbox( 'select_style' );
}
/**
 * Function to create select box down arrow settings input.
 */
function lidd_mc_settings_select_pointer() {
	//lidd_mc_settings_text_input( 'select_pointer' );
	$options = array(
		'dot5' => '.5em',
		'dot65' => '.65em',
		'dot75' => '.75em',
		'dot85' => '.85em',
		'1' => '1em'
	);
	lidd_mc_settings_selectbox( 'select_pointer', $options );
}
/**
 * Function to create CSS layout/responsive settings input.
 */
function lidd_mc_settings_css_layout() {
	lidd_mc_settings_checkbox( 'css_layout' );
}
/**
 * Function to create result summary settings input.
 */
function lidd_mc_settings_summary() {
	$options = array(
		0 => 'Don\'t include the summary', 
		1 => 'Hide the summary, but show the toggle icon',
		2 => 'Show the summary (no toggle)'
	);
	lidd_mc_settings_selectbox( 'summary', $options );
	
}
/**
 * Function to create total amount label settings input.
 */
function lidd_mc_settings_total_amount_label() {
	lidd_mc_settings_text_input( 'total_amount_label' );
}
/**
 * Function to create down payment label settings input.
 */
function lidd_mc_settings_down_payment_label() {
	lidd_mc_settings_text_input( 'down_payment_label' );
}
/**
 * Function to create interest rate label settings input.
 */
function lidd_mc_settings_interest_rate_label() {
	lidd_mc_settings_text_input( 'interest_rate_label' );
}
/**
 * Function to create amortization period label settings input.
 */
function lidd_mc_settings_amortization_period_label() {
	lidd_mc_settings_text_input( 'amortization_period_label' );
}
/**
 * Function to create payment period label settings input.
 */
function lidd_mc_settings_payment_period_label() {
	lidd_mc_settings_text_input( 'payment_period_label' );
}
/**
 * Function to create submit button value settings input.
 */
function lidd_mc_settings_submit_label() {
	lidd_mc_settings_text_input( 'submit_label' );
}
/**
 * Function to create total amount class settings input.
 */
function lidd_mc_settings_total_amount_class() {
	lidd_mc_settings_text_input( 'total_amount_class' );
}
/**
 * Function to create down payment class settings input.
 */
function lidd_mc_settings_down_payment_class() {
	lidd_mc_settings_text_input( 'down_payment_class' );
}
/**
 * Function to create interest rate class settings input.
 */
function lidd_mc_settings_interest_rate_class() {
	lidd_mc_settings_text_input( 'interest_rate_class' );
}
/**
 * Function to create amortization period class settings input.
 */
function lidd_mc_settings_amortization_period_class() {
	lidd_mc_settings_text_input( 'amortization_period_class' );
}
/**
 * Function to create payment period class settings input.
 */
function lidd_mc_settings_payment_period_class() {
	lidd_mc_settings_text_input( 'payment_period_class' );
}
/**
 * Function to create submit button class settings input.
 */
function lidd_mc_settings_submit_class() {
	lidd_mc_settings_text_input( 'submit_class' );
}


// --------------------------------------------
// Validation

/**
 * Generic function for validating labels and classes.
 */
function lidd_mc_clean_text( $text ) {
	return preg_replace( '/[^a-z0-9 _-]/i', '', $text );
}

/**
 * Generic function for setting errors.
 */
function lidd_mc_settings_error( $key, $type ) {
	add_settings_error(
		'lidd_mc_' . $key,
		'lidd_mc_' . $key . '_error',
		'The ' . $type . ' can contain only letters, numbers, spaces, hyphens and the underscore.',
		'error'
	);
}

/**
 * Callback function to validate options.
 */
function lidd_mc_validate_options( $input ) {
	$valid = array();

	// Calculator settings
	$valid['compounding_period'] = ( isset( $input['compounding_period'] ) && in_array( $input['compounding_period'], array( 1, 2, 4, 12 ) ) ) ? absint( $input['compounding_period'] ) : 2;
	if ( isset( $input['currency'] ) ) {
		switch ( $input['currency'] ) {
			case '£':
				$valid['currency'] = '£';
				break;
			case '€':
				$valid['currency'] = '€';
				break;
			case '¥':
				$valid['currency'] = '¥';
				break;
			default:
				$valid['currency'] = '$';
				break;
		}
	} else {
		$valid['currency'] = '$';
	}
	$valid['down_payment_visible'] = ( isset( $input['down_payment_visible'] ) ) ? 1 : 0;
	
	// Layout and styling
	if ( isset( $input['theme'] ) ) {
		switch ( $input['theme'] ) {
			case 'dark':
				$valid['theme'] = 'dark';
				break;
			case 'none':
				$valid['theme'] = 'none';
				break;
			case 'light':
			default:
				$valid['theme'] = 'light';
				break;
		}
	} else {
		$valid['theme'] = 'light';
	}
	$valid['select_style'] = ( isset( $input['select_style'] ) ) ? 1 : 0;
	$valid['select_pointer'] = ( isset( $input['select_pointer'] ) ) ? lidd_mc_clean_text( $input['select_pointer'] ) : null;
	$valid['css_layout'] = ( isset( $input['css_layout'] ) ) ? 1 : 0;
	
	// Results
	if ( isset( $input['summary'] ) ) {
		switch ( $input['summary'] ) {
			case 0:
				$valid['summary'] = 0;
				break;
			case 2:
				$valid['summary'] = 2;
				break;
			case 1:
			default:
				$valid['summary'] = 1;
				break;
		}
	} else {
		$valid['summary'] = 1;
	}
	
	// Define an array of label and class names
	$names = array(
		'total_amount',
		'down_payment',
		'interest_rate',
		'amortization_period',
		'payment_period',
		'submit'
	);
	
	// Clean the labels and register errors
	foreach ( $names as $name ) {
		$valid[$name . '_label'] = lidd_mc_clean_text( $input[$name . '_label'] );
		$valid[$name . '_class'] = lidd_mc_clean_text( $input[$name . '_class'] );
		if ( $valid[$name . '_label'] != $input[$name . '_label'] ) lidd_mc_settings_error( $name . '_label', 'label' );
		if ( $valid[$name . '_class'] != $input[$name . '_class'] ) lidd_mc_settings_error( $name . '_class', 'class' );
	}
	
	return $valid;
}


// --------------------------------------------
/**
 * Callback function to display the settings page.
 */
function lidd_mc_settings_page() {
	?>
	<div class="wrap">
		<h2>Lidd's Mortgage Calculator</h2>
		<p>Add the calculator widget from the Widgets page or add it to a page or post using the shortcode [mortgagecalculator] or [lidd_mc].</p>
		
		<form action="options.php" method="post">
			<?php settings_fields( LIDD_MC_OPTIONS ); ?>
			<?php do_settings_sections( LIDD_MC_OPTIONS ); ?>
			<input name="submit" type="submit" value="Save Changes" class="button button-primary" />
		</form>
	</div>
	<?php
}
