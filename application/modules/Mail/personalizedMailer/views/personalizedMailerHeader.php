<?php $studyAbroadURL = $_SERVER['SERVER_NAME'];
if(stripos($studyAbroadURL,"studyabroad.shiksha")!==false)
{
  $isStudyAbroadDomain = "/public/images/abroad_mailer_logo2.png";
}
else
{
  $isStudyAbroadDomain = "/public/images/personalizedMailers/mailerlogo.gif";
}
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "https://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="https://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title><?php echo $mailHeading;?></title>
<!--[if gte mso 9]>
<style type="text/css">
  p.MsoNormal {margin: 0px}
  table tr td {mso-line-height-rule: exactly;}
  </style>
<![endif]-->
</head>

<body>
  <style type="text/css">
  p {display:none !important;}
  table tr td {mso-line-height-rule: exactly;}
  </style>
<table style="max-width:600px;" border="0" cellspacing="0" cellpadding="0" align="center">
  <tr>
    <td width="600" bgcolor="#f1f1f2"><table style="max-width:600px; border:1px solid #ededed; border-top:4px solid #ec9939;" border="0" cellspacing="0" cellpadding="0">
        <tr>
          <td height="20"></td>
        </tr>
        <tr>  
          <td height="18"><table width="100%" border="0" cellspacing="0" cellpadding="0">
              <tr>
                <td width="59"></td>
                <td width="83"><a target="_blank" style="text-decoration:none;color: inherit;" href="<?php echo SHIKSHA_HOME; ?>/shiksha/index<!-- #AutoLogin --><!-- AutoLogin# -->"><img width="83" style="font-family:open sens, Tahoma, Helvetica, sans-serif; font-size:25px; color:#098290;border:none;" src="<?php echo SHIKSHA_HOME; ?><?php echo $isStudyAbroadDomain ?>" alt="Shiksha" /></a></td>
                <td width="425" style="font-family:Tahoma, Helvetica, sans-serif; font-size:16px; color:#999999;"><?php echo $mailHeading;?></td>
              </tr>
            </table></td> 
        </tr>
        <tr>
          <td height="20" bgcolor="#f1f1f2"></td>
        </tr>
