<?php 
/*
* Add Event
 */

if (!defined('ABSPATH')) {
	exit();
}

class wpshevEvent
{
	public function add_event(){


		try {

			if (!isset($_POST['data'])) {
				throw new Exception("Error Processing Request", 1);
			}

      $str = $_POST['data'];
      parse_str($str, $data);

			if ($data['client_id'] == '') {
				throw new Exception("Error: Client ID is required.", 1);
			}

			if ($data['event_title'] == '') {
				throw new Exception("Error: Event title is required.", 1);
			}
			if ($data['event-start-date'] == '') {
				throw new Exception("Error: Event date is empty.", 1);
			}
			if ($data['event-start-time'] == '' && $data['all-day-event'] == '') {
				throw new Exception("Error: Select specific time or all day checkbox.", 1);
			}
      
            global $wpdb;
            $tblname = $wpdb->prefix . 'wpshev_events';
            $event_time = strtotime($data['event-start-time']);
            $all_day = 0; 

            if ($data['all-day-event'] != '') {
              $event_time = '';
              $all_day = 1;
            }

            $dates = explode(',', $data['event-start-date']);
            foreach ($dates as $date) {
            $event_date = strtotime($date);

            $insert =
             $wpdb->insert(
              $tblname,
              array(
               'created_date' => current_time( 'mysql' ),
               'last_updated_date' => current_time( 'mysql' ),
               'title' => $data['event_title'],
               'event_date' => date("Y-m-d", $event_date),
               'event_time' => date('H:i:s', $event_time),
               'event_type' => $data['type-of-event'], 
               'full_day' => $all_day,
               'description' => $data['activeEditor'],
               'customer_id' => $data['client_id'],
               'instructor_id' => get_current_user_id(),
               'status' => 'active',
              ),
              array(
               '%s',
               '%s',
               '%s',
               '%s',
               '%s',
               '%s',
               '%d',
               '%s',
               '%d',
               '%d',
               '%s'
              )
            );
            if (!$insert) {
            	if($wpdb->last_error !== '') :
                     throw new Exception("Error: Value not inserted." .  $wpdb->print_error(), 1); 
                 endif;	
                 throw new Exception("Error: Value not inserted.", 1); 
            }
       }

			$response = array(
             'status' => 'success',
             'message'=>'Successfully added the event. Add new event or close the popup.',
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

  public function get_events(){

   try {
      if (!isset($_POST['customer_id'])) {
        throw new Exception("Error: Customer ID is required", 1);
      }
      if (empty($_POST['instructor_id'])) {
         throw new Exception("Error: User ID is empty", 1);
      }




      global $wpdb;
      $customer_id = $_POST['customer_id'];
      $instructor_id = $_POST['instructor_id'];
      $data = array();
      
      $query = "SELECT * FROM {$wpdb->prefix}wpshev_events WHERE customer_id = {$customer_id} AND instructor_id = {$instructor_id} AND status = 'active'";

      if (empty($_POST['instructor_id'])) {
        $query = "SELECT * FROM {$wpdb->prefix}wpshev_events WHERE customer_id = {$customer_id} AND status = 'active'";
      }

      $results = $wpdb->get_results( $query, OBJECT );

       if ($results) {
         foreach ($results as $row) {
            $data[] = array(
             'id' => $row->ID,
             'title' => $row->title,
             'event_date' => $row->event_date,
             'full_day' => $row->full_day,
             'event_time' => $row->event_time,
             'event_type' => $row->event_type
            );
         }
       }
      $response = array(
             'status' => 'success',
             'data'=> $data
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

  public function get_event(){

   try {
      if (!isset($_POST['event_id'])) {
        throw new Exception("Error: Event ID is required", 1);
      }
      
      global $wpdb; 
      $id = $_POST['event_id'];
      $data = array();

      $result = $wpdb->get_row( "SELECT * FROM {$wpdb->prefix}wpshev_events WHERE id = {$id}", OBJECT );
       

       if ($result) {
          $data['id']= $result->ID;
          $data['title']= $result->title;
          $data['full_day']= $result->full_day;
          $data['event_time']= $result->event_time;
          $data['event_type']= $result->event_type;
          $data['description']= stripslashes($result->description);

       }

      $response = array(
             'status' => 'success',
             'data'=> $data
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

  public function delete_event(){

   try {
      if (!isset($_POST['event_id'])) {
        throw new Exception("Error: Event ID is required", 1);
      }
      
      global $wpdb; 
      $id = $_POST['event_id'];
      $data = array();

      $updated = $wpdb->update(
      $wpdb->prefix . 'wpshev_events', 
      array( 
        'status' => 'removed',
        'last_updated_date' => current_time( 'mysql' )
       ), 
      array( 'id' => $id ), 
      array( 
        '%s',
        '%s' 
      ),
      array( '%d' ) 
      );

      if ( false === $updated ) {
          throw new Exception("There was an error.", 1);
      }

      $response = array(
             'status' => 'success',
             'id'=> $id
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
