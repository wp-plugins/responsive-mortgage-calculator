<?php

defined('ABSPATH') or die("...");

/**
 * An abstract class to define select inputs
 *
 * @package Lidd's Mortgage Calculator
 * @since 2.0.0
 */
class LiddMCSelectInput extends LiddMCInputExtended
{	
	/**
	 * Fancy select box setting.
	 *
	 * @var int
	 */
	private $select_style;
	
	/**
	 * Fancy select box down arrow position.
	 *
	 * @var string
	 */
	private $select_pointer;
	
	/**
	 * Select box options.
	 *
	 * @var array
	 */
	private $options = array();
	
	/**
	 * Constructor.
	 *
	 * Sets the name/id and styling options for the select box.
	 *
	 * @param  string  $name            The name/id of the select box.
	 * @param  string  $theme           The CSS theme for the form.
	 * @param  int     $css_layout      Whether to apply responsive styling to the form.
	 * @param  int     $select_style    Whether to apply additional CSS to the select box with pseudo-elements.
	 * @param  string  $select_pointer  A partial hook to adjust the vertical position of the down arrow on the select box.
	 */
	public function __construct( $name, $theme, $css_layout, $select_style, $select_pointer )
	{
		$this->name = $name;
		$this->theme = $theme;
		$this->css_layout = $css_layout;
		$this->select_style = $select_style;
		$this->select_pointer = $select_pointer;
	}

	/**
	 * Set options for the select box.
	 *
	 * @param  array  $options The value and text to display for each option.
	 */
	public function setOptions( $options )
	{
		$this->options = $options;
	}
	
	/**
	 * Return the input.
	 *
	 * @return  string  The HTML to create the select box.
	 */
	public function buildInput()
	{
		// Open the select box.
		$input = '<span class="lidd_mc_select';
		if ( isset( $this->css_layout ) && $this->css_layout ) {
			// Apply fancy responsive styles
			if ( isset( $this->select_style ) && $this->select_style && isset( $this->theme ) && ( $this->theme == 'dark' || $this->theme == 'light' ) ) {
				$input .= ' lidd_mc_select_fancy_' . esc_attr( $this->theme );
			}
			// Apply responsive styles
			else {
				$input .= ' lidd_mc_select_responsive';
			}
			
			// Add data-top for select_pointer option
			if (
				isset( $this->select_style ) &&
				$this->select_style &&
				isset( $this->theme ) &&
				$this->theme != 'none' &&
				$this->select_pointer
				) {
				$input .= ' lidd_mc_top_' . esc_attr( $this->select_pointer ) . 'em"';
			}
		}
		// Close the class and open the select box
		$input .= '"><select name="' . esc_attr( $this->name ) . '" id="' . esc_attr( $this->name ) . '">';
	
		// Create the options.
		foreach ( $this->options as $k => $v ) {
			$input .= '<option value="' . esc_attr( $k ) . '"';
            if ( $k == $this->value ) {
                $input .= ' selected';
            }
            $input .= '>' . esc_html( $v ) . '</option>';
		}
	
		// Close the select box.
		$input .= '</select></span>';
	
		return $input;
		
	}
	
}
