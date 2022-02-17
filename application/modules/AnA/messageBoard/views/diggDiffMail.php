<?php
	$nameOfUser = (trim($contentArray['firstname']) != "")?$contentArray['firstname']:$contentArray['displayname'];
	$questionTxt = isset($contentArray['msgTxt'])?$contentArray['msgTxt']:'';
	$questionUrl = isset($contentArray['urlForQuestion'])?$contentArray['urlForQuestion']:'';
	$digUpRating = isset($contentArray['digUp'])?$contentArray['digUp']:'';
?>
<br />Hi <?php echo $nameOfUser; ?>,<br /><br />
Your answer, for the below mentioned question, has received <?php echo $digUpRating; ?> thumb up ratings.<br /><br />
<a href="<?php echo $questionUrl; ?>"><?php echo shiksha_formatIt(insertWbr($questionTxt,32)); ?></a><br /><br />
You can click on the question to view the question and other answers.<br /><br />
We extremely value the contribution and efforts you have taken to help members of Shiksha.com in making the right career choices and would look forward for a continued patronage.<br /><br />
We strongly encourage you to visit Shiksha.com and help other members by sharing your expertise. Please <a href="<?php echo SHIKSHA_ASK_HOME; ?>">click here</a> to proceed.<br /><br />
Best regards,<br />Team Shiksha.com