<?php
/**
 * Admin Dashboard
 *
 */

defined( 'ABSPATH' ) || exit;

/**
 * WP Admin Dashboard class.
 */

class WPSHEV_AdminDashboard {

      public static function check_assign_status($client_id){
        try {
          if ($client_id == '') {
            throw new Exception("Client is missing!", 1);
          }
        } catch (Exception $e) {
          echo $e->getMessage();
        }

        global $wpdb;
          $prefix = $wpdb->prefix;
          $table_name = $prefix.'instructor_data';
          $result = $wpdb->get_row("SELECT * FROM {$table_name} WHERE `assigned_client_id` = $client_id AND `status` = 'assigned'");
          if ($result != NULL) {
            return $result;
          }else{
            return false;
          }
      }

}