<?php 

/*

* Payments

 */



if (!defined('ABSPATH')) {

	exit();

}



class wpshevManagePayment

{

	private static function instructor_percentage($instructor_id){

      $instructor_percentage = get_user_meta( $instructor_id, 'instructor_percentage' , true );

      if ( ! empty( $instructor_percentage ) ) {

         return $instructor_percentage;

      }

      $instructor_commission = 0;

    }



    public static function get_payments($instructor_id, $month_year){

      global $wpdb;

      

      $results = $wpdb->get_results("SELECT * FROM {$wpdb->prefix}instructor_payment_data WHERE `instructor_id` = $instructor_id AND DATE_FORMAT(payment_due_date, '%m-%Y') = '".$month_year."'", OBJECT);

      foreach ($results as $result) {

      	$amount = $result->monthly_payment;

      	$percentage = self::instructor_percentage($instructor_id);

        $new_monthly_payment = ($percentage / 100) * $amount;

        if (!is_null($result->name)) {
           $new_monthly_payment = $amount;
        }  
        

      	$result->monthly_payment = number_format($new_monthly_payment, 2);

      } 

      if (!empty($results)) {

       return $results;

      }



      return false;

    }

}

