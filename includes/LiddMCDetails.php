<?php
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
	 * Constructor.
	 *
	 * Set the summary visibility setting and theme.
	 *
	 * @param  int     $summary_settings Determines whether the summary section will be included and how.
	 * @param  string  $theme            Indicates the CSS theme.
	 */
	public function __construct( $summary_setting, $theme )
	{
		$this->summary_setting = in_array( $summary_setting, array( 0, 1, 2 ) ) ? $summary_setting : 1;
		$this->theme = $theme;
	}
	
	/**
	 * Return the mortgage details
	 *
	 * @return  string  The HTML to display the results information.
	 */
	public function getDetails() {
		
		$details = '
			<div id="lidd_mc_details" style="display: none;">
				<div id="lidd_mc_results"></div>
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
}
