<?php

defined('ABSPATH') or die("...");

/**
 * A class to define hidden inputs
 *
 * @package Lidd's Mortgage Calculator
 * @since 2.0.0
 */
class LiddMCHiddenInput extends LiddMCInput
{
	
	/**
	 * Constructor.
	 *
	 * @param string  $name   The name and id of the hidden input.
	 */
	public function __construct( $name )
	{
		$this->name = $name;
	}
	
	/**
	 * Return the input
	 *
	 * @return  string   The HTML to create the hidden input.
	 */
	public function getInput()
	{	
		// Create the input
		$input = '<input type="hidden" name="' . esc_attr( $this->name ) . '" id="' . esc_attr( $this->name ) . '" value="' . esc_attr( $this->value ) . '" />';
		return $input;
	}
	
}
