<?php 
/*
* Class Front end scripts and styles
 */

if (!defined('ABSPATH')) {
	exit();
}

class wpshevFrontEndScripts
{   
	private static $scripts = array();
	private static $styles = array();

	public static function init(){
        add_action('wp_enqueue_scripts', array(__CLASS__, 'load_scripts'));
	}

	private static function get_asset_url($path){
       return apply_filters( 'wpshev_assets_url', plugins_url($path, WPSHEV_PLUGIN_FILE), $path);
    }

    private static function register_scripts(){
    	

       $register_scripts = array(
          'load-calender' => array(
            'src'=> self::get_asset_url('core/js/load-calender.js'),
            'deps'=> array('jquery'),
            'ver'=> '1.0',
            'in_footer' => true
          ),
          'wpshev-ajax-handler' => array(
            'src'=> self::get_asset_url('core/js/front-end-ajax-script.js'),
            'deps'=> array('jquery'),
            'ver'=> '1.0',
            'in_footer' => true
          ),
          'get-calendar-client' => array(
            'src'=> self::get_asset_url('core/js/get-calendar-client.js'),
            'deps'=> array('jquery'),
            'ver'=> '1.0',
            'in_footer' => true
          ),
          'moment-js' => array(
           'src' => 'https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.22.2/moment.min.js',
           'deps'=> array('jquery'),
           'ver'=> '2.22.2',
           'in_footer'=>true
          ),
          'fullcalendar' => array(
           'src' => 'https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/3.9.0/fullcalendar.min.js',
           'deps'=> array('jquery', 'moment-js'),
           'ver'=> '3.9.0',
           'in_footer'=>true
          ),
          'magnific' => array(
           'src' => 'https://cdnjs.cloudflare.com/ajax/libs/magnific-popup.js/1.1.0/jquery.magnific-popup.min.js',
           'deps'=> array('jquery'),
           'ver'=> '1.0',
           'in_footer'=>true
          ),
          'timepicker' => array(
           'src' => 'https://cdnjs.cloudflare.com/ajax/libs/jquery-ui-timepicker-addon/1.6.3/jquery-ui-timepicker-addon.js',
           'deps'=> array('jquery'),
           'ver'=> '1.6.3',
           'in_footer'=>true
          ),
          'jquery-confirm' => array(
           'src' => 'https://cdnjs.cloudflare.com/ajax/libs/jquery-confirm/3.3.0/jquery-confirm.min.js',
           'deps'=> array('jquery'),
           'ver'=> '3.3.0',
           'in_footer'=>true
          ),
          'scripts' => array(
           'src' => self::get_asset_url('core/js/scripts.js'),
           'deps'=> array('jquery'),
           'ver'=> '1.0',
           'in_footer'=>true
          )
       );
       foreach ($register_scripts as $name => $data) {
       	 self::register_script( $name, $data['src'], $data['deps'], $data['ver'], $data['in_footer'] );
       }
    }

    private static function register_script( $handle, $src, $deps, $ver, $in_footer){
    	self::$scripts[] = $handle;
    	wp_register_script($handle, $src, $deps, $ver, $in_footer);
    }

    private static function register_styles(){
    	$register_css = array(
         'wpshev-css' => array(
          'src' => self::get_asset_url('core/assets/css/wpshev-style.css'),
          'deps' => array(),
          'version'=>'1.0',
          'has_rtl' => false
         ),
         'fullcalendar' => array(
          'src' => 'https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/3.9.0/fullcalendar.min.css',
          'deps' => array(),
          'version'=>'3.9.0',
          'has_rtl' => false
         ),
         'fullcalendar-print' => array(
          'src' => 'https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/3.9.0/fullcalendar.print.css',
          'deps' => array(),
          'version'=>'3.9.0',
          'has_rtl' => false
         ),
         'magnific-css' => array(
          'src' => 'https://cdnjs.cloudflare.com/ajax/libs/magnific-popup.js/1.1.0/magnific-popup.min.css',
          'deps' => array(),
          'version'=>'3.9.0',
          'has_rtl' => false
         ),
         'timepicker-css' => array(
          'src' => 'https://cdnjs.cloudflare.com/ajax/libs/jquery-ui-timepicker-addon/1.6.3/jquery-ui-timepicker-addon.min.css',
          'deps' => array(),
          'version'=>'1.6.3',
          'has_rtl' => false
         ),
         'jquery-confirm' => array(
          'src' => 'https://cdnjs.cloudflare.com/ajax/libs/jquery-confirm/3.3.0/jquery-confirm.min.css',
          'deps' => array(),
          'version'=>'3.3.0',
          'has_rtl' => false
         ),
         'jquery-ui' => array(
          'src' => 'http://ajax.googleapis.com/ajax/libs/jqueryui/1.9.0/themes/base/jquery-ui.css',
          'deps' => array(),
          'version'=>'1.9.0',
          'has_rtl' => false
         ),

    	);
    	foreach ($register_css as $handle => $data) {
    		self::register_style($handle, $data['src'], $data['deps'], $data['version']);
    	}
    }


    private static function register_style($handle, $src, $deps = array(), $version = '1.0', $media = 'all', $has_rtl = false ){

      self::$styles[] = $handle;
      wp_register_style($handle,$src, $deps, $version);
    }

	private static function localize_script( $handle, $data){
        if (wp_script_is( $handle )) {
        	wp_localize_script( $handle, 'wpshev_ajax_object', $data );
        }
	}

	private static function enqueue_script($handle, $src = '', $deps = array( 'jquery' ), $version = '1.0', $in_footer = false){
        if (!in_array($handle, self::$scripts, TRUE)) {
        	self::register_script($handle, $src, $deps, $version, $in_footer);
        }
        wp_enqueue_script($handle);
	}

	private static function enqueue_style($handle, $src = '', $deps = array(), $version = '1.0', $media = 'all', $has_rtl = false){

		 if (!in_array($handle, self::$styles, TRUE)) {
		 	self::register_style($handle, $src, $deps, $ver);
		 }

		 wp_enqueue_style( $handle );
	}

  public static function on_demand_script($handle)
  { 
     self::enqueue_script($handle);
  }
  public static function on_demand_localize_script($handle, $data)
  { 
     self::localize_script('wpshev-ajax-handler', $data);
  }
	public static function load_scripts(){

    // Register scripts and styles
		self::register_scripts();
		self::register_styles();

    //Frontend scripts. 
    wp_enqueue_script( 'jquery-ui-core' );
    wp_enqueue_script( 'jquery-ui-datepicker' );
    
    self::enqueue_script('moment-js');
		self::enqueue_script('fullcalendar');

    $data = array(
         'admin_url'=> admin_url( 'admin-ajax.php' ),
         'site_url' => get_site_url(),
         'ajax_nonce' => wp_create_nonce('schedule-ajax-security-nonce')
    );
		self::localize_script('wpshev-ajax-handler', $data);

    self::enqueue_script('magnific');
    self::enqueue_script('timepicker');
    self::enqueue_script('jquery-confirm');
   
    //Enqueue CSS
    self::enqueue_style( 'jquery-ui' );  
    self::enqueue_style('fullcalendar');
    self::enqueue_style('magnific-css');
    self::enqueue_style( 'timepicker-css' ); 
    self::enqueue_style( 'jquery-confirm' ); 
		self::enqueue_style('wpshev-css');
	}

}

wpshevFrontEndScripts::init();
