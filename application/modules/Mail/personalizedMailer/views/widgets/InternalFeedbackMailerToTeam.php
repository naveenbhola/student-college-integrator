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
                    <td style="font-family:open sens, Tahoma, Helvetica, sans-serif; font-size:11px; line-height:22px; color:#666666;"><?php echo ucwords($userDetails['firstName']); ?> has shared the following feedback</td>
                  </tr>
                  <tr>
                    <td height="10"></td>
                  </tr>
                  <tr>
                    <td style="font-family:open sens, Tahoma, Helvetica, sans-serif; font-size:11px; line-height:22px; color:#666666;">Ease of Use - <b><?php echo $rating['easeOfUse']; ?></b></td>
                  </tr>
                  <tr>
                    <td style="font-family:open sens, Tahoma, Helvetica, sans-serif; font-size:11px; line-height:22px; color:#666666;">Usefulness - <b><?php echo $rating['usefulness']; ?></b></td>
                  </tr>
                  <tr>
                    <td style="font-family:open sens, Tahoma, Helvetica, sans-serif; font-size:11px; line-height:22px; color:#666666;">Look & Feel - <b><?php echo $rating['looknFeel']; ?></b></td>
                  </tr>
                  <tr>
                    <td height="15"></td>
                  </tr>
                  <tr>
                    <td style="font-family:open sens, Tahoma, Helvetica, sans-serif; font-size:11px; line-height:22px; color:#666666;">Comment : </td>
                  </tr>
                  <tr>
                    <td style="font-family:open sens, Tahoma, Helvetica, sans-serif; font-size:11px; line-height:22px; color:#666666;"><b><?php echo $feedbackText;?></b></td>
                  </tr>
                  <tr>
                    <td height="15"></td>
                  </tr>
                  <tr>
                    <td style="font-family:open sens, Tahoma, Helvetica, sans-serif; font-size:11px; line-height:22px; color:#666666;">User Details : </td>
                  </tr>
                  <tr>
                    <td style="font-family:open sens, Tahoma, Helvetica, sans-serif; font-size:11px; line-height:22px; color:#666666;">First Name : <b><?php echo $userDetails['firstName'];?></b></td>
                  </tr>
                  <tr>
                    <td style="font-family:open sens, Tahoma, Helvetica, sans-serif; font-size:11px; line-height:22px; color:#666666;">Last Name : <b><?php echo $userDetails['lastName'];?></b></td>
                  </tr>
                  <tr>
                    <td style="font-family:open sens, Tahoma, Helvetica, sans-serif; font-size:11px; line-height:22px; color:#666666;">Email : <b><?php echo $userDetails['email'];?></b></td>
                  </tr>
                  <tr>
                    <td style="font-family:open sens, Tahoma, Helvetica, sans-serif; font-size:11px; line-height:22px; color:#666666;">Mobile Number : <b><?php echo $userDetails['mobile'];?></b></td>
                  </tr>
                  <tr>
                    <td style="font-family:open sens, Tahoma, Helvetica, sans-serif; font-size:11px; line-height:22px; color:#666666;">User Profile Link : <a href="<?php echo $userDetails['userProfileLink'];?>"><?php echo $userDetails['userProfileLink'];?></a></td>
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