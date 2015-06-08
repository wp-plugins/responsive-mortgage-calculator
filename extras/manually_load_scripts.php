<?php
// -- This is here for security. Don't copy it into your other files.
defined('ABSPATH') or die("...");
// --

/*
 * The JS and CSS may not load when using the shortcode inside a visual editor, like Visual Composer.
 * Include this script in your theme's functions.php file to manually load the JS and CSS.
 *
 * Be sure to change the page slug or modify the conditional to target the correct page.
 * See: https://codex.wordpress.org/Conditional_Tags
 */
function lidd_mc_manually_load_mortgage_calculator_scripts() {
    if ( is_page('your-page-slug-here') ) {
        wp_enqueue_script( 'lidd_mc' );
        wp_enqueue_style( 'lidd_mc' );
    }
}
add_action( 'wp_enqueue_scripts', 'lidd_mc_manually_load_mortgage_calculator_scripts', 100 );
