<p>Dear <?php echo $NameOfUser; ?>,</p>

<p><?php echo $answerOwnerName;?> has posted a new comment to <?php if($owner) echo "your"; else echo $questionOwnerName."'s"; echo " ".$entityType;?>.</p>
<p><b><?php if($owner) echo "Your"; else echo $questionOwnerName."'s"; echo " ".$entityType;?>:</b> <?php echo nl2br($questionText);?></p>
<p><b><?php echo $answerOwnerName;?>'s comment:</b>  <?php echo $msgTxt?>

<p>Please <a href="<?php echo $seoUrl; ?>">click here</a> to visit the complete thread or to reply to <?php echo $answerOwnerName?>.</p>
<p>If you find it objectionable or inappropriate, please <a href="<?php echo $seoUrl;?>">click here to Report it as abuse</a> and help keep Shiksha.com clean.</p>
<br/>
<p>Best regards,<br />Shiksha.com team</p>