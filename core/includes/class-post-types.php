<?php 
/*
* Post Types
 */

if (!defined('ABSPATH')) {
	exit();
}

class wpshevPostTypes
{   
      public static function jobs_post_type() {
     
      $labels = array(
        'name'               => 'Jobs',
        'singular_name'      => 'Job',
        'add_new'            => 'Add New',
        'add_new_item'       => 'Add New Job',
        'edit_item'          => 'Edit Job',
        'new_item'           => 'New Job',
        'all_items'          => 'All Jobs',
        'view_item'          => 'View Job',
        'search_items'       => 'Search Jobs',
        'not_found'          =>  'No Jobs found',
        'not_found_in_trash' => 'No Jobs found in Trash',
        'parent_item_colon'  => '',
        'menu_name'          => 'Jobs'
      );
     
      $args = array(
        'labels'             => $labels,
        'public'             => true,
        'publicly_queryable' => true,
        'show_ui'            => true,
        'show_in_menu'       => true,
        'query_var'          => true,
        'rewrite'            => array( 'slug' => 'wpshev_jobs' ),
        'capability_type'    => 'post',
        'has_archive'        => true,
        'hierarchical'       => false,
        'menu_position'      => null,
        'supports'           => array( 'title', 'author', 'custom-fields' )
      );
      register_post_type( 'wpshev_jobs', $args );
    } 
}
