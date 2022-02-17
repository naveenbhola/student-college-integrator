<div width="600">
<b>Congratulations</b> <?php echo $username; ?>!
<?php 
switch($level){
  case 'Trainee': echo " You have reached the <b>Trainee</b> level.<br/><p>As you keep contributing on <a href='".$link."'>Shiksha Caf&#233;</a>, you will soon reach the Advisor level and secure a position in our Panel of Experts. Keep up the good work!</p>";break;
  case 'Advisor': echo " The community is proud to recognize you as an Advisor.<br/><p>The community has awarded you a \"Star\" and a position in our <a href='".$link."'>Panel of Experts</a>. We applaud your achievement and hope that many more students would benefit from your expert guidance.</p>";break;
  case 'Senior Advisor': echo " The community is proud to recognize you as a Senior Advisor.<br/><p>The community has awarded you a \"Blue Star\" and a higher rank in our <a href='".$link."'>Panel of Experts</a>. We appreciate your invaluable contribution to this community and look forward to more of it. </p>";break;
  case 'Lead Advisor': echo " The community is proud to recognize you as a Lead Advisor.<br/><p>The community has awarded you a \"Pink Star\" and a senior position in our <a href='".$link."'>Panel of Experts</a>. We appreciate your invaluable contribution to this community and look forward to more of it. </p>";break;
  case 'Principal Advisor': echo " The community is proud to recognize you as a Principal Advisor.<br/><p>The community has awarded you a \"Green Star\" and a leading position in our <a href='".$link."'>Panel of Experts</a>. We appreciate your invaluable contribution to this community and look forward to more of it. </p>";break;
  case 'Chief Advisor': echo " The community is proud to hail you as our Chief Advisor .<br/><p>The community has awarded you the precious \"Orange Star\" and the top most place in our <a href='".$link."'>Panel of Experts</a>. We congratulate you on this remarkable achievement and thank you for your generous contribution in making this such a successful community. We look forward to even greater support from you so that many more students can benefit from our efforts.</p>";break;
}
?>
<br/>
Best Regards<br/>
Shiksha.com team
</div>
