<table width="600" border="0" cellspacing="0" cellpadding="0" align="center" style="border:1px solid #D9D9D9;">
  <tr>
  	<td colspan="3" background="<?php echo SHIKSHA_HOME ?>/public/images/bg.gif" style="background:url(<?php echo SHIKSHA_HOME ?>/public/images/bg.gif)">
	<div style="font-family:Arial, Helvetica, sans-serif; font-size:12px; color:#1A1A1A; padding:10px 35px 10px 35px; line-height:20px;">
	<br />

<br/>
Dear <?php echo $first_name?>,<br/><br/>
<?php if($widget == "request_callback"){ ?>
Thank you for requesting a call back on <?php echo htmlentities($courseName);?> in <?php echo htmlentities($universityName);?>. A Shiksha counselor specializing in this university will contact you on <?php echo $user_mobile;?>.<br><br>
<?php }?>
<?php if($widget != "request_callback"){ ?>
Oops! E-brochure for <?php echo htmlentities($courseName);?>, <?php echo htmlentities($universityName);?> is currently not available.<br/>
<?php }?>
<br/>Find out more details of this course, <a href = "<?php echo $URL;?><!-- #Tracking --><!-- Tracking# -->">visit this link</a>
<br/>
<br/>By the way, we do have e-brochures for similar courses at <a href="<?php echo SHIKSHA_STUDYABROAD_HOME; ?><!-- #Tracking --><!-- Tracking# -->">studyabroad.shiksha.com</a> :-)
<br/><br/>Good luck for your career :-)
<br/><span style="color:rgb(246,137,54);font-weight:bold;">Shiksha</span> <span style="color:rgb(60,84,164);font-weight:bold;">Study Abroad</span>
<br/>
<br/>
P.S. - We will love to hear from you, write to us at <a href="mailto:studyabroad@shiksha.com">studyabroad@shiksha.com</a><br />
You can also reach us on our study abroad student helpline at <?php echo ABROAD_STUDENT_HELPLINE; ?><br/>
(09:30 AM to 06:30 PM, Monday to Friday)<br/>
</div></td>
  </tr>
</table>
<table width="600" border="0" cellspacing="0" cellpadding="0" align="center">
    <?php $CI->load->view('studyAbroadCommon/mailerFooter',array('whiteBanner' => true)); ?>
  <tr>
    <td><div align="center" style="font-family:Arial, Helvetica, sans-serif; font-size:10px; padding:10px; color:#666666; line-height:14px;">You are receiving this email because you have requested this information for your email id <span style="text-decoration:underline; color:#231E19"><a href="javascript:void(0)"><?php echo $usernameemail?></a></span>.

<br />
Copyright &copy; <?php echo date('Y')?> Shiksha.com. All rights reserved.<br />
		<a href="<?=SHIKSHA_HOME?><!-- #Tracking --><!-- Tracking# -->" target="_blank" style="color:#666666">Shiksha.com</a> is located at A-88, Sector-2, NOIDA, UP 201301, INDIA</div>

	</td>
  </tr>
</table>