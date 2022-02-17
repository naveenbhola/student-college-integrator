<p>Dear <?php echo $NameOfUser; ?>,</p>

<p><?php echo $answerOwnerName;?> posted a new answer to the <?php echo $questionOwnerName;?>'s question.</p>
<p><b><?php echo $questionOwnerName;?>'s question:</b> <?php echo nl2br($questionText);?></p>
<p><b><?php echo $answerOwnerName;?>'s answer:</b>  <?php echo $msgTxt?>

<p>If you like this answer <a href="<?php echo $ratingUrl;?>">click here to upvote  <img src="<?php echo SHIKSHA_HOME_URL;?>/public/images/digUp.gif" border="0" alt="Shiksha.com" height="20" width="20"/></a>.</p>
<p>However if you find it objectionable or inappropriate, please <a href="<?php echo $seoUrl;?>">click here to Report it as abuse</a> and help keep Shiksha.com clean.</p>
<br/>
<p>Best regards,<br />Shiksha.com team</p>
