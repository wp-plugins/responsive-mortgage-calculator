jQuery(document).ready(function() {
	
	// Get the results divs.
	detailsDiv = jQuery('#lidd_rmc_details');
	resultDiv = jQuery('#lidd_rmc_results');
	summaryDiv = jQuery('#lidd_rmc_summary');
	
	jQuery( ".lidd_rmc_form" ).submit(function( event ) {
		
		// Prevent the form from being submitted.
		event.preventDefault();
		
		// Formatting function for outputting numbers with commas.
		function numberWithCommas(x) {
		    return x.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
		}
		
		// Initialize variables.
		var error = false; // Marker. Assume there is an error.
		var oir; // Store the original interest rate for later.
		var period; // Store the payment period.
		var showSummary = false; // Record whether the summary is displayed or not.
		
		// Get variables.
		var ta = jQuery('#lidd_rmc_total_amount').val();
		var dp = jQuery('#lidd_rmc_down_payment').val();
		var ir = jQuery('#lidd_rmc_interest_rate').val();
		var am = jQuery('#lidd_rmc_amortization').val();
		var pp = jQuery('#lidd_rmc_payment_period option:selected' ).val();
		
		// Get the error reporting spans.
		var ta_error = jQuery('#lidd_rmc_total_amount-error');
		var dp_error = jQuery('#lidd_rmc_down_payment-error');
		var ir_error = jQuery('#lidd_rmc_interest_rate-error');
		var am_error = jQuery('#lidd_rmc_amortization-error');
		
		// Make sure the results divs are in their default states.
		detailsDiv.hide();
		resultDiv.html( '' );
		summaryDiv.html( '' );
		summaryDiv.hide();
		
		// ********************** //
		// ***** VALIDATION ***** //
		
		// A function to trigger an error.
		function triggerError( element, message ) {
			error = true;
			element.text( message );
			element.addClass( 'lidd_rmc_error' );
		}
		
		// A function to remove an error.
		function removeError( element ) {
			element.text( '' );
			element.removeClass( 'lidd_rmc_error' );
		}
		
		// Make sure total amount * 100 is an integer, or round it to one.
		if ( jQuery.isNumeric( +ta ) && ta > 0 ) {
			ta = Math.abs( Math.round( (+ta)*100 ) / 100 );
			removeError( ta_error );
		} else {
			triggerError( ta_error, 'Please enter the total cost.');
		}
		// Down payment. If it is set, it must be less than the total amount.
		if ( +dp == 0 || ( jQuery.isNumeric( +dp ) && +dp < ta ) ) {
			dp = Math.abs( Math.round( (+dp)*100 ) / 100 );
			removeError( dp_error );
		} else {
			triggerError( dp_error, 'Please enter a down payment amount or leave blank.' );
		}
		// Interest rate. Positve value less than 100%. Leaves room for loan sharks.
		if ( jQuery.isNumeric( +ir ) && (+ir) < 100 && (+ir) > 0 ) {
			ir = +ir;
			removeError( ir_error );
		} else {
			triggerError( ir_error, 'Please enter an interest rate.' );
		}
		// Validate the payment period, just in case.
		switch( pp ) {
			case '52':
				pp = 52;
				period = 'Weekly';
				break;
			case '26':
				pp = 26;
				period = 'Bi-Weekly';
				break;
			default:
				pp = 12;
				period = 'Monthly';
				break;
		}
		// Amortization period. 50 years is absurdly long, but meh...
		if ( jQuery.isNumeric( +am ) && Math.abs(+am) < 50 && (+am) !== 0 ) {
			// The amortization period needs to fit nicely with the payment periods if there are decimals.
			am = Math.abs( Math.ceil( (+am)*pp ) / pp );
			removeError( am_error );
		} else {
			triggerError( am_error, 'Please enter an amortization period.' );
		}
		
		// ***** END VALIDATION ***** //
		// ************************** //
		
		// If there are no errors, continue with the calculation.
		if ( error === false ) {
			
			// Calculate the total amount of the loan.
			var loan = ta - dp;
		
			// Calculate the number of payments.
			var nPayments = am * pp;
		
			// Canadian mortgage interest rates are compounded semi-annually.
			// Convert the interest rate to a decimal.
			var ir = (+ir)/100;
			// Semi-annual interest rate:
			ir = ( ir/2 ) * ( 1 + ( 1 + ( ir/2 ) ) );
			// The effective interest rate depends on the payment period (monthly, bi-weekly, or weekly).
			// This is reverse compounded.
			ir = Math.pow( ( ir + 1 ), ( 1/(+pp) ) ) - 1;
		
			// Calculate the total interest rate for the duration of the loan.
			var tir = Math.pow( ( ir + 1 ), nPayments );
		
			// Calculate the payments.
			var payment = loan * ( ( ir * tir ) / ( tir - 1 ) );
		
			// Set the result for output.
			var result = numberWithCommas(parseFloat( Math.round( payment * 100 ) / 100 ).toFixed(2));
			
			// Summarize the data.
			var summary = '<p>For a mortgage of <b class="lidd-b">$' + numberWithCommas(parseInt(ta - dp).toFixed(2)) + '</b> amortized over <b class="lidd-b">';
			// Determine the payment period in years.
			summary += (Math.floor(am)) + '</b> years';
			// Check for weeks or months.
			if ( ( am - Math.floor(am) > 0 ) ) {
				var remainder = (nPayments > pp) ? nPayments % pp : pp % nPayments;
				if ( period === 'Monthly' ) {
					summary += ' and <b class="lidd-b">' + remainder + '</b> months';
				} else if ( period === 'Weekly' ) {
					summary += ' and <b class="lidd-b">' + remainder + '</b> weeks';
				} else {
					summary += ' and <b class="lidd-b">' + ( remainder * 2 ) + '</b> weeks';
				}
			}
			summary += ', your <b class="lidd-b">' + period + '</b> payment is:</p>';
			summary += '<p>Mortgage Payment: <b class="lidd-b">$' + result + '</b></p>';
			summary += '<p>Total Mortgage with Interest: <b class="lidd-b">$' + numberWithCommas(parseFloat( Math.round( (payment * nPayments) * 100 ) / 100 ).toFixed(2) ) + '</b></p>';
			
			summary += '<p>Total with Down Payment: <b class="lidd-b">$' + numberWithCommas(parseFloat( dp + Math.round( (payment * nPayments) * 100 ) / 100 ).toFixed(2) )+ '</b></p>';
			
			// Print to the messaging areas.
			resultDiv.html( '<p>Payments: <b class="lidd-b">$' + result + '</b></p>' );
			summaryDiv.html( summary );

			// Show the details div.
			detailsDiv.show();
			
			// Show the summary div when the result div is clicked.
			jQuery('#lidd_rmc_inspector').click(function() {
				if ( showSummary === false ) {
					summaryDiv.show();
					showSummary = true;
				} else {
					summaryDiv.hide();
					showSummary = false;
				}
			});
			
		}
		
	});
	
});
