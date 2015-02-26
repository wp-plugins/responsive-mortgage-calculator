<?php
/*
Plugin Name: Responsive Mortgage Calculator
Plugin URI: http://liddweaver.com/responsive-mortgage-calculator/
Description: Add a responsive mortgage calculator widget or use the shortcode [mortgagecalculator] or [rmc]. Plenty of options to customize it to your preference.
Version: 2.1.1
Author: liddweaver
Author URI: http://liddweaver.com
License: GPLv2
License URI: https://www.gnu.org/licenses/gpl-2.0.html
Domain Path: /languages
Text Domain: responsive-mortgage-calculator
*/


// Make sure the plugin is accessed through the appropriate channels
defined('ABSPATH') or die("Cannot access this plugin directly.");


// -----------------------------------
// Constants


defined( 'LIDD_MC_ROOT' ) or define( 'LIDD_MC_ROOT', plugin_dir_path( __FILE__ ) );
defined( 'LIDD_MC_URL' ) or define( 'LIDD_MC_URL', plugin_dir_url( __FILE__ ) );
defined( 'LIDD_MC_OPTIONS' ) or define( 'LIDD_MC_OPTIONS', 'lidd_mc_options' );


// -----------------------------------
// Languages / Internationalization


function lidd_mc_load_plugin_textdomain() {
	load_plugin_textdomain( 'responsive-mortgage-calculator', false, basename( dirname( __FILE__ ) ) . '/languages' );
}
add_action( 'plugins_loaded', 'lidd_mc_load_plugin_textdomain' );


// -----------------------------------
// Activation


register_activation_hook( __FILE__, 'lidd_mc_install' );
function lidd_mc_install() {
	// Only add the options if they don't already exist.
	if ( !get_option( LIDD_MC_OPTIONS ) ) {
		$defaults = include( 'includes/defaults.php' ); // Get defaults
		update_option( LIDD_MC_OPTIONS, $defaults ); // Insert defaults into the options table
	}
}


// -----------------------------------
// Initialization


include ( LIDD_MC_ROOT . 'includes/init.php' );


// -----------------------------------
// That's all, folks!
