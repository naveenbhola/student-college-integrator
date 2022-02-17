<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title><?=  htmlentities($consultantName)?></title>
</head>   
  
<body> 
<table style="max-width:600px; border:1px solid #c5c5c5;" align="center"  border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td width="600"><table style="max-width:600" border="0" cellspacing="0" cellpadding="0" align="center">
        <tr>
          <td width="600" align="center" bgcolor="#000000" style="background-image:url(<?=SHIKSHA_STUDYABROAD_HOME?>/public/images/consultant-mailer-background-image.jpg); background-repeat:no-repeat; height:208px;">
          <table width="85%" border="0" cellspacing="0"  cellpadding="0" align="center" style="background-image:url(<?=SHIKSHA_STUDYABROAD_HOME?>/public/images/consultant-mailer-black-background.png); border:1px solid #000000;  background-repeat:repeat;">
          <tr><td height="20"></td></tr>
  <tr>
    <td align="center" style="font-family:Arial, Helvetica, sans-serif; color:#ffffff; font-size:14px; text-transform:uppercase;">You showed interest in</td>
  </tr>
   <tr><td height="10"></td></tr>
  <tr>
      <td align="center"><img width="100%" style="max-width:118px;" src="<?=SHIKSHA_STUDYABROAD_HOME?>/public/images/consultant-mailer-border-center.jpg" alt="" /></td>
  </tr>
  <tr>
    <td><table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td width="10"></td>
    <td align="center" style="font-family:Tahoma, Geneva, sans-serif; font-size:20px; color:#ffffff;">"<?=htmlentities($universityName)?>"</td>
    <td width="10"></td>
  </tr>
</table>
</td>
  </tr>
   <tr><td height="20"></td></tr>
</table>
</td> 
        </tr>
        <tr>
          <td bgcolor="#eaeaea"><table width="100%" border="0" cellspacing="0" cellpadding="0">
              <tr>
                <td width="25"></td>
                <td><table width="100%" border="0" cellspacing="0" cellpadding="0">
                    <tr>
                      <td height="20"></td>
                    </tr>
                    <tr>
                      <td width="100%"><table width="278" border="0" cellspacing="0" cellpadding="0" align="left">
                          <tr>
                            <td width="271" style="font-size:15px; text-transform:uppercase;  color:#2e2e2e; font-family:Arial, Helvetica, sans-serif; text-align:right;">get more information from<br />
                              shiksha verified counsellor</td>
                            <td width="34" style="border-right:1px solid #d7d7d7;"></td>
                          </tr>
                          <tr>
                            <td height="10"></td>
                          </tr>
                        </table> 
                          <table width="254"  border="0" cellspacing="0" cellpadding="0" align="center">
                          <tr>
                            <td><table  border="0" cellspacing="0" cellpadding="0" align="center">
                                <tr>
                                    <td width="208" align="center" bgcolor="#f3852d" height="42"><a style="background-color:#f3852d;  text-transform:uppercase;  text-decoration:none; font-family:Arial, Helvetica, sans-serif; height:42px; font-size:14px; color:#ffffff; line-height:42px; float:left; width:100%;" href="<?=$consultantUrl?><!-- #AutoLogin --><!-- AutoLogin# -->" target="_blank"><strong>get free consultation</strong></a></td>
                                </tr>
                              </table></td>
                          </tr>
                        </table></td>
                    </tr>
                    <tr>
                      <td height="20"></td>
                    </tr>
                    <tr>
                      <td bgcolor="#ffffff" style="border:1px solid #c2c2c2;"><table width="100%" border="0" cellspacing="0" cellpadding="0">
                          <tr>
                            <td width="15"></td>
                            <td width="198" ><img hspace="0" width="100%" style="max-width:195px;" vspace="0" src="<?=$consultantLogo?>" alt="<?=  htmlentities($consultantName)?>" /></td>
                            <td width="14"></td>
                            <td width="319"><table width="100%" border="0" cellspacing="0" cellpadding="0">
                                <tr>
                                  <td height="15"></td>
                                </tr>
                                <tr>
                                    <td style="font-family:Arial, Helvetica, sans-serif; font-size:15px; color:#5c5c5c;"><strong style="text-transform:uppercase;"><?=htmlentities($consultantName)?></strong> <font size="-1">(shiksha verified counsellor)</font></td>
                                </tr>
                                <tr>
                                  <td height="10"></td>
                                </tr>
                                <tr>
                                    <td style="font-family:Arial, Helvetica, sans-serif; font-size:13px; color:#626262;"><?=formatArticleTitle(strip_tags($consultantDescription), 160)?></td>
                                </tr>
                              
                                
                                <tr>
                                  <td height="15"></td>
                                </tr>
                              </table></td>
                          </tr>
                        </table></td>
                    </tr>
                    <tr>
                      <td height="25"></td>
                    </tr>
                    <?php if(isset($consultantPRINumber)){?>
                    <tr>
                      <td height="30" align="center" style="font-family:Arial, Helvetica, sans-serif; color:#2c3338; font-size:14px; text-transform:uppercase;"><strong>Get connected now</strong></td>
                    </tr>
                    <tr>
                      <td height="10"></td>
                    </tr>
                    <tr> 
                      <td><table width="100%" border="0" cellspacing="0" cellpadding="0" align="center">
                          <tr>
                              <td height="45" align="center"><table width="170" border="0" cellspacing="0" cellpadding="0" align="center">
                                <tr>
                                    <td width="50" height="45" align="center" bgcolor="#363138"><img src="<?=SHIKSHA_STUDYABROAD_HOME?>/public/images/consultant-mailer-phon-icon.gif" alt="" /></td>
                                  <td width="10" bgcolor="#485fa2"></td>
                                  <td width="110" bgcolor="#485fa2" style="font-family:Arial, Helvetica, sans-serif; color:#ffffff; font-size:14px;"><?=$consultantCityName?> <br/>
                                    <strong><?=$consultantPRINumber?></strong></td>
                                </tr>
                              </table></td>
                          </tr>
                          <tr>
                            <td height="15"></td>
                          </tr>
                          <tr>
                            <td align="center" style="font-family:Arial, Helvetica, sans-serif; font-size:12px; color:#898888;"><i>*Call between 9am to 6pm from Monday to Saturday</i><br />
                              <br />
                              OR </td>
                          </tr>
                        </table></td>
                    </tr>
                    <?php }?>
                    <tr>
                      <td height="13"></td>
                    </tr>
                    <tr>
                      <td align="center" style="font-family:Arial, Helvetica, sans-serif; color:#2c3338; text-transform:uppercase; font-size:14px;"><strong>Know more about the countries represented and <br />
                        students admitted by this consultant</strong></td>
                    </tr>
                    <tr>
                      <td height="20"></td>
                    </tr>
                    <tr>
                        <td align="center"><table width="192" border="0" cellspacing="0" cellpadding="0" align="center">
                          <tr> 
                              <td height="48" align="right" valign="top"><a href="<?=$consultantUrl?><!-- #AutoLogin --><!-- AutoLogin# -->" target="_blank"><img src="<?=SHIKSHA_STUDYABROAD_HOME?>/public/images/consultant-mailer-ok-icon.gif" hspace="0" vspace="0" align="right" alt="" /></a></td>
                            <td width="120" height="48" bgcolor="#485fa2" align="center"><a style="font-family:Arial, Helvetica, sans-serif; height:48px; text-decoration:none; font-size:14px; color:#ffffff; text-transform:uppercase; line-height:48px; float:left; text-align:center; width:100%;" href="<?=$consultantUrl?><!-- #AutoLogin --><!-- AutoLogin# --> " target="_blank"><strong>find out now</strong></a></td>
                            <td height="48" align="left"><a href="<?=$consultantUrl?><!-- #AutoLogin --><!-- AutoLogin# -->" target="_blank"><img hspace="0" vspace="0" src="<?=SHIKSHA_STUDYABROAD_HOME?>/public/images/consultant-maler-side-bar.gif" align="left" alt="" /></a></td>
                          </tr>  
                        </table></td>
                    </tr>
                    <tr>
                      <td height="20"></td>
                    </tr>
                  </table></td>
                <td width="25"></td>
              </tr>
            </table></td>
        </tr>
        <tr>
            <td height="75" bgcolor="#ffffff" align="center"><a href="<?=SHIKSHA_STUDYABROAD_HOME?><!-- #AutoLogin --><!-- AutoLogin# -->" target="_blank"><img style="max-width:138px; color: #485fa2;
    font-family: arial;
    font-size: 25px;
    text-align: center; 
    text-transform: uppercase;" width="100%" src="<?=SHIKSHA_STUDYABROAD_HOME?>/public/images/shiksha-study-abroad-logo.gif" alt="Shiksha Study Abroad " /></a></td>
        </tr>
      </table></td>
  </tr>
</table>
</table>
<table cellspacing="0" cellpadding="0" border="0" align="center" style="max-width:600px; font-family:Arial, Helvetica, sans-serif; font-size:12px; color:#000000;">
  <tbody>
    <tr>
      <td height="26" align="center" style="padding-top:5px;">To unsubscribe <a style="color:#000000; text-decoration:underline;" target="_blank" href="<!-- #Unsubscribe --><!-- Unsubscribe# -->">click
        here</a> to visit the Accounts &amp; Settings page and change your
        mail preferences</td>
    </tr>
  </tbody>
</table>
</body>
</html>
