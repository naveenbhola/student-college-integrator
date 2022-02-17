
<div style="font-family:Arial, Helvetica, sans-serif; font-size:12px; color:#1A1A1A; padding:10px 35px 10px 35px; line-height:20px;">
  <table width="600" border="0" cellspacing="0" cellpadding="0" align="center" style="border:1px solid #D9D9D9;">
        <tr><td>
        <br/><br/>
        Dear <?php echo htmlentities($first_name)?>,<br/><br/> </td></tr>
        <tr><td>
        You can find more information on applying to <?php echo $scholarshipName; ?> from the below link:<br/> </td></tr>
        <tr><td><a href = "<?php echo $applyNowLink ?>" target="_blank"><?php echo $applyNowLink ?></a><br/><br/></td></tr>
         <tr><td><?php if($attachBrochure){ ?>
        We have also attached scholarship brochure for your reference.<br/><br/>
        <?php } else if($brochureDownloadLink){ ?>
        You can also download the scholarship brochure from the below link:<br/>
        <a href = "<?php echo $brochureDownloadLink ?>">click here to download the brochure</a></br></br>
        <?php } ?>
        </td></tr>
        <tr><td><br/></td></tr>
        <tr><td>Good luck <br/></td></tr>
        <tr><td>Shiksha Study Abroad<br/><br/></td></tr>
      <?php $CI->load->view('studyAbroadCommon/mailerFooter',array('whiteBanner' => true)); ?>
        <tr><td>
        Looking for more information on scholarships and courses, visit us at <a href="<?php echo SHIKSHA_STUDYABROAD_HOME; ?>" target="_blank">studyabroad.shiksha.com</a>
    </td></tr>
</table>
</div>
