<?php
function rmcp_get_localization() {

	$options = get_option( LIDD_MC_OPTIONS );
    
	// HTML wrapper on return values 
	$bs = '<b class="lidd_mc_b">';
	$be = '</b>';
    
    $localization = array(
		'ta_error' => __( 'Please enter the total amount.', 'responsive-mortgage-calculator' ),
		'dp_error' => __( 'Please enter a down payment amount or leave blank.', 'responsive-mortgage-calculator' ),
		'ir_error' => __( 'Please enter an interest rate.', 'responsive-mortgage-calculator' ),
		'ap_error' => __( 'Please enter an amortization period.', 'responsive-mortgage-calculator' ),
		'weekly' => __( 'Weekly', 'responsive-mortgage-calculator' ),
		'biweekly' => __( 'Bi-Weekly', 'responsive-mortgage-calculator' ),
		'monthly' => __( 'Monthly', 'responsive-mortgage-calculator' ),
		'weekly_payment' => __( 'Weekly Payment', 'responsive-mortgage-calculator' ),
		'biweekly_payment' => __( 'Bi-Weekly Payment', 'responsive-mortgage-calculator' ),
		'monthly_payment' => __( 'Monthly Payment', 'responsive-mortgage-calculator' ),
        'currency' => $options['currency'],
        'currency_code' => $options['currency_code'],
		'currency_format' => $bs . $options['currency_format'] . $be,
        'number_format' => $options['number_format'],
        'compounding_period' => $options['compounding_period'],
        'amortization_period_units' => $options['amortization_period_units'],
        'summary' => $options['summary'],
        'summary_interest' => $options['summary_interest'],
        'summary_downpayment' => $options['summary_downpayment'],
		'sy_text' => sprintf( // Summary with number of years
			__( 'For a mortgage of %1$s amortized over %2$s years, your %3$s payment is', 'responsive-mortgage-calculator' ),
			$bs . '{total_amount}' . $be,
			$bs . '{amortization_years}' . $be,
			$bs . '{payment_period}' . $be
		),
		'sym1_text' => sprintf( // Summary with years and months
			__( 'For a mortgage of %1$s amortized over %2$s years and %3$s month, your %4$s payment is', 'responsive-mortgage-calculator' ),
			$bs . '{total_amount}' . $be,
			$bs . '{amortization_years}' . $be,
			$bs . '1' . $be,
			$bs . '{payment_period}' . $be
		),
		'sym_text' => sprintf( // Summary with years and months
			__( 'For a mortgage of %1$s amortized over %2$s years and %3$s months, your %4$s payment is', 'responsive-mortgage-calculator' ),
			$bs . '{total_amount}' . $be,
			$bs . '{amortization_years}' . $be,
			$bs . '{amortization_months}' . $be,
			$bs . '{payment_period}' . $be
		),
		'syw1_text' => sprintf( // Summary with years and weeks
			__( 'For a mortgage of %1$s amortized over %2$s years and %3$s week, your %4$s payment is', 'responsive-mortgage-calculator' ),
			$bs . '{total_amount}' . $be,
			$bs . '{amortization_years}' . $be,
			$bs . '1' . $be,
			$bs . '{payment_period}' . $be
		),
		'syw_text' => sprintf( // Summary with years and weeks
			__( 'For a mortgage of %1$s amortized over %2$s years and %3$s weeks, your %4$s payment is', 'responsive-mortgage-calculator' ),
			$bs . '{total_amount}' . $be,
			$bs . '{amortization_years}' . $be,
			$bs . '{amortization_weeks}' . $be,
			$bs . '{payment_period}' . $be
		),
		'mp_text' => __( 'Mortgage Payment', 'responsive-mortgage-calculator' ),
		'tmwi_text' => __( 'Total Mortgage with Interest', 'responsive-mortgage-calculator' ),
		'twdp_text' => __( 'Total with Down Payment', 'responsive-mortgage-calculator' ),
	);
    
    return $localization;
}
