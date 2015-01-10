<?php
/**
 * This file adds the shortcode functionality
 *
 * @package Lidd's Mortgage Calculator
 * @since 1.0.0
 */

// Add a shortcode.
add_shortcode( 'mortgagecalculator', 'lidd_mc_shortcode' );
add_shortcode( 'lidd_mc', 'lidd_mc_shortcode' );

/**
 * Callback function for the shortcode.
 */
function lidd_mc_shortcode() {
	
	// Get the form and return it for display.
	return lidd_mc_display_form();
}
