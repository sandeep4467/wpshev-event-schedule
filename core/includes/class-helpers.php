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
    $table = $wpdb->prefix . "ihc_user_levels";
    $q = $wpdb->prepare("SELECT * FROM $table WHERE user_id=%d", $user_id);
    $data = $wpdb->get_results($q);
           if ($data){
            foreach ($data as $object){
              $temp = (array)$object;
              if (isset($levels[$object->level_id]['label'])){
                $temp['label'] = $levels[$object->level_id]['label'];
              } else {
                continue;
              }
              $user_data = $temp;
            }
           }

      return $user_data;
    }
}
