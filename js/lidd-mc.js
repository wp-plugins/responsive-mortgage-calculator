jQuery(document).ready(function() {
	
	// Get the results divs.
	detailsDiv = jQuery('#lidd_mc_details');
	resultDiv = jQuery('#lidd_mc_results');
	summaryDiv = jQuery('#lidd_mc_summary');
	
	jQuery( ".lidd_mc_form" ).submit(function( event ) {
		
		// Prevent the form from being submitted.
		event.preventDefault();
		
		// Formatting function for outputting numbers with commas.
		function numberWithCommas(x) {
		    return x.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
		}
		
		// Formatting function for currency codes
		function validateCurrencyCode(code) {
			return code.replace(/[^A-Za-z]/, "");
		}
		
		// Initialize variables.
		var error = false; // Marker. Assume there is an error.
		var period; // Store the payment period.
		var showSummary = false; // Record whether the summary is displayed or not.
		
		// Get variables.
		var ta = jQuery('#lidd_mc_total_amount').val();
		var dp = jQuery('#lidd_mc_down_payment').val();
		var ir = jQuery('#lidd_mc_interest_rate').val();
		var am = jQuery('#lidd_mc_amortization_period').val();
		var pp = jQuery('#lidd_mc_payment_period option:selected' ).val();
		var cp = jQuery('#lidd_mc_compounding_period' ).val();
		var currency = jQuery('#lidd_mc_currency' ).val();
		var currency_code = jQuery('#lidd_mc_currency_code' ).val();
		
		// Get the error reporting spans.
		var ta_error = jQuery('#lidd_mc_total_amount-error');
		var dp_error = jQuery('#lidd_mc_down_payment-error');
		var ir_error = jQuery('#lidd_mc_interest_rate-error');
		var am_error = jQuery('#lidd_mc_amortization_period-error');
		
		// Make sure the results divs are in their default states.
		detailsDiv.hide();
		resultDiv.html( '' );
		summaryDiv.html( '' );
		
		// ********************** //
		// ***** VALIDATION ***** //
		
		// A function to trigger an error.
		function triggerError( element, message ) {
			error = true;
			element.text( message );
			element.addClass( 'lidd_mc_error' );
		}
		
		// A function to remove an error.
		function removeError( element ) {
			element.text( '' );
			element.removeClass( 'lidd_mc_error' );
		}
		
		// Make sure total amount * 100 is an integer, or round it to one.
		if ( jQuery.isNumeric( +ta ) && ta > 0 ) {
			ta = Math.abs( Math.round( (+ta)*100 ) / 100 );
			removeError( ta_error );
		} else {
			triggerError( ta_error, 'Please enter the total amount of the mortgage.');
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
		// Compounding period
		switch( cp ) {
			case '1':
				cp = 1;
				break;
			case '2':
				cp = 2;
				break;
			case '4':
				cp = 4;
				break;
			case '12':
			default:
				cp = 12;
				break;
		}
		// Currency
		switch( currency ) {
		case '€': // Euro
				currency = '€';
				break;
			case '£': // Pound
				curency = '£';
				break;
			case '¥': // Yen
				currency = '¥';
				break;
			case '¤': // Generic
				currency = '¤';
				break;
			case '$': // Dollar
			default:
				currency = '$';
				break;
		}
		// Currency Code
		if ( currency_code ) {
			currency_code = validateCurrencyCode( currency_code );
		} else {
			currency_code = '';
		}
		
		// ***** END VALIDATION ***** //
		// ************************** //
		
		// If there are no errors, continue with the calculation.
		if ( error === false ) {
			
			// Calculate the total amount of the loan.
			var loan = ta - dp;
		
			// Calculate the number of payments. This is amortization * payment period
			var np = am * pp;

			// Convert the interest rate to a decimal. (Nominal Rate)
			var rNom = (+ir)/100;
			
			// Calculate the rate per payment period
			var rPeriod = ( Math.pow( ( 1 + (rNom/+cp ) ), ( +cp/pp ) ) ) - 1;
			
			// Calculate the total interest rate for the duration of the loan.
			var rFactor = Math.pow( ( rPeriod + 1 ), np );
		
			// Calculate the payments.
			var payment = loan * ( ( rPeriod * rFactor ) / ( rFactor - 1 ) );
		
			// Set the result for output.
			var result = numberWithCommas(parseFloat( Math.round( payment * 100 ) / 100 ).toFixed(2));
			
			// Summarize the data.
			var summary = '<p>For a mortgage of <b class="lidd_mc_b">' + currency + numberWithCommas(parseInt(ta - dp).toFixed(2)) + '</b> amortized over <b class="lidd_mc_b">';
			// Determine the payment period in years.
			summary += (Math.floor(am)) + '</b> years';
			// Check for weeks or months.
			if ( ( am - Math.floor(am) > 0 ) ) {
				var remainder = (np > pp) ? np % pp : pp % np;
				if ( period === 'Monthly' ) {
					summary += ' and <b class="lidd_mc_b">' + remainder + '</b> months';
				} else if ( period === 'Weekly' ) {
					summary += ' and <b class="lidd_mc_b">' + remainder + '</b> weeks';
				} else {
					summary += ' and <b class="lidd_mc_b">' + ( remainder * 2 ) + '</b> weeks';
				}
			}
			summary += ', your <b class="lidd_mc_b">' + period + '</b> payment is:</p>';
			summary += '<p>Mortgage Payment: <b class="lidd_mc_b">' + currency + result + '</b></p>';
			summary += '<p>Total Mortgage with Interest: <b class="lidd_mc_b">' + currency + numberWithCommas(parseFloat( Math.round( (payment * np) * 100 ) / 100 ).toFixed(2) ) + '</b></p>';
			
			if ( dp > 0 ) {
				summary += '<p>Total with Down Payment: <b class="lidd_mc_b">' + currency + numberWithCommas(parseFloat( dp + Math.round( (payment * np) * 100 ) / 100 ).toFixed(2) )+ '</b></p>';
			}
			
			// Print to the messaging areas.
			resultDiv.html( '<p>Payments: <b class="lidd_mc_b">' + currency + result + ' ' + currency_code + '</b></p>' );
			summaryDiv.html( summary );

			// Show the details div.
			detailsDiv.show();
			
			// Show the summary div when the result div is clicked.
			if ( document.getElementById( 'lidd_mc_inspector' ) != null ) {

				jQuery('#lidd_mc_inspector').click(function() {
					if ( showSummary === false ) {
						summaryDiv.show();
						showSummary = true;
					} else {
						summaryDiv.hide();
						showSummary = false;
					}
				});
				
			}
			
		}
		
	});
	
});
