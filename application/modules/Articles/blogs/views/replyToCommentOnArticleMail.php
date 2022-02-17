<?php
	$contentArray = is_array($contentArray[0])?$contentArray[0]:array();
 	$nameOfUser = (trim($contentArray['firstname']) != "")?$contentArray['firstname']:$contentArray['displayname'];
	$commentTxt = isset($contentArray['msgTxt'])?$contentArray['msgTxt']:'';
	$blogTitle = isset($contentArray['blogTitle'])?$contentArray['blogTitle']:'';
	$urlToBeSentInMail = isset($contentArray['urlToBeSentInMail'])?$contentArray['urlToBeSentInMail']:'';
?>
<br />
Hi <?php echo $nameOfUser; ?>,<br /><br />
You have received a new reply on your comment on the Article <b><?php echo $blogTitle; ?></b>!!!<br /><br />
<b>Your Comment:</b> <?php echo $commentTxt; ?><br /><br />
<table width="209" border="0" cellspacing="0" cellpadding="0" align="left"><tr>
      <td height="39" align="center" bgcolor="#ff9d0a" background="<?php echo SHIKSHA_HOME_URL; ?>/public/images/ans_button.gif" style="font-family:Verdana, Arial; font-size:12px; text-align:center; height:39px;"><a href="<?php echo $urlToBeSentInMail; ?>" target="_blank" title="Check Out the Reply" style="text-decoration:none; color:#000000; line-height:39px; font-size:12px; display:block;"><b>Check Out the Reply</b></a></td></tr></table>
<br /><br />
Now that you have received a reply, <b>WHAT'S NEXT?</b><br /><br />
<ul type="disc">
<li>If the user asked for further explanation, you can further help the user by replying to the user</li>
</ul>
<br /><br />
Best regards,<br />Shiksha.com team
