     

        <?php 
        $instructor_id = get_current_user_id();
        global $wpdb;
        $query = "SELECT * FROM {$wpdb->prefix}instructor_data WHERE `instructor_id` = $instructor_id  AND `status` = 'assigned'";
        $results = $wpdb->get_row( $query, OBJECT );
        $client_id = $results->assigned_client_id;

        ?>
        <?php 

        $user = wp_get_current_user();

        if ( in_array( 'fit_instructor', (array) $user->roles ) ) { ?>

            <input type="hidden" name="client_id" value="<?php echo $client_id; ?>">

            <input type="hidden" name="instructor_id" value="<?php echo $instructor_id; ?>">

            <input type="hidden" name="by" value="client">

        <?php } ?>



        <?php 

              $current_user = get_current_user_id();

              

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

        <div class="welcome-message">

         <div class="message-col">
           <h2>Please click on proceed button.</h2>
         </div>
        <div class="right-col">
            <a href="javascript:void(0)" class="proceed-to-start" id="proceed-to-start-by-instructor">Proceed to start</a>
        </div>
        </div>
        <div class="clear"></div>
        <div id="chat-section" style="margin-top: 55px;">

            <?php 

            $instructor = get_user_by('id', $instructor_id);

            $client = get_user_by('id', $client_id);

            ?>

            <div class="chat-left">

                <div class="inner-left-chat">

                <figure class="user-img">

                    <?php 

                      $avatar = 'http://2.gravatar.com/avatar/b3a4bfdceaf39304c3660e8306f08f2c?s=96&d=mm&r=g';

                      $attachment_id = get_user_meta($instructor_id, 'ihc_avatar', true);

                      if (!empty($attachment_id)) {

                        $image_attributes = wp_get_attachment_image_src( $attachment_id );

                        $avatar = $image_attributes[0];

                      }

                    ?>

                    <img src="<?php echo $avatar; ?>">

                </figure>

                <span class="status" title="Offline"></span><span class="user_name"><?php echo $instructor->first_name . ' ' . $instructor->last_name; ?> <small>Fitness Coach</small></span>

                <div class="clear"></div>

                </div>

                <div id="client-information">

                    <h2>My Information <a class="info-edit" href="iump-account-page/?ihc_ap_menu=profile">Edit Information</a></h2>

                    <div class="client-information-wrap">

                     <div class="inline-info">

                        <div class="info">

                            <?php echo $client->first_name . ' ' . $client->last_name; ?>

                            <label>Name</label>

                        </div>

                        <div class="info">

                            <?php echo get_user_meta( $client_id, 'thestate', true ) .', ' .get_user_meta( $client_id, 'country', true ); 

                            ?>

                            <label>Country</label>

                        </div>

                        <div class="info">

                            <?php echo (get_user_meta( $client_id, 'age', true )) ? get_user_meta( $client_id, 'age', true ) : 'N/A'; 

                             ?>

                            <label>Age</label>

                        </div>

                     </div>

                     <div class="clear"></div>

                        <div class="info">

                             <?php echo (get_user_meta( $client_id, 'allergytofood', true )) ? get_user_meta( $client_id, 'allergytofood', true ) : 'N/A'; 

                             ?>

                            <label>Allergy to foods</label>

                        </div>                     

                        <div class="info">

                            <?php echo (get_user_meta( $client_id, 'kind_of_food', true )) ? get_user_meta( $client_id, 'kind_of_food', true ) : 'N/A'; 

                            ?>

                            <label>Personal preference of food to avoid</label>

                        </div>

                        <div class="info">

                           <?php echo (get_user_meta( $client_id, 'work_doing', true )) ? get_user_meta( $client_id, 'work_doing', true ) : 'N/A'; 

                            ?>

                            <label>Work schedule and current job title</label>

                        </div>

    

                        <div class="info">

                            <?php echo (get_user_meta( $client_id, 'current_weight', true )) ? get_user_meta( $client_id, 'current_weight', true ) : 'N/A'; 

                            ?>

                            <label>Height & Weight</label>

                        </div>

                        <div class="info">

                            <?php echo (get_user_meta( $client_id, 'want_to_achieve', true )) ? get_user_meta( $client_id, 'want_to_achieve', true ) : 'N/A'; 

                            ?>

                            <label>Goals of weight, body type or particular toning of body parts.</label>

                        </div>

                    </div>

                </div>

            </div>

            <div class="chat-right">

                <div class="user-status-right">

                <span class="user_name"><?php echo $instructor->first_name . ' ' . $instructor->last_name; ?></span><span class="status" title="Offline"></span>    
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

            });  

            jQuery('#cancel').click(function(){

                jQuery('.chat-footer').fadeOut();

                jQuery('#chat-message').val('');

                jQuery('#send').prop("disabled", true);

                jQuery('#user-chat').css('max-height' , '477px');
                jQuery('#new-message').show();

            });  





            jQuery('#chat-message').keyup(function(){

               if (jQuery('#chat-message').val() != '') {

                   jQuery('#send').prop("disabled", false);

               }else{

                   jQuery('#send').prop("disabled", true);

               };

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



            /*Check if instructior is online*/

            function check_user_online(){

                var data = {

                    action: 'check_user_status',

                    id: <?php echo $instructor_id; ?>

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

            jQuery('body').on('click','#new-message',function(){
                jQuery('html, body').animate({
                    scrollTop: $("#chat-message").offset().top - 150
                }, 2000);
            });
        </script>



        <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">

