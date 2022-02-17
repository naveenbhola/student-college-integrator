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
                    <td style="font-family:open sens, Tahoma, Helvetica, sans-serif; font-size:11px; color:#666666;">Dear <?php echo $name; ?>,</td>
                    <?php $entityType = ($entityType == 'Blog Comment'|| $entityType == 'Event Comment')?'Comment':$entityType; ?>
                  </tr>
                  <tr>
                    <td height="15"></td>
                  </tr>
                  <tr>
                    <td style="font-family:open sens, Tahoma, Helvetica, sans-serif; font-size:11px; line-height:22px; color:#999999;">One of the <?php echo $entityType;?> you posted on <a href="<?php echo $sectionURL;?><!-- #AutoLogin --><!-- AutoLogin# -->"><?php echo $section;?></a> has been removed from the site on account of abuse reports by community members.</td>
                  </tr>
                 

                  <tr>
                    <td height="15"></td>
                  </tr>
                  <tr>
                    <td style="font-family:open sens, Tahoma, Helvetica, sans-serif; font-size:11px; line-height:22px; color:#999999;">We advise you to  adhere to our community guidelines while making a post on Shiksha Ask & Answer to avoid their removal. We hope for your cooperation in keeping Shiksha Ask & Answer clean and useful for community members. Please <a href="<?php echo $communityUrl; ?><!-- #Tracking --><!-- Tracking# -->">click here</a> to refer to the Shiksha community guidelines.</td>
                  </tr>
                  <tr>
                    <td height="15"></td>
                  </tr>
     
                  <tr>
                    <td style="font-family:open sens, Tahoma, Helvetica, sans-serif; font-size:11px; line-height:22px; color:#999999;"><b>Your <?php echo $entityType;?>:</b> <?php echo nl2br($entityText);?></td>
                  </tr>
                  <tr>
                    <td height="15"></td>
                  </tr>
                  
                  <tr>
                    <td style="font-family:open sens, Tahoma, Helvetica, sans-serif; font-size:11px; line-height:22px; color:#999999;">Please note that our moderators would review your <?php echo $entityType;?> and if it is found to be appropriate, it would be republished and penalty would be reversed. You do not need to contact us for same.</td>
                  </tr>
                
                  <tr>
                    <td height="15"></td>
                  </tr>
                  <tr>
                    <td style="font-family:open sens, Tahoma, Helvetica, sans-serif; font-size:11px; line-height:22px; color:#999999;">We hope that this suggestion will help you in getting better answer to your query.</td>
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