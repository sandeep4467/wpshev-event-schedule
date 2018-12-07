<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
<?php 
global $wpdb;
$prefix = $wpdb->prefix;
$db_name = $wpdb->dbname;
$table_name = $prefix . 'instructor_data';

if (isset($_GET['job_id'])) {
 $wpdb->update( 
    $table_name, 
    array( 
        'is_new_job' => 0, 
    ), 
    array( 'ID' => $_GET['job_id'] ), 
    array( 
        '%d'  
    ), 
    array( '%d' ) 
);

} 
if (isset($_GET['user_id'])) { ?> 
  <?php if ( !empty($_GET['user_id'])) { ?>

        <?php 
        $client_id = $_GET['user_id'];
        $instructor_id = get_current_user_id();
        $job_id = $_GET['job_id'];
        $user = wp_get_current_user();
        if ( in_array( 'fit_instructor', (array) $user->roles ) ) { ?>
            <input type="hidden" name="client_id" value="<?php echo $client_id; ?>">
            <input type="hidden" name="instructor_id" value="<?php echo $instructor_id; ?>">
            <input type="hidden" name="job_id" value="<?php echo $job_id; ?>">
            <input type="hidden" name="by" value="instructor">
        <?php }else{

        } ?>
        <?php 
              $current_user = $instructor_id;
              
              $avatar = 'http://2.gravatar.com/avatar/b3a4bfdceaf39304c3660e8306f08f2c?s=96&d=mm&r=g';
              $user = get_user_by('id', $current_user);
              $attachment_id = get_user_meta($current_user, 'ihc_avatar', true);
              if (!empty($attachment_id)) {
                    $image_attributes = wp_get_attachment_image_src( $attachment_id );
                    $avatar = $image_attributes[0];
              }
        ?>
        <input type="hidden" name="user_name" value="<?php echo $user->first_name . ' ' . $user->last_name; ?>">
        <input type="hidden" name="user_pic" value="<?php echo $avatar; ?>">

        <div id="wpshev-loader">
            <img src="<?php echo plugins_url('/core/assets/img/ajax-loader.gif', WPSHEV_PLUGIN_FILE); ?>">
            <span>Hold on! calendar is loading...</span>
        </div>

            <?php 
            $notification_dates = wpshevHelpers::get_notification_date($_GET['job_id']);

            if ($notification_dates) {
                    foreach ($notification_dates as $notification_date) {

                     $notification_start_date = date('Y-m-d',(strtotime ( '-5 day' , strtotime ( $notification_date ) ) ));
                     $notification_end_date = date('Y-m-d',(strtotime ( '0 day' , strtotime ( $notification_date ) ) ));

                    //$paymentDate = strtotime("2019-01-03");
                    $paymentDate = strtotime(date('Y-m-d'));
                    $DateBegin = strtotime($notification_start_date);
                     
                    $DateEnd = strtotime($notification_end_date);
                      if($paymentDate >= $DateBegin && $paymentDate <= $DateEnd) {
                          echo "<style>body button.fc-next-button.fc-button.fc-state-default.fc-corner-left.fc-corner-right{display:block !important;}</style>";
                      } 
                    }
            }
            ?>

        <div id='calendar'>
        </div>
        <div id="chat-section">
            <?php 
            $client = get_user_by('id', $client_id);
            ?>
            <div class="chat-left">
                <?php $user_id = $client_id;?>
                <div class="inner-left-chat">
                <figure class="user-img">
                    <?php 
                      $avatar = 'http://2.gravatar.com/avatar/b3a4bfdceaf39304c3660e8306f08f2c?s=96&d=mm&r=g';
                      $attachment_id = get_user_meta($user_id, 'ihc_avatar', true);
                      if (!empty($attachment_id)) {
                        $image_attributes = wp_get_attachment_image_src( $attachment_id );
                        $avatar = $image_attributes[0];
                      }
                    ?>
                    <img src="<?php echo $avatar; ?>">
                </figure>
                <span class="status" title="Offline"></span><span class="user_name"><?php echo $client->first_name . ' ' . $client->last_name; ?></span>
                <div class="clear"></div>
                </div>
                <div id="client-information">


				<div id="wpshev_tabs" class="wpshev_tabs">
				  <ul>
				    <li><a href="#tabs-1"><i class="fa fa-user-o" aria-hidden="true"></i> Client’s Information</a></li>
				    <li><a href="#tabs-2"><i class="fa fa-sticky-note-o" aria-hidden="true"></i> My Notes</a></li>
				  </ul>
				  <div id="tabs-1">
                    <div class="client-information-wrap">
                     <div class="inline-info">
                        <div class="info">
                            <?php echo $client->first_name . ' ' . $client->last_name; ?>
                            <label>Name</label>
                        </div>
                        <div class="info">
                            <?php echo get_user_meta( $user_id, 'thestate', true ) .', ' .get_user_meta( $user_id, 'country', true ); 
                            ?>
                            <label>Country</label>
                        </div>
                        <div class="info">
                            <?php echo (get_user_meta( $user_id, 'age', true )) ? get_user_meta( $user_id, 'age', true ) : 'N/A'; 
                             ?>
                            <label>Age</label>
                        </div>
                     </div>
                     <div class="clear"></div>
                        <div class="info">
                             <?php echo (get_user_meta( $user_id, 'allergytofood', true )) ? get_user_meta( $user_id, 'allergytofood', true ) : 'N/A'; 
                             ?>
                            <label>Allergy to foods</label>
                        </div>                     
                        <div class="info">
                            <?php echo (get_user_meta( $user_id, 'kind_of_food', true )) ? get_user_meta( $user_id, 'kind_of_food', true ) : 'N/A'; 
                            ?>
                            <label>Personal preference of food to avoid</label>
                        </div>
                        <div class="info">
                           <?php echo (get_user_meta( $user_id, 'work_doing', true )) ? get_user_meta( $user_id, 'work_doing', true ) : 'N/A'; 
                            ?>
                            <label>Work schedule and current job title</label>
                        </div>
    
                        <div class="info">
                            <?php echo (get_user_meta( $user_id, 'current_weight', true )) ? get_user_meta( $user_id, 'current_weight', true ) : 'N/A'; 
                            ?>
                            <label>Height & Weight</label>
                        </div>
                        <div class="info">
                            <?php echo (get_user_meta( $user_id, 'want_to_achieve', true )) ? get_user_meta( $user_id, 'want_to_achieve', true ) : 'N/A'; 
                            ?>
                            <label>Goals of weight, body type or particular toning of body parts.</label>
                        </div>
                    </div>
				  </div>
				  <div id="tabs-2">
				    <div class="add-notes">
				    	<div id="note-container">

				    	</div>
				    	<textarea id="note" placeholder="Add note..."></textarea>
				    	<button id="send-note"><i class="fa fa-plus" aria-hidden="true"></i> Add</button>
				    </div>
				  </div>
				</div>


                </div>
            </div>
            <div class="chat-right">
                <div class="user-status-right">
                <span class="user_name"><?php echo $client->first_name . ' ' . $client->last_name; ?></span><span class="status" title="Offline"></span>  
                <button id="new-message">New Message</button>  
                </div>
                <div class="chat-window" id="chat-window">
                    <div class="user-chat msg_container_base" id="user-chat">

                    </div>
                    
                    <div class="chat-footer" style="display: none;">
                        <textarea id="chat-message" placeholder="Type your message here..."></textarea>
                      <div class="bottom-actions">
                        <button id="cancel">Cancel</button>
                        <button name="send" id="send" disabled="">Send Message</button>
                      </div>
                    </div>
                </div>
            </div>
            <div class="clear"></div>
        </div>

        <!-- dialog itself, mfp-hide class is required to make dialog hidden -->
        <div class="wpshev-popup-outer">
        <div id="wpshev-custom-popup" class="wpshev-custom-popup">
            <h1>Add New Event</h1>
             <button class="ev-popup-close"><i class="fa fa-times" aria-hidden="true"></i></button>
            <form id="ev-add-event">
                <input type="hidden" name="client_id" value="<?php echo $client_id; ?>">
                <div class="ev-form-row">
                <input type="text" name="event_title" placeholder="Event Title" id="event_title">
                <div class="toe-wrapper">
                <span class="toe-label">Type of Event</span>
                <select name="type-of-event" class="meal-active">
                    <option value="meal" class="meal">Meal</option>
                    <option value="rest" class="rest">Rest</option>
                    <option value="workout" class="workout">Workout</option>
                </select>
                   </div>
                </div>
                <div class="ev-form-row">
                    <div class="ev-form-inline">
                    <div id="mdp-demo"></div>
                    <input id="event-start-date" type="text" placeholder="Select Dates" name="event-start-date" class="datepicker" readonly="">
                    </div>
                 <div class="ev-form-inline custom-checkbox">
                    <input id="event-start-time" type="text" placeholder="Select Time" name="event-start-time" class="datepicker" readonly="">
                    <label class="checkbox-container">All Day Event
                      <input type="checkbox" name="all-day-event" id="all-day-event">
                      <span class="checkmark"></span>
                    </label>
                 </div>
                </div>
                <div class="clear"></div>
                 <div class="ev-form-row wp-editor">
                    <label>Add Even Content Information</label>
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
                <input type="submit" name="submit" value="Add Event In Calendar">
               <div class="clear"></div>
            </form>
        </div>
         </div>




        <div class="wpshev-custom-popup-overlay"></div>
        <?php 

$html = "<input type='hidden' id='show_feedback_popup' value='true'>";   

foreach ($notification_dates as $notification_date) {

    //$today = strtotime('2018-11-27');
    $today = strtotime(date('Y-m-d'));
    $noti_date = strtotime($notification_date);

    if ($today == $noti_date) {
        echo $html;
    }
}
        ?>
        <script type="text/javascript">
            jQuery('.meal-active').on('change', function() {
                        var sval = $(this).find(":selected").val();
                        if ( sval == 'meal') {
                          jQuery(this).removeClass();
                          jQuery(this).addClass('meal-active');
                        }
                        if ( sval == 'rest') {
                          jQuery(this).removeClass();
                          jQuery(this).addClass('rest-active');
                        }
                        if ( sval == 'workout') {
                          jQuery(this).removeClass();
                          jQuery(this).addClass('workout-active');
                        }
            }); 
            jQuery('#new-message').click(function(){
                jQuery(this).hide();
                jQuery('.chat-footer').fadeIn();
                jQuery('#user-chat').css('max-height' , '371px');
                jQuery("html, body").animate({ scrollTop: $(document).height() }, "slow");
                jQuery("#chat-message").focus();
            });  
            jQuery('#cancel').click(function(){
                jQuery('.chat-footer').fadeOut();
                jQuery('#chat-message').val('');
                jQuery('#send').prop("disabled", true);
                jQuery('#user-chat').css('max-height' , '477px');
            });  

            jQuery('#chat-message').keyup(function(){
               if (jQuery('#chat-message').val() != '') {
                   jQuery('#send').prop("disabled", false);
               }else{
                   jQuery('#send').prop("disabled", true);
               };
            });
            jQuery("#all-day-event").change(function() {
                if(this.checked) {
                    jQuery('#event-start-time').val('');
                }
           });
           jQuery("#event-start-time").blur(function() {
                  jQuery("#all-day-event"). prop("checked", false);
           });

            /*Set own status*/ 
            var data = {
                action: 'user_status',
                id: <?php echo get_current_user_id(); ?> 
            };
            jQuery.ajax({
                type: 'POST',
                dataType: "json",
                url: '<?php echo admin_url('admin-ajax.php'); ?>',
                data: data
            });

            /*Check if client is online*/
            function check_user_online(){
                var data = {
                    action: 'check_user_status',
                    id: <?php echo $client_id; ?>
                }
                jQuery.ajax({
                    type: 'POST',
                    dataType: "json",
                    url: '<?php echo admin_url('admin-ajax.php'); ?>',
                    data: data,
                    success: function(response) {
                      if (response.status == 1) {
                        jQuery('#chat-section .status').addClass('online').prop('title', 'Online');
                      }else{
                        jQuery('#chat-section .status').removeClass('online').prop('title', 'Offline');; 
                      }
                    }
                });        
            }
            check_user_online();

            window.setInterval(function(){
                check_user_online();
            }, 5000);

            /*Delete own staus on window close*/
            window.onbeforeunload = function(){
                            var data = {
                                action: 'delete_user_status',
                                id: <?php echo get_current_user_id(); ?> 
                            };
                            jQuery.ajax({
                                type: 'POST',
                                dataType: "json",
                                url: '<?php echo admin_url('admin-ajax.php'); ?>',
                                data: data
                            });
            }     
        </script>
  <?php }else{  ?>
    Please select client.
  <?php } ?>
<?php  } ?> 

<style type="text/css">
    .toe-wrapper select option {
    color: #000;
}
</style>