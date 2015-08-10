jQuery(document).ready(function() {
	
	// Get the results divs.
	detailsDiv = jQuery('#lidd_mc_details');
	resultDiv = jQuery('#lidd_mc_results');
	summaryDiv = jQuery('#lidd_mc_summary');
	
	jQuery( ".lidd_mc_form" ).submit(function( event ) {
		
		// Prevent the form from being submitted.
		event.preventDefault();
		
		// Formatting function for outputting numbers with separator.
		function numberWithSeparator(x, separator, decimals, decimal_separator) {
            x = x.toFixed( decimals );
		    x = x.toString().replace(/\B(?=(\d{3})+(?!\d))/g, separator);
            if ( decimals > 0 && decimal_separator == ',' ) {
                x = x.substring( 0, x.lastIndexOf(".") ) + ',' + x.substring( x.lastIndexOf(".") + 1 );
            }
            return x;
		}
        // Indian formatting
        function indianSystem(x) {
            x = x.toFixed(0);
		    return x.slice(0, -3).toString().replace(/\B(?=(\d{2})+(?!\d))/g, ',') + ',' + x.slice(-3);
        }
        
        // Format numbers based on the given format
        function formatNumber(x) {
            switch (number_format) {
            case '1':
                return numberWithSeparator(x, ' ', 0, null);
                break;
            case '2':
                return numberWithSeparator(x, ' ', 2, '.');
                break;
            case '3':
                return numberWithSeparator(x, ' ', 3, '.');
                break;
            case '4':
                return numberWithSeparator(x, ',', 0, null);
                break;
            case '5':
                return indianSystem(x);
                break;
            case '6':
                return numberWithSeparator(x, ',', 2, '.');
                break;
            case '7':
                return numberWithSeparator(x, ',', 3, '.');
                break;
            case '8':
                return numberWithSeparator(x, '.', 0, null);
                break;
            case '9':
                return numberWithSeparator(x, '.', 2, ',');
                break;
            case '10':
                return numberWithSeparator(x, '.', 3, ',');
                break;
            case '11':
                return numberWithSeparator(x, '\'', 2, '.');
                break;
            default:
                return numberWithSeparator(x, ',', 2, '.');
                break;
            }
        }
		
		// Formatting function for currency codes
		function validateCurrencyCode(code) {
			return code.replace(/[^A-Za-z]/, "");
		}
		
		// Function to format internationalized currencies
		function formatCurrency( amount ) {
			var formatted = currency_format;
			return formatted.replace( "{amount}", amount );
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
		var cp = lidd_mc_script_vars.compounding_period;
        
        // Currency format
		var currency = lidd_mc_script_vars.currency;
		var currency_code = validateCurrencyCode( lidd_mc_script_vars.currency_code );
		var currency_format = lidd_mc_script_vars.currency_format;
		var number_format = lidd_mc_script_vars.number_format;
		currency_format = currency_format.replace( '{currency}', currency );
		currency_format = currency_format.replace( '{code}', currency_code );
        
		// Get the error reporting spans.
		var ta_error = jQuery('#lidd_mc_total_amount-error');
		var dp_error = jQuery('#lidd_mc_down_payment-error');
		var ir_error = jQuery('#lidd_mc_interest_rate-error');
		var am_error = jQuery('#lidd_mc_amortization_period-error');
		
        
        // Strip non-numeric characters from the total, down payment, and interest rate
        ta = ta.replace(/[^\d.]/g, '');
        dp = dp.replace(/[^\d.]/g, '');
        ir = ir.replace(/[^\d.]/g, '');
        
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
		if ( +dp == 0 || ( jQuery.isNumeric( +dp ) && +dp < +ta ) ) {
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
			var result = formatNumber( parseFloat( Math.round( payment * 100 ) / 100 ) );
			
			
			// Payment amount
			var display_result;
			switch ( pp ) {
				case 52:
					display_result = lidd_mc_script_vars.weekly_payment;
					break;
				case 26:
					display_result = lidd_mc_script_vars.biweekly_payment;
					break;
				case 12:
				default:
					display_result = lidd_mc_script_vars.monthly_payment;
					break;
			}
			display_result = display_result + ': ' + formatCurrency( result );
			
            // Print the result.
			resultDiv.html( '<p>' + display_result + '</p>' );
            
			// Summarize the data.
            if ( lidd_mc_script_vars.summary == 1 || lidd_mc_script_vars.summary == 2 ) {
    			var display_total = formatNumber( parseInt(ta - dp) );
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
    			summary = summary.replace( "{total_amount}", formatCurrency( display_total ) );
    			summary = summary.replace( "{amortization_years}", Math.floor(am) );
    			summary = summary.replace( "{payment_period}", period );
    			summary = '<p>' + summary + ':</p>';
			
            
    			// Mortgage payment
    			summary += '<p>' + lidd_mc_script_vars.mp_text + ': <b class="lidd_mc_b">' + formatCurrency( result ) + '</b></p>';
			
    			// Total mortgage with interest
                if ( lidd_mc_script_vars.summary_interest == 1 ) {
        			summary += '<p>' + lidd_mc_script_vars.tmwi_text + ': <b class="lidd_mc_b">' + formatCurrency(
        				formatNumber(parseFloat( Math.round( (payment * np) * 100 ) / 100 ) )
        			) + '</b></p>';
                }
			
    			// Total with down payment
                if ( lidd_mc_script_vars.summary_downpayment == 1 ) {
        			if ( dp > 0 ) {
        				summary += '<p>' + lidd_mc_script_vars.twdp_text + ': <b class="lidd_mc_b">' + formatCurrency(
        					formatNumber(parseFloat( +dp + Math.round( (payment * np) * 100 ) / 100 ) )
        				) + '</b></p>';
        			}
                }
                
    			// Add the summary to the page
    			summaryDiv.html( summary );

			
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

			// Show the details div for results and summary
			detailsDiv.show();
			
		}
		
	});
	
});
