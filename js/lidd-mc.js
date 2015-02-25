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
		
		// Function to format internationalized currencies
		function formatCurrency( format, symbol, amount, code ) {
			var formatted = format;
			formatted = formatted.replace( "{symbol}", symbol );
			formatted = formatted.replace( "{amount}", amount );
			formatted = formatted.replace( "{code}", code );
			return formatted;
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
			triggerError( ta_error, lidd_mc_script_vars.ta_error );
		}
		// Down payment. If it is set, it must be less than the total amount.
		if ( +dp == 0 || ( jQuery.isNumeric( +dp ) && +dp < ta ) ) {
			dp = Math.abs( Math.round( (+dp)*100 ) / 100 );
			removeError( dp_error );
		} else {
			triggerError( dp_error, lidd_mc_script_vars.dp_error );
		}
		// Interest rate. Positve value less than 100%. Leaves room for loan sharks.
		if ( jQuery.isNumeric( +ir ) && (+ir) < 100 && (+ir) > 0 ) {
			ir = +ir;
			removeError( ir_error );
		} else {
			triggerError( ir_error, lidd_mc_script_vars.ir_error );
		}
		// Validate the payment period, just in case.
		switch( pp ) {
			case '52':
				pp = 52;
				period = lidd_mc_script_vars.weekly;
				break;
			case '26':
				pp = 26;
				period = lidd_mc_script_vars.biweekly;
				break;
			default:
				pp = 12;
				period = lidd_mc_script_vars.monthly;
				break;
		}
		// Amortization period. 50 years is absurdly long, but meh...
		if ( jQuery.isNumeric( +am ) && Math.abs(+am) < 51 && (+am) !== 0 ) {
			// The amortization period needs to fit nicely with the payment periods if there are decimals.
			am = Math.abs( Math.ceil( (+am)*pp ) / pp );
			removeError( am_error );
		} else {
			triggerError( am_error, lidd_mc_script_vars.ap_error );
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
			var display_total = numberWithCommas(parseInt(ta - dp).toFixed(2));
			if ( ( am - Math.floor(am) > 0 ) ) {
				var remainder = (np > pp) ? np % pp : pp % np;
				if ( pp == 12 ) {
					if ( remainder > 1 ) {
						var summary = lidd_mc_script_vars.sym_text;
						summary = summary.replace( "{amortization_months}", remainder );
					} else {
						var summary = lidd_mc_script_vars.sym1_text;
					}
				} else if ( pp == 52 ) {
					if ( remainder > 1 ) {
						var summary = lidd_mc_script_vars.syw_text;
						summary = summary.replace( "{amortization_weeks}", remainder );
					} else {
						var summary = lidd_mc_script_vars.syw1_text;
					}
				} else {
					var summary = lidd_mc_script_vars.syw_text;
					summary = summary.replace( "{amortization_weeks}", remainder * 2 );
				}
			} else {
				var summary = lidd_mc_script_vars.sy_text;
			}
			summary = summary.replace( "{currency}", currency );
			summary = summary.replace( "{total_amount}", display_total );
			summary = summary.replace( "{amortization_years}", Math.floor(am) );
			summary = summary.replace( "{payment_period}", period );
			summary = '<p>' + summary + ':</p>';
			
			// Mortgage payment
			summary += '<p>' + lidd_mc_script_vars.mp_text + ': <b class="lidd_mc_b">' + formatCurrency( lidd_mc_script_vars.currency_format, currency, result, currency_code ) + '</b></p>';
			
			// Total mortgage with interest
			summary += '<p>' + lidd_mc_script_vars.tmwi_text + ': <b class="lidd_mc_b">' + formatCurrency(
				lidd_mc_script_vars.currency_format,
				currency,
				numberWithCommas(parseFloat( Math.round( (payment * np) * 100 ) / 100 ).toFixed(2) ),
				currency_code
			) + '</b></p>';
			
			// Total with down payment
			if ( dp > 0 ) {
				summary += '<p>' + lidd_mc_script_vars.twdp_text + ': <b class="lidd_mc_b">' + formatCurrency(
					lidd_mc_script_vars.currency_format,
					currency,
					numberWithCommas(parseFloat( dp + Math.round( (payment * np) * 100 ) / 100 ).toFixed(2) ),
					currency_code
				) + '</b></p>';
			}
			
			// Payments amount
			var display_result = lidd_mc_script_vars.p_text.replace( "{payment_period}", period );
			display_result = display_result + ': ' + formatCurrency(
				lidd_mc_script_vars.currency_format,
				currency,
				result,
				currency_code
			);
			//result = '<p>' + lidd_mc_script_vars.p_text.replace( "{payment_period}", period ) + ': <b class="lidd_mc_b">' + currency + result + ' ' + currency_code + '</b></p>';
			
			// Print to the messaging areas.
			resultDiv.html( '<p>' + display_result + '</p>' );
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
