<?php
/**
 * This file determines whether to load the JS and CSS
 *
 * @package Lidd's Mortgage Calculator
 * @since 1.0.0
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
	wp_enqueue_script( 'lidd_mc', LIDD_MC_URL . 'js/lidd-mc.js', 'jquery', '2.0.0', true );
	// Only enqueue the style if styles are on
	$options = get_option( LIDD_MC_OPTIONS );
	if ( $options['css_layout'] || $options['select_style'] || $options['theme'] != 'none' ) {
		wp_enqueue_style( 'lidd_mc', LIDD_MC_URL . 'css/style.css', '', '2.0.1', 'screen' );
	}
}
