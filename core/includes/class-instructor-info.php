<?php 
/*
* Instructor Info
 */

if (!defined('ABSPATH')) {
	exit();
}

class wpshevInstructorInfo
{
    public static function total_clients($instructor_id){
      global $wpdb;
      $count = $wpdb->get_var("SELECT COUNT(*) FROM `{$wpdb->prefix}instructor_data` WHERE `instructor_id` = $instructor_id AND `status` = 'assigned'");
      
      if (empty($count)) {
       return $count = 0;
      }
      return $count;
    }

    public static function current_month_payout($instructor_id, $current_month, $current_year) {

      global $wpdb;
      $month_year = $current_month . '-' .$current_year;
     
      $sum = $wpdb->get_var("SELECT SUM(`monthly_payment`) FROM {$wpdb->prefix}instructor_payment_data WHERE `instructor_id` = $instructor_id AND DATE_FORMAT(payment_due_date, '%m-%Y') = '$month_year'");
       
      if (!empty($sum)) {
        return '$' . $sum;
      }else{
        return '--';
      }

    }

    public static function outstanding_payout($instructor_id) {

      global $wpdb;
     
      $outstanding = $wpdb->get_var("SELECT SUM(`monthly_payment`) FROM `wp_instructor_payment_data` WHERE `instructor_id` = $instructor_id AND `status` = 'unpaid'");
       
      if (!empty($outstanding)) {
        return '$' . $outstanding;
      }else{
        return '--';
      }

    }

    public static function life_time_earnings($instructor_id) {
        global $wpdb;

        $total = $wpdb->get_var("SELECT SUM(`monthly_payment`) FROM `wp_instructor_payment_data` WHERE `instructor_id` = $instructor_id AND `status` = 'paid'");

        if (!empty($total)) {
          return '$' . $total;
        }else{
          return '--';
        }
    }
}
