<?php 
/*
* Payments
 */

if (!defined('ABSPATH')) {
	exit();
}

class wpshevManagePayment
{
    public static function get_payments($instructor_id, $month_year){
      global $wpdb;

      $results = $wpdb->get_results("SELECT * FROM {$wpdb->prefix}instructor_payment_data WHERE `instructor_id` = $instructor_id AND DATE_FORMAT(payment_due_date, '%m-%Y') = '".$month_year."'", OBJECT);
      
      if (!empty($results)) {
       return $results;
      }

      return false;
    }
}
