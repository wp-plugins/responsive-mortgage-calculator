<?php
/**
 * This is a function to build the form.
 *
 * @package Lidd's Mortgage Calculator
 * @since 2.1.0
 */

/**
 * Create a function to create the calculator form.
 *
 * @return  string  The HTML to display the form and results.
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
	
	// Create a display area for results.
	$details = new LiddMCDetails( $options['summary'], $options['theme'] );
	$output .= $details->getDetails();
	
	return $output;
}
