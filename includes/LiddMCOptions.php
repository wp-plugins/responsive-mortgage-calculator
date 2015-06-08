<?php

defined('ABSPATH') or die("...");

/**
 * A class to hold the plugin settings
 *
 * @package Lidd's Mortgage Calculator
 * @since 2.0.0
 */
class LiddMCOptions
{
	/**
	 * Store the plugin options.
	 *
	 * @var array
	 */
	private $options = array();
	
	/**
	 * Get the settings from the database and store in the object.
	 */
	public function __construct()
	{
		$this->options = get_option( LIDD_MC_OPTIONS );
	}
	
	/**
	 * Return an option value.
	 *
	 * @param  string           $option  The name of the plugin option.
	 * @return string|int|null           The value of the option.
	 */
	public function getOption( $option )
	{
		if ( isset( $this->options[$option] ) ) {
			return $this->options[$option];
		}
	}
	
}
