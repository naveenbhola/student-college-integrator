<p>Dear <?php echo $NameOfUser; ?>,</p>

<p>You have received a new answer for your question from <?php echo $answerOwnerName;?></p>
<p><b>Your question:</b> <?php echo nl2br($questionText);?></p>
<p><b><?php echo $answerOwnerName;?>'s answer:</b>  <?php echo $msgTxt?>

<p>If you like this answer <a href="<?php echo $ratingUrl;?>">click here to upvote  <img src="<?php echo SHIKSHA_HOME_URL;?>/public/images/digUp.gif" border="0" alt="Shiksha.com" height="20" width="20"/></a> or <a href="<?php echo $bestAnswerUrl;?>">select it as the Best Answer</a>.</p>
<p>If your question is not answered fully and you need more details then please <a href="<?php echo $seoUrl;?>">click here to visit your question</a> and reply to the answer to seek further explanation from <?php echo $answerOwnerName;?>.</p>
<p>However if you find it objectionable or inappropriate, please <a href="<?php echo $seoUrl;?>">click here to Report it as abuse</a> and help keep Shiksha.com clean.</p>
<br/>
<p>Best regards,<br />Shiksha.com team</p>
