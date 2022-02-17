<?php
	$contentArray = is_array($contentArray[0])?$contentArray[0]:array();
	$commentTxt = isset($contentArray['msgTxt'])?$contentArray['msgTxt']:"";
	$blogTitle = isset($contentArray['blogTitle'])?$contentArray['blogTitle']:"";
	$urlToBeSentInMail = isset($contentArray['urlToBeSentInMail'])?$contentArray['urlToBeSentInMail']:'';
?>
<br />
Hi ,<br /><br />
An Article <b><?php echo $blogTitle; ?></b> has received a comment/ reply!!!<br /><br />
<b>Comment:</b> <?php echo $commentTxt; ?><br /><br />
<table width="209" border="0" cellspacing="0" cellpadding="0" align="left"><tr>
      <td height="39" align="center" bgcolor="#ff9d0a" background="<?php echo SHIKSHA_HOME_URL; ?>/public/images/ans_button.gif" style="font-family:Verdana, Arial; font-size:12px; text-align:center; height:39px;"><a href="<?php echo $urlToBeSentInMail; ?>" target="_blank" title="Check Out the Comment" style="text-decoration:none; color:#000000; line-height:39px; font-size:12px; display:block;"><b>Check Out the Comment</b></a></td></tr></table>
<br /><br /><br />
Best regards,<br />Shiksha.com team
