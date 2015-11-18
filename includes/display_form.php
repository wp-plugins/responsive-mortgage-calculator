<?php

defined('ABSPATH') or die("...");

/**
 * This is a function to build the form.
 *
 * @package Lidd's Mortgage Calculator
 * @since 2.1.0
 */


/**
 * Create a function to create the calculator form.
 *
 * @param	array	$attr	The shortcode attributes
 * @return  string			The HTML to display the form and results.
 */
function lidd_mc_display_form( $attr = array() ) {
	
	// Get the options
	$options = get_option( LIDD_MC_OPTIONS );
	
	// Resolve attributes with options
	if ( !empty( $attr ) ) {
		$options = lidd_mc_resolve_attributes( $attr, $options );
	}
	
	$form = new LiddMCForm( 'lidd_mc_form', $options );
	
	$output = $form->getForm();
	
	return $output;
}
