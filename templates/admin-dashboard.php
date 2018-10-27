<div class="instructor-section">
    <?php 
    $clients = get_users( array('role'=> 'subscriber') );  
    ?> 

    <div class="fitness-data-table">
        <div class="fitness-table-header">
            <h2>Clients Panel 
            <span> | <?php echo (count($clients) > 1) ? count($clients) . ' Clients' : count($clients) . ' Client'?></span>
           </h2>
        </div>
        <table>
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Purchase Information</th>
                    <th>Current Instructor</th>
                    <th>Start Date</th>
                    <th>Instructor Assignment</th>
                    <th>Client’s Page</th>
                </tr>
            </thead>
            <tbody>
            	<?php foreach ($clients as $client) { ?>

				        <?php 
                          $label = 'Assign Instructor';
                          $date_label = 'Starting soon';
                          $ins_label = 'New Client<small>Please assign new instructor.</small>';

                          $class = '';
                          $instructor_id = '';
                          if (WPSHEV_AdminDashboard::check_assign_status($client->ID)) {
                             $data = WPSHEV_AdminDashboard::check_assign_status($client->ID);
                             $class = 'assigned';
                             $label = 'Reassign Instructor';
                            
                             $date_label = date( "d-M-Y, h:i A", strtotime( $data->created_date));

                             $instructor_id = $data->instructor_id;
                             $instructor_info = get_userdata($data->instructor_id);
                             $ins_label = $instructor_info->first_name . ' '. $instructor_info->last_name;
                          }
                        ?>		
				<tr>
                    <td><?php echo $client->display_name; ?>
                    <small><?php echo get_user_meta( $client->ID, 'country', true ); ?>, <?php echo get_user_meta( $client->ID, 'thestate', true ); ?></small></td>
                    <td>
          						<?php
          	            $plan = wpshevHelpers::get_user_membership_details($client->ID);
          	            echo $plan['label'];
          						?>
                    </td>
                    <td><?php echo $ins_label; ?></td>
                    <td><?php echo $date_label; ?></td>
                    <td>
                    	<a class="popup-modal <?php echo $class; ?>" href="#instructor-modal" data-user-id="<?php echo $client->ID ?>" data-instructor-id="<?php echo $instructor_id; ?>" data-access_limited_time_type="<?php echo $plan['access_limited_time_type']; ?>" data-access_limited_time_value="<?php echo $plan['access_limited_time_value']; ?>" data-level-id="<?php echo $plan['level_id']; ?>" data-price="<?php echo $plan['price']; ?>">
                          <?php echo $label; ?>
                      </a>
                    </td>
                    <td>
                    	<a href="?user_id=<?php echo $client->ID ?>">Enter Page</a>
                    </td>
                </tr>
				<?php } ?>
            </tbody>

        </table>
    </div>
</div>

<div id="instructor-modal" class="white-popup mfp-hide">
	<h1>Assign Instructor</h1>
	<form action="" method="post" id="assign_instructor_form">
		<input type="hidden" name="client_user_id" value="">
    <input type="hidden" name="access_limited_time_type" value="">
    <input type="hidden" name="access_limited_time_value" value="">
    <input type="hidden" name="level_id" value="">
    <input type="hidden" name="price" value="">
		<?php 
         $instructors = get_users( array('role'=> 'fit_instructor') ); 
         foreach ($instructors as $instructor) { ?>
         	<div class="form-row">
         	<input id="instructor_<?php echo $instructor->ID; ?>" type="radio" value="<?php echo $instructor->ID; ?>" name="instructor_id"> 
         	<label for="instructor_<?php echo $instructor->ID; ?>"><?php echo $instructor->display_name; ?></label>
             </div>
         <?php } ?>
         <input type="submit" name="assign" value="Assign"> 
         <img src="<?php echo get_stylesheet_directory_uri(); ?>/assets/img/ajax-loader.gif" class="ajax-laoder" style="display: none;">
	</form>
	<p><a class="popup-modal-dismiss" href="#">Dismiss</a></p>
</div>

<script type="text/javascript">
	jQuery(function () {
	jQuery('.popup-modal').magnificPopup({
		type: 'inline',
		preloader: false,
		focus: '#username',
		modal: true
	});
	jQuery(document).on('click', '.popup-modal-dismiss', function (e) {
		e.preventDefault();
		jQuery.magnificPopup.close();
	});
	jQuery('.popup-modal').click(function(){

       var user_id = jQuery(this).data('user-id');
       var instructor_id = jQuery(this).data('instructor-id');
       var access_limited_time_type = jQuery(this).data('access_limited_time_type');
       var access_limited_time_value = jQuery(this).data('access_limited_time_value');
       var level_id = jQuery(this).data('level-id');
       var price = jQuery(this).data('price');

       jQuery('input[name=client_user_id]').val(user_id);
       jQuery('input[name=access_limited_time_type]').val(access_limited_time_type);
       jQuery('input[name=access_limited_time_value]').val(access_limited_time_value);
       jQuery('input[name=level_id]').val(level_id);
       jQuery('input[name=price]').val(price);

       if (instructor_id != '') {
         jQuery('#instructor_' + instructor_id).attr('checked', true);
       }
	});
});
</script>

