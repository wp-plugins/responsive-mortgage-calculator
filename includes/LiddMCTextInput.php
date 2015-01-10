<?php
/**
 * A class to define text inputs
 *
 * @package Lidd's Mortgage Calculator
 * @since 1.0.0
 */
class LiddMCTextInput extends LiddMCInputExtended
{	
	/**
	 * Constructor.
	 *
	 * Sets the name/id and styling options for the text input.
	 *
	 * @param  string  $name            The name/id of the input.
	 * @param  string  $theme           The CSS theme for the form.
	 * @param  int     $css_layout      Whether to apply responsive styling to the form.
	 */
	public function __construct( $name, $theme, $css_layout )
	{
		$this->name = $name;
		$this->theme = $theme;
		$this->css_layout = $css_layout;
	}
	
	/**
	 * Return the input.
	 *
	 * @return  string  The HTML to display the input.
	 */
	public function buildInput()
	{
		// Create the input
		$input = '<input type="text" name="' . esc_attr( $this->name ) . '" id="' . esc_attr( $this->name ) . '" ';
		$input .= isset( $this->placeholder ) ? 'placeholder="' . esc_attr( $this->placeholder ) . '"' : '';
		$input .= '/>';
		
		return $input;
	}
	
}
