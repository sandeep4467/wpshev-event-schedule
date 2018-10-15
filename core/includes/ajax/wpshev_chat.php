<?php 
/*
* Add Chat
 */

if (!defined('ABSPATH')) {
	exit();
}

class wpshevChat
{
	public function add_chat(){
        try {

            $client_id = $_POST['client_id'];
            $instructor_id = $_POST['instructor_id'];
            $message = $_POST['message'];
            $message_type = 'new';
            $by = $_POST['by'];
            $message_time = $_POST['message_time'];

            global $wpdb;
            $tblname = $wpdb->prefix . 'wpshev_chat';


            /*Update new message flag*/ 
            $query = "SELECT `ID` FROM $tblname  WHERE client_id = {$client_id} AND instructor_id = {$instructor_id} AND `message_type` = 'new' and `by` = '$by'";

            $last_new_message = $wpdb->get_row( $query, OBJECT );

            if (count($last_new_message) > 0) {
                  $wpdb->query("UPDATE $tblname SET `message_type`= 'old' WHERE `ID` = $last_new_message->ID AND `by` = '$by'");
            }
            
            /*Insert new message*/
            $insert =
             $wpdb->insert(
              $tblname,
              array(
               'created_date' => current_time( 'mysql' ),
               'client_id' => $client_id,
               'instructor_id' => $instructor_id,
               'messages' => $message,
               'message_type' => $message_type,
               'by' => $by,
               'message_time' => $message_time
              ),
              array(
               '%s',
               '%d',
               '%d',
               '%s',
               '%s',
               '%s'
              )
            );
            if (!$insert) {
              if($wpdb->last_error !== '') :
                     throw new Exception("Error: Value not inserted." .  $wpdb->print_error(), 1); 
                 endif; 
                 throw new Exception("Error: Value not inserted.", 1); 
            }

      $response = array(
             'status' => 'success',
             'lastid' => $wpdb->insert_id
      );

    } catch (Exception $e) {
      $response = array(
             'status' => 'error',
             'message'=> $e->getMessage()
      );
    }

    echo json_encode( $response );
      wp_die();
  }

  public function load_chat(){

   try {
      if (!isset($_POST['client_id'])) {
        throw new Exception("Error: Client ID is required", 1);
      }
      if (empty($_POST['instructor_id'])) {
         throw new Exception("Error: Instructor ID is empty", 1);
      }


      global $wpdb;
      $client_id = $_POST['client_id'];
      $instructor_id = $_POST['instructor_id'];
      $data = array();
      
      $query = "SELECT * FROM {$wpdb->prefix}wpshev_chat WHERE client_id = {$client_id} AND instructor_id = {$instructor_id}";

      $results = $wpdb->get_results( $query, OBJECT );
      $html = '';

      if ($results) {

      $avatar = 'http://2.gravatar.com/avatar/b3a4bfdceaf39304c3660e8306f08f2c?s=96&d=mm&r=g';

      foreach ($results as $row) {

               /*User info*/
              if ($row->by == 'client') {
                 $id = $row->client_id;
              }
              if ($row->by == 'instructor') {
                 $id = $row->instructor_id;
              }
              $user = get_user_by('id', $id);
              
              $attachment_id = get_user_meta($id, 'ihc_avatar', true);
              if (!empty($attachment_id)) {
                $image_attributes = wp_get_attachment_image_src( $attachment_id );
                $avatar = $image_attributes[0];
              }       

              $html .= '<div class="chat-repeater">
              <figure class="user-img"><img src="'.$avatar.'"></figure>
              <div class="chat-text"><span class="user-info"> '.$user->first_name . ' ' . $user->last_name.' <strong>'.$row->message_time.'</strong></span>
              <p>'.$row->messages.'</p></div></div>';
        }
      }

      $response = array(
             'status' => 'success',
             'data'=> $html
      );
    } catch (Exception $e) {
      $response = array(
      'status'=>'error',
      'message'=> $e->getMessage()
      );
    } 
    echo json_encode( $response );
    wp_die();
  }
  public function refresh_chat(){

   try {
      if (!isset($_POST['client_id'])) {
        throw new Exception("Error: Client ID is required", 1);
      }
      if (empty($_POST['instructor_id'])) {
         throw new Exception("Error: Instructor ID is empty", 1);
      }


      global $wpdb;
      $client_id = $_POST['client_id'];
      $instructor_id = $_POST['instructor_id'];
      $data = array();
      
      $query = "SELECT * FROM {$wpdb->prefix}wpshev_chat WHERE client_id = {$client_id} AND instructor_id = {$instructor_id} AND `message_type` = 'new'";

      $results = $wpdb->get_results( $query, OBJECT );
      $html = '';

      if ($results) {

      $avatar = 'http://2.gravatar.com/avatar/b3a4bfdceaf39304c3660e8306f08f2c?s=96&d=mm&r=g';

      foreach ($results as $row) {

               /*User info*/
              if ($row->by == 'client') {
                 $id = $row->client_id;
              }
              if ($row->by == 'instructor') {
                 $id = $row->instructor_id;
              }
              $user = get_user_by('id', $id);
              
              $attachment_id = get_user_meta($id, 'ihc_avatar', true);
              if (!empty($attachment_id)) {
                $image_attributes = wp_get_attachment_image_src( $attachment_id );
                $avatar = $image_attributes[0];
              }       

              $html .= '<div class="chat-repeater">
              <figure class="user-img"><img src="'.$avatar.'"></figure>
              <div class="chat-text"><span class="user-info"> '.$user->first_name . ' ' . $user->last_name.' <strong>'.$row->created_date.'</strong></span>
              <p>'.$row->messages.'</p></div></div>';
        }
      }

      $response = array(
             'status' => 'success',
             'data'=> $html
      );
    } catch (Exception $e) {
      $response = array(
      'status'=>'error',
      'message'=> $e->getMessage()
      );
    } 
    echo json_encode( $response );
    wp_die();
  }
}
