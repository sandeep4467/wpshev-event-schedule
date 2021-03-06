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

                    `start_date` datetime DEFAULT '0000-00-00 00:00:00' NOT NULL,

                    `started` int(9) NOT NULL DEFAULT 0,

                    `instructor_id` int(9) NOT NULL,

                    `previous_instructor_id` int(9) DEFAULT NULL,

                    `assigned_client_id` int(9) NOT NULL,

                    `status` text NOT NULL,

                    `feedback` LONGTEXT NOT NULL,

                    `grace_days` int(9) NOT NULL DEFAULT 3,

                    `is_new_job` smallint DEFAULT '1' NOT NULL,  
                    `is_proceed_by_client` smallint DEFAULT '0' NOT NULL, 
                    `is_proceed_by_instructor` smallint DEFAULT '0' NOT NULL,                   

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



    // Install table for instructor payment data

        $table_name = $wpdb->prefix . "instructor_payment_data";

        if ( $wpdb->get_var( "SHOW TABLES LIKE '{$table_name}'" ) != $table_name ) {



            $sql = "CREATE TABLE $table_name (

                    ID mediumint(9) NOT NULL AUTO_INCREMENT,

                    `job_id` int(9) NOT NULL,

                    `instructor_id` int(9) NOT NULL,

                    `assigned_client_id` int(9) NOT NULL,

                    `access_limited_time_type` text NOT NULL,

                    `access_limited_time_value` int(9) NOT NULL,

                    `bill_cycle` varchar(256) NOT NULL,

                    `monthly_payment` decimal(4,2) NOT NULL,

                    `plan_price` int(9) NOT NULL,

                    `level_id` int(9) NOT NULL,

                    `status` text NOT NULL,

                    `is_job_running` smallint DEFAULT '0' NOT NULL,

                    `created_date` datetime DEFAULT '0000-00-00 00:00:00' NOT NULL,

                    `payment_due_date` datetime DEFAULT '0000-00-00 00:00:00' NOT NULL,

                    PRIMARY KEY  (ID)

            )    $charset_collate;";

            require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );

            dbDelta( $sql );

      }



        // Install table for notification data

        $table_name = $wpdb->prefix . "wpshev_notification_data";

        if ( $wpdb->get_var( "SHOW TABLES LIKE '{$table_name}'" ) != $table_name ) {

            $sql = "CREATE TABLE $table_name (

                    ID mediumint(9) NOT NULL AUTO_INCREMENT,

                    `job_id` int(9) NOT NULL,

                    `notification_dates` LONGTEXT NOT NULL,

                    `created_date` datetime DEFAULT '0000-00-00 00:00:00' NOT NULL,

                    PRIMARY KEY  (ID)

            )    $charset_collate;";

            require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );

            dbDelta( $sql );

      }

        // Install table for notification data

        $table_name = $wpdb->prefix . "wpshev_instructor_notes";

        if ( $wpdb->get_var( "SHOW TABLES LIKE '{$table_name}'" ) != $table_name ) {

            $sql = "CREATE TABLE $table_name (

                    ID mediumint(9) NOT NULL AUTO_INCREMENT,

                    `user_id` int(9) NOT NULL,

                    `job_id` int(9) NOT NULL,

                    `note` LONGTEXT NOT NULL,

                    `created_date` datetime DEFAULT '0000-00-00 00:00:00' NOT NULL,

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