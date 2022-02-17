
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

    
    <table width="528" border="0" cellspacing="0" cellpadding="0" align="center" style="font-family:Arial;font-size:14px;color:#444648">
  <tr>
    <td width="528" height="23"></td>
  </tr>
  <tr>
    <td>Dear <strong>Sales  Team,</strong></td>
  </tr>
  <tr>
    <td height="10"></td>
  </tr>
  <tr>
    <td>A new enterprise user was created with the following  information :</td>
  </tr>
    <td height="15"></td>
  </tr>
  <tr>
    <td>
    <table width="526" border="0" bgcolor="#f9f2d2" cellspacing="0" cellpadding="0" align="left" style="font-family:Arial;font-size:14px;color:#444648; text-align:left;">
  <tr>
    <td width="15" height="10"></td>
    <td width="126"></td>
    <td width="10"></td>
    <td width="10"></td>
    <td width="365"></td>
  </tr>
  <tr>
    <td></td>
    <td height="25"><strong>Name</strong></td>
    <td><strong>:</strong></td>
    <td></td>
    <td><?php echo $userdata['displayname'];?></td>
  </tr>
  <tr>
    <td></td>
    <td height="25"><strong>Institute  Name</strong></td>
    <td><strong>:</strong></td>
    <td></td>
    <td><?php echo $userdata['busiCollegeName'];?></td>
  </tr>
  <tr>
    <td></td>
    <td height="25"><strong>Type</strong></td>
    <td><strong>:</strong></td>
    <td></td>
    <td><?php echo $userdata['busiType'];?></td>
  </tr>
    <tr>
    <td></td>
    <td height="25"><strong>Contact  Person</strong></td>
    <td><strong>:</strong></td>
    <td></td>
    <td><?php echo $userdata['contactName'];?></td>
  </tr>
    <tr>
    <td></td>
    <td height="25"><strong>Contact  No.</strong></td>
    <td><strong>:</strong></td>
    <td></td>
    <td><?php 
		if(!empty($userdata['IsdCode'])) {
			echo "+".$userdata['IsdCode']."-".$userdata['originalUserMobile'];
		} else {
			echo $userdata['originalUserMobile'];
		}				
		?>
			
    </td>
  </tr>
    <tr>
    <td></td>
    <td height="25"><strong>Contact Email</strong></td>
    <td><strong>:</strong></td>
    <td></td>
    <td><?php echo $userdata['email'];?></td>
  </tr>
    <tr>
    <td></td>
    <td height="25"><strong>Country</strong></td>
    <td><strong>:</strong></td>
    <td></td>
    <td><?php echo $userdata['country'];?></td>
  </tr>
    <tr>
    <td></td>
    <td height="25"><strong>City</strong></td>
    <td><strong>:</strong></td>
    <td></td>
    <td><?php echo $userdata['city'];?></td>
  </tr>
    <tr>
    <td height="10"></td>
    <td></td>
    <td></td>
    <td></td>
    <td></td>
  </tr>
</table>


    </td>
  </tr>
  <tr>
    <td height="25"></td>
  </tr>
  <tr>
    <td><p>Please contact the user at the above information to  sell further products and services.</p></td>
  </tr>
  <tr>
    <td height="25"></td>
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
