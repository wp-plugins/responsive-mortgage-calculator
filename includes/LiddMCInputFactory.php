<?php

defined('ABSPATH') or die("...");

/**
 * An class to instantiate and return input objects to the form class.
 *
 * @package Lidd's Mortgage Calculator
 * @since 2.0.0
 */
class LiddMCInputFactory
{	
	/**
	 * Allowed input types.
	 *
	 * @var array
	 */
	private $types = array(
		'text',
		'hidden',
		'select',
		'submit'
	);

	/**
	 * CSS theme.
	 *
	 * @var string
	 */
	private $theme;
	
	/**
	 * CSS layout setting.
	 *
	 * @var int
	 */
	private $css_layout;
	
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
	 * Constructor.
	 *
	 * Sets styling variables to pass to the input objects.
	 *
	 * @param  string  $theme           The CSS theme for the form.
	 * @param  int     $css_layout      Whether to apply responsive styling to the form.
	 * @param  int     $select_style    Whether to apply additional CSS to the select box with pseudo-elements.
	 * @param  string  $select_pointer  A partial hook to adjust the vertical position of the down arrow on the select box.
	 */
	public function __construct( $theme, $css_layout, $select_style, $select_pointer )
	{
		$this->theme = $theme;
		$this->css_layout = $css_layout;
		$this->select_style = $select_style;
		$this->select_pointer = $select_pointer;
	}
	
	/**
	 * Create and return a new input.
	 *
	 * @param  string   $type  The type of input to instantiate.
	 * @param  string   $name  The name/id to give to the newly created input.
	 * @return object          A new input object of $type.
	 */
	public function newInput( $type, $name )
	{
		// Decide what type of input to create
		switch( $type ) {
			case 'hidden':
				return new LiddMCHiddenInput( $name );
				break;
			case 'select':
				return new LiddMCSelectInput( $name, $this->theme, $this->css_layout, $this->select_style, $this->select_pointer );
				break;
			case 'submit':
				return new LiddMCSubmit( $name );
				break;
			case 'text':
			default:
				return new LiddMCTextInput( $name, $this->theme, $this->css_layout );
				break;
		}
		
	}
	
}
