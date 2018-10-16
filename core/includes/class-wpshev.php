<?php
/**
 * Main Class of WP Any Post Ajax Infinite Scroll & Load More
 */
if (!defined('ABSPATH')) {
	exit();
}


final class WpScheduleEvent
{
    
    public function __construct()
    {  
        $this->wpshev_constants();
        $this->wpshev_includes();
        $this->init_hooks();
    }
    /*
    * Init Hooks
     */
    public function init_hooks(){
        register_activation_hook( WPSHEV_PLUGIN_FILE, array( 'WPSHEV_Activate', 'install' ) );
        add_action( 'init', array('WPSHEV_Shortcodes', 'init') );
        // AJAX hooks
        add_action('wp_ajax_ev_add_event', array('wpshevEvent', 'add_event') ); 
        add_action('wp_ajax_nopriv_ev_add_event', array('wpshevEvent', 'add_event') ); 
        add_action('wp_ajax_ev_get_events', array('wpshevEvent', 'get_events') ); 
        add_action('wp_ajax_nopriv_ev_get_events', array('wpshevEvent', 'get_events') ); 
        add_action('wp_ajax_ev_get_event', array('wpshevEvent', 'get_event') ); 
        add_action('wp_ajax_nopriv_ev_get_event', array('wpshevEvent', 'get_event') ); 
        add_action('wp_ajax_ev_delete_event', array('wpshevEvent', 'delete_event') ); 
        add_action('wp_ajax_nopriv_ev_delete_event', array('wpshevEvent', 'delete_event') ); 
        
        add_action('wp_ajax_ev_assign_instructor', array('wpshevAjax', 'assign_instructor') ); 
        add_action('wp_ajax_nopriv_ev_assign_instructor', array('wpshevAjax', 'assign_instructor') ); 

        add_action('wp_ajax_add_chat', array('wpshevChat', 'add_chat') ); 
        add_action('wp_ajax_nopriv_add_chat', array('wpshevChat', 'add_chat') ); 

        add_action('wp_ajax_load_chat', array('wpshevChat', 'load_chat') ); 
        add_action('wp_ajax_nopriv_load_chat', array('wpshevChat', 'load_chat') ); 
        
        add_action('wp_ajax_refresh_chat', array('wpshevChat', 'refresh_chat') ); 
        add_action('wp_ajax_nopriv_refresh_chat', array('wpshevChat', 'refresh_chat') );

        add_action('wp_ajax_user_status', array('wpshevChat', 'user_status') ); 
        add_action('wp_ajax_nopriv_user_status', array('wpshevChat', 'user_status') );

        add_action('wp_ajax_delete_user_status', array('wpshevChat', 'delete_user_status') ); 
        add_action('wp_ajax_nopriv_delete_user_status', array('wpshevChat', 'delete_user_status') );    

        add_action('wp_ajax_check_user_status', array('wpshevChat', 'check_user_status') ); 
        add_action('wp_ajax_nopriv_check_user_status', array('wpshevChat', 'check_user_status') );          
    }
    /*
    * Define Constants
     */
    public function wpshev_constants(){
      $this->define('WPSHEV_ABSPATH', dirname(WPSHEV_PLUGIN_FILE) . '/');
    }

    /**
     * Get the plugin url.
     *
     * @return string
     */
    public function plugin_url() {
        return untrailingslashit( plugins_url( '/', WPSHEV_PLUGIN_FILE ) );
    }
    /**
     * Get the plugin path.
     *
     * @return string
     */
    public function plugin_path() {
        return untrailingslashit( plugin_dir_path( WPSHEV_PLUGIN_FILE ) );
    }
    /*
    Define Constants if not already set
     */
    private function define($name, $value){
        if (!defined($name)) {
            define($name, $value);
        }
    }
    public function wpshev_includes(){
        include_once WPSHEV_ABSPATH . 'core/includes/class-wpshev-activate.php';
        include_once WPSHEV_ABSPATH . 'core/includes/ajax/wpshev_chat.php';
        include_once WPSHEV_ABSPATH . 'core/includes/wpshev_admin_scripts.php';
        include_once WPSHEV_ABSPATH . 'core/includes/wpshev_frontend_scripts.php';
        include_once WPSHEV_ABSPATH . 'core/includes/class-wpshev-shortcodes.php';
        include_once WPSHEV_ABSPATH . 'core/includes/class-wpshev-admin-dashboard.php';
        include_once WPSHEV_ABSPATH . 'core/includes/ajax/wpshev_event.php';
        include_once WPSHEV_ABSPATH . 'core/includes/ajax/wpshev_ajax.php';
        include_once WPSHEV_ABSPATH . 'core/includes/class-helpers.php';
    }
}