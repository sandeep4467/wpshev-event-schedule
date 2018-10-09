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
        include_once WPSHEV_ABSPATH . 'core/includes/wpshev_admin_scripts.php';
        include_once WPSHEV_ABSPATH . 'core/includes/wpshev_frontend_scripts.php';
        include_once WPSHEV_ABSPATH . 'core/includes/class-wpshev-shortcodes.php';
        include_once WPSHEV_ABSPATH . 'core/includes/ajax/wpshev_event.php';
    }
}