<table width="600" border="0" cellspacing="0" cellpadding="0" align="center" style="border:1px solid #D9D9D9;">
  <tr>
    <td width="297" background="<?php echo SHIKSHA_HOME ?>/public/images/tp_bg1.gif" style="background:url(<?php echo SHIKSHA_HOME ?>/public/images/tp_bg1.gif) left top repeat-x;"><img src="<?php echo SHIKSHA_HOME ?>/public/images/shiksha_logo1.gif" alt="Shiksha" width="297" height="94" /></td>
    <td background="<?php echo SHIKSHA_HOME ?>/public/images/tp_bg1.gif" style="background:url(<?php echo SHIKSHA_HOME ?>/public/images/tp_bg1.gif) left top repeat-x;" valign="top">
	<table border="0" cellspacing="0" cellpadding="0" align="right">

  <tr>
    <td valign="top"><img src="<?php echo SHIKSHA_HOME ?>/public/images/naukri-logo1.gif" alt="Naukri.com" width="224" height="58" align="right" /></td>
  </tr>
  <tr>
    <td width="50%"><div align="right" style="padding-top:8px; font-family:Arial, Helvetica, sans-serif; font-size:12px; color:#FFFFFF;"><b><?php echo "Hi ".$displayname?> &nbsp;</div></td>
  </tr>
</table>

	</td>
    <td width="25"><img src="<?php echo SHIKSHA_HOME ?>/public/images/lft_img1.gif" width="25" height="94" hspace="0" /></td>
  </tr>
  <tr>
  	<td colspan="3" background="<?php echo SHIKSHA_HOME ?>/public/images/bg.gif" style="background:url(<?php echo SHIKSHA_HOME ?>/public/images/bg.gif)">
	<div style="font-family:Arial, Helvetica, sans-serif; font-size:12px; color:#1A1A1A; padding:10px 35px 10px 35px; line-height:20px;">
	<br />
	<?php echo "Hi ".$displayname?>,<br />
<?php if(isset($mailCount)){ ?>
<br />
You have recieved <?php echo $mailCount;?> new messages in your Shiksha mail-box which are unread.
<br />
Click link below to see those messages.
<br />
<a href="<?php echo SHIKSHA_HOME;?>/mail/Mail/mailbox">Shiksha Mail Box</a>
<br />You may send or recieve messages to other Shiksha users using Shiksha mail-box.
<br />
<?php }
if(isset($friendCount))
{ ?>
<br />
You have <?php echo $friendCount; ?> new friend requests.
<br />
Click link below approve/deny these requests.
<br />
<a href="<?php echo SHIKSHA_HOME;?>/user/MyShiksha/index">Shiksha Accounts & Settings</a>
<br />
Shiksha.com allows you to connect to other shiksha.com users and communicate!
<br /><br />
<?php } ?>

Best regards,<br />
<b style="color:#FF7E00;">Shiksha</b><b style="color:#231E19;">.com team</b></div></td>
  </tr>

</table>
<table width="600" border="0" cellspacing="0" cellpadding="0" align="center">
  <tr>
    <td><div align="center" style="font-family:Arial, Helvetica, sans-serif; font-size:10px; padding:10px; color:#666666; line-height:14px;">You received this email because you are a Shiksha member, and have subscribed to receive email updates under your email id of <span style="text-decoration:underline; color:#231E19"><a href="#"><?php echo $email?></a></span>.

For questions, advertising information, or to provide feedback, email: <a href="mailto:support@shiksha.com" style="color:#231E19">support@shiksha.com</a><br />
<br />
Copyright © 2008 Shiksha.com. All rights reserved.<br />
		<a href="<?php echo SHIKSHA_HOME; ?>" target="_blank" style="color:#666666">Shiksha.com</a> is located at A-88, Sector-2, NOIDA, UP 201301, INDIA</div>

	</td>
  </tr>
</table>
