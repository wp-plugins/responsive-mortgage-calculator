<?php
/**
 * This is a function to validate shortcode attributes and resolve them with the plugin options.
 *
 * @package Lidd's Mortgage Calculator
 * @since 2.1.0
 */


/**
 * Function to compare an indexed arrays with an associative array.
 *
 * @param	array		$indexed		Index array to look up keys
 * @param	array		$associative	Associative array to compare
 * @return  bool 						True if the indexed array has an element that matches a key in the associative array
 */
function lidd_mc_compare_arrays( $indexed, $associative ) {
	$match = false;
	foreach ( $indexed as $i ) {
		if ( array_key_exists( $i, $associative ) ) {
			$match = $i;
			break;
		}
	}
	return $match;
}

/**
 * Create a function to resolve attributes to the options.
 *
 * @param	array		$attr		Shortcode attributes
 * @param	array		$options	Plugin options
 * @return  array 					Resolved options.
 */
function lidd_mc_resolve_attributes( $attr, $options ) {
	
	// Check for label attributes
	$attr_keys = array(
		'total_amount_label' => array( 'ttl', 'ta', 'total', 'amount', 'total_amount', 'totalamount' ),
		'down_payment_label' => array( 'dp', 'down', 'down_payment', 'downpayment' ),
		'interest_rate_label' => array( 'ir', 'int', 'rate', 'interest', 'interest_rate', 'interestrate' ),
		'amortization_period_label' => array( 'ap', 'amortization', 'amortization_period', 'amortizationperiod' ),
		'payment_period_label' => array( 'pp', 'payment', 'payment_period', 'payment' ),
		'submit_label' => array( 'sb', 'submit', 'button', 'calculate' )
	);
	
	foreach ( $attr_keys as $k => $array ) {
		$attr_match = lidd_mc_compare_arrays( $array, $attr );
		if ( $attr_match ) {
			$options[ $k ] = $attr[ $attr_match ];
		}
	}
	
	return $options;
}