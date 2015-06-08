<?php

defined('ABSPATH') or die("...");

/**
 * A class to define inputs with more structure.
 *
 * @package Lidd's Mortgage Calculator
 * @since 2.0.0
 */
class LiddMCInputExtended extends LiddMCInput
{	
	/**
	 * Get the input.
	 *
	 * This method builds a wrapper and label for a visible
	 * input element. It applies classes for CSS hooks,
	 * fetches the input element from a method, and
	 * fetches the error reporting span from a method.
	 *
	 * @return  string   The HTML to display the input element.
	 */
	public function getInput()
	{
		// Open a div. Add classes.
		$input = '<div class="lidd_mc_input';
		$input .= ( isset( $this->class ) && $this->class ) ? ' ' . esc_attr( $this->class ) : '';
		$input .= ( isset( $this->theme ) && ( $this->theme == 'dark' || $this->theme == 'light' ) ) ? ' lidd_mc_input_' . esc_attr( $this->theme ) : '';
		$input .= ( isset( $this->css_layout ) && $this->css_layout ) ? ' lidd_mc_input_responsive' : '';
		$input .= '">';
		
		// Create a label.
		$input .= '<label for="' . esc_attr( $this->name ) . '">' . esc_html( $this->label ) . '</label>';
		
		// Get the input
		$input .= $this->buildInput();
		
		// Append an error reporting span.
		$input .= $this->getError();
	
		// Close the div.
		$input .= '</div>';
	
		return $input;
	}
	
	/**
	 * Build the input.
	 *
	 * Defines a method for building an input in an extended class.
	 */
	protected function buildInput() {}
	
	
}