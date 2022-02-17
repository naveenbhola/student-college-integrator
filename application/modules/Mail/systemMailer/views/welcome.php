<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title></title>
</head>
<body>
<table cellspacing="0" cellpadding="0" border="0" align="center" style="max-width:600px;text-align:center">
  <tbody>
    <tr>
      <td width="600" style="padding-bottom:10px"><font face="Arial" color="#9d9d9d" style="font-size:12px">This email is sent to you because you are Shiksha.com member</font></td>
    </tr>
  </tbody>
</table>
 <table border="0" cellspacing="0" cellpadding="0" align="center" bgcolor="#e3e5e8" style="max-width:600px; -webkit-text-size-adjust: 100%;">
  <tr>
    <td></td>
    <td><table width="100%" cellspacing="0" cellpadding="0" border="0">
      <tbody><tr>
        <td align="right" style="padding:10px 5px 0px 5px">
        <?php if($unsubscribeLinkReq != 'false'){?>
          <table width="290" cellspacing="0" cellpadding="0" border="0">
            <tbody><tr>
              <td width="82" align="right"><a target="_blank" style="font-family:Arial;font-size:12px;color:#0065e8;text-decoration:none" href="<!-- #Unsubscribe --><!-- Unsubscribe# -->">Unsubscribe</a></td>
            </tr>
			</tbody></table>
          <?php }?>
			</td>
      </tr>
    </tbody></table></td>
    <td></td>
  </tr>
  <!-- <tr>
    <td></td>
    <td valign="bottom"><table width="100%" border="0" cellspacing="0" cellpadding="0" bgcolor="#333333">
        <tr>
          <td valign="top" width="280"><img src="<?php //echo SHIKSHA_HOME; ?>/public/images/welcomeMailer/img1.gif" width="6" height="6" vspace="0" hspace="0" align="left" /></td>
          <td valign="top" width="280" align="right"><img src="<?php //echo SHIKSHA_HOME; ?>/public/images/welcomeMailer/img2.gif" width="6" height="6" vspace="0" hspace="0" align="right" /></td>
        </tr>
      </table></td>
    <td></td>
  </tr> -->
  <tr>
    <td></td>
    <td style="padding-bottom:5px"><a href="<?php echo SHIKSHA_HOME; ?><!-- #AutoLogin --><!-- AutoLogin# -->" target="_blank"><img src="<?php echo SHIKSHA_HOME; ?>/public/images/abroad_mailer_logo2.png" vspace="0" hspace="0" align="left" border="0" title="shiksha.com" /></a></td>
    <td></td>
  </tr>
  <tr>
    <td width="18"><img src="<?php echo SHIKSHA_HOME; ?>/public/images/welcomeMailer/spacer.gif" width="8" height="1" vspace="0" hspace="0" align="left"></td>
    <td width="564" bgcolor="#ffffff" align="center">
    	<table width="100%" border="0" cellspacing="0" cellpadding="0" style="font-family:Arial; font-size:14px; color:#585c5f;">
          <tr>
            <td colspan="3" height="20"></td>
          </tr>
          <tr>
            <td width="28"><img src="<?php echo SHIKSHA_HOME; ?>/public/images/welcomeMailer/spacer.gif" width="10" height="1" vspace="0" hspace="0" align="left" /></td>
            <td width="508" align="center">
            	<table border="0" cellspacing="0" cellpadding="0" style="font-family:Arial; font-size:14px; color:#444648; line-height:18px; text-align:left;" width="100%">
                  <tr>
                    <td>Hi <?php echo $firstName; ?>,</td>
                  </tr>
                  <tr>
                    <td height="15"></td>
                  </tr>
                  <tr>
                    <td width="508">
					  <?php if($fullTimeMBARegistration) { ?>
					  Welcome. To get you started, please find attached a guide that will help you in your pursuit of management education.
					  <?php } else if($mmpWithAttachmentRegistration) { ?>
					  The download that you requested for is attached in this mail. Please find below your login details to explore more.
					  <?php $hideLoginDetailsLabel = TRUE; } else { ?>
					  Welcome to Shiksha.com, connecting you with unlimited careers, courses and colleges.
					  <?php } ?>
					</td>
                  </tr>
                </table>
			</td>
            <td width="28"><img src="<?php echo SHIKSHA_HOME; ?>/public/images/welcomeMailer/spacer.gif" width="10" height="1" vspace="0" hspace="0" align="left" /></td>
          </tr>
          <tr>
            <td colspan="3" height="13"></td>
          </tr>
          <tr>
            <td></td>
            <td bgcolor="#f4f4f4" style="padding:5px 10px;">
            	<table width="100%" border="0" cellspacing="0" cellpadding="0" style="font-family:Arial; font-size:14px; color:#444648; line-height:24px; text-align:left;">
				<?php if(!$hideLoginDetailsLabel) { ?>   
                <tr>
                <td style="font-size:16px;">Your Login details</td>
                </tr>
				<?php } ?>
                <tr>
                <td>Email: <strong><?php echo $email; ?></strong></td>
                </tr>
                <tr>
                <td>Password: <strong><?php echo $password; ?></strong></td>
                </tr>
                </table>
            </td>
            <td></td>
          </tr>
          <tr>
            <td></td>
            <td>
            	<table width="100%" border="0" cellspacing="0" cellpadding="0" style="font-family:Arial; font-size:12px; color:#686767; text-align:left;">
                  <tr>
                    <td colspan="2" height="11"></td>
                  </tr>
                  <tr>
                    <td colspan="2"><strong style="font-size:17px; color:#020001">Get started now.</strong></td>
                  </tr>
                  <tr>
                    <td colspan="2" height="11"></td>
                  </tr>
                  <tr>
                    <td width="100" valign="top"><img src="<?php echo SHIKSHA_HOME; ?>/public/images/welcomeMailer/cours_IC.gif" width="89" height="62" vspace="0" hspace="0" align="left" /></td>
                    <td width="464"><font face="Georgia" color="#0065e8" style="font-size:17px; line-height:24px;">Explore Courses</font><br />Extensive coverage of courses detailing what to expect, eligibility and admission procedure.

                    
                    </td>
                  </tr>
                  <tr>
                    <td colspan="2" height="5"></td>
                  </tr>
                  <tr>
                    <td valign="top"><img src="<?php echo SHIKSHA_HOME; ?>/public/images/welcomeMailer/inst_IC.gif" width="89" height="62" vspace="0" hspace="0" align="left" /></td>
                    <td><font face="Georgia" color="#0065e8" style="font-size:17px; line-height:24px;">Find Institutes</font><br />Comprehensive list of institutes in India and abroad with detailed information on placements, reviews, fees and faculty.                    
                    </td>
                  </tr>
                  <tr>
                    <td colspan="2" height="5"></td>
                  </tr>
                  <tr>
                    <td valign="top"><img src="<?php echo SHIKSHA_HOME; ?>/public/images/welcomeMailer/disc_IC.gif" width="89" height="62" vspace="0" hspace="0" align="left" /></td>
                    <td><font face="Georgia" color="#0065e8" style="font-size:17px; line-height:24px;">Discover Careers</font><br />Exhaustive database of career choices intuitively mapped to your interests and educational background.
                    </td>
                  </tr>
                  <tr>
                    <td colspan="2" height="5"></td>
                  </tr>
                  <tr>
                    <td valign="top"><img src="<?php echo SHIKSHA_HOME; ?>/public/images/welcomeMailer/exp_IC.gif" width="89" height="62" vspace="0" hspace="0" align="left" /></td>
                    <td><font face="Georgia" color="#0065e8" style="font-size:17px; line-height:24px;">Ask Experts</font><br />Carefully selected community of domain experts, current students and alumni to answer all your queries.
                    </td>
                  </tr>
                  <tr>
                    <td colspan="2" height="20"></td>
                  </tr>
                  <tr>
                    <td colspan="2">
                    	<table width="135" cellspacing="0" cellpadding="0" border="0" style="font-family:Arial;font-size:13px;color:rgb(71,74,75);display:block">
    <tbody><tr>
      <td width="135" bgcolor="#ffda3e" align="center" height="32" style="border:1px solid #e8b363;border-radius:2px"><a target="_blank" style="text-decoration:none;color:rgb(75,75,75);display:block;font-size:13px;line-height:30px" title="See all articles" href="<?php echo SHIKSHA_HOME; ?><!-- #AutoLogin --><!-- AutoLogin# -->"><b>Start Exploring</b></a></td>
    </tr>
  </tbody></table>
                    </td>
                  </tr>
                </table>
			</td>
            <td></td>
          </tr>
        </table>
    </td>
    <td width="18"><img src="<?php echo SHIKSHA_HOME; ?>/public/images/welcomeMailer/spacer.gif" width="8" height="1" vspace="0" hspace="0" align="left"></td>
  </tr>
  <tr>
  <tr>
    <td></td>
    <td bgcolor="#ffffff" height="20"></td>
    <td></td>
  </tr>
  <tr>
    <td></td>
    <td bgcolor="#FFFFFF" align="center"><table width="92%" border="0" cellspacing="0" cellpadding="0" style="font-size:12px; color:#FFFFFF; text-align:left;">
        <tr>
          <td width="501"><font face="Tahoma, Arial, sans-serif" color="#9d9d9d" style="font-size:10px; line-height:14px;">This is a system generated email, please do not reply. For more information, visit <a href="<?php echo SHIKSHA_HOME; ?>/shikshaHelp/ShikshaHelp/termCondition" target="_blank" style="font-size:10px; text-decoration:none; color:#3465e8;">Terms &amp;
            Conditions</a><br>
            <?php if($unsubscribeLinkReq != 'false'){?>
            <br>
            You received this email because you're Shiksha<span style="font-size:1px;"> </span>.com member. You can <a href="<!-- #Unsubscribe --><!-- Unsubscribe# -->" target="_blank" style="font-size:10px; text-decoration:none; color:#3465e8;">unsubscribe</a> after logging in to your account.
            <?php }?>
            </font></td>
        </tr>
        <tr>
          <td height="10"></td>
        </tr>
      </table></td>
    <td></td>
  </tr>
  <tr>
    <td></td>
    <td><table width="100%" border="0" cellspacing="0" bgcolor="#FFFFFF" cellpadding="0">
        <tr>
          <td valign="bottom"><img src="<?php echo SHIKSHA_HOME; ?>/public/images/welcomeMailer/img9.gif" width="6" height="6" vspace="0" hspace="0" align="left" /></td>
          <td></td>
          <td valign="bottom"><img src="<?php echo SHIKSHA_HOME; ?>/public/images/welcomeMailer/img10.gif" width="6" height="6" vspace="0" hspace="0" align="right" /></td>
        </tr>
      </table></td>
    <td></td>
  </tr>
  <tr>
    <td height="20" colspan="3"></td>
  </tr>
</table>
</body>
</html>
