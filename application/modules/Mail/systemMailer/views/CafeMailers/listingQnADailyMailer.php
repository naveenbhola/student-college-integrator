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
        <td width="508">
          <?php
          $listingTitle  = is_array($records[0])?$records[0]['listing_title']:"";
          $textPassword  = is_array($records[0])?$records[0]['textpassword']:"";
          $LoginEmail    = is_array($records[0])?$records[0]['email']:"";
          $listingTypeId = is_array($records[0])?$records[0]['listingTypeId']:0;
          $listingType   = is_array($records[0])?$records[0]['listingType']:0;
          $ListingUrl    = SHIKSHA_HOME_URL."/getListingDetail/".$listingTypeId."/".$listingType;
          if($type == 'listingAnADailyMailer'):
          ?>
          <br />
          Dear user,<br /><br />
          <?php echo $noOfQuestions; ?> new questions about <a href="<?php echo $ListingUrl; ?><!-- #AutoLogin --><!-- AutoLogin# -->" target="_blank"><?php echo $listingTitle; ?></a> have been posted on Shiksha today.<br /><br />
          Answer queries about your institute and connect with prospective student directly.<br /><br />
          <a href="<?php echo $urlToBeSentInMail; ?><!-- #AutoLogin --><!-- AutoLogin# -->" target="_blank">Login here</a> to answer the questions.<br /><br />
          Please note the following - <br /><br />
          <?php if($listingTypeId == 29063 || $listingTypeId==29106||$listingTypeId==29110||$listingTypeId==31874||$listingTypeId==29117
                  ||$listingTypeId==29119||$listingTypeId==29122||$listingTypeId==31873||$listingTypeId==31864||$listingTypeId==29126
                  ||$listingTypeId==29095||$listingTypeId==31858||$listingTypeId==29171||$listingTypeId==31859||$listingTypeId==29129
                  ||$listingTypeId==31876||$listingTypeId==29136||$listingTypeId==29137||$listingTypeId==31875||$listingTypeId==29144
                  ||$listingTypeId==29145||$listingTypeId==31911||$listingTypeId==31877||$listingTypeId==31878||$listingTypeId==29153
                  ||$listingTypeId==29154||$listingTypeId==29158||$listingTypeId==31860||$listingTypeId==31917||$listingTypeId==29175
                  ||$listingTypeId==31856||$listingTypeId==29315||$listingTypeId==29180||$listingTypeId==29185||$listingTypeId==29194
                  ||$listingTypeId==29199||$listingTypeId==29200||$listingTypeId==31882||$listingTypeId==29204||$listingTypeId==29206
                  ||$listingTypeId==29207||$listingTypeId==29208||$listingTypeId==29209||$listingTypeId==29212||$listingTypeId==31896
                  ||$listingTypeId==29121||$listingTypeId==29214||$listingTypeId==31861||$listingTypeId==31865){?>
          
          Username - <?php echo $LoginEmail; ?><br />
          
          <?php }else{?>
          Username - <?php echo $LoginEmail; ?><br />
          Password - <?php echo $textPassword; ?><br /><br />
          <?php } ?>
          <?php elseif($type == 'listingAnADailyMailerForNonEnterpriseUsers'): ?>
          <br />
          Dear user,<br /><br />
          <?php echo $noOfQuestions; ?> new questions have been posted about your institute today.<br /><br />
          <?php
          foreach($records as $temp):
          ?>
            &diams; &nbsp; <a href="<?php echo $temp['url']; ?><!-- #AutoLogin --><!-- AutoLogin# -->" target="_blank"><?php echo $temp['msgTxt']; ?></a><br /><br />
          <?php
          endforeach;
          ?><br /><br />
          Login to Shiksha today to answer queries about your institute and connect with
          prospective student directly.<br /><br />
          <?php endif; ?>          
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
