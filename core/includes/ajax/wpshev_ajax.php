<?php 
/*
* AJAX
 */

if (!defined('ABSPATH')) {
	exit();
}

class wpshevAjax
{ 
   
   public static function add_notification_dates($data){
      global $wpdb;
      $table_name = $wpdb->prefix . "wpshev_notification_data";
      $insert = $wpdb->insert(
           $table_name,
           array(
            'created_date' => current_time( 'mysql' ),
            'notification_dates'=> serialize($data)
           ),
           array(
            '%s',
            '%s'
           )
      );
      if ($insert) {
            return $lastid = $wpdb->insert_id;
      }
      
      return false;
  }  
 
	public function assign_instructor(){
       try {
        if (!check_ajax_referer( 'schedule-ajax-security-nonce', 'security' )) {
          throw new Exception("Error Processing Request: Nonce verification failed!", 1);
        }
        if (!isset($_POST['client_id'])) {
          throw new Exception("Error Processing Request: Client ID is request.", 1);
        }
        if (!isset($_POST['instructor_id'])) {
          throw new Exception("Error Processing Request: Instructor ID is missing.", 1);
        }

        $user_id = $_POST['client_id'];
        $instructor_id = $_POST['instructor_id'];
        $access_limited_time_type = $_POST['access_limited_time_type'];
        $access_limited_time_value = $_POST['access_limited_time_value']; 
        $level_id = $_POST['level_id'];
        $price = $_POST['price'];

        if ($user_id == '') {
          throw new Exception("Error: Client ID is required", 1);
        }
        if ($instructor_id == '') {
          throw new Exception("Error: Instructor ID is required", 1);
        }

           global $wpdb;
           $prefix = $wpdb->prefix;
           $db_name = $wpdb->dbname;
           $table_name = $prefix.'instructor_data';

 
          $count = $wpdb->get_var("SELECT COUNT(*) FROM {$table_name} WHERE `instructor_id` = $instructor_id AND `assigned_client_id` = $user_id AND `status` = 'assigned'");

          if ($count > 0) {
            throw new Exception("Warning: Already assign to selected instructor.", 1); 
          }

           /*Check for reassign to other instructor*/
           $result = $wpdb->get_results("SELECT `ID` FROM {$table_name} WHERE `assigned_client_id` = $user_id", ARRAY_A);
           $ids = array();
           foreach ($result as $row) {
            $id= (int)$row['ID'];
             $update = $wpdb->update(
              $table_name,
              array(
               'status' => 'unassigned'
              ),
              array('ID'=> $id),
              array(
                '%s'
              ),
              array(
                '%d'
              )
             );
          }

          $months = $access_limited_time_value;
          if ($access_limited_time_type == 'Y') {
              $months = $access_limited_time_value * 12;
          }
          
          $arr = [];
          $counter = 1;

          for ($i=0; $i < $months ; $i++) { 
            $time = strtotime(date('Y-m-d'));
            $arr[] = date("Y-m-d", strtotime("+".$counter." month", $time));
            $counter++;
          }

          $notification_id = self::add_notification_dates($arr);

          if ($notification_id) {
                /*Finaly insert new record*/
               $insert = $wpdb->insert(
               $table_name,
               array(
                'created_date' => current_time( 'mysql' ),
                'instructor_id'=> $instructor_id,
                'assigned_client_id'=> $user_id,
                'status' => 'assigned',
                'notification_id' => $notification_id
               ),
               array(
                '%s',
                '%d',
                '%d',
                '%s',
                '%d'
               )
              );       
          } 

       
        $client_info = get_userdata($user_id);
          $client_fullname = $client_info->first_name . ' '. $client_info->last_name;

        $instructor_info = get_userdata($instructor_id);
          $instructor_fullname = $instructor_info->first_name . ' '. $instructor_info->last_name;

        $response = array(
           'status'=>'success',
           'message' => $client_fullname . " is assigned to Instructor " . $instructor_fullname
        );


       self::add_instructor_payment_data($user_id, $instructor_id, $access_limited_time_type, $access_limited_time_value, $price, $level_id);

       } catch (Exception $e) {
        $response = array(
           'status' => 'error',
           'message' => $e->getMessage()
        );
       }
       echo json_encode( $response );
       wp_die();
	}


  public static function add_instructor_payment_data($user_id, $instructor_id, $access_limited_time_type, $access_limited_time_value, $price, $level_id){
    global $wpdb;
    $table_name = $wpdb->prefix.'instructor_payment_data';

    if ($access_limited_time_type == 'Y') {
      $access_limited_time_value = $access_limited_time_value * 12;
    }

    $monthly_payment = $price / $access_limited_time_value;
    $time = strtotime(current_time( 'mysql' ));

    // Insert Payment Monthly
    for ($i=0; $i < $access_limited_time_value ; $i++) { 

      $counter = $i + 1;

      $final = date("Y-m-d", strtotime("+".$counter." month", $time));

      $insert = $wpdb->insert(
             $table_name,
             array(
              'created_date' => current_time( 'mysql' ),
              'payment_due_date' => $final,
              'instructor_id'=> $instructor_id,
              'assigned_client_id'=> $user_id,
              'access_limited_time_type'=> $access_limited_time_type,
              'access_limited_time_value'=> $access_limited_time_value,
              'bill_cycle'=> $counter . ' out of ' . $access_limited_time_value,
              'monthly_payment' => $monthly_payment,
              'plan_price'=> $price,
              'level_id'=> $level_id,
              'status' => 'unpaid'
             ),
             array(
              '%s',
              '%s',
              '%d',
              '%d',
              '%s',
              '%s',
              '%s',
              '%s',
              '%s',
              '%d',
              '%s',
             )
            );     
    }
    }

    public function delete_payment(){
        $response = array();
       //nonce-field is created on page
        try {
          if (!check_ajax_referer('schedule-ajax-security-nonce','ajax_nonce')) {
            throw new Exception("Nonce Failed!!", 1);
          }
          if (!isset($_POST['id'])) {
            throw new Exception("Error: ID is not set", 1);
          }
          if (empty($_POST['id'])) {
            throw new Exception("Error: ID is required", 1);
          }
           global $wpdb;
           $id = $_POST['id'];

           $delete = $wpdb->delete( $wpdb->prefix.'instructor_payment_data', array( 'ID' => $id ) );
           
           if (!$delete) {
              throw new Exception("Error: Can't delete record.", 1); 
           }

           $response['status'] = 'ok';
           $response['deleted_id'] = $id;


        } catch (Exception $e) {
          $response['status'] = 'error';
          $response['message'] =  $e->getMessage();
        }
        
        echo json_encode( $response );
        wp_die();
    }

    public function update_payment_status(){
        $response = array();
       //nonce-field is created on page
        try {
          if (!check_ajax_referer('schedule-ajax-security-nonce','ajax_nonce')) {
            throw new Exception("Nonce Failed!!", 1);
          }
          if (!isset($_POST['ids'])) {
            throw new Exception("Error: IDs required.", 1);
          }
          if (empty($_POST['ids'])) {
            throw new Exception("Error: Select atleast one checkbox.", 1);    
          }


          $ids = json_decode(stripslashes($_POST['ids']), true);
          global $wpdb;

          foreach ($ids as $id) {
              $wpdb->update( 
                $wpdb->prefix . 'instructor_payment_data', 
                array( 
                  'status' => 'paid',  // string
                ), 
                array( 'ID' => $id ), 
                array( 
                  '%s', // value1
                ), 
                array( '%d' ) 
              );    
          }

          $response['status'] = 'ok';

        } catch (Exception $e) {
          $response['status'] = 'error';
          $response['message'] =  $e->getMessage();
        }
        
        echo json_encode( $response );
        wp_die();
    }

    public function start_job(){
      $response = array();
      try {

      if (!isset($_POST['job_id']) || !isset($_POST['client_id']) || !isset($_POST['instructor_id'])) {
          throw new Exception("Error Processing Request", 1);
      }

      global $wpdb;
      $update = $wpdb->update( 
        $wpdb->prefix . 'instructor_data', 
        array( 
          'start_date' => current_time( 'mysql' ),  
          'started' => 1 
        ), 
        array( 'ID' => $_POST['job_id'] ), 
        array( 
          '%s',
          '%d' 
        ), 
        array( '%d' ) 
      );
      
      if (!$update) {
        throw new Exception("DB error: Can't update row.", 1);
      }

      $response['status'] = 'success';   
      $response['message'] = 'HELLO'; 

      } catch (Exception $e) {
        $response['status'] = 'error';
        $response['message'] = $e->getMessage();
      }
      echo json_encode( $response );
      wp_die();
    }
}
