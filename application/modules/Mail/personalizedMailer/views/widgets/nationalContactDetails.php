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
     <td align="left" style="font-family:Arial, Helvetica, sans-serif; color: #333333; font-size:12px;">You have requested for the contact details of <strong><?php echo htmlentities($courseName);?> at <?php echo htmlentities($instituteName);?></strong>. </td>
  </tr>
  <tr>
     <td height="15"></td>
  </tr>
  <tr>
     <td height="19" style="font-family:Arial, Helvetica, sans-serif; color: #333333; font-size:12px;">Please find the details below.</td>
  </tr>
  <tr>
     <td height="19" style="font-family:Arial, Helvetica, sans-serif; color: #333333; font-size:12px;"><strong>Phone Number:</strong> <?php echo $contact_numbers; ?></td>
  </tr>
  <tr>
     <td height="5"></td>
  </tr>
  <tr>
     <td height="19" style="font-family:Arial, Helvetica, sans-serif; color: #333333; font-size:12px;"><strong>Email:</strong> <?php echo $contact_email; ?></td>
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
   <td height="10"></td>
</tr>
<tr>
   <td align="left" style=" font-family:Arial, Helvetica, sans-serif; color: #010101; font-size:12px;">To give you more insights about this course, we are also sending you <?php if($mailer_name != 'nationalContactDetails2'){?> the E-brochure and <?php } ?> a curated list of resources with links below. 
   <?php if($mailer_name == 'nationalContactDetails3'){ ?>  
   You can download the brochure by clicking <a style="text-decoration:none;color:#03818d;" href="<?php echo $brochureDownloadLink;?><!-- #AutoLogin --><!-- AutoLogin# -->" target="_blank">this link</a>.
   <?php } ?>
    </td></td>

</tr>

<tr>
   <td height="10"></td>
</tr>
<tr>