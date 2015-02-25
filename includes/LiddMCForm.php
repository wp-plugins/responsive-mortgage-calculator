<?php
/**
 * A class to define the mortgage calculator form
 *
 * @package Lidd's Mortgage Calculator
 * @since 2.0.0
 */
class LiddMCForm
{
	/**
	 * Store the settings
	 * @var array
	 */
	private $options;
	
	/**
	 * Store the input objects
	 * @var array
	 */
	private $inputs;
	
	/**
	 * Store the name and id
	 * @var string
	 */
	private $name;
	
	/**
	 * Constructor.
	 *
	 * Sets the name/id of the form and stores the options.
	 *
	 * @param  string  $name     The name and id of the form.
	 * @param  array   $options  The calculator settings.
	 */
	public function __construct( $name, $options )
	{
		$this->name = $name;
		$this->options = $options;
	}
	
	/**
	 * Return the form.
	 *
	 * @return  string   The HTML to display the form.
	 */
	public function getForm()
	{
		// Store the options locally
		$options = $this->options;
		
		// Create the inputs
		$factory = new LiddMCInputFactory( $options['theme'], $options['css_layout'], $options['select_style'], $options['select_pointer'] );
		
		// Total Amount
		$ta = $factory->newInput( 'text', 'lidd_mc_total_amount' );
		$ta->setLabel( $options['total_amount_label'] );
		$ta->setPlaceholder( $options['currency'] );
		$ta->setClass( $options['total_amount_class'] );
		
		// Down payment
		if ( $options['down_payment_visible'] ) {
			$dp = $factory->newInput( 'text', 'lidd_mc_down_payment' );
			$dp->setLabel( $options['down_payment_label'] );
			$dp->setPlaceholder( $options['currency'] );
			$dp->setClass( $options['down_payment_class'] );
		} else {
			$dp = $factory->newInput( 'hidden', 'lidd_mc_down_payment' );
			$dp->setValue( 0 );
		}
	
		// Interest rate
		$ir = $factory->newInput( 'text', 'lidd_mc_interest_rate' );
		$ir->setLabel( $options['interest_rate_label'] );
		$ir->setPlaceholder( '%' );
		$ir->setClass( $options['interest_rate_class'] );
	
		// Amortization period
		$ap = $factory->newInput( 'text', 'lidd_mc_amortization_period' );
		$ap->setLabel( $options['amortization_period_label'] );
		$ap->setPlaceholder( __( 'years', 'liddmc' ) );
		$ap->setClass( $options['amortization_period_class'] );
	
		// Payment period
		if ( in_array( $options['payment_period'], array( 12, 26, 52 ) ) ) {
			$pp = $factory->newInput( 'hidden', 'lidd_mc_payment_period' );
			$pp->setValue( $options['payment_period'] );
		} else {
			$pp = $factory->newInput( 'select', 'lidd_mc_payment_period' );
			$pp->setLabel( $options['payment_period_label'] );
			$pp->setClass( $options['payment_period_class'] );
			$pp->setOptions( array(
					12 => __( 'Monthly', 'liddmc' ),
					26 => __( 'Bi-Weekly', 'liddmc' ),
					52 => __( 'Weekly', 'liddmc' )
				) );
		}

		// Number of compounding periods
		$cp = $factory->newInput( 'hidden', 'lidd_mc_compounding_period' );
		$cp->setValue( $options['compounding_period'] );

		// Currency
		$cur = $factory->newInput( 'hidden', 'lidd_mc_currency' );
		$cur->setValue( $options['currency'] );
		
		// Currency Code
		$cc = $factory->newInput( 'hidden', 'lidd_mc_currency_code' );
		$cc->setValue( $options['currency_code'] );
	
		// Submit button
		$sub = $factory->newInput( 'submit', 'lidd_mc_submit' );
		$sub->setValue( $options['submit_label'] );
		$sub->setClass( $options['submit_class'] );
		
		// Build the form
		$form = "<form action=\"http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]\" id=\"" . esc_attr( $this->name ) . "\" class=\"" . esc_attr( $this->name ) . "\" method=\"post\">";

		$form .= $ta->getInput();
		$form .= $dp->getInput();
		$form .= $ir->getInput();
		$form .= $ap->getInput();
		$form .= $pp->getInput();
		$form .= $cp->getInput();
		$form .= $cur->getInput();
		$form .= $cc->getInput();
		$form .= $sub->getInput();
		
		$form .= '</form>';
		
		return $form;
	}
		
}
