<?php if($notification_type == 'email'):?>
<p>Dear <?php echo $first_name." ".$middle_name." ".$last_name;?></p>
<p>It seems that you started filling up application form for <?php echo $institute_name." - ".$course_name;?>, but haven't completed it yet. Be advised that the last date of final submissions is <?php echo date('d/m/Y',strtotime($last_date));?> which is just <?php echo $no_of_days;?> day<?php if(intval($no_of_days)>1) echo 's'; ?> away from today. Please complete your application submission before this date in order to be considered for admission by the institute.</p>
<p><a href="<?php echo $applicationLink;?>">Click Here to complete your application submission</a></p>
<p><?php echo $referralText;?></p>
<p>If you are facing problem understanding the online application process or having difficulty filling up the online forms, please let us know. We will be happy to help you in filling up the application form of your choice. You can call us or write an email to us in case you need any help.</p>
<p>Call us at: 011-4046-9621 (between 09:30 AM to 06:30 PM, Monday to Friday)</p>
<p>Email us at: Help@shiksha.com</p>
<?php endif;?>
<?php if($notification_type == 'sms'):?>
Dear Subscriber, last date for submission of application number <?php echo $application_number;?> due in <?php echo $no_of_days;?> day<?php if(intval($no_of_days)>1) echo 's'; ?>. Please complete your application on Shiksha.com
<?php endif;?>
