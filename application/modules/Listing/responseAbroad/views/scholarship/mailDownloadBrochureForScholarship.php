<div style="font-family:Arial, Helvetica, sans-serif; font-size:12px; color:#1A1A1A; padding:10px 35px 10px 35px; line-height:20px;">
<table width="600" border="0" cellspacing="0" cellpadding="0" align="center" style="border:1px solid #D9D9D9;">
  <tr>
      <td>      
        <br/><br/>
        Dear <?php echo htmlentities($first_name)?>,<br/><br/>
        </td></tr>
        <tr><td>
        <?php if($attachBrochure){ ?>
            Your requested scholarship brochure is attached in this email.<br/><br/>
        <?php }else if($brochureDownloadLink){ ?>
            Your requested scholarship brochure can be downloaded from the below link:<br/>
            <a href = "<?php echo $brochureDownloadLink ?>">click here to download the brochure</a><br/><br/>
        <?php } ?>
        </td></tr>
        <tr><td>
        Good luck <br/></td></tr>
        <tr><td>Shiksha Study Abroad<br/><br/></td></tr>
    <?php $CI->load->view('studyAbroadCommon/mailerFooter',array('whiteBanner' => true)); ?>
        <tr><td>
        Looking for more information on scholarships and courses, visit us at <a href="<?php echo SHIKSHA_STUDYABROAD_HOME; ?>" target="_blank">studyabroad.shiksha.com</a>
    </td>
  </tr>
</table>
</div>