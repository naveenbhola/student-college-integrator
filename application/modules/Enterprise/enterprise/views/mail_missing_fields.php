
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title></title>
</head>

<body>
<table width="600" border="0" cellspacing="0" cellpadding="0" align="center" bgcolor="#e3e5e8">
  <tr>
    <td>
    <table width="560" border="0" cellspacing="0" cellpadding="0" align="center" bgcolor="#FFFFFF">
  <tr>
    <td height="20" bgcolor="#e3e5e8"></td>
  </tr>
    <tr>
    <td>
    
    <table width="560" border="0" cellspacing="0" cellpadding="0" align="center" bgcolor="#333333">
  <tr>
    <td width="15" height="80"><img src="<?php echo IEPLADS_DOMAIN; ?>/mailers/2013/shiksha/EnterpriseMail/images/top_left.gif" width="15" height="80" vspace="0" hspace="0" align="left" /></td>
    <td width="12"></td>
    <td width="216" align="left"><a href="<?php echo SHIKSHA_HOME; ?>" target="_blank" style="font-family:Arial;font-size:24px;color:#feb653;text-decoration:none;"><img src="<?php echo SHIKSHA_HOME; ?>/public/images/nshik_ShikshaLogo1.gif" width="216" height="49" hspace="0" vspace="0" border="0" align="left" alt="Shiksha.com" title="Shiksha.com" style="font-family:Arial, Helvetica, sans-serif; font-size:32px; color:#f27e35; font-weight:bold;" /></a></td>
    <td width="300"></td>
    <td width="17"><img src="<?php echo IEPLADS_DOMAIN; ?>/mailers/2013/shiksha/EnterpriseMail/images/top_right.gif" width="15" height="80" vspace="0" hspace="0" align="right" /></td>
  </tr>
</table>

    
    <table width="509" border="0" cellspacing="0" cellpadding="0" align="center" style="font-family:Arial;font-size:14px;color:#444648; text-align:left;">
  <tr>
    <td width="509" height="23"></td>
  </tr>
  <tr>
    <td>Dear <strong>Enterprise User,</strong></td>
  </tr>
  <tr>
    <td height="21"></td>
  </tr>
  <tr>
    <td>Our records indicate you have not shared the following information:
    <br/><br/>
    <?php
    foreach($missing_fields as $field){
        $val = "";
        if($field == "mobile"){
          $val = "Contact number";
        }
        if($field == "city"){
          $val = "Contact location";
        }
        if(!empty($val)){
          echo "<b>" . $val . "</b><br/>";
        }
    }
    ?>
    </td>
  </tr>
  <tr>
    <td height="24"></td>
  </tr>
  <tr>
    <td>Kindly reply to this email with above mentioned imformation, so that we can get in touch with you.</td>
  </tr>
  <tr>
    <td height="21"></td>
  </tr>
  
  <tr>
    <td>Best Wishes,</td>
  </tr>
  <tr>
    <td height="5"></td>
  </tr>
  <tr>
    <td>Sales Team at <strong><font>Shiksha<span style="font-size:1px;"> </span>.</font><font>com</font></strong></td>
  </tr>
  <tr>
    <td height="26"></td>
  </tr>
  <tr>
    <td style="font-size:12px; margin-top:20px; display: block">
      Institutes inside India can call us at our local city office. <a href="<?php echo SHIKSHA_HOME; ?>/shikshaHelp/ShikshaHelp/contactUs" target="_new">Click here</a> to see details of our local offices.<br/>
	  Institutes outside India can call us at our Toll Free number: 1800-717-1094
	</td>
  </tr>
 <tr>
    <td height="26"></td>
  </tr>
</table>

    
    </td>
  </tr>
    <tr>
    <td height="20" bgcolor="#e3e5e8"></td>
  </tr>
</table>

    </td>
  </tr>
</table>

</body>
</html>
