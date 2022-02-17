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
        <td width="508"><p>Hi <?php echo $NameOfUser; ?>,</p>
          <p><?php echo $questionOwnerName;?> has selected <?php echo $answerOwnerName;?>'s answer as the best answer for their question.</p>
          <p><b><?php echo $questionOwnerName;?>'s question:</b> <?php echo nl2br($questionText);?></p>
          <p><b><?php echo $answerOwnerName;?>'s answer:</b> <?php echo $msgTxt;?></p>
          <p>If you also like the answer of <?php echo $answerOwnerName;?>, <a href="<?php echo $ratingUrl;?><!-- #AutoLogin --><!-- AutoLogin# -->">click here to give it a thumbs up <img src="<?php echo SHIKSHA_HOME_URL;?>/public/images/digUp.gif" border="0" alt="Shiksha.com" height="20" width="20"/></a> and express your appreciation for the good work! </p>
          <p>Please <a href="<?php echo $seoUrl;?><!-- #AutoLogin --><!-- AutoLogin# -->">click here</a> to see and rate all other answers to <?php echo $questionOwnerName;?>'s question.</p>
          <p>You are receiving this mail because you have either answerd <?php echo $questionOwnerName;?>'s question or commented on any answer to his question.</p>
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
  <td height="11">Best wishes,<br />
    <font color="#c76d32">Shiksha.</font><font color="#426491">com</font></td>
  <td></td>
