<?php
/**
 * This is a file to build the form.
 *
 * @package Lidd's Mortgage Calculator
 * @since 1.0.0
 */

// Include classes
include ( LIDD_MC_ROOT . 'includes/LiddMCForm.php' );
include ( LIDD_MC_ROOT . 'includes/LiddMCInputFactory.php' );
include ( LIDD_MC_ROOT . 'includes/LiddMCInput.php' );
include ( LIDD_MC_ROOT . 'includes/LiddMCInputExtended.php' );
include ( LIDD_MC_ROOT . 'includes/LiddMCTextInput.php' );
include ( LIDD_MC_ROOT . 'includes/LiddMCHiddenInput.php' );
include ( LIDD_MC_ROOT . 'includes/LiddMCSelectInput.php' );
include ( LIDD_MC_ROOT . 'includes/LiddMCSubmit.php' );
include ( LIDD_MC_ROOT . 'includes/LiddMCDetails.php' );


# *********************************** #
# ***** CREATE FORM AND INPUTS ****** #

/**
 * Create a function to create the calculator form.
 *
 * @return  string  The HTML to display the form and results.
 */
function lidd_mc_display_form() {
	
	// Get the options
	$options = get_option( LIDD_MC_OPTIONS );
	
	$form = new LiddMCForm( 'lidd_mc_form', $options );
	
	$output = $form->getForm();
	
	// Create a display area for results.
	$details = new LiddMCDetails( $options['summary'], $options['theme'] );
	$output .= $details->getDetails();
	
	return $output;
}

# ***** END CREATE FORM AND INPUTS ****** #
# *************************************** #
