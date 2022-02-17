<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>shiksha</title>
</head>
<body>
<table style="max-width:600px; border:1px solid #ebebeb;" align="center" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td width="600" bgcolor="#ffffff">
    <table style="max-width:600px;" align="center" border="0" cellspacing="0" cellpadding="0">
        <tr>
          <td width="600" align="center" bgcolor="#f5f5f5" style=" border-bottom:#e7e7e7 solid 1px;">
            <table width="94%" border="0" cellspacing="0" cellpadding="0">
              <tr>
                <td width="50%" align="left" height="48">
                <img src="<?php echo SHIKSHA_HOME; ?>/public/images/personalizedMailers/lean_mailer_logo.png" width="81" height="18" alt="shiksha" style="font-family:Arial, Helvetica, sans-serif; font-size:18px; color:#03818d;" /></td>
                <?php if(!isset($unregisteredUser)){ ?>
                <td width="50%" align="right" style="font-family:Arial, Helvetica, sans-serif; font-size:15px; color:#1f65a6;">
                  <?php if($type == 'MMM') {
                    $profileLinkExt = "<!-- #widgettracker --><!-- widgettracker# -->";
                  } else {
                    $profileLinkExt = "<!-- #AutoLogin --><!-- AutoLogin# -->";
                  } ?>
                  <a href="<?php echo $profileUrl.$profileLinkExt;?>" target="_blank" style=" color:#1f65a6; text-decoration:none;">Your Profile</a>
                </td>
              <?php } ?>
              </tr>
            </table>
          </td>
        </tr>
