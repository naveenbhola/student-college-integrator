<div width="600">
Dear <?php echo $name; ?>,<br/>
<?php $entityType = ($entityType == 'Blog Comment'|| $entityType == 'Event Comment')?'Comment':$entityType; ?>
<p>A Shiksha.com user has reported your <?php echo $entityType;?> posted on <a href="<?php echo $sectionURL;?>"><?php echo $section;?></a> as inappropriate for the following reason(s)</p>
<?php 
for($i=1;$i<=count($reasons[0]);$i++)
{
  $j = $i-1;
  echo "<br/>".$i.". ".$reasons[0][$j];
}
?>
<p>Our moderators would review these abuse reports and take appropriate action. If these reports are found vaild, your post would be removed. Please note that your post may also get deleted automatically if too many abuse reports are received.</p>
<p><b>Your <?php echo $entityType;?>:</b> <?php echo nl2br($entityText);?></p>
<p>We advise you to adhere to our community guidelines while making a post on  Shiksha.com to avoid its removal. We hope for your cooperation in keeping Shiksha.com clean and useful for community members. 
Please <a href="<?php echo $communityUrl; ?> ">click here</a> to refer to the Shiksha community guidelines.</p>
<br/>
Best Regards<br/>
Shiksha.com team
</div>
