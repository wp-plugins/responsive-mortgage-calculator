<?php

defined('ABSPATH') or die("...");

/**
 * A class to define the mortgage calculator details section.
 *
 * @package Lidd's Mortgage Calculator
 * @since 2.0.0
 */
class LiddMCDetails
{
	/**
	 * Summary visibility setting.
	 *
	 * 0 = don't include
	 * 1 = toggle
	 * 2 = show always
	 *
	 * @var int
	 */
	private $summary_setting;
	
	/**
	 * Store the CSS theme name
	 *
	 * @var string
	 */
	private $theme;
    
    /**
     * Store a reference to the processor
     * @var object
     */
	private $processor;
    
    private $options;
    
	/**
	 * Constructor.
	 *
	 * Set the summary visibility setting and theme.
	 *
	 * @param  int     $summary_settings Determines whether the summary section will be included and how.
	 * @param  string  $theme            Indicates the CSS theme.
     * @param  object  $processor        The submission and calculation processor
	 */
	public function __construct( $options, $processor )
	{
        $this->options   = $options;
        $this->processor = $processor;
        
		$this->summary_setting  = in_array( $options['summary'], array( 0, 1, 2 ) ) ? $options['summary'] : 1;
		$this->theme            = $options['theme'];
	}
	
	/**
	 * Return the mortgage details
	 *
	 * @return  string  The HTML to display the results information.
	 */
	public function getDetails() {
		
		$details = '
			<div id="lidd_mc_details" class="lidd_mc_details"';
        if ( ! $this->processor->has_submission() || $this->processor->has_error() ) {
            $details .= ' style="display: none;">';
        }
		$details .= '<div id="lidd_mc_results" class="lidd_mc_results">' . $this->getResult() . '</div>
				';
				
		// Include the inspector if the summary is set to toggle
		if ( $this->summary_setting == 1 ) {
			$details .= '<img id="lidd_mc_inspector" src="' . LIDD_MC_URL . 'img/icon_inspector.png" alt="Details">';
		}
		
		// Include the summary if the summary is set to toggle or visible
		if ( $this->summary_setting > 0 ) {
			$details .= '<div id="lidd_mc_summary" class="lidd_mc_summary';
			// Check for a theme
			$details .= ( $this->theme == 'dark' || $this->theme == 'light' ) ? ' lidd_mc_summary_' . esc_attr( $this->theme ) : '';
			$details .= '" style="display: ';
			// Check whether the summary is toggle-able or permanent
			$details .= ( $this->summary_setting == 1 ) ? 'none' : 'block';
			$details .= ';"></div>';
		}
		
		$details .= '
			</div>
			';
			
		return $details;
	}
    
    private function getResult()
    {
        if ( ! $this->processor->has_submission() || $this->processor->has_error() ) {
            return null;
        }
        
        $localization = rmcp_get_localization();
        
        // Determine the correct phrase to use
        $pp = $this->processor->get( 'payment_period' );
        switch ( $pp ) {
            case 52:
                $phrase = $localization['weekly_payment'];
                break;
            case 26:
                $phrase = $localization['biweekly_payment'];
                break;
            case 12:
            default:
                $phrase = $localization['monthly_payment'];
                break;
        }
        
        $amount = $this->formatAmount( $this->processor->get( 'payment_result' ) );
        
        return $phrase . ': ' . $amount;
    }
    
    private function formatAmount( $amount )
    {
        $amount = $this->formatNumber( $amount, $this->options['number_format'] );
        
        $format = $this->options['currency_format'];
        
        if ( strpos( $format, '{amount}' ) ) {
            $format = str_replace( '{amount}', $amount, $format );
            $format = str_replace( '{code}', $this->options['currency_code'], $format );
            $format = str_replace( '{currency}', $this->options['currency'], $format );
        
            return $format;
        }
        
        return $amount;
    }

    private function formatNumber( $amount, $format ) {
        switch ($format) {
        case '1':
            return number_format( $amount, 0, null, ' ');
            break;
        case '2':
            return number_format( $amount, 2, '.', ' ');
            break;
        case '3':
            return number_format( $amount, 3, '.', ' ');
            break;
        case '4':
            return number_format( $amount, 0, null, ',');
            break;
        case '5':
            return $this->formatIndianSystem( $amount );
            break;
        case '6':
            return number_format( $amount, 2, '.', ',');
            break;
        case '7':
            return number_format( $amount, 3, '.', ',');
            break;
        case '8':
            return number_format( $amount, 0, null, '.');
            break;
        case '9':
            return number_format( $amount, 2, ',', '.');
            break;
        case '10':
            return number_format( $amount, 3, ',', '.');
            break;
        case '11':
            return number_format( $amount, 2, '.', '\'');
            break;
        default:
            return number_format( $amount, 2, '.', ',');
            break;
        }
    }
    
    private function formatIndianSystem( $amount )
    {
        $amount = ceil( $amount );
        
        if ( strlen($amount) < 4 ) {
            return $amount;
        }
        
        $three = substr( $amount, -3 );
        $start = substr( $amount, 0, -3 );
        
        $digit = null;
        if ( ( strlen( $start ) % 2 ) != 0 ) {
            $digit = substr( $start, 0, 1 );
            $start = substr( $start, 1 );
        }
        $parts = str_split( $start, 2 );
        
        $amount = $digit;
        
        if ( $amount ) {
            $amount .= ',';
        }
        
        $amount .= implode( ',', $parts ) . ',' . $three;
        return $amount;
    }
}
