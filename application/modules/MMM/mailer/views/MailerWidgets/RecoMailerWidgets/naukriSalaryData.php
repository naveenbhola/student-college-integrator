    <table width="226" border="0" cellspacing="0" cellpadding="0" align="left">
  <tr>
    <td width="218" bgcolor="#ffffff" style="border:1px #dbdbdb solid" valign="top">				     <table width="218" border="0" cellspacing="0" cellpadding="0" align="center">
  <tr>
    <td height="38" style="border-bottom:1px #dbdbdb solid;font-family:Arial, Helvetica, sans-serif;font-size:15px;color:#474a4b">
      <table width="215" border="0" cellspacing="0" cellpadding="0" align="left">
	<tr>
	  <td width="35"><img src="<?php echo $image_url; ?>salary_stats.gif" width="39" height="18" vspace="0" hspace="0" align="left" /></td>
	  <td width="180" style="font-family:Arial, Helvetica, sans-serif;font-size:15px;color:#474a4b"><strong>Alumni Employment Stats</strong></td>
	</tr>
      </table>
    </td>
  </tr>
  <tr>
    <td height="193" valign="top">
    <table width="211" border="0" cellspacing="0" cellpadding="0" align="left" style="font-family:Arial, Helvetica, sans-serif;font-size:16px;color:#474a4b;">
  <tr>
    <?php
	$maxSlabs = count($recommendationWidgets['naukriSalaryData']);
	
	$gap = ((3 - $maxSlabs) * 7) + 31;
    ?>
    
    <td width="19" height="<?php echo $gap; ?>"></td>
    <td width="192"></td>
  </tr>
    <tr>
    <td></td>
    <td align="right" style="font-family:Arial, Helvetica, sans-serif;font-size:10px;color:#474a4b;">Data Source:<img src="<?php echo $image_url; ?>small_naukri.gif" width="64" height="10" vspace="0" hspace="0" align="absmiddle" /></td>
  </tr>
      <tr>
    <td height="5"></td>
    <td></td>
  </tr>
    <?php if(isset($recommendationWidgets['naukriSalaryData']['5+'])) { ?>
  <tr>
    <td height="30"></td>
    <td align="center" style="border-bottom:1px #f1f1f1 solid;border-left:3px #6eb414 solid">5+ years : <strong><?php echo round($recommendationWidgets['naukriSalaryData']['5+'], 2); ?> Lacs</strong></td>
  </tr>
  <?php } ?>
  <?php if(isset($recommendationWidgets['naukriSalaryData']['2-5'])) { ?>
  <tr>
    <td height="30"></td>
    <td align="center" style="border-bottom:1px #f1f1f1 solid;border-left:3px #a9d374 solid">2 - 5 years : <strong><?php echo round($recommendationWidgets['naukriSalaryData']['2-5'], 2); ?> Lacs</strong></td>
  </tr>
  <?php } ?>
  <?php if(isset($recommendationWidgets['naukriSalaryData']['0-2'])) { ?>
  <tr>
    <td height="30"></td>
    <td align="center" style="border-bottom:1px #f1f1f1 solid;border-left:3px #d9f1ba solid">0 - 2 years : <strong><?php echo round($recommendationWidgets['naukriSalaryData']['0-2'], 2); ?> Lacs</strong></td>
  </tr>
  <?php } ?>
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
  </tr>
</table>
	</td>
    <td width="10"></td>
  </tr>
  <tr>
  <td height="10"></td>
  <td></td>
  </tr>
</table>