<div width="600">
Dear <?php echo $mentioningUserName; ?>,<br/>

<p><?php echo $userName;?> has just mentioned you in 
<?php 
switch($typeOfEntity){
  case 'answer': echo "an answer"; break;
  case 'answerComment': echo "an answer comment"; break;
  case 'discussion': echo "a discussion"; break;
  case 'discussionComment': echo "a discussion comment"; break;
}
?>
.<br/>
<?php echo $userName;?>: <?php echo $text;?></p>
<p><a href="<?php echo $link;?>">View entire thread</a></p>

<br/>
Thanks,<br/>
Shiksha Team
</div>
