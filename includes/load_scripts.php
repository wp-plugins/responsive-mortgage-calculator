<?php
/**
 * This file determines whether to load the JS and CSS
 *
 * @package Lidd's Mortgage Calculator
 * @since 2.0.0
 */

// Load JS and CSS if the widget is active.
add_action( 'init', 'lidd_mc_check_widget' );

function lidd_mc_check_widget() {
	if ( is_active_widget( '', '', 'lidd_mc_widget' ) ) {
		// Call the function to enqueue the style and script.
		lidd_mc_enqueue_scripts();
	}
}

// Make sure the stylesheet and jquery is included in the header if the shortcode is called.
add_action( 'wp', 'lidd_mc_detect_shortcode' );

function lidd_mc_detect_shortcode() {
	global $post;
	
	$pattern = get_shortcode_regex();
	
	// Check the content.
	if ( preg_match_all( '/' . $pattern . '/s', $post->post_content, $matches )
		&& array_key_exists( 2, $matches ) 
		&& ( in_array( 'mortgagecalculator', $matches[2] ) || in_array( 'rmc', $matches[2] ) ) ) {
		
			// Call the function to enqueue the style and script.
			lidd_mc_enqueue_scripts();
		
	}
}

// Function to enqueue the stylesheet and JavaScript.
// Called for the widget or the shortcode.
function lidd_mc_enqueue_scripts() {
	wp_enqueue_script( 'lidd_mc', LIDD_MC_URL . 'js/lidd-mc.js', 'jquery', '2.0.1', true );
	// Localize script
	$bs = '<b class="lidd_mc_b">';
	$be = '</b>';
	wp_localize_script( 'lidd_mc', 'lidd_mc_script_vars', array(
			'ta_error' => __( 'Please enter the total amount of the mortgage.', 'liddmc' ),
			'dp_error' => __( 'Please enter a down payment amount or leave blank.', 'liddmc' ),
			'ir_error' => __( 'Please enter an interest rate.', 'liddmc' ),
			'weekly' => __( 'Weekly', 'liddmc' ),
			'biweekly' => __( 'Bi-Weekly', 'liddmc' ),
			'monthly' => __( 'Monthly', 'liddmc' ),
			'ap_error' => __( 'Please enter an amortization period.', 'liddmc' ),
			'p_text' => sprintf(
				__( '%s Payment', 'liddmc' ),
				$period
			),
			'sy_text' => sprintf( // Summary with number of years
				__( 'For a mortgage of %1$s amortized over %2$s years, your %3$s payment is', 'liddmc' ),
				$bs . '{total_amount}' . $be,
				$bs . '{amortization_years}' . $be,
				$bs . '{payment_period}' . $be
			),
			'sym1_text' => sprintf( // Summary with years and months
				__( 'For a mortgage of %1$s amortized over %2$s years and %3$s month, your %4$s payment is', 'liddmc' ),
				$bs . '{total_amount}' . $be,
				$bs . '{amortization_years}' . $be,
				$bs . '1' . $be,
				$bs . '{payment_period}' . $be
			),
			'sym_text' => sprintf( // Summary with years and months
				__( 'For a mortgage of %1$s amortized over %2$s years and %3$s months, your %4$s payment is', 'liddmc' ),
				$bs . '{total_amount}' . $be,
				$bs . '{amortization_years}' . $be,
				$bs . '{amortization_months}' . $be,
				$bs . '{payment_period}' . $be
			),
			'syw1_text' => sprintf( // Summary with years and weeks
				__( 'For a mortgage of %1$s amortized over %2$s years and %3$s week, your %4$s payment is', 'liddmc' ),
				$bs . '{total_amount}' . $be,
				$bs . '{amortization_years}' . $be,
				$bs . '1' . $be,
				$bs . '{payment_period}' . $be
			),
			'syw_text' => sprintf( // Summary with years and weeks
				__( 'For a mortgage of %1$s amortized over %2$s years and %3$s weeks, your %4$s payment is', 'liddmc' ),
				$bs . '{total_amount}' . $be,
				$bs . '{amortization_years}' . $be,
				$bs . '{amortization_weeks}' . $be,
				$bs . '{payment_period}' . $be
			),
			'mp_text' => __( 'Mortgage Payment', 'liddmc' ),
			'tmwi_text' => __( 'Total Mortgage with Interest', 'liddmc' ),
			'twdp_text' => __( 'Total with Down Payment', 'liddmc' ),
		)
	);
	// Only enqueue the style if styles are on
	$options = get_option( LIDD_MC_OPTIONS );
	if ( $options['css_layout'] || $options['select_style'] || $options['theme'] != 'none' ) {
		wp_enqueue_style( 'lidd_mc', LIDD_MC_URL . 'css/style.css', '', '2.0.1', 'screen' );
	}
}
