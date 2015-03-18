<?php
/**
 * This file determines whether to load the JS and CSS
 *
 * @package Lidd's Mortgage Calculator
 * @since 2.0.0
 */

// Check if widget is active
function lidd_mc_detect_widget() {
	return is_active_widget( false, false, 'lidd_mc_widget', true );
}

// Check if the shortcode is included in the content.
function lidd_mc_detect_shortcode() {
	global $post;
	
	$pattern = get_shortcode_regex();
	
	// Check the content.
	return ( preg_match_all( '/' . $pattern . '/s', $post->post_content, $matches )
		&& array_key_exists( 2, $matches ) 
		&& ( in_array( 'mortgagecalculator', $matches[2] ) || in_array( 'rmc', $matches[2] ) )
	);
}

// Check whether to load JS and CSS
function lidd_mc_are_scripts_required() {
	if ( lidd_mc_detect_widget() || lidd_mc_detect_shortcode() ) {
		lidd_mc_enqueue_scripts();
	}
}
add_action( 'wp', 'lidd_mc_are_scripts_required' );

// Localize JavaScript
function lidd_mc_localize_script() {
	
	// HTML wrapper on return values 
	$bs = '<b class="lidd_mc_b">';
	$be = '</b>';
	
	wp_localize_script( 'lidd_mc', 'lidd_mc_script_vars', array(
			'ta_error' => __( 'Please enter the total amount of the mortgage.', 'responsive-mortgage-calculator' ),
			'dp_error' => __( 'Please enter a down payment amount or leave blank.', 'responsive-mortgage-calculator' ),
			'ir_error' => __( 'Please enter an interest rate.', 'responsive-mortgage-calculator' ),
			'ap_error' => __( 'Please enter an amortization period.', 'responsive-mortgage-calculator' ),
			'weekly' => __( 'Weekly', 'responsive-mortgage-calculator' ),
			'biweekly' => __( 'Bi-Weekly', 'responsive-mortgage-calculator' ),
			'monthly' => __( 'Monthly', 'responsive-mortgage-calculator' ),
			'weekly_payment' => __( 'Weekly Payment', 'responsive-mortgage-calculator' ),
			'biweekly_payment' => __( 'Bi-Weekly Payment', 'responsive-mortgage-calculator' ),
			'monthly_payment' => __( 'Monthly Payment', 'responsive-mortgage-calculator' ),
			'currency_format' => $bs . sprintf(
				_x( '%1$s%2$s %3$s', 'Currency format, eg. $2.00 USD', 'responsive-mortgage-calculator' ),
				'{symbol}',
				'{amount}',
				'{code}'
			) . $be,
			'sy_text' => sprintf( // Summary with number of years
				__( 'For a mortgage of %1$s%2$s amortized over %3$s years, your %4$s payment is', 'responsive-mortgage-calculator' ),
				$bs . '{currency}' . $be,
				$bs . '{total_amount}' . $be,
				$bs . '{amortization_years}' . $be,
				$bs . '{payment_period}' . $be
			),
			'sym1_text' => sprintf( // Summary with years and months
				__( 'For a mortgage of %1$s%2$s amortized over %3$s years and %4$s month, your %5$s payment is', 'responsive-mortgage-calculator' ),
				$bs . '{currency}' . $be,
				$bs . '{total_amount}' . $be,
				$bs . '{amortization_years}' . $be,
				$bs . '1' . $be,
				$bs . '{payment_period}' . $be
			),
			'sym_text' => sprintf( // Summary with years and months
				__( 'For a mortgage of %1$s%2$s amortized over %3$s years and %4$s months, your %5$s payment is', 'responsive-mortgage-calculator' ),
				$bs . '{currency}' . $be,
				$bs . '{total_amount}' . $be,
				$bs . '{amortization_years}' . $be,
				$bs . '{amortization_months}' . $be,
				$bs . '{payment_period}' . $be
			),
			'syw1_text' => sprintf( // Summary with years and weeks
				__( 'For a mortgage of %1$s%2$s amortized over %3$s years and %4$s week, your %5$s payment is', 'responsive-mortgage-calculator' ),
				$bs . '{currency}' . $be,
				$bs . '{total_amount}' . $be,
				$bs . '{amortization_years}' . $be,
				$bs . '1' . $be,
				$bs . '{payment_period}' . $be
			),
			'syw_text' => sprintf( // Summary with years and weeks
				__( 'For a mortgage of %1$s%2$s amortized over %3$s years and %4$s weeks, your %5$s payment is', 'responsive-mortgage-calculator' ),
				$bs . '{currency}' . $be,
				$bs . '{total_amount}' . $be,
				$bs . '{amortization_years}' . $be,
				$bs . '{amortization_weeks}' . $be,
				$bs . '{payment_period}' . $be
			),
			'mp_text' => __( 'Mortgage Payment', 'responsive-mortgage-calculator' ),
			'tmwi_text' => __( 'Total Mortgage with Interest', 'responsive-mortgage-calculator' ),
			'twdp_text' => __( 'Total with Down Payment', 'responsive-mortgage-calculator' ),
		)
	);
}

// Function to enqueue JS and CSS
function lidd_mc_enqueue_scripts() {
	
	// Enqueue script
	wp_enqueue_script( 'lidd_mc', LIDD_MC_URL . 'js/lidd-mc.js', 'jquery', '2.1.3', true );
	
	// Localize script
	lidd_mc_localize_script();
	
	// Get options to check for styling
	$options = get_option( LIDD_MC_OPTIONS );
	
	// Enqueue styles if needed
	if ( $options['css_layout'] || $options['theme'] != 'none' ) {
		wp_enqueue_style( 'lidd_mc', LIDD_MC_URL . 'css/style.css', '', '2.1.3', 'screen' );
	}
}
