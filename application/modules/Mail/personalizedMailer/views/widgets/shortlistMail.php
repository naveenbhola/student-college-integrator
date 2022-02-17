
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
            <td align="left" style="font-family:Arial, Helvetica, sans-serif; color: #333333; font-size:12px;">We have added the course <strong><?php echo htmlentities($courseName);?> at <?php echo htmlentities($instituteName);?></strong> to &quot;<a href="<?php echo SHIKSHA_HOME;?>/resources/colleges-shortlisting#myshortlist<!-- #AutoLogin --><!-- AutoLogin# -->" target="_blank" style="color:#03818d; text-decoration:none;">My Shortlist</a>&quot; section on Shiksha.com. </td>
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
            <td height="15"></td>
         </tr>
         <tr>
            <td align="left" style=" font-family:Arial, Helvetica, sans-serif; color: #010101; font-size:12px;">To give you more insights about this course, we are also sending you <?php if($mailer_name != 'shortlistMailer2'){?>  the E-brochure and <?php } ?> a curated list of resources with links below.
            <?php if($mailer_name == 'shortlistMailer3'){?>   
             You can download the brochure by clicking <a style="text-decoration:none;color:#03818d;" href="<?php echo $brochureDownloadLink;?><!-- #AutoLogin --><!-- AutoLogin# -->" target="_blank">this link</a>.
            <?php }?>
            </td>
         </tr>
         <tr>
            <td height="10"></td>
         </tr>
<tr>