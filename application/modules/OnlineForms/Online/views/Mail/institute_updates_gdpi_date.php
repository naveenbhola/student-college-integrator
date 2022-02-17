<?php if($notification_type == 'email'):?>
<p>Dear <?php echo $first_name." ".$middle_name." ".$last_name;?></p>
<p>This is to inform you that <?php echo $institute_name;?> has confirmed your <?=$gdPiName?> date and Location towards the application number <?php echo $application_number;?> for admission in <?php echo $course_name;?>.</p> 
<p>Following is the schedule for <?=$gdPiName?></p>
<p>Date: <?php echo date('d/m/Y',strtotime($gdpi_date));?></p>
<p>Location: <?php echo $gdpi_city;?></p>
<p>Address: To be confirmed by the institute</p>
<p>Time Slot: To be confirmed by the institute</p>
<p><strong>Please Note:</strong> This is an initial confirmation of the <?=$gdPiName?> date and Location . 
<?php echo $institute_name;?> will send you a formal intimation confirming the address and time slot, separately.</p>  
<p>We wish you all the very best in your <?=$gdPiName?> and hope you get selected to the institute of your choice!</p>
<p>In case you have any doubt or query regarding this mail, we are happy to help you out. You can call us or write an email to us in case you need any help.</p>
<p>Call us at: 011-4046-9621 (between 09:30 AM to 06:30 PM, Monday to Friday)</p>
<p>Email us at: Help@shiksha.com</p>
<?php endif;?>
<?php if($notification_type == 'sms'):?>
Towards your app no. <?php echo $application_number;?>, <?=$gdPiName?> is scheduled on <?php echo date('d/m/Y',strtotime($gdpi_date));?> For details, check your email or log into Shiksha.com
<?php endif;?>
