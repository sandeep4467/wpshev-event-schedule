<?php
/**
 * Shortcodes
 *
 */

defined( 'ABSPATH' ) || exit;

/**
 * WP Schedule Shortcodes class.
 */
class WPSHEV_Shortcodes {

	/**
	 * Init shortcodes.
	 */
	public static function init() {
		$shortcodes = array(
			'wpshev_scheduling_calender'     => __CLASS__ . '::scheduling_calender',
			'wpshev_calender_client'     => __CLASS__ . '::schedule_calender_client',
		);

		foreach ( $shortcodes as $shortcode => $function ) {
			add_shortcode($shortcode, $function);
		}
	}

	/**
	 * Shortcode Wrapper.
	 *
	 * @param string[] $function Callback function.
	 * @param array    $atts     Attributes. Default to empty array.
	 * @param array    $wrapper  Customer wrapper data.
	 *
	 * @return string
	 */
	public static function shortcode_wrapper(
		$content,
		$atts = array(),
		$wrapper = array(
			'class'  => 'wpshev_scheduling_calender',
			'before' => null,
			'after'  => null,
		)
	) {
		ob_start();
		echo empty( $wrapper['before'] ) ? '<div class="' . esc_attr( $wrapper['class'] ) . '">' : $wrapper['before'];
		echo $content;
		echo empty( $wrapper['after'] ) ? '</div>' : $wrapper['after'];
		return ob_get_clean();
	}
	/**
	 * Scheduling Calender page shortcode.
	 *
	 * @param array $atts Attributes.
	 * @return string
	 */
	public static function scheduling_calender( $atts ) {
		ob_start();

		if( ! is_user_logged_in() ) {
		    include_once WPSHEV_ABSPATH . 'templates/access-denied.php';
    	}else{
			$user = wp_get_current_user();
			$role = ( array ) $user->roles;
			if ($role[0] != "fit_instructor") {
			    	include_once WPSHEV_ABSPATH . 'templates/access-denied.php';
			}else{
				    wpshevFrontEndScripts::on_demand_script('load-calender');
					wpshevFrontEndScripts::on_demand_script('wpshev-ajax-handler');
				    $data = array(
				         'calender_editable'=> 'true'
				    );
					wpshevFrontEndScripts::on_demand_localize_script('wpshev-ajax-handler', $data);


					wpshevFrontEndScripts::on_demand_script('scripts');
			        include_once WPSHEV_ABSPATH . 'templates/scheduling-calender.php';	
			} 
    	}

		$output = ob_get_contents();  // stores buffer contents to the variable
		ob_end_clean();  // clears buffer and closes buffering
		return self::shortcode_wrapper( $output, $atts ); 
	}

	/**
	 * Schedule Calender page shortcode.
	 *
	 * @param array $atts Attributes.
	 * @return string
	 */
	public static function schedule_calender_client( $atts ) {
		ob_start();

		if( ! is_user_logged_in() ) {
		    include_once WPSHEV_ABSPATH . 'templates/access-denied.php';
    	}else{
			$user = wp_get_current_user();
			$role = ( array ) $user->roles;
			if ($role[0] != "subscriber") {
			    	include_once WPSHEV_ABSPATH . 'templates/access-denied.php';
			}else{
				    
                    wpshevFrontEndScripts::on_demand_script('load-calender');
					wpshevFrontEndScripts::on_demand_script('get-calendar-client');
					$data = array(
				         'calender_editable'=> 'false'
				    );
					wpshevFrontEndScripts::on_demand_localize_script('wpshev-ajax-handler', $data);
					wpshevFrontEndScripts::on_demand_script('scripts');

			        include_once WPSHEV_ABSPATH . 'templates/schedule_calender_client.php';	
			} 
    	}

		$output = ob_get_contents();  // stores buffer contents to the variable
		ob_end_clean();  // clears buffer and closes buffering
		return self::shortcode_wrapper( $output, $atts ); 
	}
}
