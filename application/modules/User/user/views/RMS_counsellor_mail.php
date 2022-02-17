<div style="text-align: left">
Following student has requested a call back on <?=(htmlentities($course_name))?> in <?=(htmlentities($univ_name))?>, <?=($univ_country)?> (course id:<?=($course_id)?>). Please call as soon as possible.<br><br>
 
Details of the student :<br><br>
------------------------------------------------------------------<br><br>

Name : <?=($user_name)?><br><br>

Email : <?=($user_email)?><br><br>

Mobile : <?=($user_mobile)?><br><br>

Response To: <?=(htmlentities($course_name))?><br><br>
<br><br>
<br><br>

Date of response : <?=($response_submit_date)?><br><br>

Last login on : <?=($last_login)?><br><br>

Signup date: <?=($registration_date)?><br><br>

 

Is in NDNC list : <?=($inNDNC)?><br><br>
<?php if($user_current_location != ''){ ?>
Current Location: <?=($user_current_location)?><br><br>
<?php } ?>
Countries of Interest: <?=($destination_countries)?><br><br>
<br><br><br><br>
 

<?php if ($exam_with_scores != "") { ?>
Exam given: <?=($exam_with_scores)?><br><br>
<?php } else { ?>
Exam given: No<br><br>

Passport: <?=($passport)?>
<?php } ?>
</div>