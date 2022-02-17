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
                    <td style="font-family:open sens, Tahoma, Helvetica, sans-serif; font-size:11px; color:#666666;">Hi <?php echo $firstName; ?>,</td>
                  </tr>
                  <tr>
                    <td height="15"></td>
                  </tr>
                  <tr>
                    <td style="font-family:open sens, Tahoma, Helvetica, sans-serif; font-size:11px; line-height:22px; color:#999999;"><?php echo $body_html?></td>
                  </tr>
                  <tr>
                    <td height="15"></td>
                  </tr>
                  <tr>
                    <td>
                      <table width="100%" border="0" cellspacing="0" cellpadding="0">
                        <tr>
                          <td width="65" valign="top" style="font-family:open sens, Tahoma, Helvetica, sans-serif; font-size:11px; color:#999999;">Username</td>
                          <td width="10" valign="top" style="font-family:open sens, Tahoma, Helvetica, sans-serif; font-size:11px; color:#999999;">:</td>
                          <td valign="top" style="font-family:open sens, Tahoma, Helvetica, sans-serif; font-size:11px; color:#333333;"><strong><?php echo $email; ?></strong></td>
                        </tr>
                        <tr>
                          <td height="5"></td>
                          <td></td>
                          <td></td>
                        </tr>
                        <tr>
                          <td width="65" valign="top" style="font-family:open sens, Tahoma, Helvetica, sans-serif; font-size:11px; color:#999999;">Password </td>
                          <td width="10" valign="top" style="font-family:open sens, Tahoma, Helvetica, sans-serif; font-size:11px; color:#999999;">:</td>
                          <td valign="top" style="font-family:open sens, Tahoma, Helvetica, sans-serif; font-size:11px; color:#333333;"><strong><?php echo $password; ?></strong></td>
                        </tr>
                      </table>
                    </td>
                  </tr>
                  <tr>
                    <td height="10"></td>
                  </tr>
                  <?php if($is_attachment == 'Y') { ?>
                  <tr>
                    <td style="font-family:open sens, Tahoma, Helvetica, sans-serif; font-size:11px; line-height:22px; color:#999999;">Please find attached in this email, resource(s) that we feel would help you kick start your admission preparation.</td>
                  </tr>
                  <?php } ?>
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
                    <td style="font-family:open sens, Tahoma, Helvetica, sans-serif; font-size:11px; color:#333333;"><strong>Shiksha</strong></td>
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