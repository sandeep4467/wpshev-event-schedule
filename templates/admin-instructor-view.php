<?php if(!isset($_GET['instructor_id']) && !isset($_GET['month_year'])){?>
<?php $instructors = get_users( array('role'=> 'fit_instructor') ); ?>    
<div class="fitness-data-table">
    <div class="fitness-table-header">
        <h2>Instructor Panel 
         <span> | <?php echo (count($instructors) > 1) ? count($instructors) . ' Instructors' : count($instructors) . ' Instructor'?></span>
      </h2>
    </div>
    <table>
        <thead>
            <tr>
                <th>Name</th>
                <th>Number of Clients</th>
                <th>Current Month’s Payout</th>
                <th>Outstanding Payout</th>
                <th>Total Amount Payout</th>
                <th>Information</th>
            </tr>
        </thead>
        <tbody>
            <?php 
                $current_year = date('Y');
                $current_month = date('n');
            ?>
            <?php if(!empty($instructors)){ ?>
            <?php foreach ($instructors as $instructor) { ?>
            <?php $client_count = wpshevInstructorInfo::total_clients($instructor->ID);?>
            <tr>
                <td><?php echo $instructor->display_name; ?></td>
                <td><?php echo ($client_count <= 1 ) ? $client_count . ' Client' : $client_count . ' Clients'; ?> </td>

                <td><?php echo wpshevInstructorInfo::current_month_payout($instructor->ID, $current_month, $current_year); ?></td>

                <td><?php echo wpshevInstructorInfo::outstanding_payout($instructor->ID); ?></td>
                
                <td><?php echo wpshevInstructorInfo::life_time_earnings($instructor->ID); ?></td>
                <td>
                    <a href="?instructor_id=<?php echo $instructor->ID; ?>&month_year=<?php echo $current_month; ?>-<?php echo $current_year; ?>">View All</a>
                </td>
            </tr>
            <?php } ?>
            <?php } ?>
        </tbody>
    </table>
</div>
<?php }else{
    include_once(WPSHEV_ABSPATH.'templates/admin-instructor-view-single.php');
} ?>