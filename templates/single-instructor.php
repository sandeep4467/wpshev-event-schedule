<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
<?php 
if (isset($_GET['user_id'])) { ?> 
  <?php if ( !empty($_GET['user_id'])) { ?>


        <?php 
        $client_id = $_GET['user_id'];
        $instructor_id = get_current_user_id();
        $user = wp_get_current_user();
        if ( in_array( 'fit_instructor', (array) $user->roles ) ) { ?>
            <input type="hidden" name="client_id" value="<?php echo $client_id; ?>">
            <input type="hidden" name="instructor_id" value="<?php echo $instructor_id; ?>">
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
                <span class="status"></span><span class="user_name"><?php echo $client->first_name . ' ' . $client->last_name; ?></span>
                <div class="clear"></div>
                <p>Funny comparison. so would the proper antivirus not slow down the internet as much or do you...</p>
                </div>
                <div id="client-information">
                    <h2>Client’s Information</h2>
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
            </div>
            <div class="chat-right">
                <div class="user-status-right">
                <span class="user_name"><?php echo $client->first_name . ' ' . $client->last_name; ?></span><span class="status"></span>    
                </div>
                <div class="chat-window" id="chat-window">
                    <div class="user-chat msg_container_base" id="user-chat">

                    </div>
                    <button id="new-message">New Message</button>
                    <div class="chat-footer" style="display: none;">
                        <textarea id="chat-message" placeholder="Type your message here..."></textarea>
                      <div class="bottom-actions">
                        <button id="cancel">Cancel</button>
                        <button name="send" id="send" disabled="">New Message</button>
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
                      <input type="checkbox" checked="checked">
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
                jQuery('.chat-footer').fadeIn();
            });  
            jQuery('#cancel').click(function(){
                jQuery('.chat-footer').fadeOut();
                jQuery('#chat-message').val('');
                jQuery('#send').prop("disabled", true);
            });  

            jQuery('#chat-message').keyup(function(){
               if (jQuery('#chat-message').val() != '') {
                   jQuery('#send').prop("disabled", false);
               }else{
                   jQuery('#send').prop("disabled", true);
               };
            });
        </script>
  <?php }else{  ?>
    Please select client.
  <?php } ?>
<?php  } ?> 