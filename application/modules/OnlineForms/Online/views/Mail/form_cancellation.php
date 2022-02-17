<?php if($notification_type == 'email'):?>
<p>Dear <?php echo $institute_name;?> Admin,</p>
<p>This is to inform you that <?php echo $first_name." ".$middle_name." ".$last_name;?>  has applied for cancellation of his application number <?php echo $application_number;?> for admission in <?php echo $course_name;?>.</p>
<p>For details, log into your online applications dashboard on Shiksha.com.</p>
<p>In case you have any doubt or query regarding this mail, we are happy to help you out. You can call us or write an email to us in case you need any help.</p>
<p>Call us at: 011-4046-9621 (between 09:30 AM to 06:30 PM, Monday to Friday)</p>
<p>Email us at: Help@shiksha.com</p>
<?php endif;?>
<?php if($notification_type == 'sms'):?>
An applicant has applied for cancellation of application for app no. <?php echo $application_number;?> For details, check your email or log into Shiksha.com
<?php endif;?>
