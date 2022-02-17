<?php if($notification_type == 'email'):?>
<p>Dear <?php echo $first_name." ".$middle_name." ".$last_name;?></p>
<p>This is to inform you that <?php echo $institute_name;?> has asked for your recent photographs towards the application number <?php echo $application_number;?> for admission in <?php echo $course_name;?>. 
Institute might request your photographs due to various reasons for example the photographs provided by you in the online application form might not be of the format required by the institute, they might not be clearly visible or some other reason.</p>
<p>Following are specifications of the photographs that are required by <?php echo $institute_name;?>:</p>
<p><?php echo $photograph_spec;?></p>
<p>These photographs are to be sent through email to the institute at <?php echo $inst_email_addrs;?>
<p><strong style="text-decoration: underline;">Note:</strong> Always mention your full name and application number while communicating with the institute directly. 
Your application number is <?php echo $application_number;?>.</p>
<p>In case you have any doubt or query regarding this mail, we are happy to help you out. You can call us or write an email to us in case you need any help.</p>
<p>Call us at: 011-4046-9621 (between 09:30 AM to 06:30 PM, Monday to Friday)</p>
<p>Email us at: Help@shiksha.com</p>
<?php endif;?>
<?php if($notification_type == 'sms'):?>
Dear Subscriber, Institute has requested your recent photos towards app no. <?php echo $application_number;?>. Check your email or log into your online app on Shiksha.com
<?php endif;?>
