<?php if($type == 'MMM') {
  $trackingLink = "<!-- #widgettracker --><!-- widgettracker# -->";
} else {
  $trackingLink = "<!-- #AutoLogin --><!-- AutoLogin# -->";
} ?>
<!-- Footer starts -->
        <tr>
            <td align="center" bgcolor="f5f5f5" style="border-top:#e7e7e7 solid 1px;">
                <table width="260" border="0" cellspacing="0" cellpadding="0">
                    <tr>
                        <td align="center" valign="top">
                            <table border="0" cellspacing="0" cellpadding="0">
                                <tr>
                                    <td width="35" style="border-bottom:#333333 solid 1px;" height="15"></td>
                                </tr>
                            </table>
                        </td>

                        <td width="150" height="32" align="center" style="font-family:Arial, sans-serif; color:#000000; font-size:12px;">On Shiksha, get access to</td>
                        
                        <td align="center" valign="top">
                            <table border="0" cellspacing="0" cellpadding="0">
                              <tr>
                                <td width="35" style="border-bottom:#333333 solid 1px;" height="15"></td>
                              </tr>
                            </table>
                        </td>
                      </tr>
                      <tr>
                        <td colspan="3" align="center">
                            <table width="100%" border="0" cellspacing="0" cellpadding="0">
                              <tr>
                                <td width="64" align="center" style="font-family:Arial, Helvetica, sans-serif; font-size:10px; color:#666666; border-right:#d9d9d9 solid 1px;">
                                    <strong style=" font-size:15px; color:#333333"><?php echo $footer['instCount']; ?></strong><br />Colleges</td>
                                <td width="64" align="center" style="font-family:Arial, Helvetica, sans-serif; font-size:10px; color:#666666; border-right:#d9d9d9 solid 1px;">
                                    <strong style=" font-size:15px; color:#333333"><?php echo $footer['examCount']; ?></strong><br />Exams</td>
                                <td width="64" align="center" style="font-family:Arial, Helvetica, sans-serif; font-size:10px; color:#666666; border-right:#d9d9d9 solid 1px;">
                                    <strong style=" font-size:15px; color:#333333"><?php echo $footer['reviewsCount']; ?></strong><br />Reviews</td>
                                <td width="64" align="center" style="font-family:Arial, Helvetica, sans-serif; font-size:10px; color:#666666;">
                                    <strong style=" font-size:15px; color:#333333"><?php echo $footer['questionsAnsweredCount']; ?></strong><br />Answers</td>
                              </tr>
                            </table>
                        </td>
                      </tr>
                    </table>

                  </td>
                </tr>
<!--                 <tr>
                  <td bgcolor="f5f5f5" height="17"></td>
                </tr> -->

                <tr>
                  <td valign="top" align="center" bgcolor="#f5f5f5">
                    <table width="62%" align="center" border="0" cellspacing="0" cellpadding="0">
                      <tbody>
                        <tr>
                          <td valign="top" height="15"></td>
                        </tr>
                        <tr>
                          <td valign="top" align="center" style="font-family:Arial, Helvetica, sans-serif; font-size:12px; color:#666666; line-height:18px; font-weight:normal;">For regular updates on  <strong>courses</strong>, <strong>colleges</strong> and <strong>exams</strong>, follow us on</td>
                        </tr>
                        <tr>
                          <td valign="top" height="13"></td>
                        </tr>
                        <tr>
                          <td valign="top" align="center">
                            <table width="180" align="center" border="0" cellspacing="0" cellpadding="0">
                              <tbody>
                                <tr>
                                 <td width="46" align="left"><a href="https://www.facebook.com/shikshacafe<?php echo $trackingLink;?>" target="_blank" style="display:block;"><img src="<?php echo SHIKSHA_HOME;?>/public/images/fb.png" width="36" height="35" alt="Facebook"></a></td>
                                 <td width="46" align="left"><a href="https://www.instagram.com/shikshadotcom<?php echo $trackingLink;?>" target="_blank" style="display:block;"><img src="<?php echo SHIKSHA_HOME;?>/public/images/insta.png" width="36" height="35" alt="Instagram"></a></td>
                                 <td width="46" align="left"><a href="https://twitter.com/shikshadotcom<?php echo $trackingLink;?>" target="_blank" style="display:block;"><img src="<?php echo SHIKSHA_HOME;?>/public/images/tw.png" width="36" height="35" alt="Twitter"></a></td>
                                 <td width="36" align="left"><a href="https://www.youtube.com/user/shikshacafe<?php echo $trackingLink;?>" target="_blank" style="display:block;"><img src="<?php echo SHIKSHA_HOME;?>/public/images/yt.png" width="36" height="35" alt="Youtube"></a></td>
                                </tr>
                              </tbody>
                            </table>
                          </td>
                        </tr>
                        <tr>
                          <td valign="top" height="16"></td>
                        </tr>
                      </tbody>
                    </table>
                  </td>
                </tr>

              </table>
            </td>
          </tr>
        </table>
        <?php if(!isset($unregisteredUser)){ ?>
        <table border="0" align="center" cellpadding="0" cellspacing="0" style="font-family: Arial, Helvetica, sans-serif; font-size: 12px; color: #737171; max-width: 550px;">
        <tbody>
        <tr>
        <td height="26" align="center" style="padding-top: 5px;">To unsubscribe
        <?php if($type == 'MMM') {
            $unsubscribeLink = "<!-- #ip --><!-- ip# -->/userprofile/edit?selected_tab=1&encodedMail=<!-- #encodedemail --><!-- encodedemail# -->&mailerUnsubscribe=1~unsubscribe<!-- #widgettracker --><!-- widgettracker# -->";
          } else {
            $unsubscribeLink = "<!-- #Unsubscribe --><!-- Unsubscribe# -->";
          } ?>
        <a href="<?php echo $unsubscribeLink; ?>" target="_blank" style="color: #737171; text-decoration: underline;">click here</a> to visit the Accounts &amp; Settings page and change your mail preferences</td>
        </tr>

      </tbody>
    </table>
  <?php } ?>
  </body>
</html>
