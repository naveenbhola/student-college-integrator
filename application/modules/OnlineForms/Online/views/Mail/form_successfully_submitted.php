<?php if($notification_type == 'email'):?>
<p>Dear <?php echo $first_name." ".$middle_name." ".$last_name;?></p>
<p>Your application for <?php echo $institute_name." ".$course_name;?> has been successfully submitted to the Institute.</p> 
<p>Following are the details of your application:</p>
<?php if($payment_mode == 'Online'):?>
<p><strong>Application number:</strong> <?php echo $application_number;?></p>
<p><strong>Order value:</strong> INR. <?php echo $order_value;?></p>
<p><strong>Date:</strong> <?php echo date('d/m/Y',strtotime($transaction_date));?></p>
<p><strong>Transaction ID:</strong> <?php echo $transaction_id;?></p>
<p><strong>Payment status:</strong> Payment successful</p>
<p><strong>Application status:</strong> Application submission successful</p>
<?php endif;if($payment_mode == 'Offline'):?>
<p><strong>Application number:</strong> <?php echo $application_number;?></p>
<p><strong>Form fee (pending):</strong> INR. <?php echo $order_value;?></p>
<p><strong>Date:</strong> <?php echo date('d/m/Y',strtotime($transaction_date));?></p>
<p><strong>Payment status:</strong> Draft awaited</p>
<p><strong>Application status:</strong> Application submission successful</p>
<p><strong>Please Note:</strong> Although your application has been submitted, it may not be processed further by the institute without the application fee, payable through draft. 
Please send the draft at the earliest and update the draft details in your application forms dashboard on Shiksha.com.</p>
<?php endif;?>
<p>Once again we thank you for choosing Shiksha.com for submitting your application. You can now start tracking the status of your application on your Shiksha online applications dashboard.
We wish you all the very best and hope you get selected to the Institute of your choice!</p>
<p>To check the status of your application, log into your dashboard at:</p> 
<p><a href="<?php echo $GLOBALS['SHIKSHA_ONLINE_FORMS_HOME'];?>"><?php echo $GLOBALS['SHIKSHA_ONLINE_FORMS_HOME'];?></a></p>
<p>In case you have any doubt or queries regarding your application, please feel free to contact us on phone or email:</p>
<p>Call us at: 011-4046-9621 (between 09:30 AM to 06:30 PM, Monday to Friday)</p>
<p>Email us at: Help@shiksha.com</p>
<?php endif;?>
<?php if($notification_type == 'sms'):?>
Your application form is successfully submitted. Your application reference number is <?php echo $application_number;?>. Please check your email for details.
<?php endif;?>
