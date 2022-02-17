<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "https://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="https://www.w3.org/1999/xhtml">
<body>
<table border="0" cellspacing="0" cellpadding="0" align="center" style="max-width:600px; font-family:Verdana, Geneva, sans-serif; font-size:13px; color:#333333;border: 1px solid #EBEBEB;">
 <tbody><tr>
    <td height="82" align="center"><a href="<?php echo SHIKSHA_HOME; ?>" target="_blank"><img src="<?php echo SHIKSHA_HOME; ?>/public/images/mailer_logo_2015.png" width="193" height="45" alt="shiksha.com" hspace="0" vspace="0" border="0" align="left" style="font-size:12px; color:#f38335; font-weight:bold; padding-left:20px;" /></a></td>
  </tr>
 <tr>
  <td valign="top" height="10"></td>
 </tr>
 <tr>
    <td bgcolor="#3bb1fc" align="center"><img src="<?php echo IEPLADS_DOMAIN; ?>/mailers/2014/shiksha/shiksha1sep/images/thankyou.gif" alt="THANKYOU" border="0" style="width:100%; font-size:35px; color:#ffffff; font-weight:bold;" hspace="0" vspace="0" align="center" /></td>
  </tr>
  <tr>
    <td bgcolor="#3BB1FC" height="20" colspan="2"></td>
  </tr>
  <tr>
   <td valign="top">
    <table width="100%" cellspacing="0" cellpadding="0" border="0">
      <tbody><tr>
        <td width="10" bgcolor="#3BB1FC"></td>

        <td width="578" valign="top">
          <table style="border:solid 1px #1d92dc;" width="100%" cellspacing="0" cellpadding="0" align="center">
            <tbody>
              <tr>
                <td height="20" bgcolor="#FFFFFF" align="center"></td>
              </tr>
              <tr>
                <td bgcolor="#FFFFFF" align="center">
                  <table width="100%" cellspacing="0" cellpadding="0" border="0">
                    <tbody>
                      <tr>
                        <td width="20"></td>
                        <td width="538" align="center">
                          <table width="100%" cellspacing="0" cellpadding="0" border="0">
                            <tbody>
                              <tr>
                                <td style="font-family:Verdana, Geneva, sans-serif; font-size:13px; color:#333333;">Hi <?php echo $username; ?>,<br></td>
                              </tr>
                              <tr>
                                <td height="20"></td>
                              </tr>
                              <tr>
                                <td style="font-family:Verdana, Geneva, sans-serif; font-size:13px; color:#333333;">Thank you so much.<br>
                                  <br>
                                  Your college review for <?php echo $college_name;?> has been received by us. The review has not been published yet. Our moderation team will go through the review and inform you when it is published. If your review does not meet our quality standards, it will be rejected by our team and available to you for further editing.
                                </td>
                              </tr>
                              <tr>
                                <td height="20"></td>
                              </tr>
                              <tr>
                                <td height="30" style="font-family:Verdana, Geneva, sans-serif; font-size:13px; color:#333333;">Here is a copy of what you&#39;ve written : </td>
                              </tr>
                              <tr>
                                <td>
                                  <table width="100%" cellspacing="0" cellpadding="0" border="0" align="center">
                                    <tbody>
                                      <tr>
                                        <td></td>
                                        <td width="430"></td>
                                        <td></td>
                                      </tr>
                                        <?php if(!empty($reviewTitle)){ ?>
                                      <tr>
                                        <td></td>
                                        <td width="430" style="text-align:justify;font-family:Verdana, Geneva, sans-serif; font-size:13px; color:#999999;"><span style="color:#999999;"><b>Title : </b><?php echo $reviewTitle; ?></span></td>
                                        <td></td>
                                      </tr>
                                        <?php } ?>
                                      <tr>
                                        <td height="15"></td>
                                        <td></td>
                                        <td></td>
                                      </tr>
                                        <?php if(!empty($placementDescription)){ ?>
                                      <tr>
                                        <td width="32" valign="top" align="center" ><img src="<?php echo IEPLADS_DOMAIN; ?>/mailers/2014/shiksha/shiksha1sep/images/img1.gif" width="26" height="69" hspace="0" vspace="0" align="center" alt="" /></td>
                                        <td style="font-family:Verdana, Geneva, sans-serif; font-size:13px; color:#999999;text-align:justify;"><span style="color:#999999; text-align:justify;"><?php echo '<b>Placements : </b>'.$placementDescription; ?></span></td>
                                          <td></td>
                                      </tr>
                                      <tr>
                                        <td></td>
                                        <td height="20"></td>
                                        <td></td>
                                      </tr>
                                        <?php } ?>
                                        <?php if(!empty($infraDescription)){ ?>
                                      <tr>
                                        <td></td>
                                        <td style="font-family:Verdana, Geneva, sans-serif; font-size:13px; color:#999999;text-align:justify;"><span style="color:#999999;text-align:justify;"><?php echo '<b>Infrastructure : </b>'.$infraDescription; ?></span></td>
                                        <td></td>
                                      </tr>
                                      <tr>
                                        <td></td>
                                        <td height="20"></td>
                                        <td></td>
                                      </tr>
                                        <?php } ?>
                                        <?php if(!empty($facultyDescription)){ ?>
                                      <tr>
                                        <td></td>
                                        <td style="font-family:Verdana, Geneva, sans-serif; font-size:13px; color:#999999;text-align:justify;"><span style="color:#999999;"><?php echo '<b>Faculty : </b>'.$facultyDescription; ?></span></td>
                                        <?php if(empty($reviewDescription)){ ?>
                                          <td width="32" valign="bottom" align="center"  style="font-family:Verdana, Geneva, sans-serif; font-size:13px; color:#999999;"><img src="<?php echo IEPLADS_DOMAIN; ?>/mailers/2014/shiksha/shiksha1sep/images/img2.gif" width="26" height="69" alt="" hspace="0" vspace="0" align="center" /></td>
                                        <?php } ?>
                                      </tr>
                                      <tr>
                                        <td></td>
                                        <td height="20"></td>
                                        <td></td>
                                      </tr>
                                        <?php } ?>
                                        <?php if(!empty($reviewDescription)){ ?>
                                      <tr>
                                        <td></td>
                                        <td style="font-family:Verdana, Geneva, sans-serif; font-size:13px; color:#999999;text-align:justify;">
                                        <span style="color:#999999;"><?php echo '<b>Other Details : </b>'.$reviewDescription; ?></span></td>
                                        <td width="32" valign="bottom" align="center"  style="font-family:Verdana, Geneva, sans-serif; font-size:13px; color:#999999;"><img src="<?php echo IEPLADS_DOMAIN; ?>/mailers/2014/shiksha/shiksha1sep/images/img2.gif" width="26" height="69" alt="" hspace="0" vspace="0" align="center" /></td>
                                      </tr>
                                      <tr>
                                        <td></td>
                                        <td height="20"></td>
                                        <td></td>
                                      </tr>
                                        <?php } ?>
                                    </tbody>
                                  </table>
                                </td>
                              </tr>
                            </tbody>
                          </table>
                        </td>
                        <td width="20"></td>
                      </tr>
                    </tbody>
                  </table>
                </td>
              </tr>
              <tr>
                <td valign="top" align="center" bgcolor="#e8e8e9"><img src="<?php echo IEPLADS_DOMAIN; ?>/mailers/2014/shiksha/shiksha1sep/images/bar2.gif" alt="" hspace="0" vspace="0" align="left" width="100%" /></td>
              </tr>
              <tr>
                <td bgcolor="#E6E6E7" align="center">
                  <table width="100%" cellspacing="0" cellpadding="0" border="0">
                    <tbody>
                      <tr>
                        <td width="20"></td>
                        <td>
                          <table width="100%" cellspacing="0" cellpadding="0" border="0" align="center">
                            <tbody>
                              <tr>
                                <td height="6"></td>
                              </tr>
                              <tr>
                                <td style="font-family:Verdana, Geneva, sans-serif; font-size:13px; color:#333333;">If this review is not written by you, inform us at college.reviews@shiksha.com.</td>
                              </tr>
                              <tr>
                                <td height="10"></td>
                              </tr>
                            </tbody>
                          </table>
                        </td>
                        <td width="10"></td>
                      </tr>
                    </tbody>
                  </table>
                </td>
              </tr>
            </tbody>
          </table>
        </td>
        <td width="10" bgcolor="#3BB1FC"></td>
      </tr></tbody>
    </table>
  </td>
  </tr>
  <tr>
   <td valign="top" colspan="2">
    <table width="100%" cellspacing="0" cellpadding="0" border="0">
      <tbody><tr>
        <td width="10" bgcolor="#3BB1FC"></td>
        <td width="578">
         <table width="100%" cellspacing="0" cellpadding="0" border="0">
          <tbody><tr style="padding-bottom:10px">
    <td style="color:#a0d7f8; font-size:10px;" bgcolor="#3BB1FC" align="center"><table width="100%" cellspacing="0" cellpadding="0" border="0">
      <tbody><tr>
        <td style="font-family:Verdana, Geneva, sans-serif; color:#a0d7f8; font-size:10px;" height="80" align="center">This is a system generated email, please do not reply.
    You received this email because you're Shiksha.com member. You can unsubscribe after logging in to your account.</td>
      </tr>
    </tbody></table></td>
  </tr>
        </tbody></table>

        </td>
        <td width="10" bgcolor="#3BB1FC"></td>
      </tr>
    </tbody></table>

   </td>
  </tr>
</tbody></table>

</body></html>