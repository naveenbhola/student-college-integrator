
<tr>
   <td align="center">
      <table width="94%" border="0" cellspacing="0" cellpadding="0">
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
   <td align="left" style=" font-family:Arial, Helvetica, sans-serif; color: #333333; font-size:12px;">You have shown interest in <strong><?php echo htmlentities($courseName);?> at <?php echo htmlentities($instituteName);?></strong> by using Shiksha compare tool.</td>
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
   <td height="14"></td>
</tr>
<tr>
   <td align="left" style=" font-family:Arial, Helvetica, sans-serif; color: #010101; font-size:12px;">To give you more insights about this course, we are also sending you <?php if($mailer_name != 'compareMailer2'){?> the E-brochure and <?php } ?> a curated list of resources with links below. 
   <?php if($mailer_name == 'compareMailer3'){?>	
   You can download the brochure by clicking <a style="text-decoration:none;color:#03818d;" href="<?php echo $brochureDownloadLink;?><!-- #AutoLogin --><!-- AutoLogin# -->" target="_blank">this link</a>.
   <?php } ?>
    </td></td>

</tr>
<tr>
   <td height="10"></td>
</tr>
<tr>