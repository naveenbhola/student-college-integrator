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
                    <td style="font-family:open sens, Tahoma, Helvetica, sans-serif; font-size:11px; color:#666666;">Dear <?php echo ucwords($name); ?>,</td>
                  </tr>
                  <?php $entityType = ($entityType == 'Blog Comment'|| $entityType == 'Event Comment')?'Comment':$entityType; ?>
                  <tr>
                    <td height="15"></td>
                  </tr>
                  <tr>
                    <td style="font-family:open sens, Tahoma, Helvetica, sans-serif; font-size:11px; line-height:22px; color:#999999;">Our moderators have reviewed the <a href="<?php echo $entityURL;?><!-- #AutoLogin --><!-- AutoLogin# -->"><?php echo $entityType;?></a> you reported as abuse on <a href="<?php echo $sectionURL;?><!-- #AutoLogin --><!-- AutoLogin# -->"><?php echo $section;?></a> and found that to be appropriate. 
Henceforth, your abuse report has been rejected<?php echo $penalty;?>.
                  </td>
                  </tr>
                  <tr>
                    <td style="font-family:open sens, Tahoma, Helvetica, sans-serif; font-size:11px; line-height:22px; color:#999999;">We advise you to take into account our <a href="<?php echo $communityUrl; ?><!-- #Tracking --><!-- Tracking# -->">community guidelines</a> while choosing to report any content published on Shiksha Ask & Answer as abuse. We hope for your cooperation.</td>
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
                    <td style="font-family:open sens, Tahoma, Helvetica, sans-serif; font-size:11px; color:#333333;"><strong>Shiksha Ask & Answer </strong></td>
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