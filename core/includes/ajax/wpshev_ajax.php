<?php 
/*
* AJAX
 */

if (!defined('ABSPATH')) {
	exit();
}

class wpshevAjax
{

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

           /*Finaly insert new record*/
           $insert = $wpdb->insert(
           $table_name,
           array(
            'created_date' => current_time( 'mysql' ),
            'instructor_id'=> $instructor_id,
            'assigned_client_id'=> $user_id,
            'status' => 'assigned'
           ),
           array(
            '%s',
            '%d',
            '%d',
            '%s'
           )
          );

          if (!$insert) {
             throw new Exception("WordPress database error: " . $wpdb->last_error, 1);
          }

        $client_info = get_userdata($user_id);
          $client_fullname = $client_info->first_name . ' '. $client_info->last_name;

        $instructor_info = get_userdata($instructor_id);
          $instructor_fullname = $instructor_info->first_name . ' '. $instructor_info->last_name;

        $response = array(
           'status'=>'success',
           'message' => $client_fullname . " is assigned to Instructor " . $instructor_fullname
        );

       } catch (Exception $e) {
        $response = array(
           'status' => 'error',
           'message' => $e->getMessage()
        );
       }
       echo json_encode( $response );
       wp_die();
	}
}
