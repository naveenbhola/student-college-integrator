<p>Hi <?php echo $NameOfUser; ?>,</p>

<p>Congratulations! Your answer has been selected as the best answer by <?php if($byuser=='automated') echo 'Shiksha Cafe community'; else echo $questionOwnerName;?> and you have been awarded 30 Shiksha points for same.</p>

<p><b><?php echo $questionOwnerName;?>'s question:</b> <?php echo nl2br($questionText);?></p>
<p><b>Your answer:</b> <?php echo $msgTxt;?></p>

<p>Please <a href="<?php echo $seoUrl;?>">click here</a> to see and rate all other answers to <?php echo $questionOwnerName;?>'s question.
<br/><p>We extremely value the contribution and efforts you have taken to help students on <a href="<?php echo $url;?>">Shiksha Caf&#233;</a> in making the 
right career choices and would look forward for a continued patronage.</p><br/>

<p>Best regards,<br />Shiksha.com team</p>