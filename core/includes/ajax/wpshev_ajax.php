<?php
/*
* AJAX
*/

if (!defined('ABSPATH'))
{
  exit();
}

class wpshevAjax

{

  public function assign_instructor()
  {
    try
    {
      if (!check_ajax_referer('schedule-ajax-security-nonce', 'security'))
      {
        throw new Exception("Error Processing Request: Nonce verification failed!", 1);
      }

      if (!isset($_POST['client_id']))
      {
        throw new Exception("Error Processing Request: Client ID is request.", 1);
      }

      if (!isset($_POST['instructor_id']))
      {
        throw new Exception("Error Processing Request: Instructor ID is missing.", 1);
      }

      $user_id = $_POST['client_id'];
      $new_instructor_id = $_POST['instructor_id'];
      $access_limited_time_type = $_POST['access_limited_time_type'];
      $access_limited_time_value = $_POST['access_limited_time_value'];
      $level_id = $_POST['level_id'];
      $price = $_POST['price'];
      $reassign = $_POST['reassign'];
      $job_id = $_POST['job_id'];
      $previous_instructor_id = $_POST['current_instructor_id'];

      if ($user_id == '')
      {
        throw new Exception("Error: Client ID is required", 1);
      }

      if ($new_instructor_id == '')
      {
        throw new Exception("Error: Instructor ID is required", 1);
      }

      global $wpdb;
      $prefix = $wpdb->prefix;
      $db_name = $wpdb->dbname;
      $table_name = $prefix . 'instructor_data';

      $count = $wpdb->get_var("SELECT COUNT(*) FROM {$table_name} WHERE `instructor_id` = $new_instructor_id AND `assigned_client_id` = $user_id AND `status` = 'assigned'");

      if ($count > 0) {
          throw new Exception("Warning: Already assign to selected instructor.", 1); 
      }
      
      // If Attemping to reassign instructor
      if ($reassign == 'true')
      {    /*update existing record*/
           $wpdb->update( 
            $table_name, 
            array( 
              'instructor_id' => $new_instructor_id,  // string
              'previous_instructor_id' => $previous_instructor_id // integer (number) 
            ), 
            array( 'ID' => $job_id ), 
            array( 
              '%d', // value1
              '%d'  // value2
            ), 
            array( '%d' ) 
          );
      }else{
          /*insert new record*/
          $insert = $wpdb->insert($table_name, array(
            'created_date' => current_time('mysql') ,
            'instructor_id' => $new_instructor_id,
            'assigned_client_id' => $user_id,
            'status' => 'assigned'
          ) , array(
            '%s',
            '%d',
            '%d',
            '%s'
          ));
          $job_id = $wpdb->insert_id;
      }


      /*Add Payments to assigned Instructor*/
      self::add_instructor_payment_data($user_id, $job_id, $new_instructor_id, $access_limited_time_type, $access_limited_time_value, $price, $level_id);

      /*Add Monthly Notifications date to assigned Instructor*/

      $notification_id = self::add_notification_dates($access_limited_time_type, $access_limited_time_value, $job_id);

      /*Send response*/
      $response = array(
        'status' => 'success',
        'message' => "All set!!!"
      );


    }

    catch(Exception $e)
    {
      $response = array(
        'status' => 'error',
        'message' => $e->getMessage()
      );
    }

    echo json_encode($response);
    wp_die();
  }

  
  public static function add_notification_dates($access_limited_time_type, $access_limited_time_value, $job_id)
  {
    global $wpdb;
    $table_name = $wpdb->prefix . "wpshev_notification_data";

    $count = $wpdb->get_var("SELECT COUNT(*) FROM {$table_name} WHERE `job_id` = $job_id");

    if ($count > 0) {
      return;
    }
    /*Prepare months*/
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

    /*Insert monthly notification*/
    $insert = $wpdb->insert($table_name, array(
      'job_id' => $job_id,
      'notification_dates' => serialize($arr),
      'created_date' => current_time('mysql')
    ) , array(
      '%d',
      '%s',
      '%s'
    ));

    if ($insert)
    {
      return true;
    }

    return false;
  }

  public static function add_instructor_payment_data($user_id, $job_id, $new_instructor_id, $access_limited_time_type, $access_limited_time_value, $price, $level_id)
  {
    global $wpdb;
    $table_name = $wpdb->prefix . 'instructor_payment_data';

    /*Prepare data*/
    if ($access_limited_time_type == 'Y')
    {
      $access_limited_time_value = $access_limited_time_value * 12;
    }

    $monthly_payment = $price / $access_limited_time_value;
    $time = strtotime(current_time('mysql'));

    // Insert Payment Monthly

    for ($i = 0; $i < $access_limited_time_value; $i++)
    {
      $counter = $i + 1;
      $final = date("Y-m-d", strtotime("+" . $counter . " month", $time));
      $insert = $wpdb->insert($table_name, array(
        'job_id' => $job_id, 
        'instructor_id' => $new_instructor_id,
        'assigned_client_id' => $user_id,
        'access_limited_time_type' => $access_limited_time_type,
        'access_limited_time_value' => $access_limited_time_value,
        'bill_cycle' => $counter . ' out of ' . $access_limited_time_value,
        'monthly_payment' => $monthly_payment,
        'plan_price' => $price,
        'level_id' => $level_id,
        'status' => 'unpaid',
        'is_job_running' => 1,
        'created_date' => current_time('mysql') ,
        'payment_due_date' => $final
      ) , array(
        '%d',
        '%d',
        '%d',
        '%s',
        '%s',
        '%s',
        '%s',
        '%d',
        '%d',
        '%s',
        '%d',
        '%s',
        '%s',
      ));
    }
  }

  public function delete_payment()
  {
    $response = array();

    // nonce-field is created on page

    try
    {
      if (!check_ajax_referer('schedule-ajax-security-nonce', 'ajax_nonce'))
      {
        throw new Exception("Nonce Failed!!", 1);
      }

      if (!isset($_POST['id']))
      {
        throw new Exception("Error: ID is not set", 1);
      }

      if (empty($_POST['id']))
      {
        throw new Exception("Error: ID is required", 1);
      }

      global $wpdb;
      $id = $_POST['id'];
      $delete = $wpdb->delete($wpdb->prefix . 'instructor_payment_data', array(
        'ID' => $id
      ));
      if (!$delete)
      {
        throw new Exception("Error: Can't delete record.", 1);
      }

      $response['status'] = 'ok';
      $response['deleted_id'] = $id;
    }

    catch(Exception $e)
    {
      $response['status'] = 'error';
      $response['message'] = $e->getMessage();
    }

    echo json_encode($response);
    wp_die();
  }

  public function update_payment_status()
  {
    $response = array();

    // nonce-field is created on page

    try
    {
      if (!check_ajax_referer('schedule-ajax-security-nonce', 'ajax_nonce'))
      {
        throw new Exception("Nonce Failed!!", 1);
      }

      if (!isset($_POST['ids']))
      {
        throw new Exception("Error: IDs required.", 1);
      }

      if (empty($_POST['ids']))
      {
        throw new Exception("Error: Select atleast one checkbox.", 1);
      }

      $ids = json_decode(stripslashes($_POST['ids']) , true);
      global $wpdb;
      foreach($ids as $id)
      {
        $wpdb->update($wpdb->prefix . 'instructor_payment_data', array(
          'status' => 'paid', // string
        ) , array(
          'ID' => $id
        ) , array(
          '%s', // value1
        ) , array(
          '%d'
        ));
      }

      $response['status'] = 'ok';
    }

    catch(Exception $e)
    {
      $response['status'] = 'error';
      $response['message'] = $e->getMessage();
    }

    echo json_encode($response);
    wp_die();
  }

  public function start_job()
  {
    $response = array();
    try
    {
      if (!isset($_POST['job_id']) || !isset($_POST['client_id']) || !isset($_POST['instructor_id']))
      {
        throw new Exception("Error Processing Request", 1);
      }

      global $wpdb;
      $update = $wpdb->update($wpdb->prefix . 'instructor_data', array(
        'start_date' => current_time('mysql') ,
        'started' => 1
      ) , array(
        'ID' => $_POST['job_id']
      ) , array(
        '%s',
        '%d'
      ) , array(
        '%d'
      ));
      if (!$update)
      {
        throw new Exception("DB error: Can't update row.", 1);
      }

      $response['status'] = 'success';
      $response['message'] = 'Done!!!';
    }

    catch(Exception $e)
    {
      $response['status'] = 'error';
      $response['message'] = $e->getMessage();
    }

    echo json_encode($response);
    wp_die();
  }

  public function add_note(){
         $response = array();
    try
    {
      if (!isset($_POST['instructor_id']) || !isset($_POST['note']) || !isset($_POST['job_id']))
      {
        throw new Exception("Error Processing Request", 1);
      }

      global $wpdb;


      $insert = $wpdb->insert( 
        $wpdb->prefix . 'wpshev_instructor_notes', 
        array( 
        'user_id' => $_POST['instructor_id'],
        'job_id' => $_POST['job_id'],
        'note' => $_POST['note'],
        'created_date' => current_time('mysql')
        ), 
        array( 
          '%d', 
          '%d', 
          '%s',
          '%s'
        ) 
      );

      if (!$insert)
      {
        throw new Exception("DB error: Can't insert row.", 1);
      }

      $response['status'] = 'success';
      $response['message'] = 'Done!!';
    }

    catch(Exception $e)
    {
      $response['status'] = 'error';
      $response['message'] = $e->getMessage();
    }

    echo json_encode($response);
    wp_die();
  }

  public function get_notes(){
    $response = array();
    try
    {
      if (!isset($_POST['job_id']))
      {
        throw new Exception("Error Processing Request", 1);
      }
      global $wpdb;
      $job_id = $_POST['job_id'];
      $results = $wpdb->get_results( "SELECT * FROM {$wpdb->prefix}wpshev_instructor_notes WHERE job_id = $job_id", OBJECT );

      $html = ''; 

      if (!empty($results)) {
        foreach ($results as $row) {
           $html .= '<div class="note-repater" id="note_'.$row->ID.'">'.$row->note.'<button  class="delete-note" data-id="'.$row->ID.'"><i class="fa fa-trash-o" aria-hidden="true"></i></button></div>';
        }
      }else{
        $html .= '<p class="no-notes">No notes found!!!</p>';
      }

      $response['status'] = 'success';
      $response['data'] = $html;
    }

    catch(Exception $e)
    {
      $response['status'] = 'error';
      $response['message'] = $e->getMessage();
    }

    echo json_encode($response);
    wp_die();
  }

  public function delete_note(){
    global $wpdb;
    if (!isset($_POST['id']))
    {
     return false;
    }
    $id = $_POST['id'];
    $wpdb->delete( "{$wpdb->prefix}wpshev_instructor_notes", array( 'ID' => $id ), array( '%d' ) );
    wp_die();
  }
}