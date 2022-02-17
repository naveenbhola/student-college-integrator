<?php if($notification_type == 'email'):?>
<p>Dear <?php echo $first_name." ".$middle_name." ".$last_name;?></p>
<p>Your application for <?php echo $institute_name." ".$course_name;?> has been unsuccessful due to interruption on payment gateway.
This might occur due to various reasons like incorrect credit card/debit card information provided by you or insufficient funds into your account or some other similar reasons.</p>
<p>In case you feel there might be other reason, please get in touch with our support team and they will be happy to help you out.
Please don't forget to mention the following information when you contact us:</p>
<p><strong>Application number:</strong> <?php echo $application_number;?></p>
<p><strong>Order value:</strong> INR. <?php echo $order_value;?></p>
<p><strong>Date:</strong> <?php echo date('d/m/Y',strtotime($transaction_date));?></p>
<p><strong>Transaction ID:</strong> <?php echo $transaction_id;?></p>
<p><strong>Payment status:</strong><?php echo $payment_status;?></p>
<p><strong>Mobile Number:</strong><?php echo $ResultOfDetails['mobileNumber'];?></p>
<p>Please feel free to contact us on phone or email:</p>
<p>Call us at: 011-4046-9621 (between 09:30 AM to 06:30 PM, Monday to Friday)</p>
<p>Email us at: Help@shiksha.com</p>
<?php endif;?>
<?php if($notification_type == 'sms'):?>
Your online order towards the application fee for app no. <?php echo $application_number;?> could not be completed. Check your email or Shiksha.com for details.
<?php endif;?>
