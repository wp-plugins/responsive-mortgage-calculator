<?php
/**
 * Initialization file.
 *
 * This file includes other necessary files.
 *
 * @package Lidd's Mortgage Calculator
 * @since 2.0.0
 */

defined('ABSPATH') or die("...");

include ( LIDD_MC_ROOT . 'includes/LiddMCOptions.php' );
include ( LIDD_MC_ROOT . 'includes/function-rmcp-get-localization.php' );
include ( LIDD_MC_ROOT . 'includes/load_form.php' );
include ( LIDD_MC_ROOT . 'includes/shortcode.php' );
include ( LIDD_MC_ROOT . 'includes/widget.php' );
include ( LIDD_MC_ROOT . 'includes/load_scripts.php' );
include ( LIDD_MC_ROOT . 'includes/options.php' );
