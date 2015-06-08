<?php

defined('ABSPATH') or die("...");

/**
 * An class to define submit inputs
 *
 * @package Lidd's Mortgage Calculator
 * @since 2.0.0
 */
class LiddMCSubmit extends LiddMCInput
{	
	/**
	 * Constructor.
	 *
	 * Sets the name of the submit button.
	 *
	 * @param  string  $name  The name of the button.
	 */
	public function __construct( $name )
	{
		$this->name = $name;
	}
	
	/**
	 * Return the input.
	 *
	 * @return  string  The HTML to display the button.
	 */
	public function getInput()
	{
		// Open a div. Add classes.
		$input = '<div class="lidd_mc_input">';
		
		// Create the input
		$input .= '<input type="submit" name="' . esc_attr( $this->name ) . '" id="' . esc_attr( $this->name ) . '"';
		$input .= ( isset( $this->class ) && $this->class ) ? ' class="' . esc_attr( $this->class ) .'"' : '';
		$input .= ' value="' . esc_attr( $this->value ) . '"/>';
	
		// Close the div.
		$input .= '</div>';
	
		return $input;
		
	}
	
}
