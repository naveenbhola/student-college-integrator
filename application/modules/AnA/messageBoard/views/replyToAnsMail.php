<p>Dear <?php echo $NameOfUser; ?>,</p>

<p><?php echo $commenterName;?> has posted a new comment to <?php echo $answerOwnerName;?> answer</p>
<p><b><?php if(isset($answerDisplayName) && $answerDisplayName!='') echo $answerDisplayName; else echo $answerOwnerName;?> answer:</b> <?php echo $msgTxt;?></p>
<p><b><?php echo $commenterName;?>'s comment:</b> <?php echo $commentTxt?>
<p>Please <a href="<?php echo $seoUrl;?>">click here</a> to visit the complete thread or to reply to <?php echo $commenterName;?>.</p>
<p>If you find the comment of <?php echo $commenterName;?> objectionable or inappropriate, 
please <a href="<?php echo $seoUrl;?>">click here to Report it as abuse</a> and help keep Shiksha.com clean.</p>
<p>If you like the answer of <?php echo $answerOwnerName;?>, <a href="<?php echo $ratingUrl;?>">click here to give it a thumbs up <img src="<?php echo SHIKSHA_HOME_URL;?>/public/images/digUp.gif" border="0" alt="Shiksha.com" height="20" width="20"/></a> and express your appreciation for the good work! </p>
<br/>
<p>Best regards,<br />Shiksha.com team</p>
