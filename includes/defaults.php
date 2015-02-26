<?php
/**
 * This file maintains the default settings for the plugin.
 *
 * @package Lidd's Mortgage Calculator
 * @since 2.0.0
 */

return array(
	'css_layout' => 1,
	'theme' => 'light',
	'select_style' => 1,
	'select_pointer' => null,
	'total_amount_label' => __( 'Total Amount', 'responsive-mortgage-calculator' ),
	'down_payment_label' => __( 'Down Payment', 'responsive-mortgage-calculator' ),
	'interest_rate_label' => __( 'Interest Rate', 'responsive-mortgage-calculator' ),
	'amortization_period_label' => __( 'Amortization Period', 'responsive-mortgage-calculator' ),
	'payment_period_label' => __( 'Payment Period', 'responsive-mortgage-calculator' ),
	'submit_label' => __( 'Calculate', 'responsive-mortgage-calculator' ),
	'total_amount_class' => null,
	'down_payment_class' => null,
	'interest_rate_class' => null,
	'amortization_period_class' => null,
	'payment_period_class' => null,
	'submit_class' => null,
	'down_payment_visible' => 1,
	'compounding_period' => 2,
	'currency' => '$',
	'currency_code' => null,
	'summary' => 1
);
