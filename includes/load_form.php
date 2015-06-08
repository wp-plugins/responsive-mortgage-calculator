<?php

defined('ABSPATH') or die("...");

/**
 * This is a file to build the form.
 *
 * @package Lidd's Mortgage Calculator
 * @since 2.0.0
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

// Include the function to resolve attributes and options
include ( LIDD_MC_ROOT . 'includes/resolve_attributes.php' );

// Include the form building function
include ( LIDD_MC_ROOT . 'includes/display_form.php' );

