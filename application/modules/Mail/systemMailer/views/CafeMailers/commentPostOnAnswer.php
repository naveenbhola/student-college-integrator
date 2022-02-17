<td width="564" bgcolor="#ffffff" align="center"><table width="100%" border="0" cellspacing="0" cellpadding="0" style="font-family:Arial; font-size:14px; color:#585c5f; text-align:left;">
<tr>
  <td colspan="3" height="15"></td>
</tr>
<tr>
  <td width="28"><img src="<?php echo IEPLADS_DOMAIN; ?>/mailers/2013/shiksha/verify3oct/images/spacer.gif" width="10" height="1" vspace="0" hspace="0" align="left" /></td>
  <td width="508" align="center"><table border="0" cellspacing="0" cellpadding="0" style="font-family:Arial; font-size:14px; color:#444648; line-height:18px; text-align:left;" width="100%">
      <tr>
        <td height="15"></td>
      </tr>
      <tr>
        <td width="508"><p>Dear <?php echo $NameOfUser; ?>,</p>          
          <p><?php echo $commenterName;?> has posted a new comment to <?php echo $answerOwnerName;?> answer</p>
          <p><b><?php if(isset($answerDisplayName) && $answerDisplayName!='') echo $answerDisplayName; else echo $answerOwnerName;?> answer:</b> <?php echo $msgTxt;?></p>
          <p><b><?php echo $commenterName;?>'s comment:</b> <?php echo $commentTxt?></p>
          <p>Please <a href="<?php echo $seoUrl;?><!-- #AutoLogin --><!-- AutoLogin# -->">click here</a> to visit the complete thread or to reply to <?php echo $commenterName;?>.</p>
          <p>If you find the comment of <?php echo $commenterName;?> objectionable or inappropriate, 
please <a href="<?php echo $seoUrl;?><!-- #AutoLogin --><!-- AutoLogin# -->">click here to Report it as abuse</a> and help keep Shiksha.com clean.</p>
          <p>If you like the answer of <?php echo $answerOwnerName;?>, <a href="<?php echo $ratingUrl;?><!-- #AutoLogin --><!-- AutoLogin# -->">click here to give it a thumbs up <img src="<?php echo SHIKSHA_HOME_URL;?>/public/images/digUp.gif" border="0" alt="Shiksha.com" height="20" width="20"/></a> and express your appreciation for the good work! </p>
          <br/>
        </td>
      </tr>
    </table></td>
  <td width="28"><img src="<?php echo IEPLADS_DOMAIN; ?>/mailers/2013/shiksha/verify3oct/images/spacer.gif" width="10" height="1" vspace="0" hspace="0" align="left" /></td>
</tr>
<tr>
  <td height="20" colspan="3"></td>
</tr>
<tr>
  <td></td>
  <td height="11">Best regards,<br />
    <font color="#c76d32">Shiksha.</font><font color="#426491">com</font></td>
  <td></td>
