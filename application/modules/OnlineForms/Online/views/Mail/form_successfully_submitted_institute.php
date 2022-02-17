<?php if($notification_type == 'email'):?>
<p>Dear <?php echo $institute_name;?> Admin,</p>
<p>This is to inform you that <?php echo $first_name." ".$middle_name." ".$last_name;?>  has just submitted a form towards <?php echo $course_name;?></p>
<p>Following are the details of the submission.</p> 
<p><strong>Name of applicant:</strong> <?php echo $first_name." ".$middle_name." ".$last_name;?></p> 
<p><strong>Application Number:</strong> <?php echo $application_number;?></p>
<?php if($payment_mode == 'Online'):?>
<p><strong>Payment Method:</strong> Online</p>
<p><strong>Payment Status:</strong> Received</p>
<p><strong>Transaction Number:</strong> <?php echo $transaction_id;?></p>
<?php endif;if($payment_mode == 'Offline'):?>
<p><strong>Payment Method:</strong> Demand Draft</p>
<p><strong>Payment Status:</strong> In process</p>
<?php endif;?>
<p>For more details and view the form, please log into your Shiksha.com dashboard.</p>
<p>In case you have any doubt or query regarding this mail, we are happy to help you out. You can call us or write an email to us in case you need any help.</p>
<p>Call us at: 011-4046-9621 (between 09:30 AM to 06:30 PM, Monday to Friday)</p>
<p>Email us at: Help@shiksha.com</p>
<?php endif;?>
<?php if($notification_type == 'sms'):?>
An applicant has submitted a new application form with application Number <?php echo $application_number;?> Check your email or Shiksha Dashboard for details.
<?php endif;?>
