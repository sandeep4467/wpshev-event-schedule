<?php
/**
 * Activate
 *
 */

defined( 'ABSPATH' ) || exit;

/**
 * WP Activate class.
 */
class WPSHEV_Activate {

     public static function add_user_role(){
        add_role( 'fit_instructor', 'Instructor' );
     }

     public static function install_tables() {
        global $wpdb;
        $table_name = $wpdb->prefix . "wpshev_events";
        $charset_collate = $wpdb->get_charset_collate();
     
        if ( $wpdb->get_var( "SHOW TABLES LIKE '{$table_name}'" ) != $table_name ) {

            $sql = "CREATE TABLE $table_name (
                    ID mediumint(9) NOT NULL AUTO_INCREMENT,
                    `created_date` datetime DEFAULT '0000-00-00 00:00:00' NOT NULL,
                    `last_updated_date` datetime DEFAULT '0000-00-00 00:00:00' NOT NULL,
                    `title` text NOT NULL,
                    `start_date_time` datetime NOT NULL,
                    `end_date_time` datetime NOT NULL,
                    `description` longtext NOT NULL,
                    `customer_id` int(9) NOT NULL,
                    `instructor_id` int(9) NOT NULL,
                    `status` text NOT NULL,
                    PRIMARY KEY  (ID)
            )    $charset_collate;";
            require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
            dbDelta( $sql );
      }
     }

     public static function install() {
      self::install_tables();
      self::add_user_role();
     }
}