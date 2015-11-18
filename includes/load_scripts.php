<?php

defined('ABSPATH') or die("...");

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
	
	// Check the content.
    $rmc = preg_match( '/\[rmc.*?\]/i', $post->post_content );
    $mortgagecalculator = preg_match( '/\[mortgagecalculator.*?\]/i', $post->post_content );
    
    return ( $rmc || $mortgagecalculator );
    
    // Old pattern matching code, held for reference
	/*
	$pattern = get_shortcode_regex();
	return (
        (   preg_match_all( '/' . $pattern . '/s', $post->post_content, $matches )
		    && array_key_exists( 2, $matches ) 
		    && ( in_array( 'mortgagecalculator', $matches[2] ) || in_array( 'rmc', $matches[2] ) )
        ) || (
            strpos( $post->post_content, 'lidd_mc_form' ) !== false
        )
	);
    */
}

// Check whether to load JS and CSS
function lidd_mc_are_scripts_required() {
    
    $version = '2.2.4';
    
    // Register JS
    wp_register_script( 'lidd_mc', LIDD_MC_URL . 'js/lidd-mc.js', 'jquery', $version, true );
    lidd_mc_localize_script();
    
    // Register CSS
	wp_enqueue_style( 'lidd_mc', LIDD_MC_URL . 'css/style.css', '', $version, 'screen' );
    
	if ( lidd_mc_detect_widget() || lidd_mc_detect_shortcode() ) {
		lidd_mc_enqueue_scripts();
	}
}
add_action( 'wp_enqueue_scripts', 'lidd_mc_are_scripts_required' );

// Localize JavaScript
function lidd_mc_localize_script() {

    $localization = rmcp_get_localization();
    
	wp_localize_script( 'lidd_mc', 'lidd_mc_script_vars', $localization );
}

// Function to enqueue JS and CSS
function lidd_mc_enqueue_scripts() {
	
	// Enqueue script
	wp_enqueue_script( 'lidd_mc' );
	
	// Get options to check for styling
	$options = get_option( LIDD_MC_OPTIONS );
	
	// Enqueue styles if needed
	if ( $options['css_layout'] || $options['theme'] != 'none' ) {
		wp_enqueue_style( 'lidd_mc' );
	}
}
