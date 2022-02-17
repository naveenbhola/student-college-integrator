<table width="215" border="0" cellspacing="0" cellpadding="0" align="left" bgcolor="#ffffff">
  <tr>
    <td height="38" style="border:1px #dbdbdb solid;font-family:Arial, Helvetica, sans-serif;font-size:15px;color:#474a4b"><img src="<?php echo $image_url; ?>recruiters.gif" width="39" height="19" vspace="0" hspace="0" align="absmiddle" /><strong>Recruiting Companies</strong></td>
  </tr>
  <tr>
    <td height="193" valign="top" style="border:1px #dbdbdb solid;border-top:none;">
    <table width="186" border="0" cellspacing="0" cellpadding="0" align="left" style="font-family:Arial, Helvetica, sans-serif;font-size:16px;color:#474a4b;">
	
	
    <?php
    $companyCount = 0;
    $maxCompany = count($recommendationWidgets['recruiters']['companyLogo']) >= 3 ? 3 : count($recommendationWidgets['recruiters']['companyLogo']);
    foreach($recommendationWidgets['recruiters']['companyLogo'] as $logoURL) {
	$gap = $companyCount == 0 ? (((3 - $maxCompany) * 24) + 15) : 10;
	$companyCount++;
    ?>
  <tr>
    <td width="30" height="<?php echo $gap; ?>"></td>
    <td width="156"></td>
  </tr>
  <tr>
    <td></td>
    <td align="center"><img src="<?php echo $logoURL; ?>" width="111" height="37" vspace="0" hspace="0" align="absmiddle" style="border:1px #d9d9d9 solid"/></td>
  </tr>
    <?php } ?>
  <tr>
    <td width="30" height="15"></td>
    <td width="156"></td>
  </tr>
  <tr>
    <td></td>
    <td align="center"><a href="<?php echo $course_url."~recommendation"; ?><!-- #widgettracker --><!-- widgettracker# -->" title="View All <?php echo $recommendationWidgets['recruiters']['count']; ?> Companies" target="_blank" style="font-family:Arial, Helvetica, sans-serif;font-size:13px;color:#2170e8;text-decoration:none;">View All <?php echo $recommendationWidgets['recruiters']['count']; ?> Companies<img src="<?php echo $image_url; ?>more_info.gif" width="9" height="8" vspace="0" hspace="0" align="absmiddle" border="0" /></a></td>
  </tr>
</table>

    </td>
  </tr>
  <tr>
  <td height="10" bgcolor="#efefef"></td>
  </tr>
</table>