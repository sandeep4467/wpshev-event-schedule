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


     public static function install_tables() {
        global $wpdb;
        $charset_collate = $wpdb->get_charset_collate();

        // Install table for all events
        $table_name = $wpdb->prefix . "wpshev_events";
        if ( $wpdb->get_var( "SHOW TABLES LIKE '{$table_name}'" ) != $table_name ) {

            $sql = "CREATE TABLE $table_name (
                    ID mediumint(9) NOT NULL AUTO_INCREMENT,
                    `created_date` datetime DEFAULT '0000-00-00 00:00:00' NOT NULL,
                    `last_updated_date` datetime DEFAULT '0000-00-00 00:00:00' NOT NULL,
                    `title` text NOT NULL,
                    `event_date` date NOT NULL,
                    `event_time` time NULL,
                    `event_type` text NOT NULL,
                    `full_day` TINYINT(1) NULL DEFAULT '0',
                    `description` longtext NOT NULL,
                    `customer_id` int(9) NOT NULL,
                    `instructor_id` int(9) NOT NULL,
                    `status` text NOT NULL,
                    PRIMARY KEY  (ID)
            )    $charset_collate;";
            require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
            dbDelta( $sql );
      }

       // Install table for instructor data
        $table_name = $wpdb->prefix . "instructor_data";
        if ( $wpdb->get_var( "SHOW TABLES LIKE '{$table_name}'" ) != $table_name ) {

            $sql = "CREATE TABLE $table_name (
                    ID mediumint(9) NOT NULL AUTO_INCREMENT,
                    `created_date` datetime DEFAULT '0000-00-00 00:00:00' NOT NULL,
                    `instructor_id` int(9) NOT NULL,
                    `assigned_client_id` int(9) NOT NULL,
                    `status` text NOT NULL,
                    PRIMARY KEY  (ID)
            )    $charset_collate;";
            require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
            dbDelta( $sql );
      }

        // Install table chats
        $table_name = $wpdb->prefix . "wpshev_chat";
        if ( $wpdb->get_var( "SHOW TABLES LIKE '{$table_name}'" ) != $table_name ) {
            $sql = "CREATE TABLE $table_name (
                    ID mediumint(9) NOT NULL AUTO_INCREMENT,
                    `created_date` datetime DEFAULT '0000-00-00 00:00:00' NOT NULL,
                    `client_id` int(9) NOT NULL,
                    `instructor_id` int(9) NOT NULL,
                    `messages` longtext NOT NULL,
                    `message_type` text NOT NULL,
                    `by` text NOT NULL,
                    `message_time` datetime DEFAULT '0000-00-00 00:00:00' NOT NULL,
                    PRIMARY KEY  (ID)
            )    $charset_collate;";
            require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
            dbDelta( $sql );
      }
        // Install table for check user online
        $table_name = $wpdb->prefix . "wpshev_user_chat_status";
        if ( $wpdb->get_var( "SHOW TABLES LIKE '{$table_name}'" ) != $table_name ) {
            $sql = "CREATE TABLE $table_name (
                    ID mediumint(9) NOT NULL AUTO_INCREMENT,
                    `user_id` int(9) NOT NULL,
                    PRIMARY KEY  (ID)
            )    $charset_collate;";
            require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
            dbDelta( $sql );
      }
     }
     public static function add_user_role(){
        add_role( 'fit_instructor', 'Instructor' );
     }
     public static function install() {
      self::install_tables();
      self::add_user_role();
     }
}