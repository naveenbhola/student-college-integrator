<table width="225" border="0" cellspacing="0" cellpadding="0" align="left" bgcolor="#ffffff">
  <tr>
    <td height="38" style="border:1px #dbdbdb solid;font-family:Arial, Helvetica, sans-serif;font-size:15px;color:#474a4b"><img src="<?php echo $image_url; ?>fees.gif" width="39" height="19" vspace="0" hspace="0" align="absmiddle" /><?php if($isAbroadRecommendation) { echo ' <strong>1st Year Tuition Fees</strong>'; } else if(!$isAbroadRecommendation) { echo '<strong>Total Fees </strong>(INR)'; } ?></td>
    <td width="10" bgcolor="#efefef"></td>
  </tr>
  <tr>
    <td height="193" valign="top" style="border:1px #dbdbdb solid;border-top:none;">
    <table width="205" border="0" cellspacing="0" cellpadding="0" align="left" style="font-family:Arial, Helvetica, sans-serif;font-size:16px;color:#474a4b;">
  <tr>
    <td width="13" height="<?php if($isAbroadRecommendation) { echo '50'; } else { echo '66'; }?>"></td>
    <td width="150"></td>
  </tr>
  <?php if($isAbroadRecommendation) { ?>
  <tr>
    <td></td>
    <td>Annually</td>
  </tr>
  <?php } ?>
  <tr>
    <td height="30"></td>
    <td><?php if(strlen(trim($recommendationWidgets['courseFees']['fees']))) { echo $recommendationWidgets['courseFees']['fees'].' = '; } echo $recommendationWidgets['courseFees']['feesInRupee']; ?></td>
  </tr>
  <tr>
    <td height="10"></td>
    <td></td>
  </tr>
  <tr>
    <td></td>
    <td><a href="<?php echo $course_url."~recommendation"; ?><!-- #widgettracker --><!-- widgettracker# -->" title="More info" target="_blank" style="font-family:Arial, Helvetica, sans-serif;font-size:13px;color:#2170e8;text-decoration:none;">More Info<img src="<?php echo $image_url; ?>more_info.gif" width="9" height="8" vspace="0" hspace="0" align="absmiddle" border="0" /></a></td>
  </tr>
</table>
    </td>
    <td width="10" bgcolor="#efefef"></td>
  </tr>
  <tr>
  <td height="10" bgcolor="#efefef"></td>
  <td width="10" bgcolor="#efefef"></td>
  </tr>
</table>