<?php 
/*
* Instructor Info
 */

if (!defined('ABSPATH')) {
	exit();
}

class wpshevInstructorInfo
{   
    private static function instructor_percentage($instructor_id){
      $instructor_percentage = get_user_meta( $instructor_id, 'instructor_percentage' , true );
      if ( ! empty( $instructor_percentage ) ) {
         return $instructor_percentage;
      }
      $instructor_commission = 0;
    }

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
       
      $percentage = self::instructor_percentage($instructor_id);
      $new_sum = ($percentage / 100) * $sum;

      if (!empty($new_sum)) {
        return '$' . number_format($new_sum, 2);
      }else{
        return '--';
      }

    }

    public static function outstanding_payout($instructor_id) {

      global $wpdb;
     
      $outstanding = $wpdb->get_var("SELECT SUM(`monthly_payment`) FROM `wp_instructor_payment_data` WHERE `instructor_id` = $instructor_id AND `status` = 'unpaid'");

      $percentage = self::instructor_percentage($instructor_id);
      $new_outstanding = ($percentage / 100) * $outstanding;

      if (!empty($new_outstanding)) {
        return '$' . number_format($new_outstanding, 2);
      }else{
        return '--';
      }

    }

    public static function life_time_earnings($instructor_id) {
        global $wpdb;

        $total = $wpdb->get_var("SELECT SUM(`monthly_payment`) FROM `wp_instructor_payment_data` WHERE `instructor_id` = $instructor_id AND `status` = 'paid'");

        $percentage = self::instructor_percentage($instructor_id);
        $new_total = ($percentage / 100) * $total;

        if (!empty($new_total)) {
          return '$' . number_format($new_total, 2);
        }else{
          return '--';
        }
    }
}
