<?php if($mailContentFirstLine!='') { ?>
<tr>
  <td height="16"></td>
</tr>
<tr>
  <td align="left" style=" font-family:Arial, Helvetica, sans-serif; font-size:12px; color:#048088; line-height:17px;"><strong><?php echo $mailContentFirstLine;?></strong></td>
</tr>
<?php } ?>
<tr>
  <td height="16"></td>
</tr>
<tr>
  <td align="left" style=" font-family:Arial, Helvetica, sans-serif; color: #333333; font-size:12px;">Hi <?php echo $first_name; ?>,</td>
</tr>
<tr>
  <td height="10"></td>
</tr>
<tr>
  <td align="left" style=" font-family:Arial, Helvetica, sans-serif; color: #333333; font-size:12px;"> You can download the <?php echo $mailContentSecondLineText;?>brochure for <strong><?php echo htmlentities($courseName);?> at <?php echo htmlentities($instituteName);?></strong> by clicking <strong></strong><a style="text-decoration:none;color:#0000ff;font-weight:bold" href="<?php echo $brochureDownloadLink;?><!-- #AutoLogin --><!-- AutoLogin# -->" target="_blank">this link</a>.
</td>
</tr>
<?php if($clientMsg !='') { ?>
<tr>
  <td height="10"></td>
</tr>
<tr>
  <td align="left" style=" font-family:Arial, Helvetica, sans-serif; color: #333333; font-size:12px;"><strong style="background: #E6FAEF; padding: 7px;"><?php echo ($clientMsg);?></strong> </td>
</tr>
<?php } ?>
<tr>
  <td height="19"></td>
</tr>
<tr>
  <td align="left" style=" font-family:Arial, Helvetica, sans-serif; color: #010101; font-size:12px;">To give you more insights about this course, we are also sending you a curated list of resources with links below.</td>
</tr>