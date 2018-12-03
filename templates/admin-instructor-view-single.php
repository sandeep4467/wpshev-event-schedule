    <?php 
     $str = $_GET['month_year'];
     $arr = explode('-', $str);
    ?>
   <?php 
        $instructor_id = $_GET['instructor_id'];
        $month_year = $_GET['month_year'];
        $payments = wpshevManagePayment::get_payments($instructor_id, $month_year);
    ?>
<div class="instructor-profile">
<div class="profile-left">
    <div class="instructor-info">
    <h3>Instructor</h3>
    <?php $user_info = get_userdata($instructor_id); ?>
    <span class="instructor-name"><?php echo $user_info->first_name . ' '. $user_info->last_name; ?></span>
    </div>
    <figure class="instructor-pic">
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
    <div class="clear">
        
    </div>
    <span>Payment type: <a target="_blank" id="edit-payment" href="<?php echo get_edit_user_link( $instructor_id ); ?>">Edit</a></span>
    <div class="payment-type">
        Paypal
        <span class="paypal-email"><?php echo get_user_meta( $instructor_id, 'paypalemail', true ) ?></span>
    </div>
    <div class="instructor_percentage">
        <?php 
            $instructor_percentage = get_user_meta( $instructor_id, 'instructor_percentage' , true );
            if ( ! empty( $instructor_percentage ) ) {
                echo 'Instructor Commission: ' . $instructor_percentage . '%' . '<a href="'.get_edit_user_link( $instructor_id ).'"> Edit</a>';
            }else{
                echo "You have not defined any percentage for this instructor.";
            }
        ?>
    </div>
</div>

<div class="profile-right">
    <div class="header-insturctor">
        <div class="ins-block ins-block1">
            <span>Current Month’s Payout <i class="fa fa-heart" aria-hidden="true"></i></span>
            <img class="ins-icon" src="http://35.237.229.167/fitness/wp-content/themes/twentysixteen/assets/img/icon1.png" alt="">
            <div class="price">
             <?php echo wpshevInstructorInfo::current_month_payout($instructor_id, $arr[0], $arr[1]); ?>
            </div>
        </div>
        <div class="ins-block ins-block2">
            <span>Outstanding Payout <i class="fa fa-heart" aria-hidden="true"></i></span>
            <img class="ins-icon" src="http://35.237.229.167/fitness/wp-content/themes/twentysixteen/assets/img/icon2.png" alt="">
            <div class="price">
                  <?php echo wpshevInstructorInfo::outstanding_payout($instructor_id); ?>
            </div>
        </div>

        <div class="ins-block ins-block3">
            <span>Total Amount Payout <i class="fa fa-heart" aria-hidden="true"></i></span>
            <img class="ins-icon" src="http://35.237.229.167/fitness/wp-content/themes/twentysixteen/assets/img/icon3.png" alt="">
            <div class="price"><?php echo wpshevInstructorInfo::life_time_earnings($instructor_id); ?></div>
        </div>
    </div>
</div>
</div>
<div class="clear"></div>
<div class="payment-list">
    <div class="month-view">
         <a id="prev-year" href="?instructor_id=<?php echo $instructor_id; ?>&month_year=01-<?php echo $arr[1] - 1; ?>"> < <?php echo $arr[1] - 1 ?>  </a>
        <a id="next-year" href="?instructor_id=<?php echo $instructor_id; ?>&month_year=01-<?php echo $arr[1] + 1; ?>"> <?php echo $arr[1] + 1 ?> > </a>
        <div class="clear"></div>
        <?php 
            $months = array(
                '01'=> 'January',
                '02'=> 'February',
                '03'=> 'March',
                '04'=> 'April',
                '05'=> 'May',
                '06'=> 'June',
                '07'=> 'July ',
                '08'=> 'August',
                '09'=> 'September',
                '10'=> 'October',
                '11'=> 'November',
                '12'=> 'December',
            );


           

            echo " <ul>";
            foreach ($months as $month_digit => $month) {
                $class = '';
                echo "<li class='".$class."'><a href='?instructor_id=".$instructor_id."&month_year=".$month_digit."-".$arr[1]."'>" .$month. " " . $arr[1] . "</a></li>";
            }
            echo "</ul>";
            ?>

    </div>

    <div class="payment-data">
        <div class="data-header">
            <h2><?php echo $months[$arr[0]]; ?> <?php echo $arr[1]; ?> <span> |
            <?php if($payments){ ?>
                <?php echo (count($payments) > 1 ) ? count($payments) . ' Payments' : count($payments) . ' Payment';?>
            <?php }else{ ?>
                 0 Payment
            <?php } ?> 
       
            </span></h2>
            <button id="mark-paid">MARK AS PAID</button>
            <!-- <button id="add-payment">Add New Payment</button> -->
        </div>

        <table id="data-list">
            <thead>
                <tr>
                    <th width="7%"></th>
                    <th>Name</th>
                    <th>Purchased</th>
                    <th>Current Payment Cycle</th>
                    <th>Amount</th>
                    <th>Action</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                <?php if ($payments) { ?>
                    <?php foreach ($payments as $payment) { ?>
                        <?php $user_info = get_userdata($payment->assigned_client_id); ?>
                        <tr id="row_<?php echo $payment->ID; ?>">
                            <td width="7%">
                                <input type="checkbox" name="update_payment_status" value="<?php echo $payment->ID; ?>">
                            </td>
                            <td>
                                <?php 
                                 echo $user_info->first_name .  " " . $user_info->last_name; 
                                ?>  
                            </td>
                            <td>
                            <?php
                                $plan = wpshevHelpers::get_user_membership_details($payment->assigned_client_id);
                                echo $plan['label'];
                            ?>
                            </td>
                            <td><?php echo $payment->bill_cycle; ?></td>
                            <td>$<?php echo $payment->monthly_payment; ?></td>
                            <td><button class="delete" id="delete_<?php echo $payment->ID; ?>" data-id="<?php echo $payment->ID; ?>">Delete</button></td>
                            <td>
                                <?php 
                                 if ($payment->status == 'paid') {
                                    echo "<span class='paid'>Paid</span>";
                                 }else{
                                    echo "<span class='unpaid'>Unpaid</span>";
                                 }
                                ?>
                            </td>
                        </tr>
                    <?php } ?>
                <?php } ?>

            </tbody>
        </table>
    </div>
</div>

