<?php

defined('ABSPATH') or die("...");

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
     * Store the submission processor
     * @var object
     */
    private $processor;
	
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
        
        $this->set_processor();
	}
	
    /**
     * Process submissions
     */
    private function set_processor()
    {
        include LIDD_MC_ROOT . 'includes/LiddMCProcessor.php';
        $this->processor = new LiddMCProcessor( $this->options['compounding_period'] );
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
		$ir->setValue( $options['interest_rate_value'] );
	
		// Amortization period
		$ap = $factory->newInput( 'text', 'lidd_mc_amortization_period' );
		$ap->setLabel( $options['amortization_period_label'] );
        if ( isset( $options['amortization_period_units'] ) ) {
            switch ( $options['amortization_period_units'] ) {
                case 1:
            		$ap->setPlaceholder( __( 'months', 'responsive-mortgage-calculator' ) );
                    break;
                case 0:
                default:
            		$ap->setPlaceholder( __( 'years', 'responsive-mortgage-calculator' ) );
                    break;
                    
            }
        } else {
    		$ap->setPlaceholder( __( 'years', 'responsive-mortgage-calculator' ) );
        }
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
					12 => __( 'Monthly', 'responsive-mortgage-calculator' ),
					26 => __( 'Bi-Weekly', 'responsive-mortgage-calculator' ),
					52 => __( 'Weekly', 'responsive-mortgage-calculator' )
				) );
		}
	
		// Submit button
		$sub = $factory->newInput( 'submit', 'lidd_mc_submit' );
		$sub->setValue( $options['submit_label'] );
		$sub->setClass( $options['submit_class'] );
        
        // Set submitted data submission
        if ( $this->processor->has_submission() ) {
            
            $ta->setValue( $this->processor->get( 'total_amount' ) );
            $dp->setValue( $this->processor->get( 'down_payment' ) );
            $ir->setValue( $this->processor->get( 'interest_rate' ) );
            $ap->setValue( $this->processor->get( 'amortization_period' ) );
            $pp->setValue( $this->processor->get( 'payment_period' ) );
            
            if ( $this->processor->has_error() ) {
                
                $localization = rmcp_get_localization();
                $errors = $this->processor->get_errors();
                
                ( isset( $errors['total_amount'] ) ) && $ta->setError( $localization['ta_error'] );
                ( isset( $errors['down_payment'] ) ) && $dp->setError( $localization['dp_error'] );
                ( isset( $errors['interest_rate'] ) ) && $ir->setError( $localization['ir_error'] );
                ( isset( $errors['amortization_period'] ) ) && $ap->setError( $localization['ap_error'] );
            }
        }
        
    	// Create a display area for results.
    	$details = new LiddMCDetails( $options, $this->processor );
		
		// Build the form
        $protocol = ( is_ssl() ) ? 'https://' : 'http://';
		$form = "<form action=\"$protocol$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]#" . esc_attr( $this->name ) . "\" id=\"" . esc_attr( $this->name ) . "\" class=\"" . esc_attr( $this->name ) . "\" method=\"post\">";

		$form .= $ta->getInput();
		$form .= $dp->getInput();
		$form .= $ir->getInput();
		$form .= $ap->getInput();
		$form .= $pp->getInput();
		$form .= $sub->getInput();
		
		$form .= '</form>';
        
        $form .= $details->getDetails();
		
		return $form;
	}
		
}
