Dear <?php echo $clientName?>,<br/><br/>
You have received a new consultant profile enquiry on studyabroad.shiksha.com<br>
Please contact following student at the earliest to get a good response:
<br><br>
Details of the Students : <br>
------------------------------------------------------------------<br>
<?php if($userName!=""){ ?>
Name : <?=$userName?><br>
<?php } ?>
<?php if($userEmail!=""){ ?>
Email : <?=$userEmail?><br>
<?php } ?>
<?php if($userMobile!=""){ ?>
Mobile : <?=$userMobile?><br>
<?php } ?>
<?php if($userInfo['courseId']){ ?>
Course of interest : <?=($userInfo['courseName'])?> <br>
Date of response : <?=(date('jS M Y',strtotime($userInfo['submitTime'])))?><br>
<?php } ?>
<?php if($userInfo['timeOfStart']){ ?>
Planning to start by : <?=($userInfo['timeOfStart'])?><br>
<?php } ?>
<?php if($userInfo['fieldOfInterest']){ ?>
Field of interest: <?=($userInfo['fieldOfInterest'])?><br>
<?php } ?>
<?php if($userInfo['residenceCity']){ ?>
Current location : <?=($userInfo['residenceCity'])?><br>
<?php } ?>
<?php if($userInfo['preferredDestination']){ ?>
Preferred destination : <?=($userInfo['preferredDestination'])?><br>
<?php } ?>
<?php if($userInfo['universityName']!=""){ ?>
Seeking assistance for <?=($userInfo['universityName'])?><br>
<?php } ?>
<br>------------------------------------------------------------------<br>
<br>
Regards,<br>
Shiksha Team<br>