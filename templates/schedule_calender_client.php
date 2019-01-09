<?php
    global $wpdb;
    $user_id = get_current_user_id(); 
?>

<input type="hidden" name="customer_id" value="<?php echo get_current_user_id(); ?>">

		
<div id="wpshev-loader">
	<img src="<?php echo plugins_url('/core/assets/img/ajax-loader.gif', WPSHEV_PLUGIN_FILE); ?>">
	<span>Hold on! calendar is loading...</span>
</div>
<div id='calendar'>
</div>
