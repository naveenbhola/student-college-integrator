 <p>Hi <?php echo $NameOfUser; ?>,</p>

<p><?php echo $questionOwnerName;?> has selected <?php echo $answerOwnerName;?>'s answer as the best answer for their question.</p>
<p><b><?php echo $questionOwnerName;?>'s question:</b> <?php echo nl2br($questionText);?></p>
<p><b><?php echo $answerOwnerName;?>'s answer:</b> <?php echo $msgTxt;?></p>

<p>If you also like the answer of <?php echo $answerOwnerName;?>, <a href="<?php echo $ratingUrl;?>">click here to give it a thumbs up <img src="<?php echo SHIKSHA_HOME_URL;?>/public/images/digUp.gif" border="0" alt="Shiksha.com" height="20" width="20"/></a> and express your appreciation for the good work! 
<p>Please <a href="<?php echo $seoUrl;?>">click here</a> to see and rate all other answers to <?php echo $questionOwnerName;?>'s question.</p>
<p>You are receiving this mail because you have either answerd <?php echo $questionOwnerName;?>'s question or commented on any answer to his question.</p>
<p>Best regards,<br />Shiksha.com team</p>
