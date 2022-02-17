<?php if($notification_type == 'email'):?>
<p>Dear <?php echo $first_name." ".$middle_name." ".$last_name;?></p>
<p>We just noticed that you were trying to make a payment towards application fee <?php echo $institute_name." ".$course_name;?> on Shiksha, but did not complete the order. Is there anything that we can do to help you complete this order?</p> 
<p>We would like to assure you that all online transactions on Shiksha are completely safe and 100% secure.  
Shiksha.com, a part of Naukri.com group, is a credible brand name that you can easily trust for your career planning.</p> 
<p>In case you need any assistance in completing your form submission, we would be happy to help you. 
Please feel free to contact us on phone or email:</p>
<p>Call us at: 011-4046-9621 (between 09:30 AM to 06:30 PM, Monday to Friday)</p>
<p>Email us at: <a href='mailto:Help@shiksha.com'>Help@shiksha.com</a></p>
<p>Please don't forget to mention the following information when you contact us:</p> 
<p><strong>Application number:</strong> <?php echo $application_number;?></p>
<p><strong>Order value:</strong> INR. <?php echo $order_value;?></p>
<p><strong>Date:</strong> <?php echo date('d/m/Y H:i:s',strtotime($transaction_date));?></p>
<p><strong>Transaction ID:</strong> <?php echo $transaction_id;?></p>
<p><strong>Payment status:</strong><?php echo $payment_status;?></p>
<p><strong>Mobile Number:</strong><?php echo $ResultOfDetails['mobileNumber'];?></p>
<p>Please feel free to contact us on phone or email.</p>
<?php endif;?>
<?php if($notification_type == 'sms'):?>
Your online order towards the application fee for app no. <?php echo $application_number;?> could not be completed. Check your email or Shiksha.com  dashboard for details.
<?php endif;?>
