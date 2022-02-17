     <td width="564" bgcolor="#ffffff" align="center"><table width="100%" border="0" cellspacing="0" cellpadding="0" style="font-family:Arial; font-size:14px; color:#585c5f; text-align:left;">
        <tr>
          <td colspan="3" height="15"></td>
        </tr>
        <tr>
          <td width="28"><img src="<?php echo IEPLADS_DOMAIN;?>/mailers/2013/shiksha/verify3oct/images/spacer.gif" width="10" height="1" vspace="0" hspace="0" align="left" /></td>
          <td width="508" align="center"><table border="0" cellspacing="0" cellpadding="0" style="font-family:Arial; font-size:14px; color:#444648; line-height:18px; text-align:left;" width="100%">
              <tr>
                <td height="15"></td>
              </tr>
              <tr>
                <td width="508">Dear <?php echo ucwords($menteeDetails[0]['firstname']); ?>,<br />
                  <br />
                 Your request for chat on <?=$slotTime; ?> has been <strong><?php if(isset($status) && $status == 'declined'){echo 'rejected';}else{echo 'accepted';}?></strong> by your mentor.<br /><br />
                  <?php if(isset($status) && $status == 'declined'){?> Please select a slot when you mentor is free or request a new slot at a different date & time.<?php } ?></td>
              </tr>
            </table></td>
          <td width="28"><img src="<?php echo IEPLADS_DOMAIN;?>/mailers/2013/shiksha/verify3oct/images/spacer.gif" width="10" height="1" vspace="0" hspace="0" align="left" /></td>
        </tr>
        <tr>
          <td height="20" colspan="3"></td>
        </tr>
       
        <tr>
          <td></td>
          <td height="11">Best Regards,<br />
            <font color="#c76d32">Shiksha.</font><font color="#426491">com</font></td>
          <td></td>