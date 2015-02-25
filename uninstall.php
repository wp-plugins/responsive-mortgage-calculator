<?php
/**
 *	Lidd Mortgage Calculator uninstall script
 *
 * @package Lidd's Mortgage Calculator
 * @since 2.0.0
 */

// Make sure this file is called from within Wordpress.
defined( 'WP_UNINSTALL_PLUGIN' ) or exit();

// Name for the options in the database.
defined( LIDD_MC_OPTIONS ) or define( 'LIDD_MC_OPTIONS', 'lidd_mc_options' );

// Delete single site options.
delete_option( LIDD_MC_OPTIONS );
	
// Delete options in multi-site installation.
delete_site_option( LIDD_MC_OPTIONS );