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

     

      $outstanding = $wpdb->get_var("SELECT SUM(`monthly_payment`) FROM {$wpdb->prefix}instructor_payment_data WHERE `instructor_id` = $instructor_id AND `status` = 'unpaid'");



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

        $results = $wpdb->get_results("SELECT * FROM {$wpdb->prefix}instructor_payment_data WHERE `instructor_id` = $instructor_id AND `status` = 'paid'");

        foreach ($results as $result) {
          $amount = $result->monthly_payment;
          $percentage = self::instructor_percentage($instructor_id);
          $new_monthly_payment = ($percentage / 100) * $amount;
          if (!is_null($result->name)) {
             $new_monthly_payment = $amount;
          }  
          $sum += $new_monthly_payment;

        }

        if (!empty($sum)) {

          return '$' . number_format($sum, 2);

        }else{

          return '--';

        }

    }

}

