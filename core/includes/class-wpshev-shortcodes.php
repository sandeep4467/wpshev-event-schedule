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
			'wpshev_calender_client'     => __CLASS__ . '::schedule_calender_client',
			'admin_dashboard'     => __CLASS__ . '::admin_dashboard',
			'instructor_dashboard'     => __CLASS__ . '::instructor_dashboard',
			'single_client'     => __CLASS__ . '::single_client'
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

	/**
	 * Admin Dashboard.
	 *
	 * @param array $atts Attributes.
	 * @return string
	 */
	public static function admin_dashboard( $atts ) {
		ob_start();

		if( ! is_user_logged_in() ) {
		    include_once WPSHEV_ABSPATH . 'templates/access-denied.php';
    	}else{
			$user = wp_get_current_user();
			$role = ( array ) $user->roles;
			if ($role[0] != "administrator") {
			    	include_once WPSHEV_ABSPATH . 'templates/access-denied.php';
			}else{
				wpshevFrontEndScripts::on_demand_script('toastr');
				wpshevFrontEndScripts::on_demand_script('admin-dashboard');
				wpshevFrontEndScripts::on_demand_css('toastr');
				
				$data = array(
			         'admin_url'=> admin_url( 'admin-ajax.php' ),
			         'site_url' => get_site_url(),
			         'ajax_nonce' => wp_create_nonce('schedule-ajax-security-nonce')
			    );
			    wpshevFrontEndScripts::on_demand_localize_script('admin-dashboard', $data);

				include_once WPSHEV_ABSPATH . 'templates/admin-dashboard.php';
			}
    	}

		$output = ob_get_contents();  // stores buffer contents to the variable
		ob_end_clean();  // clears buffer and closes buffering
		return self::shortcode_wrapper( $output, $atts ); 
	}
	/**
	 * Instructor Dashboard.
	 *
	 * @param array $atts Attributes.
	 * @return string
	 */
	public static function instructor_dashboard( $atts ) {
		ob_start();

		if( ! is_user_logged_in() ) {
		    include_once WPSHEV_ABSPATH . 'templates/access-denied.php';
    	}else{
			$user = wp_get_current_user();
			$role = ( array ) $user->roles;
			if ($role[0] != "fit_instructor") {
			    	include_once WPSHEV_ABSPATH . 'templates/access-denied.php';
			}else{
				if (isset($_GET['single_page']) && isset($_GET['user_id'])) {

					wpshevFrontEndScripts::on_demand_script('load-calender');
					wpshevFrontEndScripts::on_demand_script('wpshev-ajax-handler');
					$data = array(
				         'admin_url'=> admin_url( 'admin-ajax.php' ),
				         'site_url' => get_site_url(),
				         'ajax_nonce' => wp_create_nonce('schedule-ajax-security-nonce'),
				         'calender_editable'=> 'true'
				    );
					wpshevFrontEndScripts::on_demand_localize_script('wpshev-ajax-handler', $data);
					wpshevFrontEndScripts::on_demand_script('scripts');

					include_once WPSHEV_ABSPATH . 'templates/single-instructor.php';
				}else{
					include_once WPSHEV_ABSPATH . 'templates/instructor-dashboard.php';
				}
			}
    	}

		$output = ob_get_contents();  // stores buffer contents to the variable
		ob_end_clean();  // clears buffer and closes buffering
		return self::shortcode_wrapper( $output, $atts ); 
	}

	/**
	 * Single Client.
	 *
	 * @param array $atts Attributes.
	 * @return string
	 */
	public static function single_client( $atts ) {
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
					wpshevFrontEndScripts::on_demand_script('wpshev-ajax-handler');
					$data = array(
				         'admin_url'=> admin_url( 'admin-ajax.php' ),
				         'site_url' => get_site_url(),
				         'ajax_nonce' => wp_create_nonce('schedule-ajax-security-nonce'),
				         'calender_editable'=> 'false'
				    );
					wpshevFrontEndScripts::on_demand_localize_script('wpshev-ajax-handler', $data);
					wpshevFrontEndScripts::on_demand_script('scripts');
					include_once WPSHEV_ABSPATH . 'templates/single-client.php';
			}
    	}

		$output = ob_get_contents();  // stores buffer contents to the variable
		ob_end_clean();  // clears buffer and closes buffering
		return self::shortcode_wrapper( $output, $atts ); 
	}	
}
