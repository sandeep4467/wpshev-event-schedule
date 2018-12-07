<?php 
/*
* Helpers
 */

if (!defined('ABSPATH')) {
	exit();
}

class wpshevHelpers
{
    public static function get_user_membership_details($user_id){
    global $wpdb;
    $user_data = false;
    $levels = get_option('ihc_levels');

    // echo "<pre>";
    // print_r($levels);
    // echo "</pre>";

    $table = $wpdb->prefix . "ihc_user_levels";

    $q = $wpdb->prepare("SELECT * FROM $table WHERE user_id=%d", $user_id);
    $data = $wpdb->get_results($q);

    $temp = array();

           if ($data){
            foreach ($data as $object){
              if (isset($levels[$object->level_id]['label'])){

                $temp['label'] = $levels[$object->level_id]['label'];
                $temp['level_id'] = $object->level_id;
                $temp['access_limited_time_type'] = $levels[$object->level_id]['access_limited_time_type'];
                $temp['access_limited_time_value'] = $levels[$object->level_id]['access_limited_time_value'];
                $temp['price'] = $levels[$object->level_id]['price'];

              } else {
                continue;
              }
            }
           }

      return $temp;
    }

    public static function get_notification_date($job_id){
      global $wpdb;
      $table_name = $wpdb->prefix.'wpshev_notification_data';
      $data = $wpdb->get_row("SELECT `notification_dates` FROM {$table_name} WHERE `job_id` = $job_id", OBJECT);
      if ($data) {
        return unserialize($data->notification_dates);
      }else{
        return false;
      }
    }
}
