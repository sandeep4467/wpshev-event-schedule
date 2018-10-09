<?php
    global $wpdb;
    $user_id = get_current_user_id(); 
    /*Get all Clients*/  
    $result = $wpdb->get_results("SELECT * FROM {$wpdb->prefix}instructor_data WHERE `instructor_id` = $user_id AND `status`= 'assigned'");
?>

<form id="select_client" name="select_client" action="" method="post">
	<select name="client_id" class="client_id">
		<option value="">Please select client...</option>
	<?php  
	   foreach ($result as $row) {
	   $client_id = $row->assigned_client_id;  
	   $client_info = get_userdata($client_id);
	   $client_fullname = $client_info->first_name . ' '. $client_info->last_name;
	?>
	<?php 
	$selected = false;
	if (isset($_POST['client_id'])) {
		if ($client_id == $_POST['client_id']) {
			$selected = true;
		}else{
			$selected = false;
		}
	} 
	?>
	<option <?php echo ($selected) ? 'selected' : '' ?> value="<?php echo $client_id; ?>"><?php echo $client_fullname; ?>
	</option>

	<?php } ?>
	</select>
</form>



<?php 
if (isset($_POST['client_id'])) { ?> 
  <?php if ( !empty($_POST['client_id'])) { ?>
		<a class="ev-popup" href="#small-dialog">Add Event</a>
		
		<input type="hidden" name="user_id" value="<?php echo get_current_user_id(); ?>">
		<input type="hidden" name="customer_id" value="<?php echo $_POST['client_id']; ?>">
        
        <div id="wpshev-loader">
        	<img src="<?php echo plugins_url('/core/assets/img/ajax-loader.gif', WPSHEV_PLUGIN_FILE); ?>">
        	<span>Hold on! calendar is loading...</span>
        </div>
		<div id='calendar'>
			
		</div>

		<!-- dialog itself, mfp-hide class is required to make dialog hidden -->
		<div class="wpshev-popup-outer">
		<div id="wpshev-custom-popup" class="wpshev-custom-popup">
			<h1>Add Event</h1>
			<form id="ev-add-event">
				<input type="hidden" name="client_id" value="<?php echo $_POST['client_id']; ?>">
				<div class="ev-form-row">
				<input type="text" name="event_title" placeholder="Event Title">
			    </div>
				<div class="ev-form-row">
					<div class="ev-form-inline">
					<input id="event-start-date" type="text" placeholder="Start date" name="event-start-date" class="datepicker" readonly="">
				    </div>
				    			<div class="ev-form-inline">
					<input id="event-end-date" type="text" placeholder="End date" name="event-end-date" class="datepicker" readonly="">
				</div>
				</div>
				<div class="clear"></div>
			<div class="ev-form-row">
<?php 
$content = '';
$editor_id = 'kv_frontend_editor';
$settings =   array(
    'wpautop' => true, // use wpautop?
    'media_buttons' => true, // show insert/upload button(s)
    'textarea_name' => $editor_id, // set the textarea name to something different, square brackets [] can be used here
    'textarea_rows' => 30, // rows="..."
    'tabindex' => '',
    'editor_css' => '', //  extra styles for both visual and HTML editors buttons, 
    'editor_class' => '', // add extra class(es) to the editor textarea
    'teeny' => false, // output the minimal editor config used in Press This
    'dfw' => false, // replace the default fullscreen with DFW (supported on the front-end in WordPress 3.4)
    'tinymce' => true, // load TinyMCE, can be used to pass settings directly to TinyMCE using an array()
    'quicktags' => true // load Quicktags, can be used to pass settings directly to Quicktags using an array()
);
?>
<?php wp_editor( $content, $editor_id, $settings = array() ); ?>
				  </div>
				<input type="submit" name="submit" value="Add">
				<a class="ev-popup-close">Close</a>
			</form>
		</div>
	     </div>
		<div class="wpshev-custom-popup-overlay"></div>
  <?php }else{  ?>
  	Please select client.
  <?php } ?>
<?php  } ?> 

