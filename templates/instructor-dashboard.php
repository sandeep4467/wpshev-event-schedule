<?php 
    $current_year = date('Y');
    $current_month = date('n');
    $user_id = get_current_user_id(); 
?>
<div class="instructor-section">
    <div class="header-insturctor">
        <div class="ins-block ins-block1">
            <span>Current Month’s Payout <i class="fa fa-heart" aria-hidden="true"></i></span>
            <img class="ins-icon" src="<?php echo get_stylesheet_directory_uri(); ?>/assets/img/icon1.png" alt="">
            <div class="price"><?php echo wpshevInstructorInfo::current_month_payout($user_id, $current_month, $current_year); ?></div>
        </div>
        <div class="ins-block ins-block2">
            <span>Outstanding Payout <i class="fa fa-heart" aria-hidden="true"></i></span>
            <img class="ins-icon" src="<?php echo get_stylesheet_directory_uri(); ?>/assets/img/icon2.png" alt="">
            <div class="price"><?php echo wpshevInstructorInfo::outstanding_payout($user_id); ?></div>
        </div>

        <div class="ins-block ins-block3">
            <span>Total Amount Payout <i class="fa fa-heart" aria-hidden="true"></i></span>
            <img class="ins-icon" src="<?php echo get_stylesheet_directory_uri(); ?>/assets/img/icon3.png" alt="">
            <div class="price"><?php echo wpshevInstructorInfo::life_time_earnings($user_id); ?></div>
        </div>
    </div>
   <?php
    global $wpdb; 
    /*Get all Clients*/  
    $result = $wpdb->get_results("SELECT * FROM {$wpdb->prefix}instructor_data WHERE `instructor_id` = $user_id AND `status`= 'assigned'");
    if (!empty($result)) {
    ?>
    <div class="fitness-data-table">
        <div class="fitness-table-header">
            <?php 
             $count = count($result);
            ?>
            <h2>All Clients <span> | <?php echo ($count > 1 ) ? $count.' clients' : $count.' client'; ?> </span></h2>
        </div>
        <table>
            <thead>
                <tr>
                    <th>Conference</th>
                    <th>Purchase Information</th>
                    <th>Notifications</th>
                    <th>Client’s Page</th>
                </tr>
            </thead>
            <tbody>
                <?php 
                   foreach ($result as $row) {
                   $client_id = $row->assigned_client_id;  
                   $client_info = get_userdata($client_id);
                   $client_fullname = $client_info->first_name . ' '. $client_info->last_name;
                ?> 
                <tr>
                    <td>
                        <?php echo $client_fullname; ?>
                        <small>
                        <?php echo get_user_meta( $client_id, 'country', true ); ?>, <?php echo get_user_meta( $client_id, 'thestate', true ); ?> 
                        </small>
                    </td>

                    <td>
                     <?php
                     $plan =wpshevHelpers::get_user_membership_details($client_id);
                      echo $plan['label'];
                     ?> 
                     </td>
                    <td>New Client<small>Please engage and setup calendar.</small></td>
                    <td><a href="?single_page=true&user_id=<?php echo $client_id ?>">Enter Page</a></td>
                </tr>
                <?php } ?>
            </tbody>

        </table>
    </div>
    <?php }else{
        echo "<div class='warning'>No customer assigned to you yet!.</div>";
    } ?>
</div>

<style type="text/css">
    .header-insturctor {
        text-align: center;
        margin-bottom: 40px;
    }
    
    .ins-block {
        width: 310px;
        display: inline-block;
        margin-right: 20px;
        text-align: center;
        border: 1px solid #ededed;
        padding-top: 15px;
    }
    
    .ins-block .price {
        color: #fff;
        font-size: 31px;
        margin-top: 20px;
    }
    
    .ins-block.ins-block1 .price {
        background: #9b26af;
    }
    
    .ins-block.ins-block1 span {
        display: block;
        color: #9b26af;
        margin-bottom: 20px;
    }
    
    .ins-block.ins-block2 .price {
        background: #3e50b4;
    }
    
    .ins-block.ins-block2 span {
        display: block;
        color: #3e50b4;
        margin-bottom: 20px;
    }
    
    .ins-block.ins-block3 .price {
        background: #00a651;
    }
    
    .ins-block.ins-block3 span {
        display: block;
        color: #00a651;
        margin-bottom: 20px;
    }
    
    .fitness-data-table {
        border: 1px solid #ededed;
        border-radius: 5px;
    }
    
    .fitness-table-header {
        padding: 20px;
    }
    
    .fitness-table-header h2 span {
        font-size: 12px;
        color: #ccc;
        vertical-align: middle;
    }
    
    .fitness-data-table table {
        border-left: 0;
        border-radius: 0;
        border-color: #ededed;
        margin-bottom: 0;
    }
    
    .fitness-data-table table tr td,
    .fitness-data-table table tr th {
        border-color: #ededed;
        padding: 10px 20px;
    }
    
    .fitness-data-table table tr td:last-child,
    .fitness-data-table table tr th:last-child {
        border-right: 0;
    }
    
    .fitness-data-table thead {
        background: #f5f8fa;
    }
    
    .fitness-data-table tr td small {
        display: block;
        color: #b3b2b2;
    }
    
    .fitness-data-table tr td a {
        background: #34ac45;
        color: #fff;
        padding: 10px 20px;
        border-radius: 6px;
    }
</style>
