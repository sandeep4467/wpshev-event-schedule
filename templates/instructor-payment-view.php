    <?php 

     $str = $_GET['month_year'];

     $arr = explode('-', $str);

    ?>

   <?php 

        $instructor_id = $_GET['instructor_id'];

        $month_year = $_GET['month_year'];

        $payments = wpshevManagePayment::get_payments($instructor_id, $month_year);
    ?>
<div class="back-to"><a href="<?php echo get_permalink(563); ?>"><i class="fa fa-chevron-left" aria-hidden="true"></i> Back to all clients</a></div>
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

    <span>Payment type: Paypal</span>

    <div class="payment-type">
        Paypal: <span class="paypal-email"><?php echo get_user_meta( $instructor_id, 'paypalemail', true ) ?></span>
    </div>

    <div class="instructor_percentage">

        <?php 

            $instructor_percentage = get_user_meta( $instructor_id, 'instructor_percentage' , true );

            if ( ! empty( $instructor_percentage ) ) {

                echo 'Your Commission: ' . $instructor_percentage . '%';

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

            <img class="ins-icon" src="<?php echo PLUGIN_DIR_URL; ?>core/assets/img/icon1.png" alt="">

            <div class="price">

             <?php echo wpshevInstructorInfo::current_month_payout($instructor_id, $arr[0], $arr[1]); ?>

            </div>

        </div>

        <div class="ins-block ins-block2">

            <span>Outstanding Payout <i class="fa fa-heart" aria-hidden="true"></i></span>

             <img class="ins-icon" src="<?php echo PLUGIN_DIR_URL; ?>core/assets/img/icon2.png" alt="">

            <div class="price">

                  <?php echo wpshevInstructorInfo::outstanding_payout($instructor_id); ?>

            </div>

        </div>



        <div class="ins-block ins-block3">

            <span>Total Amount Payout <i class="fa fa-heart" aria-hidden="true"></i></span>

           <img class="ins-icon" src="<?php echo PLUGIN_DIR_URL; ?>core/assets/img/icon3.png" alt="">

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

                echo "<li class='".$class."'><a href='?payment_view=true&instructor_id=".$instructor_id."&month_year=".$month_digit."-".$arr[1]."'>" .$month. " " . $arr[1] . "</a></li>";

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
        </div>

        <table id="data-list">

            <thead>

                <tr>
                    <th>Name</th>
                    <th>Purchased</th>
                    <th>Current Payment Cycle</th>
                    <th>Amount</th>
                    <th>Status</th>

                </tr>

            </thead>

            <tbody>

                <?php if ($payments) { ?>

                    <?php foreach ($payments as $payment) { ?>
                        <?php 
                        $user_info = get_userdata($payment->assigned_client_id); 
                        ?>
                        <tr id="row_<?php echo $payment->ID; ?>">

                            <td>

                         <?php 
                         if (!is_null($payment->name)) {
                             echo $payment->name;
                         }else{
                           echo $user_info->first_name .  " " . $user_info->last_name; 
                         }
                          
                         ?>  

                            </td>

                            <td>

                            <?php
                               if (!is_null($payment->purchased)) {
                                echo $payment->purchased;
                               }else{
                                    $plan = wpshevHelpers::get_user_membership_details($payment->assigned_client_id);
                                    echo $plan['label']; 
                               }
                            ?>

                            </td>

                            <td><?php echo $payment->bill_cycle; ?></td>

                            <td>$<?php echo $payment->monthly_payment; ?></td>

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


<style type="text/css">
.disable-btn {
    opacity: 0.5;
    pointer-events: none;
}
#add-payment-form ul{
    list-style: none;
    margin-top: 20px;
}
#add-payment-form h1 {
    margin: 0;
    font-size: 23px;
}
#add-payment-form input[type="text"], #add-payment-form select {
    border: 1px solid #ccc;
    width: 56%;
    padding: 10px 14px;
    border-radius: 3px;
    margin-left: 5%;
}
#add-payment-form select{
    width: 61%;
}
#add-payment-form li label {
    width: 30%;
    display: inline-block;
    color: #333;
}
#add-payment-form ul li {
    margin-bottom: 10px;
}
button#add-btn {
    background: #39b54a;
    border: 0;
    color: #fff;
    padding: 10px 20px;
    width: 160px;
    border-radius: 3px;
    font-size: 16px;
    text-transform: uppercase;
    cursor: pointer;
}
.white-popup {
  position: relative;
  background: #FFF;
  padding: 20px;
  width:auto;
  max-width: 90%;
   width: 600px;
  margin: 0 auto; 
  border-radius: 7px;
}
#add-payment {
    color: #fff;
    text-align: center;
    line-height: 35px;
}
.back-to {
    border-bottom: 1px solid #e9e9e9;
    padding: 10px 5px;
}
</style>