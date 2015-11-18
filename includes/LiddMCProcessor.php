<?php
defined( 'ABSPATH' ) || die;

if ( ! class_exists( 'LiddMCProcessor' ) ) :

class LiddMCProcessor
{
    private $has_submission;
    
    protected $total_amount;
    protected $down_payment;
    protected $interest_rate;
    protected $amortization_period;
    protected $payment_period;
    protected $compounding_period;
    
    
    // Values used in the calculation
    protected $loan_amount;
    protected $number_of_payments;
    protected $nominal_interest_rate;
    protected $interest_rate_per_period; // This is the effective rate with compounding factored in
    protected $r_factor;
        
    // Data for internally defined tags
    protected $payment_result;
    protected $interest_amount;
    protected $loan_amount_with_interest;
    protected $loan_amount_with_interest_and_base_payments;
    
    private $errors;
    
    /**
     * 
     * @var float
     */
    
    
    /**
     * Start it and run it
     */
    public function __construct( $compounding_period )
    {
        $this->compounding_period = $compounding_period;
        
        $this->has_submission   = false;
        $this->errors           = array();
        
        $this->process();
    }
    
    public function has_submission()
    {
        return $this->has_submission;
    }
    
    public function has_error()
    {
        return ( ! empty( $this->errors ) );
    }
    
    public function get_errors()
    {
        return $this->errors;
    }
    
    public function get( $key )
    {
        if ( isset( $this->$key ) ) {
            return $this->$key;
        }
        return null;
    }
    
    private function process()
    {
        if ( $_SERVER['REQUEST_METHOD'] != 'POST' || ! isset( $_POST['lidd_mc_submit'] ) ) {
            return;
        }
        
        $this->has_submission = true;
        
        $this->set_values();
        
        if ( ! empty( $this->errors ) ) {
            return;
        }
        
        $this->calculate();
    }
    
    private function set_values()
    {
        $this->set_value( 'total_amount', true );
        $this->set_value( 'down_payment', false );
        $this->set_value( 'interest_rate', true );
        $this->set_value( 'amortization_period', true );
        $this->set_payment_period();
    }
    
    private function set_value( $name, $required )
    {
        if ( ! isset( $_POST['lidd_mc_' . $name] ) ) {
            if ( $required ) {
                $this->errors[$name] = true;
            }
            else {
                $this->$name = null;
            }
            return;
        }
        
        $clean = $this->clean_number( $_POST['lidd_mc_' . $name] );
        
        if ( ! $clean ) {
            if ( $required ) {
                $this->errors[$name] = true;
            }
            else {
                $this->$name = null;
            }
            return;
        }
        
        $this->$name = $clean;
    }
    
    private function set_payment_period()
    {
        if ( ! isset( $_POST['lidd_mc_payment_period'] ) ) {
            $value = 12;
        }
        else {
            switch ( trim( $_POST['lidd_mc_payment_period'] ) ) {
                case 52:
                    $value = 52;
                    break;
                case 26:
                    $value = 26;
                    break;
                case 12:
                default:
                    $value = 12;
                    break;
            }
        }
        
        $this->payment_period = $value;
    }
    
    private function clean_number( $number )
    {
        return preg_replace( '/[^0-9.]/', '', $number );
    }
    
    protected function calculate()
    {
        if ( $this->error ) {
            return;
        }
        
        // Determine what type of calculation to apply
        $this->calculate_payment();
        
        $this->set_additional_data();
    }
    
    protected function calculate_payment()
    {
        $this->set_loan_amount();
        $this->set_number_of_payments();
        $this->set_nominal_interest_rate();
        $this->set_interest_rate_per_period();
        $this->set_r_factor();

        $this->set_payment_result();
    }
    
    protected function set_loan_amount()
    {
        $e = $this->total_amount;
        $p = $this->down_payment;
        
        $this->loan_amount = $e - $p;
    }
    
    protected function set_number_of_payments()
    {
        $pp = $this->payment_period;
        $lt = $this->amortization_period;
        
        $this->number_of_payments = ceil($lt * $pp);
    }
    
    protected function set_nominal_interest_rate()
    {
        $i = $this->interest_rate;
        
        $this->nominal_interest_rate = $i/100;
    }
    
    protected function set_interest_rate_per_period()
    {
        $ni = $this->nominal_interest_rate;
        $cp = $this->compounding_period;
        $pp = $this->payment_period;
        
        if ( $cp == 0 ) { // Simple interest
            $rate = $ni / $pp;
        }
        else { // Compound interest
            $rate = ( pow( 1 + ( $ni / $cp ), $cp / $pp ) - 1 );
        }
        
        $this->interest_rate_per_period = $rate;
    }
    
    protected function set_r_factor()
    {
        if ( ! $this->compounding_period ) {
            $this->r_factor = null;
        }
        else {
            $irp = $this->interest_rate_per_period;
            $np  = $this->number_of_payments;
            
            $this->r_factor = pow( $irp + 1, $np );
        }
    }
    
    protected function set_payment_result()
    {
        $la  = $this->loan_amount;
        $irp = $this->interest_rate_per_period;
        $rf  = $this->r_factor;
        
        if ( $rf && $rf != 1 ) {
            $this->payment_result = $la * ( $irp * $rf / ( $rf - 1 ) );
            $this->loan_amount_with_interest = $this->payment_result * $this->number_of_payments;
        }
        elseif ( $this->number_of_payments != 0 ) {
            $this->loan_amount_with_interest = $this->loan_amount * ( 1 + $this->nominal_interest_rate );
            $this->payment_result = $this->loan_amount_with_interest / $this->number_of_payments;
        }
    }
    
    protected function set_additional_data()
    {
        $this->loan_amount_with_interest_and_base_payment = $this->loan_amount_with_interest + $this->base_payment;
    }
}   
 
endif;
