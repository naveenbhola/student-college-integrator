<tr>
  <td valign="top">
    <table width="100%" border=" 0" cellspacing="0" cellpadding="0">
      <tr>
        <td valign="top" bgcolor="#f1f1f2">
          <table width="100%" border="0" cellspacing="0" cellpadding="0" style="border:1px solid #e8e8e8; border-radius:5px 5px 5px 5px; background-color:#ffffff; box-shadow: -2px 0 5px #e0e0e0;">
            <tr>
              <td width="45"></td>
              <td>
                <table width="100%" border="0" cellspacing="0" cellpadding="0">
                  <tr>
                    <td height="30"></td>
                  </tr>
                  <tr>
                    <td style="font-family:open sens, Tahoma, Helvetica, sans-serif; font-size:11px; color:#666666;">Dear <?php echo ucwords($followedUserName); ?>,</td>
                  </tr>
                  <tr>
                    <td height="15"></td>
                  </tr>
                  <tr>
                    <td style="font-family:open sens, Tahoma, Helvetica, sans-serif; font-size:11px; line-height:22px; color:#999999;">You have received a new answer on the question you follow from <?php echo $answerOwnerName;?></td>
                  </tr>
                  <tr>
                    <td height="15"></td>
                  </tr>
                  <tr>
                    <td>
                      <table width="100%" border="0" cellspacing="0" cellpadding="0">
                        <tr>
                          <td width="55" valign="top" style="font-family:open sens, Tahoma, Helvetica, sans-serif; font-size:11px; color:#999999;">Question</td>
                          <td width="13" valign="top" style="font-family:open sens, Tahoma, Helvetica, sans-serif; font-size:11px; color:#999999;">:</td>
                          <td width="308"valign="top" style="font-family:open sens, Tahoma, Helvetica, sans-serif; font-size:11px; color:#333333;"><a href="<?php echo $seoUrl;?><!-- #AutoLogin --><!-- AutoLogin# -->"><strong><?php echo nl2br($questionText);?></strong></a></td>
                        </tr>
                        <tr>
                          <td height="5"></td>
                          <td></td>
                          <td></td>
                        </tr>
                        <tr>
                          <td width="55" valign="top" style="font-family:open sens, Tahoma, Helvetica, sans-serif; font-size:11px; color:#999999;"><?php echo ucwords($answerOwnerName);?>'s answer</td>
                          <td width="13" valign="top" style="font-family:open sens, Tahoma, Helvetica, sans-serif; font-size:11px; color:#999999;">:</td>
                          <td valign="top" style="font-family:open sens, Tahoma, Helvetica, sans-serif; font-size:11px; color:#333333;"><strong><?php echo $msgTxt?></strong></td>
                        </tr>
                      </table>
                    </td>
                  </tr>

                     <tr>
                    <td height="15"></td>
                  </tr>
                  <tr>
                    <td style="font-family:open sens, Tahoma, Helvetica, sans-serif; font-size:11px; line-height:22px; color:#999999;">If you like this answer <a href="<?php echo $ratingUrl;?><!-- #AutoLogin --><!-- AutoLogin# -->">click here to give it a upvote </a>.</td>
                  </tr>
                  <tr>
                    <td style="font-family:open sens, Tahoma, Helvetica, sans-serif; font-size:11px; line-height:22px; color:#999999;">However if you find it objectionable or inappropriate, please <a href="<?php echo $seoUrl;?><!-- #AutoLogin --><!-- AutoLogin# -->">click here to Report it as abuse</a> and help keep Shiksha Ask & Answer clean.</td>
                  </tr>
                      
                  <tr>
                    <td height="10"></td>
                  </tr>
                
                  <tr>
                    <td height="15"></td>
                  </tr>
                  <tr>
                    <td style="font-family:open sens, Tahoma, Helvetica, sans-serif; font-size:11px; color:#666666;">Regards, </td>
                  </tr>
                  <tr>
                    <td height="5"></td>
                  </tr>
                  <tr>
                    <td style="font-family:open sens, Tahoma, Helvetica, sans-serif; font-size:11px; color:#333333;"><strong>Shiksha Ask & Answer</strong></td>
                  </tr>
                  <tr>
                    <td height="25"></td>
                  </tr>
                </table>
              </td>
              <td width="45"></td>
            </tr>
          </table>
        </td>
      </tr>
      <tr>
        <td height="14" align="top"></td>
      </tr>
    </table>
  </td>
</tr>