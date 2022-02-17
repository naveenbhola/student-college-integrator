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
                    <td style="font-family:open sens, Tahoma, Helvetica, sans-serif; font-size:11px; color:#666666;">Dear <?php echo ucwords($NameOfUser); ?>,</td>
                  </tr>
                  <tr>
                    <td height="15"></td>
                  </tr>
                  <tr>
                    <td style="font-family:open sens, Tahoma, Helvetica, sans-serif; font-size:11px; line-height:22px; color:#999999;"><?php echo $commenterName;?> has posted a new comment to <?php echo $answerOwnerName;?> answer</td>
                  </tr>
                  <tr>
                    <td height="15"></td>
                  </tr>
                  <tr>
                    <td>
                      <table width="100%" border="0" cellspacing="0" cellpadding="0">
                        <tr>
                          <td width="55" valign="top" style="font-family:open sens, Tahoma, Helvetica, sans-serif; font-size:11px; color:#999999;"><?php if(isset($answerDisplayName) && $answerDisplayName!='') echo $answerDisplayName; else echo $answerOwnerName;?> answer</td>
                          <td width="13" valign="top" style="font-family:open sens, Tahoma, Helvetica, sans-serif; font-size:11px; color:#999999;">:</td>
                          <td width="308"valign="top" style="font-family:open sens, Tahoma, Helvetica, sans-serif; font-size:11px; color:#333333;"><strong><?php echo nl2br($msgTxt);?></strong></td>
                        </tr>
                        <tr>
                          <td height="5"></td>
                          <td></td>
                          <td></td>
                        </tr>
                        <tr>
                          <td width="55" valign="top" style="font-family:open sens, Tahoma, Helvetica, sans-serif; font-size:11px; color:#999999;"><?php echo $commenterName;?>'s comment</td>
                          <td width="13" valign="top" style="font-family:open sens, Tahoma, Helvetica, sans-serif; font-size:11px; color:#999999;">:</td>
                          <td valign="top" style="font-family:open sens, Tahoma, Helvetica, sans-serif; font-size:11px; color:#333333;"><strong><?php echo $commentTxt;?></strong></td>
                        </tr>
                      </table>
                    </td>
                  </tr>

                     <tr>
                    <td height="15"></td>
                  </tr>
                  <tr>
                    <td style="font-family:open sens, Tahoma, Helvetica, sans-serif; font-size:11px; line-height:22px; color:#999999;">Please <a href="<?php echo $seoUrl;?><!-- #AutoLogin --><!-- AutoLogin# -->">click here</a> to visit the complete thread or to reply to <?php echo $commenterName;?>.</td>
                  </tr>
                  <tr>
                    <td style="font-family:open sens, Tahoma, Helvetica, sans-serif; font-size:11px; line-height:22px; color:#999999;">If you find the comment of <?php echo $commenterName;?> objectionable or inappropriate, 
please <a href="<?php echo $seoUrl;?><!-- #AutoLogin --><!-- AutoLogin# -->">click here to Report it as abuse</a> and help keep Shiksha Ask & Answer clean.</td>
                  </tr>
                  <?php if($answerOwnerName != 'your'){ ?>
                    <tr>
                    <td style="font-family:open sens, Tahoma, Helvetica, sans-serif; font-size:11px; line-height:22px; color:#999999;">If you like the answer of <?php echo $answerOwnerName;?>, <a href="<?php echo $ratingUrl;?><!-- #AutoLogin --><!-- AutoLogin# -->">click here to give it a upvote </a> and express your appreciation for the good work!</td>
                     </tr>
                 <?php } ?>
                  
                      
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